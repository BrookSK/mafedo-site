<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Helpers de resposta HTTP.
 */
final class Response
{
    public static function redirect(string $url, int $status = 302): never
    {
        header('Location: ' . $url, true, $status);
        exit;
    }

    public static function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function status(int $code): void
    {
        http_response_code($code);
    }

    /** Cabeçalhos de segurança aplicados a todas as respostas HTML. */
    public static function securityHeaders(): void
    {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('X-XSS-Protection: 0'); // obsoleto; CSP é a proteção moderna
        header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    }
}
