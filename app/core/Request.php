<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Abstração da requisição HTTP atual.
 */
final class Request
{
    private string $method;
    private string $path;
    private array $query;
    private array $body;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        // A URL vem via parâmetro ?url= do front controller (.htaccess) ou do PATH_INFO.
        $url = $_GET['url'] ?? ($_SERVER['PATH_INFO'] ?? '');
        if ($url === '') {
            $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
            $url = $uri;
        }
        $this->path = '/' . trim((string) $url, '/');

        $this->query = $_GET;
        unset($this->query['url']);
        $this->body = $_POST;

        // Suporte a method override (_method) para PUT/DELETE em formulários HTML.
        if ($this->method === 'POST' && isset($this->body['_method'])) {
            $override = strtoupper((string) $this->body['_method']);
            if (in_array($override, ['PUT', 'PATCH', 'DELETE'], true)) {
                $this->method = $override;
            }
        }
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function isPost(): bool
    {
        return $this->method === 'POST';
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    /** String limpa (trim). */
    public function str(string $key, string $default = ''): string
    {
        $value = $this->input($key, $default);
        return is_string($value) ? trim($value) : $default;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function only(array $keys): array
    {
        $out = [];
        foreach ($keys as $key) {
            $out[$key] = $this->input($key);
        }
        return $out;
    }

    public function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    public function files(string $key): ?array
    {
        return $_FILES[$key] ?? null;
    }

    public function ip(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public function userAgent(): string
    {
        return substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);
    }

    public function isAjax(): bool
    {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    }
}
