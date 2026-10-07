<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;

/**
 * Contrato de middleware. handle() retorna true para prosseguir ou interrompe
 * a execução (redirect/response) por conta própria.
 */
interface Middleware
{
    public function handle(Request $request, array $params): bool;
}
