<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

/**
 * Model base com operações CRUD genéricas usando prepared statements.
 * Cada model concreto define $table e $fillable.
 */
abstract class BaseModel
{
    protected string $table = '';
    /** @var string[] colunas que podem ser preenchidas em insert/update */
    protected array $fillable = [];

    public function find(int $id): ?array
    {
        return Database::fetch("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1", ['id' => $id]);
    }

    public function findBy(string $column, mixed $value): ?array
    {
        $this->assertColumn($column);
        return Database::fetch("SELECT * FROM {$this->table} WHERE {$column} = :v LIMIT 1", ['v' => $value]);
    }

    public function all(string $orderBy = 'id', string $direction = 'ASC'): array
    {
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $this->assertColumn($orderBy);
        return Database::fetchAll("SELECT * FROM {$this->table} ORDER BY {$orderBy} {$direction}");
    }

    public function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        if ($where !== '') {
            $sql .= " WHERE {$where}";
        }
        return (int) Database::scalar($sql, $params);
    }

    public function create(array $data): int
    {
        $data = $this->filter($data);
        $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');

        $columns = array_keys($data);
        $placeholders = array_map(static fn ($c) => ':' . $c, $columns);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        return (int) Database::insert($sql, $data);
    }

    public function update(int $id, array $data): bool
    {
        $data = $this->filter($data);
        $data['updated_at'] = date('Y-m-d H:i:s');

        $sets = array_map(static fn ($c) => "{$c} = :{$c}", array_keys($data));
        $data['id'] = $id;

        $sql = sprintf('UPDATE %s SET %s WHERE id = :id', $this->table, implode(', ', $sets));
        Database::run($sql, $data);
        return true;
    }

    public function delete(int $id): bool
    {
        Database::run("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
        return true;
    }

    /** Mantém apenas as colunas permitidas (fillable). */
    protected function filter(array $data): array
    {
        if ($this->fillable === []) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /** Protege contra injeção em nomes de coluna (orderBy/where dinâmicos). */
    protected function assertColumn(string $column): void
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
            throw new \InvalidArgumentException('Nome de coluna inválido.');
        }
    }

    /**
     * Gera um slug único para a tabela, ignorando opcionalmente um ID (edição).
     */
    public function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = slugify($base);
        $candidate = $slug;
        $i = 2;
        while ($this->slugExists($candidate, $ignoreId)) {
            $candidate = $slug . '-' . $i;
            $i++;
        }
        return $candidate;
    }

    protected function slugExists(string $slug, ?int $ignoreId): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE slug = :slug";
        $params = ['slug' => $slug];
        if ($ignoreId !== null) {
            $sql .= ' AND id <> :id';
            $params['id'] = $ignoreId;
        }
        return (int) Database::scalar($sql, $params) > 0;
    }
}
