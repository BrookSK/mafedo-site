<?php
/**
 * Front controller — ponto de entrada único da aplicação (web root = /public).
 */

declare(strict_types=1);

$basePath = dirname(__DIR__);

require $basePath . '/app/core/Autoloader.php';

$autoloader = new App\Core\Autoloader($basePath . '/app');
$autoloader->register();

$kernel = new App\Core\Kernel($basePath);
$kernel->boot();
$kernel->run();
