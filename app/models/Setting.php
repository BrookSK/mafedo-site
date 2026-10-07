<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Crypto;
use App\Core\Database;

/**
 * Acesso às configurações do site (tabela settings).
 *
 * - Carrega todas as configurações uma vez por requisição (cache em memória).
 * - Valores do tipo 'encrypted' (ex.: senha SMTP) são descriptografados na leitura
 *   e criptografados na escrita.
 */
final class Setting
{
    private static array $cache = [];
    private static array $types = [];
    private static bool $loaded = false;

    private static function load(): void
    {
        if (self::$loaded) {
            return;
        }
        try {
            $rows = Database::fetchAll('SELECT setting_key, setting_value, type FROM settings');
        } catch (\Throwable) {
            // Banco ainda não migrado: opera com cache vazio.
            $rows = [];
        }
        foreach ($rows as $row) {
            $value = $row['setting_value'];
            if ($row['type'] === 'encrypted' && $value !== null && $value !== '') {
                $value = Crypto::decrypt($value);
            }
            self::$cache[$row['setting_key']] = $value;
            self::$types[$row['setting_key']] = $row['type'];
        }
        self::$loaded = true;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::load();
        return self::$cache[$key] ?? $default;
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $value = self::get($key, $default ? '1' : '0');
        return in_array((string) $value, ['1', 'true', 'on', 'yes'], true);
    }

    /** Retorna todas as configurações de um grupo (para formulários do painel). */
    public static function group(string $group): array
    {
        try {
            $rows = Database::fetchAll(
                'SELECT setting_key, setting_value, type FROM settings WHERE setting_group = :g ORDER BY id ASC',
                ['g' => $group]
            );
        } catch (\Throwable) {
            return [];
        }
        $out = [];
        foreach ($rows as $row) {
            $value = $row['setting_value'];
            if ($row['type'] === 'encrypted' && $value !== null && $value !== '') {
                $value = Crypto::decrypt($value);
            }
            $out[$row['setting_key']] = $value;
        }
        return $out;
    }

    /**
     * Define/atualiza um valor. Se a chave estiver marcada como 'encrypted',
     * o valor é criptografado antes de persistir.
     */
    public static function set(string $key, mixed $value): void
    {
        self::load();
        $type = self::$types[$key] ?? 'string';

        $stored = (string) $value;
        if ($type === 'encrypted' && $stored !== '') {
            $stored = Crypto::encrypt($stored);
        }

        $exists = Database::fetch('SELECT id FROM settings WHERE setting_key = :k', ['k' => $key]);
        if ($exists) {
            Database::run(
                'UPDATE settings SET setting_value = :v, updated_at = :u WHERE setting_key = :k',
                ['v' => $stored, 'u' => date('Y-m-d H:i:s'), 'k' => $key]
            );
        } else {
            Database::run(
                'INSERT INTO settings (setting_key, setting_value, type, setting_group, created_at)
                 VALUES (:k, :v, :t, :g, :c)',
                ['k' => $key, 'v' => $stored, 't' => $type, 'g' => 'general', 'c' => date('Y-m-d H:i:s')]
            );
        }

        // Atualiza cache (guarda valor em claro no cache).
        self::$cache[$key] = $value;
        self::$types[$key] = $type;
    }

    /** Salva vários valores de uma vez. */
    public static function setMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            self::set($key, $value);
        }
    }

    public static function clearCache(): void
    {
        self::$cache = [];
        self::$types = [];
        self::$loaded = false;
    }
}
