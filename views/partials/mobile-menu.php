<?php
/** @var \App\Core\Auth|null $auth */
/** @var string|null $csrfToken */

$auth ??= null;
$csrfToken ??= '';
$isAuthenticated = $auth?->check() ?? false;

$navItems = [
    [
        'label' => 'Inicio',
        'href' => '/App-Control-Gastos/public',
        'icon' => 'home',
    ],
    [
        'label' => 'Movimientos',
        'href' => '/App-Control-Gastos/public/transacciones',
        'icon' => 'repeat-2',
    ],
    [
        'label' => 'Reportes',
        'href' => '/App-Control-Gastos/public/reportes',
        'icon' => 'line-chart',
    ],
    [
        'label' => 'Perfil',
        'href' => '/App-Control-Gastos/public/perfil',
        'icon' => 'user-round',
    ],
];

function mobileIcon(string $icon): string
{
    $available = [
        'home',
        'repeat-2',
        'line-chart',
        'user-round',
    ];

    $name = in_array($icon, $available, true) ? $icon : 'circle-dot';

    return '<span class="h-5 w-5 text-brand-600 dark:text-info" data-lucide="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" aria-hidden="true"></span>';
}
?>
<div id="mobileMenu" class="lg:hidden hidden" data-mobile-menu role="dialog" aria-modal="true" aria-labelledby="mobileMenuTitle">
    <div class="fixed inset-0 z-40 flex">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-mobile-overlay></div>
        <div class="relative ml-auto w-full max-w-xs bg-white dark:bg-slate-900 border-l border-slate-200/70 dark:border-slate-800/70 shadow-xl flex flex-col">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200/70 dark:border-slate-800/70">
                <div>
                    <p id="mobileMenuTitle" class="text-sm font-semibold text-slate-900 dark:text-white">Control de Gastos</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Tu resumen en cualquier dispositivo</p>
                </div>
                <button type="button" class="inline-flex items-center justify-center rounded-full border border-slate-200/70 dark:border-slate-800/70 p-2 text-slate-500 hover:text-brand-600 transition" data-mobile-close aria-label="Cerrar menú">
                    <span class="h-4 w-4" data-lucide="x" aria-hidden="true"></span>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto px-6 py-6 space-y-2 text-sm">
                <?php foreach ($navItems as $item): ?>
                    <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
                       class="mobile-nav-link"
                       data-nav-link>
                        <?= mobileIcon($item['icon']) ?>
                        <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="px-6 pb-6 space-y-3 border-t border-slate-200/70 dark:border-slate-800/70">
                <?php if ($isAuthenticated): ?>
                    <form action="/App-Control-Gastos/public/logout" method="POST">
                        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                        <button class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-brand-600 text-white font-semibold px-4 py-3 hover:bg-brand-700 transition">
                            <span class="h-4 w-4" data-lucide="log-out" aria-hidden="true"></span>
                            Cerrar sesión
                        </button>
                    </form>
                <?php else: ?>
                    <a href="/App-Control-Gastos/public/login" class="block w-full text-center rounded-full bg-brand-600 text-white font-semibold px-4 py-3 hover:bg-brand-700 transition">Iniciar sesión</a>
                    <a href="/App-Control-Gastos/public/registro" class="block w-full text-center rounded-full border border-brand-500/60 text-brand-600 font-semibold px-4 py-3 hover:bg-brand-50 transition">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
