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
        <div class="relative ml-auto w-full max-w-xs bg-white dark:bg-slate-900 border-l border-slate-200/70 dark:border-slate-800/70 shadow-xl flex flex-col translate-x-full" data-mobile-panel>
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200/70 dark:border-slate-800/70">
                <div>
                    <p id="mobileMenuTitle" class="text-sm font-semibold text-slate-900 dark:text-white">Control de Gastos</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Tu resumen en cualquier dispositivo</p>
                </div>
                <button type="button" class="inline-flex items-center justify-center rounded-full border border-slate-200/70 dark:border-slate-800/70 p-2 text-slate-500 hover:text-brand-600 transition" data-mobile-close aria-label="Cerrar menú">
                    <span class="h-4 w-4" data-lucide="x" aria-hidden="true"></span>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6 text-sm scroll-invisible">
                <div class="mobile-search">
                    <label for="mobileMenuSearch" class="sr-only">Buscar en la aplicación</label>
                    <div class="relative">
                        <input id="mobileMenuSearch" type="search" placeholder="Buscar movimientos o categorías..." class="w-full rounded-full border border-slate-200/70 dark:border-slate-800/70 bg-white/95 dark:bg-slate-900/80 px-4 py-3 pl-10 text-sm focus:border-brand-300 focus:ring focus:ring-info/20 transition" autocomplete="off">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 dark:text-slate-500" data-lucide="search" aria-hidden="true"></span>
                    </div>
                    <p class="mt-3 text-xs text-slate-400 dark:text-slate-500">Encuentra movimientos, reportes o accesos frecuentes.</p>
                </div>
                <nav class="space-y-3" aria-label="Navegación principal">
                    <?php foreach ($navItems as $item): ?>
                        <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
                           class="flex items-center gap-3 rounded-2xl border border-transparent bg-slate-50/60 dark:bg-slate-800/40 px-4 py-3 text-slate-700 dark:text-slate-200 font-semibold hover:border-info/40 hover:text-brand-600 dark:hover:text-info transition"
                           data-nav-link>
                            <?= mobileIcon($item['icon']) ?>
                            <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                        </a>
                    <?php endforeach; ?>
                </nav>
                <div class="space-y-3" aria-label="Accesos rápidos">
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-400 dark:text-slate-500 font-semibold">Accesos rápidos</p>
                    <div class="quick-shortcuts">
                        <a href="/App-Control-Gastos/public/transacciones#registro" class="flex flex-col gap-1 rounded-2xl border border-slate-200/70 dark:border-slate-800/70 bg-white/90 dark:bg-slate-900/60 px-3 py-3 text-xs font-semibold text-slate-600 dark:text-slate-200 hover:text-brand-600 dark:hover:text-info transition">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-brand-600">
                                <span class="h-4 w-4" data-lucide="plus-circle" aria-hidden="true"></span>
                            </span>
                            Registrar
                        </a>
                        <a href="/App-Control-Gastos/public/reportes" class="flex flex-col gap-1 rounded-2xl border border-slate-200/70 dark:border-slate-800/70 bg-white/90 dark:bg-slate-900/60 px-3 py-3 text-xs font-semibold text-slate-600 dark:text-slate-200 hover:text-brand-600 dark:hover:text-info transition">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-sky-100 text-brand-600">
                                <span class="h-4 w-4" data-lucide="line-chart" aria-hidden="true"></span>
                            </span>
                            Reportes
                        </a>
                        <a href="/App-Control-Gastos/public/alertas" class="flex flex-col gap-1 rounded-2xl border border-slate-200/70 dark:border-slate-800/70 bg-white/90 dark:bg-slate-900/60 px-3 py-3 text-xs font-semibold text-slate-600 dark:text-slate-200 hover:text-brand-600 dark:hover:text-info transition">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                                <span class="h-4 w-4" data-lucide="bell" aria-hidden="true"></span>
                            </span>
                            Alertas
                        </a>
                    </div>
                </div>
            </div>
            <div class="px-6 pb-6 space-y-4 border-t border-slate-200/70 dark:border-slate-800/70">
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
                <p class="text-xs text-slate-400 dark:text-slate-500 text-center">¿Buscas ayuda? Visita el <a href="/App-Control-Gastos/public/ayuda" class="underline decoration-dotted decoration-brand-400 hover:text-brand-600 dark:hover:text-info">centro de soporte</a>.</p>
            </div>
        </div>
    </div>
</div>
