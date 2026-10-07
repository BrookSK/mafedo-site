<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Logger;
use App\Models\Setting;

/**
 * Cliente SMTP em PHP puro (sem PHPMailer/Composer).
 *
 * Suporta STARTTLS (porta 587), SSL implícito (porta 465) e sem criptografia.
 * Lê as credenciais da tabela settings (grupo 'smtp'). A senha é descriptografada
 * automaticamente pelo model Setting.
 */
final class Mailer
{
    /**
     * Envia um e-mail HTML.
     *
     * @return array{ok:bool,error:string}
     */
    public static function send(string $to, string $subject, string $htmlBody, ?string $replyTo = null): array
    {
        $host = (string) Setting::get('smtp_host', '');
        $port = (int) Setting::get('smtp_port', 587);
        $user = (string) Setting::get('smtp_username', '');
        $pass = (string) Setting::get('smtp_password', '');
        $enc  = (string) Setting::get('smtp_encryption', 'tls');
        $fromName = (string) Setting::get('smtp_from_name', 'Mafedo Engenharia');
        $fromEmail = (string) Setting::get('smtp_from_email', '');

        if ($host === '' || $fromEmail === '') {
            return ['ok' => false, 'error' => 'SMTP não configurado. Preencha host e e-mail do remetente.'];
        }
        if (!filter_var($to, FILTER_VALIDATE_EMAIL) || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'error' => 'E-mail de origem ou destino inválido.'];
        }

        try {
            return self::transmit($host, $port, $enc, $user, $pass, $fromName, $fromEmail, $to, $subject, $htmlBody, $replyTo);
        } catch (\Throwable $e) {
            Logger::error('Falha SMTP: ' . $e->getMessage());
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * @return array{ok:bool,error:string}
     */
    private static function transmit(
        string $host,
        int $port,
        string $enc,
        string $user,
        string $pass,
        string $fromName,
        string $fromEmail,
        string $to,
        string $subject,
        string $htmlBody,
        ?string $replyTo
    ): array {
        $enc = strtolower($enc);
        $transport = ($enc === 'ssl' || $port === 465) ? 'ssl://' : '';

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
                'allow_self_signed' => false,
            ],
        ]);

        $errno = 0;
        $errstr = '';
        $fp = @stream_socket_client(
            $transport . $host . ':' . $port,
            $errno,
            $errstr,
            15,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$fp) {
            throw new \RuntimeException("Não foi possível conectar ao servidor SMTP ({$errstr}).");
        }
        stream_set_timeout($fp, 15);

        $read = static function () use ($fp): string {
            $data = '';
            while (($line = fgets($fp, 515)) !== false) {
                $data .= $line;
                // A 4ª posição ' ' (vs '-') indica fim da resposta multiline.
                if (isset($line[3]) && $line[3] === ' ') {
                    break;
                }
            }
            return $data;
        };
        $write = static function (string $cmd) use ($fp): void {
            fwrite($fp, $cmd . "\r\n");
        };
        $expect = static function (string $response, array $codes) {
            $code = (int) substr($response, 0, 3);
            if (!in_array($code, $codes, true)) {
                throw new \RuntimeException('Resposta inesperada do SMTP: ' . trim($response));
            }
        };

        $expect($read(), [220]);

        $hostname = $_SERVER['SERVER_NAME'] ?? 'localhost';
        $write('EHLO ' . $hostname);
        $ehlo = $read();
        $expect($ehlo, [250]);

        // STARTTLS
        if ($enc === 'tls' && $transport === '') {
            $write('STARTTLS');
            $expect($read(), [220]);
            if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT)) {
                throw new \RuntimeException('Falha ao iniciar TLS.');
            }
            $write('EHLO ' . $hostname);
            $expect($read(), [250]);
        }

        // Autenticação (AUTH LOGIN) quando há usuário.
        if ($user !== '') {
            $write('AUTH LOGIN');
            $expect($read(), [334]);
            $write(base64_encode($user));
            $expect($read(), [334]);
            $write(base64_encode($pass));
            $expect($read(), [235]);
        }

        $write('MAIL FROM:<' . $fromEmail . '>');
        $expect($read(), [250]);
        $write('RCPT TO:<' . $to . '>');
        $expect($read(), [250, 251]);
        $write('DATA');
        $expect($read(), [354]);

        $headers = self::buildHeaders($fromName, $fromEmail, $to, $subject, $replyTo);
        $message = $headers . "\r\n" . self::prepareBody($htmlBody);

        // "Dot stuffing": linhas iniciando com '.' recebem um '.' extra.
        $message = preg_replace('/^\./m', '..', $message) ?? $message;

        $write($message);
        $write('.');
        $expect($read(), [250]);

        $write('QUIT');
        @fclose($fp);

        return ['ok' => true, 'error' => ''];
    }

    private static function buildHeaders(string $fromName, string $fromEmail, string $to, string $subject, ?string $replyTo): string
    {
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $encodedFromName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';

        $headers = [];
        $headers[] = 'Date: ' . date('r');
        $headers[] = 'From: ' . $encodedFromName . ' <' . $fromEmail . '>';
        $headers[] = 'To: <' . $to . '>';
        if ($replyTo !== null && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $headers[] = 'Reply-To: <' . $replyTo . '>';
        }
        $headers[] = 'Subject: ' . $encodedSubject;
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = 'Content-Transfer-Encoding: base64';
        $headers[] = 'Message-ID: <' . bin2hex(random_bytes(12)) . '@' . ($_SERVER['SERVER_NAME'] ?? 'mafedo.local') . '>';

        return implode("\r\n", $headers);
    }

    private static function prepareBody(string $htmlBody): string
    {
        // Base64 em linhas de 76 caracteres (RFC 2045).
        return chunk_split(base64_encode($htmlBody), 76, "\r\n");
    }
}
