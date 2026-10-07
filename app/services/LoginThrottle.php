<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

/**
 * Proteção contra brute force no login.
 *
 * Bloqueia após MAX_ATTEMPTS tentativas falhas (por e-mail OU por IP) dentro de
 * uma janela de DECAY_MINUTES minutos.
 */
final class LoginThrottle
{
    private const MAX_ATTEMPTS = 5;
    private const DECAY_MINUTES = 15;

    public static function tooManyAttempts(string $identifier, string $ip): bool
    {
        $since = date('Y-m-d H:i:s', time() - self::DECAY_MINUTES * 60);

        $byEmail = (int) Database::scalar(
            'SELECT COUNT(*) FROM login_attempts
             WHERE identifier = :id AND successful = 0 AND attempted_at >= :since',
            ['id' => $identifier, 'since' => $since]
        );
        $byIp = (int) Database::scalar(
            'SELECT COUNT(*) FROM login_attempts
             WHERE ip_address = :ip AND successful = 0 AND attempted_at >= :since',
            ['ip' => $ip, 'since' => $since]
        );

        return $byEmail >= self::MAX_ATTEMPTS || $byIp >= self::MAX_ATTEMPTS;
    }

    public static function record(string $identifier, string $ip, bool $successful): void
    {
        Database::run(
            'INSERT INTO login_attempts (identifier, ip_address, successful, attempted_at)
             VALUES (:id, :ip, :s, :at)',
            [
                'id' => mb_substr($identifier, 0, 190),
                'ip' => $ip,
                's'  => $successful ? 1 : 0,
                'at' => date('Y-m-d H:i:s'),
            ]
        );
    }

    /** Limpa as tentativas falhas após um login bem-sucedido. */
    public static function clear(string $identifier, string $ip): void
    {
        Database::run(
            'DELETE FROM login_attempts WHERE identifier = :id OR ip_address = :ip',
            ['id' => $identifier, 'ip' => $ip]
        );
    }

    public static function remainingLockMinutes(): int
    {
        return self::DECAY_MINUTES;
    }
}
