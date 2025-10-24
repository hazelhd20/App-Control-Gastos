<?php
/** @var \App\Core\Auth|null $auth */
/** @var \App\Core\Session|null $session */
/** @var string|null $csrfToken */

$auth ??= null;
$session ??= null;
$csrfToken ??= '';

$navItems = [
    [
        'label' => 'Inicio',
        'href' => '/App-Control-Gastos/public',
        'icon' => 'home',
        'description' => 'Panel general y resumen financiero',
    ],
    [
        'label' => 'Reportes',
        'href' => '/App-Control-Gastos/public/reportes',
        'icon' => 'report',
        'description' => 'Graficas e informes descargables',
    ],
    [
        'label' => 'Movimientos',
        'href' => '/App-Control-Gastos/public/transacciones',
        'icon' => 'arrows',
        'description' => 'Registra gastos e ingresos al instante',
    ],
    [
        'label' => 'Alertas',
        'href' => '/App-Control-Gastos/public/alertas',
        'icon' => 'bell',
        'description' => 'Notificaciones y recordatorios activos',
    ],
    [
        'label' => 'Perfil',
        'href' => '/App-Control-Gastos/public/perfil',
        'icon' => 'user',
        'description' => 'Datos personales y limites de gasto',
    ],
    [
        'label' => 'Configuracion',
        'href' => '/App-Control-Gastos/public/perfil#preferencias',
        'icon' => 'settings',
        'description' => 'Preferencias, idioma y seguridad',
    ],
];

function renderSidebarIcon(string $icon): string
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
    return '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">'
        . $path .
        '</svg>';
}
?>
<aside class="hidden lg:flex flex-col bg-white/90 dark:bg-slate-900/85 backdrop-blur-2xl border-r border-slate-200/70 dark:border-slate-800/70 px-6 py-8 relative" data-app-sidebar>
    <button type="button" class="sidebar-toggle hidden xl:flex transition hover:shadow-floating" data-sidebar-toggle aria-expanded="true">
        <span class="sr-only" data-sidebar-toggle-label>Colapsar menu</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m15.75 19.5-7.5-7.5 7.5-7.5"/>
        </svg>
    </button>
    <div class="flex items-center gap-3 mb-10 sidebar-brand">
        <span class="flex h-12 w-12 items-center justify-center rounded-3xl bg-brand-600 text-white text-xl font-semibold shadow-floating sidebar-brand__logo">
            CG
        </span>
        <div class="sidebar-brand__copy">
            <p class="text-base font-semibold text-slate-900 dark:text-white">Control de Gastos</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Tecnologia para tus finanzas</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1 text-sm text-slate-500 dark:text-slate-400">
        <?php foreach ($navItems as $item): ?>
            <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
               class="nav-pill hover-lift"
               data-nav-link
               data-nav-pill
               aria-label="<?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?>">
                <span class="icon-circle"><?= renderSidebarIcon($item['icon']) ?></span>
                <span class="flex flex-col">
                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-100"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="text-xs text-slate-400 dark:text-slate-500"><?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?></span>
                </span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="mt-10 rounded-3xl border border-slate-200/70 dark:border-slate-700/60 bg-white/60 dark:bg-slate-900/70 p-5 shadow-soft sidebar-support">
        <p class="text-xs uppercase tracking-wide text-slate-400 dark:text-slate-500 font-semibold mb-2">Soporte</p>
        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
            ¿Necesitas ayuda? Escribenos y recibe acompanamiento personalizado.
        </p>
        <a href="mailto:soporte@controlgastos.local" class="mt-4 inline-flex items-center gap-2 rounded-full bg-brand-600/90 text-white text-xs font-semibold px-4 py-2 hover:bg-brand-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a1.5 1.5 0 0 1-1.5 1.5H3.75a1.5 1.5 0 0 1-1.5-1.5V6.75m19.5 0-9.75 6.375L2.25 6.75m19.5 0L12 13.125 2.25 6.75"/>
            </svg>
            soporte@controlgastos.local
        </a>
    </div>
</aside>
