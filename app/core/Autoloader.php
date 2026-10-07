<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Autoloader (sem Composer).
 *
 * Mapeia o namespace raiz "App\" para o diretório /app.
 *
 * Resolução de caminho tolerante a convenções mistas de capitalização de
 * pastas. No Windows (filesystem case-insensitive) qualquer variação funciona;
 * em Linux (case-sensitive) tentamos, em ordem:
 *   1) sub-pastas exatamente como no namespace  (ex.: controllers/Site/HomeController.php)
 *   2) sub-pastas totalmente em minúsculas       (ex.: core/Database.php, models/User.php)
 *   3) primeiro segmento minúsculo + demais como no namespace
 *      (ex.: controllers/Site/..., controllers/Admin/...)
 *
 * O nome da CLASSE (último segmento) sempre preserva o PascalCase do arquivo.
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

        foreach ($this->candidateDirSets($parts) as $dirs) {
            $pathParts = array_merge($dirs, [$className]);
            $file = $this->baseDir . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $pathParts) . '.php';
            if (is_file($file)) {
                require $file;
                return;
            }
        }
    }

    /**
     * Gera as variações de diretórios a serem testadas, sem duplicar.
     *
     * @param string[] $parts segmentos de namespace entre App\ e a classe
     * @return array<int,string[]>
     */
    private function candidateDirSets(array $parts): array
    {
        if ($parts === []) {
            return [[]];
        }

        $asIs = $parts;                              // Controllers\Site -> controllers/Site? (ver abaixo)
        $lower = array_map('strtolower', $parts);    // tudo minúsculo

        // Primeiro segmento minúsculo (raiz da camada: controllers, models, ...),
        // demais segmentos preservando o case do namespace (Site, Admin, ...).
        $firstLowerRestAsIs = $parts;
        $firstLowerRestAsIs[0] = strtolower($firstLowerRestAsIs[0]);

        $candidates = [$firstLowerRestAsIs, $lower, $asIs];

        // Remove duplicatas preservando a ordem.
        $seen = [];
        $unique = [];
        foreach ($candidates as $set) {
            $key = implode('/', $set);
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $unique[] = $set;
            }
        }
        return $unique;
    }
}
