<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Service extends BaseModel
{
    protected string $table = 'services';
    protected array $fillable = [
        'title', 'slug', 'short_description', 'description', 'image', 'icon',
        'featured', 'sort_order', 'status', 'seo_title', 'seo_description',
        'created_at', 'updated_at',
    ];

    /** Serviços ativos, ordenados — para o site público. */
    public function active(): array
    {
        return Database::fetchAll(
            'SELECT * FROM services WHERE status = 1 ORDER BY sort_order ASC, title ASC'
        );
    }

    /** Serviços ativos marcados como destaque — para a Home. */
    public function featured(int $limit = 6): array
    {
        return Database::fetchAll(
            'SELECT * FROM services WHERE status = 1 AND featured = 1
             ORDER BY sort_order ASC, title ASC LIMIT ' . (int) $limit
        );
    }

    public function activeBySlug(string $slug): ?array
    {
        return Database::fetch(
            'SELECT * FROM services WHERE slug = :slug AND status = 1 LIMIT 1',
            ['slug' => $slug]
        );
    }

    /** Listagem administrativa. */
    public function paginatedAdmin(): array
    {
        return Database::fetchAll(
            'SELECT * FROM services ORDER BY sort_order ASC, created_at DESC'
        );
    }
}
