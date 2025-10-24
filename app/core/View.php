<?php

namespace App\Core;

class View
{
    protected string $basePath;

    public function __construct(?string $basePath = null)
    {
        $this->basePath = $basePath ?? __DIR__ . '/../../views';
    }

    public function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void
    {
        $viewPath = $this->basePath . '/' . str_replace('.', '/', $view) . '.php';

        if (!is_file($viewPath)) {
            throw new \RuntimeException("Vista {$view} no encontrada");
        }

        // Asegurar helpers compartidos antes de evaluar la vista
        $sharedHelpers = $this->basePath . '/partials/icons.php';
        if (is_file($sharedHelpers)) {
            require_once $sharedHelpers;
        }

        extract($data);
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        if ($layout) {
            $layoutPath = $this->basePath . '/' . $layout . '.php';
            if (!is_file($layoutPath)) {
                throw new \RuntimeException("Layout {$layout} no encontrado");
            }

            require $layoutPath;
            return;
        }

        echo $content;
    }
}
