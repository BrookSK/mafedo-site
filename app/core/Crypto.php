<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Criptografia autenticada (AES-256-GCM) para valores sensíveis armazenados no
 * banco — por exemplo, a senha do SMTP. A chave deriva de app_key (config).
 *
 * Formato do texto cifrado: base64( iv | tag | ciphertext ), prefixado por "enc:".
 */
final class Crypto
{
    private const PREFIX = 'enc:';
    private const CIPHER = 'aes-256-gcm';

    private static function key(): string
    {
        $appKey = (string) Config::get('app_key', '');
        // Deriva 32 bytes determinísticos da app_key.
        return hash('sha256', 'mafedo|' . $appKey, true);
    }

    public static function encrypt(string $plaintext): string
    {
        $iv = random_bytes(12);
        $tag = '';
        $ciphertext = openssl_encrypt($plaintext, self::CIPHER, self::key(), OPENSSL_RAW_DATA, $iv, $tag);
        if ($ciphertext === false) {
            return $plaintext; // fallback seguro: não quebra a aplicação
        }
        return self::PREFIX . base64_encode($iv . $tag . $ciphertext);
    }

    public static function decrypt(string $value): string
    {
        if (!str_starts_with($value, self::PREFIX)) {
            return $value; // valor não criptografado (compatibilidade)
        }
        $raw = base64_decode(substr($value, strlen(self::PREFIX)), true);
        if ($raw === false || strlen($raw) < 28) {
            return '';
        }
        $iv = substr($raw, 0, 12);
        $tag = substr($raw, 12, 16);
        $ciphertext = substr($raw, 28);
        $plaintext = openssl_decrypt($ciphertext, self::CIPHER, self::key(), OPENSSL_RAW_DATA, $iv, $tag);
        return $plaintext === false ? '' : $plaintext;
    }

    public static function isEncrypted(string $value): bool
    {
        return str_starts_with($value, self::PREFIX);
    }
}
