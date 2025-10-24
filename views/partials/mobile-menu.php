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
        'label' => 'Reportes',
        'href' => '/App-Control-Gastos/public/reportes',
        'icon' => 'report',
    ],
    [
        'label' => 'Movimientos',
        'href' => '/App-Control-Gastos/public/transacciones',
        'icon' => 'arrows',
    ],
    [
        'label' => 'Alertas',
        'href' => '/App-Control-Gastos/public/alertas',
        'icon' => 'bell',
    ],
    [
        'label' => 'Perfil',
        'href' => '/App-Control-Gastos/public/perfil',
        'icon' => 'user',
    ],
    [
        'label' => 'Configuracion',
        'href' => '/App-Control-Gastos/public/perfil#preferencias',
        'icon' => 'settings',
    ],
];

function renderMobileIcon(string $icon): string
{
    $icons = [
        'home' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75 12 3l9 6.75V21a.75.75 0 0 1-.75.75h-5.5a.75.75 0 0 1-.75-.75v-4.5h-4V21a.75.75 0 0 1-.75.75h-5.5A.75.75 0 0 1 3 21z"/>',
        'report' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25h15M4.5 3.75h15M4.5 3.75v16.5a.75.75 0 0 0 .75.75h4.5a.75.75 0 0 0 .75-.75V3.75m0 0v8.25m0 0 4.5-3.75m-4.5 3.75 4.5 3.75"/>',
        'arrows' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.5 9h6a3 3 0 0 0 0-6h-6m6 0L8.25 4.5M10.5 3 8.25 1.5M19.5 15h-6a3 3 0 0 0 0 6h6m-6 0 2.25-2.25M13.5 21l2.25 2.25"/>',
        'bell' => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.25 18.75a2.25 2.25 0 0 1-4.5 0m9.75-6c0-3.35-2.31-6.15-5.39-6.81a2.36 2.36 0 0 0-4.72 0C6.31 6.6 4 9.4 4 12.75v2.07a2.25 2.25 0 0 1-.66 1.59l-.59.59a.75.75 0 0 0 .53 1.28h17.44a.75.75 0 0 0 .53-1.28l-.59-.59a2.25 2.25 0 0 1-.66-1.59z"/>',
        'user' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2.25c-5.25 0-7.5 3-7.5 4.5v1.5a.75.75 0 0 0 .75.75h13.5a.75.75 0 0 0 .75-.75v-1.5c0-1.5-2.25-4.5-7.5-4.5Z"/>',
        'settings' => '<path stroke-linecap="round" stroke-linejoin="round" d="m15 9 6.75 5.25m-9.45 7.8 2.4-8.1m-8.43 8.61 6.03-6.03M9 9l-6.75 5.25m9.45 7.8-2.4-8.1m8.43 8.61-6.03-6.03"/>',
    ];

    $path = $icons[$icon] ?? $icons['home'];
    return '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">'
        . $path .
        '</svg>';
}
?>
<div class="lg:hidden hidden" data-mobile-menu>
    <div class="fixed inset-0 z-40 flex">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-mobile-overlay></div>
        <div class="relative ml-auto w-full max-w-xs bg-white dark:bg-slate-900 border-l border-slate-200/70 dark:border-slate-800/70 shadow-xl flex flex-col">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200/70 dark:border-slate-800/70">
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Control de Gastos</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Tu resumen en cualquier dispositivo</p>
                </div>
                <button type="button" class="inline-flex items-center justify-center rounded-full border border-slate-200/70 dark:border-slate-800/70 p-2 text-slate-500 hover:text-brand-600 transition" data-mobile-close aria-label="Cerrar menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m6 6 12 12M6 18 18 6"/>
                    </svg>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto px-6 py-6 space-y-3 text-sm">
                <?php foreach ($navItems as $item): ?>
                    <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
                       class="flex items-center gap-3 rounded-2xl border border-transparent bg-slate-50/60 dark:bg-slate-800/40 px-4 py-3 text-slate-700 dark:text-slate-200 font-semibold hover:border-info/40 hover:text-brand-600 dark:hover:text-info transition"
                       data-nav-link>
                        <?= renderMobileIcon($item['icon']) ?>
                        <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                    </a>
                <?php endforeach; ?>
            </nav>
            <div class="px-6 pb-6 space-y-3 border-t border-slate-200/70 dark:border-slate-800/70">
                <?php if ($isAuthenticated): ?>
                    <form action="/App-Control-Gastos/public/logout" method="POST">
                        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                        <button class="w-full inline-flex items-center justify-center gap-2 rounded-full bg-brand-600 text-white font-semibold px-4 py-3 hover:bg-brand-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M9 12h12m0 0-3-3m3 3-3 3"/>
                            </svg>
                            Cerrar sesion
                        </button>
                    </form>
                <?php else: ?>
                    <a href="/App-Control-Gastos/public/login" class="block w-full text-center rounded-full bg-brand-600 text-white font-semibold px-4 py-3 hover:bg-brand-700 transition">Iniciar sesion</a>
                    <a href="/App-Control-Gastos/public/registro" class="block w-full text-center rounded-full border border-brand-500/60 text-brand-600 font-semibold px-4 py-3 hover:bg-brand-50 transition">Registrarse</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
