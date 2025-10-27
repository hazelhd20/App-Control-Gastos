<?php
/** @var \App\Core\Auth|null $auth */
/** @var \App\Core\Session|null $session */
/** @var string|null $csrfToken */

$auth ??= null;
$session ??= null;
$csrfToken ??= '';

$navGroups = [
    [
        'heading' => 'Principal',
        'items' => [
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
        ],
    ],
    [
        'heading' => 'Cuenta',
        'items' => [
            [
                'label' => 'Perfil',
                'href' => '/App-Control-Gastos/public/perfil',
                'icon' => 'user',
                'description' => 'Datos, límites y preferencias personales',
            ],
        ],
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

    $classes = implode(' ', [
        'sidebar-icon',
        'flex h-10 w-10 shrink-0 items-center justify-center rounded-full',
        'bg-gradient-to-br from-info/20 via-brand-500/10 to-brand-600/10',
        'text-brand-600 ring-1 ring-brand-500/10 shadow-inner',
        'transition-all duration-200 ease-out',
        'group-hover:ring-info/40 group-hover:text-brand-700',
        'dark:from-info/25 dark:via-slate-900/60 dark:to-slate-900/80',
        'dark:text-slate-100 dark:group-hover:text-info',
    ]);

    return '<span class="' . $classes . '" data-lucide="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" aria-hidden="true"></span>';
}
?>
<aside class="hidden lg:flex flex-col bg-white/90 dark:bg-slate-900/85 backdrop-blur-2xl border-r border-slate-200/70 dark:border-slate-800/70 px-6 py-8 relative" data-app-sidebar>
    <button type="button" class="sidebar-toggle hidden lg:flex transition hover:shadow-floating" data-sidebar-toggle aria-expanded="true">
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

    <div class="sidebar-sections flex-1 space-y-7 text-sm text-slate-500 dark:text-slate-400">
        <?php foreach ($navGroups as $groupIndex => $group): ?>
            <section class="space-y-3" data-sidebar-section>
                <h2 class="sidebar-section__title text-xs uppercase tracking-[0.35em] text-slate-400 dark:text-slate-500 font-semibold">
                    <?= htmlspecialchars($group['heading'], ENT_QUOTES, 'UTF-8') ?>
                </h2>
                <nav class="space-y-1" aria-label="<?= htmlspecialchars($group['heading'], ENT_QUOTES, 'UTF-8') ?>">
                    <?php foreach ($group['items'] as $index => $item): ?>
                        <?php
                        $linkId = 'sidebar-link-' . $groupIndex . '-' . $index;
                        $descriptionId = $linkId . '-desc';
                        ?>
                        <a href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
                           id="<?= $linkId ?>"
                           class="group relative flex items-center gap-3 rounded-full px-4 py-3 font-medium text-slate-600 dark:text-slate-200 transition-all duration-200 ease-out hover:-translate-y-[2px] hover:translate-x-[2px] hover:shadow-floating hover:bg-white/80 hover:text-brand-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300/60 focus-visible:ring-offset-0 aria-[current=page]:bg-gradient-to-r aria-[current=page]:from-brand-600/15 aria-[current=page]:to-info/20 aria-[current=page]:text-brand-700 aria-[current=page]:shadow-[0_0_0_1px_rgba(59,130,246,0.35)] dark:hover:bg-slate-800/60 dark:hover:text-info dark:aria-[current=page]:from-info/20 dark:aria-[current=page]:to-brand-600/15 dark:aria-[current=page]:text-info"
                           data-nav-link
                           data-nav-pill
                           data-sidebar-tooltip="<?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?>"
                           aria-describedby="<?= $descriptionId ?>">
                            <span class="sidebar-active-indicator" aria-hidden="true"></span>
                            <?= renderSidebarIcon($item['icon']) ?>
                            <span class="flex flex-col">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-100"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                                <span id="<?= $descriptionId ?>" class="text-xs text-slate-400 dark:text-slate-500">
                                    <?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </section>
        <?php endforeach; ?>
    </div>

    <footer class="sidebar-support mt-8 space-y-3 text-xs text-slate-500 dark:text-slate-400">
        <p class="font-semibold uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500">Ayuda</p>
        <div class="rounded-3xl border border-slate-200/70 dark:border-slate-800/60 bg-slate-50/80 dark:bg-slate-800/40 px-4 py-4 space-y-3">
            <p class="text-sm font-semibold text-slate-600 dark:text-slate-200">¿Necesitas soporte?</p>
            <p class="text-xs leading-relaxed">Escríbenos a <a href="mailto:soporte@controlgastos.app" class="underline decoration-dotted decoration-brand-400 hover:text-brand-600 dark:hover:text-info">soporte@controlgastos.app</a> o revisa la sección de preguntas frecuentes.</p>
            <a href="/App-Control-Gastos/public/ayuda" class="inline-flex items-center gap-2 rounded-full border border-brand-200/60 text-brand-600 px-3 py-2 font-semibold hover:bg-brand-50 transition">
                <?= lucide_icon('life-buoy', 'h-4 w-4') ?>
                Centro de ayuda
            </a>
        </div>
    </footer>
</aside>
