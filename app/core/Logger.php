<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Logger de arquivo simples. Nunca expõe conteúdo ao usuário final.
 */
final class Logger
{
    private static function logDir(): string
    {
        $dir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        return $dir;
    }

    private static function write(string $level, string $message): void
    {
        $line = sprintf('[%s] %s: %s%s', date('Y-m-d H:i:s'), strtoupper($level), $message, PHP_EOL);
        @file_put_contents(self::logDir() . '/app-' . date('Y-m-d') . '.log', $line, FILE_APPEND | LOCK_EX);
    }

    public static function error(string $message): void
    {
        self::write('error', $message);
    }

    public static function warning(string $message): void
    {
        self::write('warning', $message);
    }

    public static function info(string $message): void
    {
        self::write('info', $message);
    }
}
