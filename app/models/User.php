<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class User extends BaseModel
{
    protected string $table = 'users';
    protected array $fillable = [
        'name', 'email', 'password', 'status', 'last_login_at', 'created_at', 'updated_at',
    ];

    public function all(string $orderBy = 'name', string $direction = 'ASC'): array
    {
        return parent::all($orderBy, $direction);
    }

    public function emailExists(string $email, ?int $ignoreId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM users WHERE email = :e';
        $params = ['e' => $email];
        if ($ignoreId !== null) {
            $sql .= ' AND id <> :id';
            $params['id'] = $ignoreId;
        }
        return (int) Database::scalar($sql, $params) > 0;
    }

    /** @return string[] nomes dos papéis do usuário */
    public function roleNames(int $userId): array
    {
        $rows = Database::fetchAll(
            'SELECT r.name FROM roles r
             INNER JOIN user_roles ur ON ur.role_id = r.id
             WHERE ur.user_id = :id',
            ['id' => $userId]
        );
        return array_map(static fn ($r) => $r['name'], $rows);
    }

    /** @return int[] ids dos papéis do usuário */
    public function roleIds(int $userId): array
    {
        $rows = Database::fetchAll(
            'SELECT role_id FROM user_roles WHERE user_id = :id',
            ['id' => $userId]
        );
        return array_map(static fn ($r) => (int) $r['role_id'], $rows);
    }

    public function syncRoles(int $userId, array $roleIds): void
    {
        Database::run('DELETE FROM user_roles WHERE user_id = :id', ['id' => $userId]);
        foreach (array_unique(array_map('intval', $roleIds)) as $rid) {
            Database::run(
                'INSERT INTO user_roles (user_id, role_id) VALUES (:u, :r)',
                ['u' => $userId, 'r' => $rid]
            );
        }
    }

    public function updatePassword(int $userId, string $plainPassword): void
    {
        Database::run(
            'UPDATE users SET password = :p, updated_at = :u WHERE id = :id',
            ['p' => password_hash($plainPassword, PASSWORD_DEFAULT), 'u' => date('Y-m-d H:i:s'), 'id' => $userId]
        );
    }

    public function isSuperAdmin(int $userId): bool
    {
        return in_array('super-admin', $this->roleNames($userId), true);
    }

    /** Conta quantos super admins ativos existem (para impedir remover o último). */
    public function activeSuperAdminCount(): int
    {
        return (int) Database::scalar(
            "SELECT COUNT(DISTINCT u.id) FROM users u
             INNER JOIN user_roles ur ON ur.user_id = u.id
             INNER JOIN roles r ON r.id = ur.role_id
             WHERE r.name = 'super-admin' AND u.status = 1"
        );
    }
}
