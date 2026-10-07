<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class Role extends BaseModel
{
    protected string $table = 'roles';
    protected array $fillable = ['name', 'description', 'created_at'];

    public function all(string $orderBy = 'name', string $direction = 'ASC'): array
    {
        return parent::all($orderBy, $direction);
    }

    /** @return string[] permissões de um papel */
    public function permissionNames(int $roleId): array
    {
        $rows = Database::fetchAll(
            'SELECT p.name FROM permissions p
             INNER JOIN role_permissions rp ON rp.permission_id = p.id
             WHERE rp.role_id = :id',
            ['id' => $roleId]
        );
        return array_map(static fn ($r) => $r['name'], $rows);
    }
}
