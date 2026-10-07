<?php
/**
 * CLI de migrations.
 *
 * Uso:
 *   php database/migrate.php          # aplica migrations pendentes
 *   php database/migrate.php status   # lista o status das migrations
 *
 * O driver (mysql/sqlite) vem de config/config.php (e config.local.php).
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Este script só pode ser executado via linha de comando.');
}

$basePath = dirname(__DIR__);

require $basePath . '/app/core/Autoloader.php';
(new App\Core\Autoloader($basePath . '/app'))->register();

use App\Core\Config;
use App\Core\Migrator;

Config::load(require $basePath . '/config/config.php');
date_default_timezone_set((string) Config::get('timezone', 'America/Sao_Paulo'));

$migrator = new Migrator($basePath . '/database/migrations');
$command = $argv[1] ?? 'migrate';

// Garante conexão (define o driver efetivo) antes de qualquer echo de status.
App\Core\Database::connection();

try {
    if ($command === 'status') {
        echo "Status das migrations (driver: " . App\Core\Database::driver() . "):\n";
        foreach ($migrator->status() as $name => $state) {
            printf("  [%s] %s\n", $state === 'aplicada' ? 'X' : ' ', $name);
        }
        exit(0);
    }

    echo "Executando migrations (driver: " . App\Core\Database::driver() . ")...\n";
    $result = $migrator->run();

    foreach ($result['skipped'] as $name) {
        echo "  - ignorada (já aplicada): {$name}\n";
    }
    foreach ($result['applied'] as $name) {
        echo "  + aplicada: {$name}\n";
    }
    if ($result['applied'] === []) {
        echo "Nenhuma migration pendente.\n";
    } else {
        echo count($result['applied']) . " migration(s) aplicada(s) com sucesso.\n";
    }
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, "ERRO: " . $e->getMessage() . "\n");
    exit(1);
}
