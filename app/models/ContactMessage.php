<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class ContactMessage extends BaseModel
{
    protected string $table = 'contact_messages';
    protected array $fillable = [
        'name', 'email', 'phone', 'company', 'subject', 'message',
        'status', 'ip_address', 'user_agent', 'email_sent', 'created_at',
    ];

    public const STATUS_UNREAD = 0;
    public const STATUS_READ = 1;

    public function latest(int $limit = 50): array
    {
        return Database::fetchAll(
            'SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT ' . (int) $limit
        );
    }

    public function unreadCount(): int
    {
        return $this->count('status = :s', ['s' => self::STATUS_UNREAD]);
    }

    public function setStatus(int $id, int $status): void
    {
        Database::run(
            'UPDATE contact_messages SET status = :s WHERE id = :id',
            ['s' => $status, 'id' => $id]
        );
    }

    public function markEmailSent(int $id, bool $sent): void
    {
        Database::run(
            'UPDATE contact_messages SET email_sent = :e WHERE id = :id',
            ['e' => $sent ? 1 : 0, 'id' => $id]
        );
    }

    /** Conta mensagens recentes de um IP (anti-spam simples). */
    public function countRecentFromIp(string $ip, int $minutes = 10): int
    {
        $since = date('Y-m-d H:i:s', time() - $minutes * 60);
        return $this->count('ip_address = :ip AND created_at >= :since', ['ip' => $ip, 'since' => $since]);
    }
}
