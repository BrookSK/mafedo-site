<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Autoloader (sem Composer).
 *
 * Mapeia o namespace raiz "App\" para o diretório /app.
 *
 * Convenção de pastas: os segmentos de namespace (sub-pastas) são escritos em
 * minúsculas no disco — conforme a estrutura MVC do projeto
 * (app/controllers, app/models, app/core, ...) — enquanto o nome da CLASSE
 * (último segmento) preserva o PascalCase do arquivo. Essa normalização torna o
 * carregamento previsível tanto no Windows quanto em servidores Linux
 * (sistemas de arquivos case-sensitive).
 *
 * Ex.: App\Controllers\Admin\ServiceController
 *      -> app/controllers/admin/ServiceController.php
 */
final class Autoloader
{
    private string $baseDir;
    private string $prefix = 'App\\';

    public function __construct(string $baseDir)
    {
        $this->baseDir = rtrim($baseDir, '/\\');
    }

    public function register(): void
    {
        spl_autoload_register([$this, 'load']);
    }

    public function load(string $class): void
    {
        if (!str_starts_with($class, $this->prefix)) {
            return;
        }

        $relative = substr($class, strlen($this->prefix));
        $parts = explode('\\', $relative);
        $className = array_pop($parts);

        // Sub-pastas em minúsculo; nome da classe preserva o case original.
        $dirs = array_map('strtolower', $parts);
        $pathParts = array_merge($dirs, [$className]);

        $file = $this->baseDir . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $pathParts) . '.php';

        if (is_file($file)) {
            require $file;
        }
    }
}
