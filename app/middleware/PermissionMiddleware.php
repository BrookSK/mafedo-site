<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Session;
use App\Core\View;

/**
 * Exige uma permissão específica. Usada como 'permission:projects.view' nas
 * definições de rota — o Kernel instancia passando o argumento após ':'.
 */
final class PermissionMiddleware implements Middleware
{
    public function __construct(private string $permission)
    {
    }

    public function handle(Request $request, array $params): bool
    {
        if (!Auth::can($this->permission)) {
            http_response_code(403);
            Session::flash('error', 'Você não tem permissão para acessar esta área.');
            echo View::render('admin/errors/403', [
                'title' => 'Acesso negado',
            ], 'layouts/admin');
            return false;
        }
        return true;
    }
}
