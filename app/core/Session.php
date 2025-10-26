<?php

namespace App\Core;

class Session
{
    protected const FLASH_KEY = '_flash';

    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start([
                'cookie_httponly' => true,
                'cookie_samesite' => 'Lax',
                'use_strict_mode' => true,
            ]);
        }

        $this->loadFlashMessages();
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function destroy(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }

    public function flash(string $key, mixed $message): void
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'message' => $message,
            'remove' => false,
        ];
    }

    public function flashInput(array $input): void
    {
        $_SESSION['_old'] = $input;
    }

    public function old(string $key, mixed $default = null): mixed
    {
        $old = $_SESSION['_old'] ?? [];
        return $old[$key] ?? $default;
    }

    public function pullOld(): array
    {
        $old = $_SESSION['_old'] ?? [];
        unset($_SESSION['_old']);

        return $old;
    }

    public function token(): string
    {
        if (empty($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_token'];
    }

    public function getFlash(string $key, mixed $default = null): mixed
    {
        return $_SESSION[self::FLASH_KEY][$key]['message'] ?? $default;
    }

    protected function loadFlashMessages(): void
    {
        $flashed = $_SESSION[self::FLASH_KEY] ?? [];

        foreach ($flashed as $key => $flash) {
            if (($flash['remove'] ?? false) === true) {
                unset($flashed[$key]);
                continue;
            }

            $flash['remove'] = true;
            $flashed[$key] = $flash;
        }

        if (empty($flashed)) {
            unset($_SESSION[self::FLASH_KEY]);
            return;
        }

        $_SESSION[self::FLASH_KEY] = $flashed;
    }
}
