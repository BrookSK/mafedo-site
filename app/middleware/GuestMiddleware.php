<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;

/**
 * Permite acesso apenas a visitantes (não autenticados). Útil na tela de login.
 */
final class GuestMiddleware implements Middleware
{
    public function handle(Request $request, array $params): bool
    {
        if (Auth::check()) {
            Response::redirect(url('admin'));
        }
        return true;
    }
}
