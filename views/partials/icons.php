<?php
// Icon helpers centralizados para Lucide
if (!function_exists('lucide_icon')) {
    function lucide_icon(string $name, string $classes = 'h-4 w-4'): string
    {
        return '<span class="' . htmlspecialchars($classes, ENT_QUOTES, 'UTF-8') . '" data-lucide="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" aria-hidden="true"></span>';
    }
}

if (!function_exists('__lucide_icon_helper')) {
    function __lucide_icon_helper(string $name, string $classes = 'h-4 w-4'): string
    {
        return lucide_icon($name, $classes);
    }
}

