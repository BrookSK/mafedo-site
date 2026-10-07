<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use PDOStatement;
use RuntimeException;

/**
 * Camada fina sobre PDO.
 *
 * - Conexão única (singleton) por processo.
 * - Suporta MySQL/MariaDB (produção) e SQLite (desenvolvimento/testes).
 * - Sempre usa prepared statements para evitar SQL Injection.
 */
final class Database
{
    private static ?PDO $pdo = null;
    private static string $driver = 'mysql';

    public static function connection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $config = Config::get('db', []);
        $driver = $config['driver'] ?? 'mysql';
        self::$driver = $driver;

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            if ($driver === 'sqlite') {
                $path = $config['sqlite_path'];
                $dir = dirname($path);
                if (!is_dir($dir)) {
                    mkdir($dir, 0775, true);
                }
                self::$pdo = new PDO('sqlite:' . $path, null, null, $options);
                self::$pdo->exec('PRAGMA foreign_keys = ON');
            } else {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                    $config['host'] ?? '127.0.0.1',
                    $config['port'] ?? '3306',
                    $config['database'] ?? '',
                    $config['charset'] ?? 'utf8mb4'
                );
                self::$pdo = new PDO($dsn, $config['username'] ?? '', $config['password'] ?? '', $options);
            }
        } catch (PDOException $e) {
            // Não vaza credenciais nem detalhes para o usuário final.
            Logger::error('Falha ao conectar ao banco de dados: ' . $e->getMessage());
            throw new RuntimeException('Não foi possível conectar ao banco de dados.');
        }

        return self::$pdo;
    }

    public static function driver(): string
    {
        return self::$driver;
    }

    /** Executa uma query parametrizada e retorna o statement. */
    public static function run(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /** Retorna uma única linha (ou null). */
    public static function fetch(string $sql, array $params = []): ?array
    {
        $row = self::run($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    /** Retorna todas as linhas. */
    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll();
    }

    /** Retorna o valor da primeira coluna da primeira linha. */
    public static function scalar(string $sql, array $params = []): mixed
    {
        $value = self::run($sql, $params)->fetchColumn();
        return $value === false ? null : $value;
    }

    /** Insere e retorna o ID gerado. */
    public static function insert(string $sql, array $params = []): string
    {
        self::run($sql, $params);
        return self::connection()->lastInsertId();
    }

    public static function beginTransaction(): void
    {
        self::connection()->beginTransaction();
    }

    public static function commit(): void
    {
        self::connection()->commit();
    }

    public static function rollBack(): void
    {
        if (self::connection()->inTransaction()) {
            self::connection()->rollBack();
        }
    }
}
