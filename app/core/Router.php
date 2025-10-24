<?php

namespace App\Core;

use Closure;
use RuntimeException;

class Router
{
    protected array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $uri, array|Closure $action): void
    {
        $this->routes['GET'][$this->normalize($uri)] = $action;
    }

    public function post(string $uri, array|Closure $action): void
    {
        $this->routes['POST'][$this->normalize($uri)] = $action;
    }

    public function dispatch(Request $request, Container $container): mixed
    {
        $method = $request->method();
        $uri = $request->uri();
        $action = $this->routes[$method][$uri] ?? null;

        if ($action === null) {
            http_response_code(404);
            echo 'Pagina no encontrada';
            return null;
        }

        if ($action instanceof Closure) {
            return $action($container, $request);
        }

        [$class, $method] = $action;

        if (!class_exists($class)) {
            throw new RuntimeException("Controlador {$class} no encontrado");
        }

        $controller = new $class($container);

        if (!method_exists($controller, $method)) {
            throw new RuntimeException("Metodo {$method} no definido en el controlador {$class}");
        }

        return $controller->{$method}($request);
    }

    protected function normalize(string $uri): string
    {
        return '/' . trim($uri, '/');
    }
}
