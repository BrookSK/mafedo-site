<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Controlador base. Fornece helpers de renderização, redirecionamento e
 * manipulação de dados de requisição comuns a todos os controllers.
 */
abstract class Controller
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /** Renderiza uma view com layout e envia ao navegador. */
    protected function view(string $view, array $data = [], ?string $layout = 'layouts/site'): void
    {
        echo View::render($view, $data, $layout);
    }

    /** Renderiza uma view do painel administrativo. */
    protected function adminView(string $view, array $data = []): void
    {
        echo View::render($view, $data, 'layouts/admin');
    }

    protected function redirect(string $path): never
    {
        Response::redirect(str_starts_with($path, 'http') ? $path : url($path));
    }

    protected function back(string $fallback = '/'): never
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '';
        $this->redirect($ref !== '' ? $ref : url($fallback));
    }

    protected function json(array $data, int $status = 200): never
    {
        Response::json($data, $status);
    }

    /** Verifica o token CSRF do corpo da requisição; aborta em caso de falha. */
    protected function verifyCsrf(): void
    {
        if (!Csrf::validate($this->request->input(Csrf::FIELD))) {
            Session::flash('error', 'Sessão expirada ou requisição inválida. Tente novamente.');
            if ($this->request->isAjax()) {
                $this->json(['ok' => false, 'message' => 'Token CSRF inválido.'], 419);
            }
            $this->back();
        }
    }

    /** Guarda os dados do formulário para repreencher após falha de validação. */
    protected function flashOld(array $data): void
    {
        unset($data[Csrf::FIELD], $data['_method'], $data['password'], $data['password_confirmation']);
        Session::flash('__old', $data);
    }

    protected function flashErrors(array $errors): void
    {
        Session::flash('errors', $errors);
    }
}
