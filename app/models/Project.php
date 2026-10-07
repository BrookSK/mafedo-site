<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Project extends BaseModel
{
    protected string $table = 'projects';
    protected array $fillable = [
        'title', 'slug', 'short_description', 'description', 'main_image',
        'category', 'location', 'year', 'client', 'segment', 'characteristics',
        'featured', 'sort_order', 'status', 'seo_title', 'seo_description',
        'created_at', 'updated_at',
    ];

    /** Projetos ativos, com filtro opcional por categoria. */
    public function active(?string $category = null): array
    {
        if ($category !== null && $category !== '') {
            return Database::fetchAll(
                'SELECT * FROM projects WHERE status = 1 AND category = :cat
                 ORDER BY sort_order ASC, created_at DESC',
                ['cat' => $category]
            );
        }
        return Database::fetchAll(
            'SELECT * FROM projects WHERE status = 1 ORDER BY sort_order ASC, created_at DESC'
        );
    }

    public function featured(int $limit = 6): array
    {
        return Database::fetchAll(
            'SELECT * FROM projects WHERE status = 1 AND featured = 1
             ORDER BY sort_order ASC, created_at DESC LIMIT ' . (int) $limit
        );
    }

    public function activeBySlug(string $slug): ?array
    {
        return Database::fetch(
            'SELECT * FROM projects WHERE slug = :slug AND status = 1 LIMIT 1',
            ['slug' => $slug]
        );
    }

    /** Categorias distintas (para filtros no site). */
    public function categories(): array
    {
        $rows = Database::fetchAll(
            "SELECT DISTINCT category FROM projects
             WHERE status = 1 AND category IS NOT NULL AND category <> ''
             ORDER BY category ASC"
        );
        return array_map(static fn ($r) => $r['category'], $rows);
    }

    /** Projetos relacionados (mesma categoria), excluindo o atual. */
    public function related(int $excludeId, ?string $category, int $limit = 3): array
    {
        if ($category !== null && $category !== '') {
            return Database::fetchAll(
                'SELECT * FROM projects WHERE status = 1 AND category = :cat AND id <> :id
                 ORDER BY sort_order ASC, created_at DESC LIMIT ' . (int) $limit,
                ['cat' => $category, 'id' => $excludeId]
            );
        }
        return Database::fetchAll(
            'SELECT * FROM projects WHERE status = 1 AND id <> :id
             ORDER BY sort_order ASC, created_at DESC LIMIT ' . (int) $limit,
            ['id' => $excludeId]
        );
    }

    public function paginatedAdmin(): array
    {
        return Database::fetchAll(
            'SELECT * FROM projects ORDER BY sort_order ASC, created_at DESC'
        );
    }
}
