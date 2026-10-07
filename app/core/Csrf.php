<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Proteção CSRF por token sincronizado em sessão.
 */
final class Csrf
{
    private const KEY = '__csrf_token';
    public const FIELD = '_csrf';

    public static function token(): string
    {
        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::KEY];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="' . self::FIELD . '" value="' . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function validate(?string $token): bool
    {
        $stored = $_SESSION[self::KEY] ?? '';
        return is_string($token) && $stored !== '' && hash_equals($stored, $token);
    }
}
