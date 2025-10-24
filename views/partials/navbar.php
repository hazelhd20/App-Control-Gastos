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
        'description' => 'Resumen general y objetivos en curso',
    ],
    [
        'label' => 'Movimientos',
        'href' => '/App-Control-Gastos/public/transacciones',
        'icon' => 'arrows',
        'description' => 'Registra ingresos y gastos en segundos',
    ],
    [
        'label' => 'Reportes',
        'href' => '/App-Control-Gastos/public/reportes',
        'icon' => 'report',
        'description' => 'Análisis claros de tus finanzas',
    ],
    [
        'label' => 'Perfil',
        'href' => '/App-Control-Gastos/public/perfil',
        'icon' => 'user',
        'description' => 'Datos, límites y preferencias personales',
    ],
];

function renderSidebarIcon(string $icon): string
{
    $icons = [
        'home' => 'layout-dashboard',
        'report' => 'line-chart',
        'arrows' => 'repeat-2',
        'user' => 'user-round',
        'default' => 'circle-dot',
    ];

    $name = $icons[$icon] ?? $icons['default'];

    return '<span class="icon-circle" data-lucide="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" aria-hidden="true"></span>';
}
?>
<aside class="hidden lg:flex flex-col bg-white/90 dark:bg-slate-900/85 backdrop-blur-2xl border-r border-slate-200/70 dark:border-slate-800/70 px-6 py-8 relative" data-app-sidebar>
    <button type="button" class="sidebar-toggle hidden xl:flex transition hover:shadow-floating" data-sidebar-toggle aria-expanded="true">
        <span class="sr-only" data-sidebar-toggle-label>Colapsar menú</span>
        <span class="h-5 w-5" data-lucide="panel-left-close" aria-hidden="true"></span>
    </button>
    <div class="flex items-center gap-3 mb-10 sidebar-brand">
        <span class="flex h-12 w-12 items-center justify-center rounded-3xl bg-brand-600 text-white text-xl font-semibold shadow-floating sidebar-brand__logo">
            CG
        </span>
        <div class="sidebar-brand__copy">
            <p class="text-base font-semibold text-slate-900 dark:text-white">Control de Gastos</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Tecnología para tus finanzas</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1 text-sm text-slate-500 dark:text-slate-400">
        <?php foreach ($navItems as $item): ?>
            <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
               class="nav-pill hover-lift"
               data-nav-link
               data-nav-pill
               aria-label="<?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?>">
                <?= renderSidebarIcon($item['icon']) ?>
                <span class="flex flex-col">
                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-100"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="text-xs text-slate-400 dark:text-slate-500"><?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?></span>
                </span>
            </a>
        <?php endforeach; ?>
    </nav>

</aside>
