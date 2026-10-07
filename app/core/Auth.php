<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Autenticação e autorização (RBAC) do painel administrativo.
 *
 * - Verifica credenciais com password_verify().
 * - Carrega papéis (roles) e permissões do usuário autenticado.
 * - Super Admin (papel 'super-admin') tem acesso irrestrito.
 */
final class Auth
{
    private const SESSION_KEY = 'auth_user_id';

    /** Tenta autenticar. Retorna o usuário em caso de sucesso, ou null. */
    public static function attempt(string $email, string $password): ?array
    {
        $user = Database::fetch(
            'SELECT * FROM users WHERE email = :email LIMIT 1',
            ['email' => $email]
        );

        if ($user === null || (int) $user['status'] !== 1) {
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }

        // Rehash se o algoritmo/custo mudou.
        if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
            Database::run(
                'UPDATE users SET password = :p WHERE id = :id',
                ['p' => password_hash($password, PASSWORD_DEFAULT), 'id' => $user['id']]
            );
        }

        return $user;
    }

    public static function login(array $user): void
    {
        Session::regenerate();
        Session::set(self::SESSION_KEY, (int) $user['id']);
        Database::run(
            'UPDATE users SET last_login_at = :now WHERE id = :id',
            ['now' => date('Y-m-d H:i:s'), 'id' => $user['id']]
        );
    }

    public static function logout(): void
    {
        Session::remove(self::SESSION_KEY);
        Session::regenerate();
    }

    public static function check(): bool
    {
        return Session::get(self::SESSION_KEY) !== null;
    }

    public static function id(): ?int
    {
        $id = Session::get(self::SESSION_KEY);
        return $id === null ? null : (int) $id;
    }

    private static ?array $cachedUser = null;

    public static function user(): ?array
    {
        if (self::$cachedUser !== null) {
            return self::$cachedUser;
        }
        $id = self::id();
        if ($id === null) {
            return null;
        }
        $user = Database::fetch('SELECT * FROM users WHERE id = :id LIMIT 1', ['id' => $id]);
        if ($user === null || (int) $user['status'] !== 1) {
            self::logout();
            return null;
        }
        self::$cachedUser = $user;
        return $user;
    }

    /** @return string[] nomes dos papéis do usuário atual */
    public static function roles(): array
    {
        $id = self::id();
        if ($id === null) {
            return [];
        }
        $rows = Database::fetchAll(
            'SELECT r.name FROM roles r
             INNER JOIN user_roles ur ON ur.role_id = r.id
             WHERE ur.user_id = :id',
            ['id' => $id]
        );
        return array_map(static fn ($r) => $r['name'], $rows);
    }

    public static function isSuperAdmin(): bool
    {
        return in_array('super-admin', self::roles(), true);
    }

    /** @return string[] permissões efetivas do usuário atual */
    public static function permissions(): array
    {
        $id = self::id();
        if ($id === null) {
            return [];
        }
        $rows = Database::fetchAll(
            'SELECT DISTINCT p.name FROM permissions p
             INNER JOIN role_permissions rp ON rp.permission_id = p.id
             INNER JOIN user_roles ur ON ur.role_id = rp.role_id
             WHERE ur.user_id = :id',
            ['id' => $id]
        );
        return array_map(static fn ($r) => $r['name'], $rows);
    }

    public static function can(string $permission): bool
    {
        if (self::isSuperAdmin()) {
            return true;
        }
        return in_array($permission, self::permissions(), true);
    }
}
