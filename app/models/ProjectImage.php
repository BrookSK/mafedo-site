<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class ProjectImage extends BaseModel
{
    protected string $table = 'project_images';
    protected array $fillable = ['project_id', 'image', 'alt_text', 'sort_order', 'created_at'];

    /** @return array<int,array> imagens de um projeto, ordenadas */
    public function forProject(int $projectId): array
    {
        return Database::fetchAll(
            'SELECT * FROM project_images WHERE project_id = :id ORDER BY sort_order ASC, id ASC',
            ['id' => $projectId]
        );
    }

    public function deleteForProject(int $projectId): void
    {
        Database::run('DELETE FROM project_images WHERE project_id = :id', ['id' => $projectId]);
    }

    public function nextSortOrder(int $projectId): int
    {
        $max = Database::scalar(
            'SELECT MAX(sort_order) FROM project_images WHERE project_id = :id',
            ['id' => $projectId]
        );
        return (int) $max + 1;
    }
}
