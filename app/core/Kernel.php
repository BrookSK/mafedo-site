<?php

declare(strict_types=1);

namespace App\Core;

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\Middleware;
use App\Middleware\PermissionMiddleware;
use Throwable;

/**
 * Núcleo da aplicação: inicializa o ambiente, roteia a requisição, executa
 * middleware e despacha para o controller apropriado.
 */
final class Kernel
{
    private Router $router;
    private Request $request;

    public function __construct(private string $basePath)
    {
    }

    public function boot(): void
    {
        // Configuração
        $config = require $this->basePath . '/config/config.php';
        Config::load($config);

        date_default_timezone_set((string) Config::get('timezone', 'America/Sao_Paulo'));

        // Tratamento de erros conforme ambiente
        $isDev = Config::get('env') === 'development';
        error_reporting(E_ALL);
        ini_set('display_errors', $isDev ? '1' : '0');
        ini_set('log_errors', '1');

        set_exception_handler([$this, 'handleException']);
        set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
            if (!(error_reporting() & $severity)) {
                return false;
            }
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });

        // Views e helpers
        View::setViewsPath($this->basePath . '/app/views');
        require $this->basePath . '/app/helpers/functions.php';
        require $this->basePath . '/app/helpers/icons.php';

        // Sessão
        Session::start();

        // Consolida 'old input' do flash para a requisição atual
        $old = Session::flash('__old');
        Session::set('__old_current', is_array($old) ? $old : []);

        $this->request = new Request();
        $this->router = new Router();

        // Rotas
        $registrar = require $this->basePath . '/routes/web.php';
        $registrar($this->router);
    }

    public function run(): void
    {
        Response::securityHeaders();

        $match = $this->router->dispatch($this->request);

        if ($match['status'] === 404) {
            $this->renderError(404, 'Página não encontrada');
            return;
        }
        if ($match['status'] === 405) {
            $this->renderError(405, 'Método não permitido');
            return;
        }

        // Middleware
        foreach ($match['middleware'] as $alias) {
            $instance = $this->resolveMiddleware($alias);
            if ($instance !== null && $instance->handle($this->request, $match['params']) === false) {
                return; // middleware interrompeu o fluxo
            }
        }

        $this->dispatchController($match['handler'], $match['params']);
    }

    private function resolveMiddleware(string $alias): ?Middleware
    {
        [$name, $arg] = array_pad(explode(':', $alias, 2), 2, null);

        return match ($name) {
            'auth'       => new AuthMiddleware(),
            'guest'      => new GuestMiddleware(),
            'permission' => new PermissionMiddleware((string) $arg),
            default      => null,
        };
    }

    private function dispatchController(string $handler, array $params): void
    {
        [$controller, $method] = explode('@', $handler);
        $class = 'App\\Controllers\\' . $controller;

        if (!class_exists($class)) {
            throw new \RuntimeException("Controller não encontrado: {$class}");
        }

        $instance = new $class($this->request);
        if (!method_exists($instance, $method)) {
            throw new \RuntimeException("Método não encontrado: {$class}@{$method}");
        }

        $instance->{$method}(...array_values($params));
    }

    public function handleException(Throwable $e): void
    {
        Logger::error(sprintf('%s: %s em %s:%d', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine()));

        $isDev = Config::get('env') === 'development';
        http_response_code(500);

        if ($isDev) {
            echo '<pre style="padding:20px;font:14px/1.5 monospace;background:#01071F;color:#FEFEFE;">';
            echo e(get_class($e) . ': ' . $e->getMessage()) . "\n\n";
            echo e($e->getFile() . ':' . $e->getLine()) . "\n\n";
            echo e($e->getTraceAsString());
            echo '</pre>';
            return;
        }

        $this->renderError(500, 'Ocorreu um erro inesperado');
    }

    private function renderError(int $code, string $message): void
    {
        http_response_code($code);
        $view = 'site/errors/' . $code;
        if (View::exists($view)) {
            echo View::render($view, ['code' => $code, 'message' => $message], 'layouts/site');
        } else {
            echo View::render('site/errors/generic', ['code' => $code, 'message' => $message], 'layouts/site');
        }
    }
}
