<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

/**
 * Exige usuário autenticado. Redireciona ao login caso contrário.
 */
final class AuthMiddleware implements Middleware
{
    public function handle(Request $request, array $params): bool
    {
        if (!Auth::check()) {
            Session::set('intended_url', $request->path());
            Session::flash('error', 'Faça login para acessar o painel.');
            Response::redirect(url('admin/login'));
        }
        return true;
    }
}
