<?php
/**
 * Front controller — ponto de entrada único da aplicação (web root = /public).
 */

declare(strict_types=1);

// Servidor embutido do PHP (php -S): entrega arquivos estáticos existentes
// diretamente, sem passar pelo front controller. Em produção (Apache) este
// bloco é ignorado — quem serve os estáticos é o servidor web.
if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($file !== __DIR__ . '/' && is_file($file)) {
        return false;
    }
}

$basePath = dirname(__DIR__);

require $basePath . '/app/core/Autoloader.php';

$autoloader = new App\Core\Autoloader($basePath . '/app');
$autoloader->register();

$kernel = new App\Core\Kernel($basePath);
$kernel->boot();
$kernel->run();
