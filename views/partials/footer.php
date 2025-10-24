<footer class="mt-16 border-t border-slate-200/70 dark:border-slate-800/70 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-slate-500 dark:text-slate-400">
        <div class="text-center md:text-left">
            &copy; <?= date('Y') ?> Control de Gastos. Claridad y confianza para tus finanzas personales.
        </div>
        <div class="flex items-center gap-4">
            <span class="font-semibold text-brand-600 dark:text-info">Soporte directo:</span>
<?php if (!function_exists('__lucide_icon_helper')): ?>
    <?php
    function __lucide_icon_helper(string $name, string $classes = 'h-4 w-4'): string
    {
        return '<span class="' . htmlspecialchars($classes, ENT_QUOTES, 'UTF-8') . '" data-lucide="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" aria-hidden="true"></span>';
    }
    ?>
<?php endif; ?>

            <a href="mailto:soporte@controlgastos.local" class="inline-flex items-center gap-2 hover:text-brand-600 dark:hover:text-info transition">
                <?= __lucide_icon_helper('mail', 'h-4 w-4') ?>
                soporte@controlgastos.local
            </a>
        </div>
    </div>
</footer>
