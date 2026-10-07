<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Csrf;
use App\Core\Session;

/**
 * Funções auxiliares globais, carregadas no bootstrap.
 */

if (!function_exists('e')) {
    /** Escapa saída HTML (proteção XSS). */
    function e(mixed $value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('base_url')) {
    function base_url(): string
    {
        $configured = (string) Config::get('base_url', '');
        if ($configured !== '') {
            return rtrim($configured, '/');
        }
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        if (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') {
            $scheme = 'https';
        }
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host;
    }
}

if (!function_exists('url')) {
    /** Monta uma URL absoluta a partir de um caminho da aplicação. */
    function url(string $path = ''): string
    {
        return base_url() . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /** URL para um arquivo estático em /public/assets. */
    function asset(string $path): string
    {
        return base_url() . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('upload_url')) {
    /** URL para um arquivo enviado (uploads). Retorna placeholder se vazio. */
    function upload_url(?string $file): string
    {
        if ($file === null || $file === '') {
            return asset('images/placeholder.svg');
        }
        if (str_starts_with($file, 'http://') || str_starts_with($file, 'https://')) {
            return $file;
        }
        // Caminho absoluto da aplicação (ex.: '/assets/...') — serve direto.
        if (str_starts_with($file, '/')) {
            return base_url() . $file;
        }
        return base_url() . rtrim((string) Config::get('uploads_url', '/uploads'), '/') . '/' . ltrim($file, '/');
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return Csrf::field();
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return Csrf::token();
    }
}

if (!function_exists('old')) {
    /**
     * Recupera um valor antigo de formulário após falha de validação.
     * O bootstrap move o flash '__old' para '__old_current' no início da
     * requisição, então aqui apenas lemos esse array já consolidado.
     */
    function old(string $key, string $default = ''): string
    {
        $current = Session::get('__old_current', []);
        return isset($current[$key]) ? (string) $current[$key] : $default;
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        $text = trim($text);

        // Transliteração confiável de acentos (independe do iconv do servidor).
        $map = [
            'á'=>'a','à'=>'a','ã'=>'a','â'=>'a','ä'=>'a','Á'=>'a','À'=>'a','Ã'=>'a','Â'=>'a','Ä'=>'a',
            'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','É'=>'e','È'=>'e','Ê'=>'e','Ë'=>'e',
            'í'=>'i','ì'=>'i','î'=>'i','ï'=>'i','Í'=>'i','Ì'=>'i','Î'=>'i','Ï'=>'i',
            'ó'=>'o','ò'=>'o','õ'=>'o','ô'=>'o','ö'=>'o','Ó'=>'o','Ò'=>'o','Õ'=>'o','Ô'=>'o','Ö'=>'o',
            'ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u','Ú'=>'u','Ù'=>'u','Û'=>'u','Ü'=>'u',
            'ç'=>'c','Ç'=>'c','ñ'=>'n','Ñ'=>'n',
        ];
        $text = strtr($text, $map);

        // Fallback adicional para quaisquer outros acentos remanescentes.
        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
            if ($converted !== false && $converted !== '') {
                $text = $converted;
            }
        }

        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
        $text = trim($text, '-');
        return $text === '' ? 'item' : $text;
    }
}

if (!function_exists('str_excerpt')) {
    function str_excerpt(string $text, int $length = 160): string
    {
        $text = trim(strip_tags($text));
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return rtrim(mb_substr($text, 0, $length)) . '…';
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): never
    {
        \App\Core\Response::redirect(str_starts_with($path, 'http') ? $path : url($path));
    }
}
