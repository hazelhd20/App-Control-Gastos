<?php

namespace App\Core;

class Request
{
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function uri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = explode('?', $uri, 2)[0];

        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');

        if ($basePath && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }

        return '/' . trim($uri, '/');
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $_REQUEST[$key] ?? $default;
    }

    public function only(array $keys): array
    {
        $data = [];
        foreach ($keys as $key) {
            if (isset($_REQUEST[$key])) {
                $data[$key] = $_REQUEST[$key];
            }
        }

        return $data;
    }

    public function all(): array
    {
        return $_REQUEST;
    }

    public function inputArray(string $key): array
    {
        $value = $_POST[$key] ?? $_GET[$key] ?? [];

        return is_array($value) ? $value : [$value];
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function csrfToken(): string
    {
        return $_SESSION['_token'] ?? '';
    }
}
