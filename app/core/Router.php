<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Roteador com suporte a:
 * - parâmetros dinâmicos ({slug}, {id})
 * - grupos com prefixo e middleware
 * - middleware por rota
 *
 * Handlers no formato 'Controlador@metodo' (namespace App\Controllers\...).
 */
final class Router
{
    /** @var array<int,array{method:string,pattern:string,regex:string,params:array,handler:string,middleware:array}> */
    private array $routes = [];

    private array $groupStack = [];

    public function get(string $pattern, string $handler, array $middleware = []): void
    {
        $this->add('GET', $pattern, $handler, $middleware);
    }

    public function post(string $pattern, string $handler, array $middleware = []): void
    {
        $this->add('POST', $pattern, $handler, $middleware);
    }

    public function put(string $pattern, string $handler, array $middleware = []): void
    {
        $this->add('PUT', $pattern, $handler, $middleware);
    }

    public function delete(string $pattern, string $handler, array $middleware = []): void
    {
        $this->add('DELETE', $pattern, $handler, $middleware);
    }

    /** Atalho: aceita GET e POST para o mesmo handler. */
    public function match(array $methods, string $pattern, string $handler, array $middleware = []): void
    {
        foreach ($methods as $method) {
            $this->add(strtoupper($method), $pattern, $handler, $middleware);
        }
    }

    public function group(array $attributes, callable $callback): void
    {
        $this->groupStack[] = $attributes;
        $callback($this);
        array_pop($this->groupStack);
    }

    private function add(string $method, string $pattern, string $handler, array $middleware): void
    {
        $prefix = '';
        $groupMiddleware = [];
        foreach ($this->groupStack as $group) {
            $prefix .= $group['prefix'] ?? '';
            $groupMiddleware = array_merge($groupMiddleware, $group['middleware'] ?? []);
        }

        $full = '/' . trim($prefix . '/' . trim($pattern, '/'), '/');
        $full = $full === '/' ? '/' : rtrim($full, '/');

        [$regex, $params] = $this->compile($full);

        $this->routes[] = [
            'method'     => $method,
            'pattern'    => $full,
            'regex'      => $regex,
            'params'     => $params,
            'handler'    => $handler,
            'middleware' => array_merge($groupMiddleware, $middleware),
        ];
    }

    private function compile(string $pattern): array
    {
        $params = [];
        $regex = preg_replace_callback('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', function ($m) use (&$params) {
            $params[] = $m[1];
            return '([^/]+)';
        }, $pattern);

        return ['#^' . $regex . '$#', $params];
    }

    /**
     * Resolve a requisição. Retorna um array descritivo que o Kernel executa.
     *
     * @return array{status:int,handler?:string,params?:array,middleware?:array}
     */
    public function dispatch(Request $request): array
    {
        $path = $request->path();
        $method = $request->method();
        $pathMatchedButMethod = false;

        foreach ($this->routes as $route) {
            if (!preg_match($route['regex'], $path, $matches)) {
                continue;
            }
            if ($route['method'] !== $method) {
                $pathMatchedButMethod = true;
                continue;
            }

            array_shift($matches);
            $params = [];
            foreach ($route['params'] as $i => $name) {
                $params[$name] = $matches[$i] ?? null;
            }

            return [
                'status'     => 200,
                'handler'    => $route['handler'],
                'params'     => $params,
                'middleware' => $route['middleware'],
            ];
        }

        return ['status' => $pathMatchedButMethod ? 405 : 404];
    }
}
