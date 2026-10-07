<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

/**
 * Executor de migrations.
 *
 * - Lê arquivos .sql de /database/migrations em ordem alfabética (prefixo numérico).
 * - Registra cada migration aplicada na tabela `migrations` (controle de versão).
 * - NUNCA reexecuta uma migration já aplicada.
 * - Quando o driver é SQLite, adapta o SQL (escrito para MySQL) de forma básica,
 *   permitindo rodar o mesmo conjunto de migrations em desenvolvimento.
 *
 * REGRA DO PROJETO: nunca edite uma migration já criada/aplicada. Para alterar o
 * schema, crie uma nova migration (ex.: 012_add_coluna.sql).
 */
final class Migrator
{
    private string $path;

    public function __construct(string $migrationsPath)
    {
        $this->path = rtrim($migrationsPath, '/\\');
    }

    private function ensureControlTable(): void
    {
        $driver = Database::driver();
        if ($driver === 'sqlite') {
            $sql = 'CREATE TABLE IF NOT EXISTS migrations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                migration VARCHAR(190) NOT NULL,
                batch INTEGER NOT NULL,
                applied_at TEXT NOT NULL
            )';
        } else {
            $sql = 'CREATE TABLE IF NOT EXISTS migrations (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                migration VARCHAR(190) NOT NULL,
                batch INT NOT NULL,
                applied_at DATETIME NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY uq_migrations_migration (migration)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';
        }
        Database::connection()->exec($sql);
    }

    /** @return string[] nomes de migrations já aplicadas */
    private function applied(): array
    {
        $rows = Database::fetchAll('SELECT migration FROM migrations ORDER BY migration ASC');
        return array_map(static fn ($r) => $r['migration'], $rows);
    }

    /** @return string[] caminhos de arquivos .sql ordenados */
    private function files(): array
    {
        $files = glob($this->path . '/*.sql') ?: [];
        sort($files, SORT_STRING);
        return $files;
    }

    private function nextBatch(): int
    {
        $max = Database::scalar('SELECT MAX(batch) FROM migrations');
        return (int) $max + 1;
    }

    /**
     * Executa todas as migrations pendentes.
     * @return array{applied:string[],skipped:string[]}
     */
    public function run(): array
    {
        $this->ensureControlTable();
        $applied = $this->applied();
        $batch = $this->nextBatch();

        $result = ['applied' => [], 'skipped' => []];

        foreach ($this->files() as $file) {
            $name = basename($file);
            if (in_array($name, $applied, true)) {
                $result['skipped'][] = $name;
                continue;
            }

            $sql = (string) file_get_contents($file);
            if (Database::driver() === 'sqlite') {
                $sql = $this->adaptToSqlite($sql);
            }

            $pdo = Database::connection();
            $pdo->beginTransaction();
            try {
                foreach ($this->splitStatements($sql) as $statement) {
                    if (trim($statement) !== '') {
                        $pdo->exec($statement);
                    }
                }
                $pdo->prepare('INSERT INTO migrations (migration, batch, applied_at) VALUES (:m, :b, :a)')
                    ->execute(['m' => $name, 'b' => $batch, 'a' => date('Y-m-d H:i:s')]);
                $pdo->commit();
                $result['applied'][] = $name;
            } catch (\Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                throw new \RuntimeException("Falha na migration {$name}: " . $e->getMessage(), 0, $e);
            }
        }

        return $result;
    }

    /** Divide um script em statements individuais (por ';' no fim da linha). */
    private function splitStatements(string $sql): array
    {
        // Remove comentários de linha iniciados por --
        $lines = preg_split('/\r?\n/', $sql) ?: [];
        $clean = [];
        foreach ($lines as $line) {
            $trimmed = ltrim($line);
            if (str_starts_with($trimmed, '--')) {
                continue;
            }
            $clean[] = $line;
        }
        $sql = implode("\n", $clean);

        $parts = explode(';', $sql);
        return array_map('trim', $parts);
    }

    /**
     * Adaptação pragmática de SQL MySQL -> SQLite para uso em desenvolvimento.
     * Cobre os padrões usados pelas migrations deste projeto.
     */
    private function adaptToSqlite(string $sql): string
    {
        // Remove opções de tabela específicas do MySQL
        $sql = preg_replace('/\)\s*ENGINE=\w+\s+DEFAULT\s+CHARSET=\w+(\s+COLLATE=\w+)?/i', ')', $sql) ?? $sql;
        $sql = preg_replace('/\s+COLLATE=\w+/i', '', $sql) ?? $sql;

        // Tipos: chave primária auto-incremento
        $sql = preg_replace(
            '/BIGINT\s+UNSIGNED\s+NOT\s+NULL\s+AUTO_INCREMENT/i',
            'INTEGER',
            $sql
        ) ?? $sql;
        // PRIMARY KEY (id) com coluna INTEGER vira autoincrement no SQLite
        // Convertemos "PRIMARY KEY (id)," + coluna id INTEGER para id INTEGER PRIMARY KEY AUTOINCREMENT
        // Estratégia: transformar a definição da coluna id e remover a linha PRIMARY KEY (id).
        if (preg_match('/\bid\s+INTEGER\b/i', $sql) && preg_match('/PRIMARY\s+KEY\s*\(\s*id\s*\)/i', $sql)) {
            $sql = preg_replace('/\bid\s+INTEGER\b/i', 'id INTEGER PRIMARY KEY AUTOINCREMENT', $sql, 1) ?? $sql;
            $sql = preg_replace('/,\s*PRIMARY\s+KEY\s*\(\s*id\s*\)/i', '', $sql) ?? $sql;
        }

        // Demais tipos
        $sql = preg_replace('/BIGINT\s+UNSIGNED/i', 'INTEGER', $sql) ?? $sql;
        $sql = preg_replace('/\bBIGINT\b/i', 'INTEGER', $sql) ?? $sql;
        $sql = preg_replace('/\bTINYINT\b/i', 'INTEGER', $sql) ?? $sql;
        $sql = preg_replace('/\bINT\b/i', 'INTEGER', $sql) ?? $sql;
        $sql = preg_replace('/\bLONGTEXT\b/i', 'TEXT', $sql) ?? $sql;
        $sql = preg_replace('/\bDATETIME\b/i', 'TEXT', $sql) ?? $sql;
        $sql = preg_replace('/VARCHAR\(\d+\)/i', 'TEXT', $sql) ?? $sql;

        // Índices nomeados inline (KEY idx ...) não são suportados dentro de CREATE TABLE no SQLite.
        // Removemos linhas que começam com KEY/UNIQUE KEY, convertendo UNIQUE KEY em UNIQUE inline não é trivial;
        // para dev, transformamos "UNIQUE KEY nome (col)" em "UNIQUE (col)" e removemos "KEY nome (col)".
        $sql = preg_replace('/,\s*UNIQUE\s+KEY\s+\w+\s*\(([^)]+)\)/i', ', UNIQUE ($1)', $sql) ?? $sql;
        $sql = preg_replace('/,\s*KEY\s+\w+\s*\([^)]+\)/i', '', $sql) ?? $sql;

        // CONSTRAINT fk ... FOREIGN KEY mantém-se compatível no SQLite.

        // --- Compatibilidade de comandos de dados (seeds) ---
        // INSERT IGNORE INTO -> INSERT OR IGNORE INTO
        $sql = preg_replace('/INSERT\s+IGNORE\s+INTO/i', 'INSERT OR IGNORE INTO', $sql) ?? $sql;
        // NOW() / CURRENT_TIMESTAMP() -> CURRENT_TIMESTAMP
        $sql = preg_replace('/\bNOW\(\)/i', "CURRENT_TIMESTAMP", $sql) ?? $sql;
        // ON DUPLICATE KEY UPDATE não é suportado — não usado nos seeds deste projeto.
        // CROSS JOIN é suportado pelo SQLite.

        return $sql;
    }

    /** Lista o status (aplicadas x pendentes). */
    public function status(): array
    {
        $this->ensureControlTable();
        $applied = $this->applied();
        $status = [];
        foreach ($this->files() as $file) {
            $name = basename($file);
            $status[$name] = in_array($name, $applied, true) ? 'aplicada' : 'pendente';
        }
        return $status;
    }
}
