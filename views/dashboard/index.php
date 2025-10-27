<?php
/** @var array $profile */
/** @var array $summary */
/** @var array $trend */
/** @var array $topCategories */
/** @var array $paymentBreakdown */
/** @var array $recent */
/** @var array $alerts */
/** @var int|null $daysSinceLastMovement */
/** @var string $csrfToken */

$currency = htmlspecialchars($profile['currency'], ENT_QUOTES, 'UTF-8');
$media = is_array($profile['spending_media'])
    ? $profile['spending_media']
    : json_decode($profile['spending_media'] ?? '[]', true);

$goalTypeLabels = [
    'save' => 'Ahorrar',
    'debt' => 'Pagar deudas',
    'control' => 'Controlar gastos',
    'other' => 'Otro objetivo',
];

$goalLabel = $goalTypeLabels[$profile['goal_type']] ?? 'Objetivo activo';
$limitUsagePercent = $summary['limit'] > 0 ? max(0, min(100, (float) $summary['limit_usage'])) : 0;
$alerts = $alerts ?? [];

$ringRadius = 42;
$ringCircumference = 2 * pi() * $ringRadius;
$ringOffset = $ringCircumference - ($ringCircumference * ($limitUsagePercent / 100));

$alertPalette = [
    'danger' => [
        'border' => 'border-danger/50',
        'bg' => 'bg-rose-50 dark:bg-rose-900/30',
        'text' => 'text-rose-700 dark:text-rose-200',
        'accent' => 'bg-rose-100/70 dark:bg-rose-900/60',
        'badgeText' => 'text-rose-700 dark:text-rose-200',
        'icon' => 'alert-triangle',
    ],
    'warning' => [
        'border' => 'border-amber-200/80',
        'bg' => 'bg-amber-50 dark:bg-amber-900/30',
        'text' => 'text-amber-700 dark:text-amber-200',
        'accent' => 'bg-amber-100/80 dark:bg-amber-900/50',
        'badgeText' => 'text-amber-700 dark:text-amber-200',
        'icon' => 'shield-alert',
    ],
    'info' => [
        'border' => 'border-sky-200/70',
        'bg' => 'bg-sky-50 dark:bg-sky-900/30',
        'text' => 'text-slate-600 dark:text-slate-200',
        'accent' => 'bg-sky-100/80 dark:bg-sky-900/50',
        'badgeText' => 'text-slate-700 dark:text-slate-100',
        'icon' => 'info',
    ],
];

// Helper centralizado en views/partials/icons.php
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <section class="space-y-12">
        <header class="gradient-card relative overflow-hidden rounded-3xl px-6 py-8 sm:px-8 sm:py-10 lg:px-12 shadow-floating before:absolute before:inset-0 before:bg-slate-900/20 before:backdrop-blur-[1px] before:content-['']">
            <div class="absolute hidden md:block top-12 right-12">
                <div class="flex h-32 w-32 items-center justify-center rounded-full border border-white/15 bg-white/10 text-xs uppercase tracking-[0.4em] text-white/75">
                    <?= htmlspecialchars(strtoupper($currency), ENT_QUOTES, 'UTF-8') ?>
                </div>
            </div>
            <div class="relative z-10 space-y-8">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl space-y-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-white/70">Panel financiero</p>
                        <h1 class="text-3xl font-semibold leading-tight text-white sm:text-4xl">
                        Hola, <?= htmlspecialchars($profile['name'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?>
                        </h1>
                        <p class="text-balance leading-relaxed text-white/80">
                        Observa tus resultados del mes, controla el uso de tu límite y avanza hacia <?= htmlspecialchars(strtolower($goalLabel), ENT_QUOTES, 'UTF-8') ?> con decisiones basadas en datos.
                        </p>
                        <?php if ($daysSinceLastMovement !== null && $daysSinceLastMovement >= 7): ?>
                            <div class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-5 py-2.5 text-xs font-medium text-white/85 shadow-sm backdrop-blur">
                                <span class="inline-flex h-2 w-2 rounded-full bg-white"></span>
                                Han pasado <?= $daysSinceLastMovement ?> días sin registrar movimientos. Actualiza tus datos para mantener el panorama preciso.
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="w-full rounded-3xl border border-white/20 bg-white/10 px-6 py-6 text-sm backdrop-blur-xl shadow-floating md:max-w-sm">
                        <p class="text-xs font-semibold uppercase tracking-wide text-white/70">Objetivo en curso</p>
                        <p class="text-lg font-semibold text-white">
                            <?= htmlspecialchars($goalLabel, ENT_QUOTES, 'UTF-8') ?>
                        </p>
                        <?php if (!empty($profile['goal_description'])): ?>
                            <p class="text-balance text-white/80">
                                <?= htmlspecialchars($profile['goal_description'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                        <?php endif; ?>
                        <div class="flex items-center gap-3 text-xs text-white/70">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-white/20">
                                <?= lucide_icon('alarm-clock', 'h-4 w-4') ?>
                            </span>
                            Mantente al día actualizando tus ingresos y límites para obtener alertas oportunas.
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section class="space-y-4">
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-white/70">Acciones rápidas</p>
            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                <a href="/App-Control-Gastos/public/transacciones#registro" class="group flex w-full min-w-[16rem] flex-col items-start gap-4 rounded-3xl border border-slate-200/70 bg-white/95 px-5 py-5 shadow-soft transition hover:-translate-y-1 hover:shadow-floating focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 dark:border-slate-800/70 dark:bg-slate-900/70 sm:flex-row sm:items-center sm:justify-between">
                    <span class="flex items-start gap-3 sm:items-center">
                        <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-100 via-white to-brand-50 text-brand-600 shadow-[0_8px_20px_rgba(15,23,42,0.08)]">
                            <?= lucide_icon('plus-circle', 'h-5 w-5') ?>
                        </span>
                        <span class="flex flex-col">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-100">Registrar movimiento</span>
                            <span class="text-xs text-slate-400 dark:text-slate-500">Añade ingresos o gastos en segundos.</span>
                        </span>
                    </span>
                    <?= lucide_icon('chevron-right', 'hidden h-4 w-4 text-slate-300 transition group-hover:text-brand-400 sm:block sm:self-center sm:shrink-0') ?>
                </a>
                <a href="/App-Control-Gastos/public/reportes" class="group flex w-full min-w-[16rem] flex-col items-start gap-4 rounded-3xl border border-slate-200/70 bg-white/95 px-5 py-5 shadow-soft transition hover:-translate-y-1 hover:shadow-floating focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 dark:border-slate-800/70 dark:bg-slate-900/70 sm:flex-row sm:items-center sm:justify-between">
                    <span class="flex items-start gap-3 sm:items-center">
                        <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-100 via-white to-brand-50 text-brand-600 shadow-[0_8px_20px_rgba(15,23,42,0.08)]">
                            <?= lucide_icon('line-chart', 'h-5 w-5') ?>
                        </span>
                        <span class="flex flex-col">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-100">Explorar reportes</span>
                            <span class="text-xs text-slate-400 dark:text-slate-500">Analiza tendencias y detecta oportunidades.</span>
                        </span>
                    </span>
                    <?= lucide_icon('chevron-right', 'hidden h-4 w-4 text-slate-300 transition group-hover:text-brand-400 sm:block sm:self-center sm:shrink-0') ?>
                </a>
                <a href="/App-Control-Gastos/public/perfil#limite" class="group flex w-full min-w-[16rem] flex-col items-start gap-4 rounded-3xl border border-slate-200/70 bg-white/95 px-5 py-5 shadow-soft transition hover:-translate-y-1 hover:shadow-floating focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 dark:border-slate-800/70 dark:bg-slate-900/70 sm:flex-row sm:items-center sm:justify-between">
                    <span class="flex items-start gap-3 sm:items-center">
                        <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-100 via-white to-brand-50 text-brand-600 shadow-[0_8px_20px_rgba(15,23,42,0.08)]">
                            <?= lucide_icon('gauge', 'h-5 w-5') ?>
                        </span>
                        <span class="flex flex-col">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-100">Ajustar límites</span>
                            <span class="text-xs text-slate-400 dark:text-slate-500">Optimiza tu presupuesto mensual al instante.</span>
                        </span>
                    </span>
                    <?= lucide_icon('chevron-right', 'hidden h-4 w-4 text-slate-300 transition group-hover:text-brand-400 sm:block sm:self-center sm:shrink-0') ?>
                </a>
            </div>
        </section>
    <?php if (!empty($alerts)): ?>
        <section class="space-y-4">
            <?php foreach ($alerts as $alert): ?>
                <?php
                $palette = $alertPalette[$alert['level']] ?? [
                    'border' => 'border-slate-200/70',
                    'bg' => 'bg-white dark:bg-slate-900/70',
                    'text' => 'text-slate-600 dark:text-slate-300',
                    'accent' => 'bg-slate-200/60',
                    'badgeText' => 'text-slate-600 dark:text-slate-200',
                    'icon' => 'alert-circle',
                ];
                $payload = $alert['payload'] ?? [];
                ?>
                <article class="surface-card rounded-3xl border <?= $palette['border'] ?> <?= $palette['bg'] ?> px-6 py-5 shadow-soft transition hover-lift">
                    <div class="flex flex-col gap-5 md:flex-row md:items-start md:justify-between md:gap-6">
                        <div class="flex-1 space-y-3">
                            <div class="inline-flex items-center gap-2 rounded-full <?= $palette['accent'] ?> px-4 py-1.5 text-xs font-semibold uppercase tracking-wide <?= $palette['badgeText'] ?> self-start md:self-auto">
                                <span class="inline-flex h-2 w-2 rounded-full bg-current"></span>
                                Alerta <?= strtoupper($alert['level']) ?>
                            </div>
                            <p class="flex items-start gap-3 text-sm font-semibold <?= $palette['text'] ?> text-balance">
                                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-current/20 bg-white/70 text-current dark:bg-slate-900/50">
                                    <?= lucide_icon($palette['icon'], 'h-4 w-4') ?>
                                </span>
                                <span><?= htmlspecialchars($alert['message'], ENT_QUOTES, 'UTF-8') ?></span>
                            </p>
                            <?php if (!empty($payload)): ?>
                                <dl class="grid gap-3 text-xs text-slate-500 dark:text-slate-300 sm:grid-cols-2">
                                    <?php foreach ($payload as $key => $value): ?>
                                        <div class="rounded-2xl border border-slate-200/50 bg-white/55 px-3 py-2 dark:border-slate-700/40 dark:bg-slate-900/40">
                                            <dt class="text-[0.65rem] font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">
                                                <?= htmlspecialchars(str_replace('_', ' ', ucfirst($key)), ENT_QUOTES, 'UTF-8') ?>
                                            </dt>
                                            <dd class="text-xs font-medium text-slate-600 dark:text-slate-200">
                                                <?= htmlspecialchars(is_array($value) ? json_encode($value) : (string) $value, ENT_QUOTES, 'UTF-8') ?>
                                            </dd>
                                        </div>
                                    <?php endforeach; ?>
                                </dl>
                            <?php endif; ?>
                        </div>
                        <form action="/App-Control-Gastos/public/alertas/marcar" method="POST" class="shrink-0">
                            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="alert_id" value="<?= (int) $alert['id'] ?>">
                            <button class="inline-flex items-center gap-2 self-start rounded-full border border-slate-200/70 px-5 py-2.5 text-xs font-semibold text-brand-600 transition hover:border-brand-200 hover:bg-brand-50/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 dark:border-slate-700/60 dark:text-info md:self-end <?= $alert['seen_at'] ? 'opacity-60 pointer-events-none' : '' ?>">
                                <?= lucide_icon('check', 'h-4 w-4') ?>
                                <?= $alert['seen_at'] ? 'Alerta atendida' : 'Marcar como leída' ?>
                            </button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

        <section class="space-y-4">
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-slate-400">Resumen financiero</p>
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-3xl bg-white/95 p-6 shadow-[0_15px_45px_rgba(15,23,42,0.08)] ring-1 ring-slate-200/80 transition hover:-translate-y-1 hover:shadow-floating dark:bg-slate-900/70 dark:ring-slate-800/60">
            <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Ingresos base</p>
                    <p class="mt-2 text-3xl font-bold text-brand-700 dark:text-info">
                        <?= number_format($summary['base_income'], 2) ?> <?= $currency ?>
                    </p>
                </div>
                <span class="icon-circle shrink-0">
                    <?= lucide_icon('wallet', 'h-5 w-5') ?>
                </span>
            </div>
            <p class="mt-4 text-sm text-slate-500">Incluye tus ingresos mensuales y adicionales configurados en el perfil.</p>
                </article>

                <article class="rounded-3xl bg-white/95 p-6 shadow-[0_15px_45px_rgba(15,23,42,0.08)] ring-1 ring-slate-200/80 transition hover:-translate-y-1 hover:shadow-floating dark:bg-slate-900/70 dark:ring-slate-800/60">
            <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Ingresos registrados</p>
                    <p class="mt-2 text-3xl font-bold text-brand-700 dark:text-info">
                        <?= number_format($summary['registered_income'], 2) ?> <?= $currency ?>
                    </p>
                </div>
                <span class="icon-circle shrink-0">
                    <?= lucide_icon('badge-check', 'h-5 w-5') ?>
                </span>
            </div>
            <p class="mt-4 text-sm text-slate-500">Dinero capturado en el periodo actual, perfecto para actualizar tus flujos.</p>
                </article>

                <article class="rounded-3xl bg-white/95 p-6 shadow-[0_15px_45px_rgba(15,23,42,0.08)] ring-1 ring-rose-200/70 transition hover:-translate-y-1 hover:shadow-floating dark:bg-slate-900/70 dark:ring-rose-800/60">
            <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Gastos del mes</p>
                    <p class="mt-2 text-3xl font-bold text-danger">
                        <?= number_format($summary['expenses'], 2) ?> <?= $currency ?>
                    </p>
                </div>
                <span class="icon-circle shrink-0">
                    <?= lucide_icon('trending-down', 'h-5 w-5') ?>
                </span>
            </div>
            <p class="mt-4 text-sm text-slate-500">Total de egresos registrados. Analiza tus categorías para mejorar cada rubro.</p>
                </article>

                <article class="rounded-3xl bg-white/95 p-6 shadow-[0_15px_45px_rgba(15,23,42,0.08)] ring-1 ring-slate-200/80 transition hover:-translate-y-1 hover:shadow-floating dark:bg-slate-900/70 dark:ring-slate-800/60">
            <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Saldo disponible</p>
                    <p class="mt-2 text-3xl font-bold <?= $summary['available'] >= 0 ? 'text-brand-700 dark:text-info' : 'text-danger' ?>">
                        <?= number_format($summary['available'], 2) ?> <?= $currency ?>
                    </p>
                </div>
                <span class="icon-circle shrink-0">
                    <?= lucide_icon('piggy-bank', 'h-5 w-5') ?>
                </span>
            </div>
            <p class="mt-4 text-sm text-slate-500">Resultado entre ingresos y gastos. Ideal para planear tus próximos objetivos.</p>
                </article>
            </div>
        </section>

        <section class="space-y-4">
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-slate-400">Análisis y límites</p>
            <div class="grid gap-6 lg:grid-cols-[1.6fr_1fr] xl:grid-cols-[1.8fr_1fr]">
                <article class="chart-card rounded-3xl border border-slate-200/70 bg-white px-6 py-6 shadow-soft dark:border-slate-800/60 dark:bg-slate-900/80">
                    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-brand-700 dark:text-info">Tendencia de ingresos y gastos</h2>
                            <p class="mt-1 text-sm text-slate-500">Identifica patrones y toma acciones preventivas ante los picos de gasto.</p>
                        </div>
                        <a href="/App-Control-Gastos/public/reportes" class="inline-flex items-center gap-2 self-start rounded-full border border-brand-200/70 px-5 py-2.5 text-xs font-semibold text-brand-600 transition hover:bg-brand-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 md:self-end">
                            Ver reportes
                            <?= lucide_icon('chevron-right', 'h-4 w-4') ?>
                        </a>
                    </div>
                    <canvas id="dashboardTrendChart" height="240"></canvas>
                </article>

                <article class="rounded-3xl border border-slate-200/70 p-6 sm:p-7 shadow-soft backdrop-blur dark:border-slate-800/60">
                    <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Uso del límite mensual</p>
                            <p class="text-lg font-semibold text-brand-700 dark:text-info"><?= $limitUsagePercent ?>%</p>
                            <?php if ($summary['over_limit']): ?>
                                <span class="inline-flex items-center gap-2 rounded-full bg-rose-100/70 px-3 py-1 text-xs font-semibold text-rose-700 dark:bg-rose-900/40 dark:text-rose-200">Sobre el límite</span>
                            <?php endif; ?>
                        </div>
                        <div class="relative h-28 w-28 shrink-0">
                            <svg width="112" height="112">
                                <circle class="text-slate-200/60 dark:text-slate-700/40" stroke="currentColor" stroke-width="10" fill="transparent" r="<?= $ringRadius ?>" cx="56" cy="56"></circle>
                                <circle class="text-brand-500 progress-ring" stroke="currentColor" stroke-width="10" fill="transparent" r="<?= $ringRadius ?>" cx="56" cy="56" stroke-dasharray="<?= $ringCircumference ?>" stroke-dashoffset="<?= $ringOffset ?>" stroke-linecap="round"></circle>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-xs font-semibold text-slate-600">
                                <span>Límite</span>
                                <span><?= number_format($summary['limit'], 2) ?> <?= $currency ?></span>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600">
                        <?= $summary['over_limit']
                            ? 'Superaste tu límite planificado. Revisa las categorías con mayor impacto y define acciones inmediatas.'
                            : 'Estás dentro de tu límite mensual. Mantente atento a los próximos movimientos para conservar el equilibrio.' ?>
                    </p>
                    <div class="space-y-3 rounded-2xl border border-slate-200/70 bg-slate-50/70 p-4 text-xs text-slate-600 dark:border-slate-700/60 dark:bg-slate-900/50">
                        <div class="flex items-center justify-between">
                            <span>Gastos acumulados</span>
                            <span class="font-semibold text-rose-600 dark:text-rose-300"><?= number_format($summary['expenses'], 2) ?> <?= $currency ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Ingresos disponibles</span>
                            <span class="font-semibold text-brand-700 dark:text-info"><?= number_format($summary['available'], 2) ?> <?= $currency ?></span>
                        </div>
                    </div>
                    <a href="/App-Control-Gastos/public/perfil" class="inline-flex items-center gap-2 self-start rounded-full bg-brand-600 px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-brand-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 md:self-end">
                        Ajustar límite
                        <?= lucide_icon('arrow-left-right', 'h-4 w-4') ?>
                    </a>
                </article>
            </div>
        </section>

        <section class="space-y-4">
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-slate-400">Insights rápidos</p>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-[1.5fr_1fr]">
                <article class="rounded-3xl border border-slate-200/70 bg-white/95 p-6 sm:p-7 shadow-soft transition hover:-translate-y-1 hover:shadow-floating dark:border-slate-800/60 dark:bg-slate-900/70">
            <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold text-brand-700 dark:text-info">Acciones sugeridas</h2>
                <span class="badge-soft">Productividad</span>
            </div>
            <ul class="space-y-4 text-sm text-slate-600 dark:text-slate-300">
                <li class="flex items-start gap-3">
                    <span class="icon-circle shrink-0">
                        <?= lucide_icon('clipboard-check', 'h-4 w-4') ?>
                    </span>
                    Registra tus movimientos recientes para mantener actualizado el balance del periodo.
                </li>
                <li class="flex items-start gap-3">
                    <span class="icon-circle shrink-0">
                        <?= lucide_icon('alarm-clock', 'h-4 w-4') ?>
                    </span>
                    Activa recordatorios para anticiparte a los picos de gasto.
                </li>
                <li class="flex items-start gap-3">
                    <span class="icon-circle shrink-0">
                        <?= lucide_icon('bar-chart-3', 'h-4 w-4') ?>
                    </span>
                    Explora los reportes comparativos para detectar oportunidades de ahorro sostenido.
                </li>
            </ul>
            <div class="flex flex-wrap gap-3">
                <a href="/App-Control-Gastos/public/transacciones" class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-brand-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 self-start md:self-auto">
                    Registrar movimiento
                    <?= lucide_icon('plus-circle', 'h-4 w-4') ?>
                </a>
                <a href="/App-Control-Gastos/public/alertas" class="inline-flex items-center gap-2 rounded-full border border-brand-200/70 px-5 py-2.5 text-xs font-semibold text-brand-600 transition hover:bg-brand-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 self-start md:self-auto">
                    Ver alertas
                </a>
            </div>
                </article>

                <article class="rounded-3xl border border-slate-200/70 bg-white/95 p-6 sm:p-7 shadow-soft dark:border-slate-800/60 dark:bg-slate-900/70">
                    <h2 class="mb-3 text-lg font-semibold text-brand-700 dark:text-info">Métodos de gasto configurados</h2>
                    <?php if (empty($media)): ?>
                        <p class="text-sm text-slate-500 text-balance">Configura tus medios de gasto para visualizar comparativas por canal.</p>
                    <?php else: ?>
                        <ul class="divide-y divide-slate-100 text-sm text-slate-600 dark:divide-slate-800 dark:text-slate-200">
                            <?php foreach ($media as $item): ?>
                                <li class="flex items-center justify-between px-0 py-3 first:pt-0 last:pb-0">
                                    <span class="font-semibold"><?= htmlspecialchars(ucfirst($item), ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="text-xs uppercase tracking-wide text-slate-400">Activo</span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>
            </div>
        </section>

        <section class="space-y-4">
            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.3em] text-slate-400">Comportamiento por categorías</p>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3">
                <article class="rounded-3xl border border-slate-200/70 bg-white/95 p-6 sm:p-7 shadow-soft dark:border-slate-800/60 dark:bg-slate-900/70">
                    <h2 class="mb-4 text-lg font-semibold text-brand-700 dark:text-info">Top categorías de gasto</h2>
                    <?php if (empty($topCategories)): ?>
                        <p class="text-sm text-slate-500 text-balance">Aún no hay datos suficientes este mes. Registra tus gastos para comenzar a analizarlos.</p>
                    <?php else: ?>
                        <ul class="divide-y divide-slate-100 text-sm text-slate-600 dark:divide-slate-800 dark:text-slate-200">
                            <?php foreach ($topCategories as $item): ?>
                                <li class="flex items-center justify-between px-0 py-3 first:pt-0 last:pb-0">
                                    <span class="font-semibold"><?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="font-semibold text-danger">-<?= number_format((float) $item['total'], 2) ?> <?= $currency ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>

                <article class="rounded-3xl border border-slate-200/70 bg-white/95 p-6 sm:p-7 shadow-soft dark:border-slate-800/60 dark:bg-slate-900/70">
                    <h2 class="mb-4 text-lg font-semibold text-brand-700 dark:text-info">Métodos de pago más usados</h2>
                    <?php if (empty($paymentBreakdown)): ?>
                        <p class="text-sm text-slate-500 text-balance">Registra movimientos para visualizar tus métodos de pago preferidos.</p>
                    <?php else: ?>
                        <ul class="divide-y divide-slate-100 text-sm text-slate-600 dark:divide-slate-800 dark:text-slate-200">
                            <?php foreach ($paymentBreakdown as $item): ?>
                                <li class="flex items-center justify-between px-0 py-3 first:pt-0 last:pb-0">
                                    <span class="font-semibold"><?= ucfirst($item['payment_method']) ?></span>
                                    <span class="font-semibold text-brand-700 dark:text-info"><?= number_format((float) $item['total'], 2) ?> <?= $currency ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>

                <article class="rounded-3xl border border-slate-200/70 bg-white/95 p-6 sm:p-7 shadow-soft dark:border-slate-800/60 dark:bg-slate-900/70">
                    <h2 class="mb-4 text-lg font-semibold text-brand-700 dark:text-info">Historial de consumo</h2>
                    <?php if (empty($recent)): ?>
                        <div class="mx-auto flex max-w-sm flex-col items-center justify-center gap-4 rounded-2xl border border-dashed border-slate-300/70 bg-gradient-to-b from-slate-50 via-white to-slate-50 px-6 py-10 text-center dark:border-slate-700/70 dark:bg-slate-900/40">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white/80 text-brand-500 shadow-inner shadow-white/40 dark:bg-slate-800/70">
                                <?= lucide_icon('clock-3', 'h-5 w-5') ?>
                            </span>
                            <div class="space-y-1">
                                <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Aún no registras movimientos recientes.</p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 text-balance">Registra tus ingresos o gastos para verlos reflejados inmediatamente.</p>
                            </div>
                            <a href="/App-Control-Gastos/public/transacciones#registro" class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-brand-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                                <?= lucide_icon('plus-circle', 'h-4 w-4') ?>
                                Registrar movimiento
                            </a>
                        </div>
                    <?php else: ?>
                        <ul class="list-timeline max-h-72 space-y-4 overflow-y-auto pr-2 text-sm text-slate-600 dark:text-slate-200">
                            <?php foreach ($recent as $item): ?>
                                <li class="relative pl-3">
                                    <div class="flex items-center justify-between gap-4">
                                        <p class="font-semibold"><?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?></p>
                                        <span class="font-semibold <?= $item['type'] === 'income' ? 'text-brand-600 dark:text-info' : 'text-danger' ?>">
                                            <?= $item['type'] === 'income' ? '+' : '-' ?>
                                            <?= number_format((float) $item['amount'], 2) ?> <?= $currency ?>
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-400">
                                        <?= date('d/m/Y', strtotime($item['happened_on'])) ?> · <?= ucfirst($item['payment_method']) ?>
                                    </p>
                                    <?php if (!empty($item['description'])): ?>
                                        <p class="mt-1 text-xs text-slate-500"><?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>
            </div>
        </section>

    <section class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 sm:p-8 shadow-soft space-y-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-brand-700 dark:text-info">Movimientos recientes</h2>
                <p class="text-sm text-slate-500">Consulta tu historial mas cercano y valida que todo este en orden.</p>
            </div>
            <a href="/App-Control-Gastos/public/transacciones" class="inline-flex items-center gap-2 self-start rounded-full border border-brand-200/70 px-5 py-2.5 text-xs font-semibold text-brand-600 transition hover:bg-brand-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 md:self-end">
                Gestionar movimientos
                <?= lucide_icon('chevron-right', 'h-4 w-4') ?>
            </a>
        </div>
        <div class="w-full overflow-x-auto md:overflow-visible rounded-2xl border border-slate-200/60 dark:border-slate-800/60">
            <table class="min-w-[720px] divide-y divide-slate-200 text-xs md:text-sm dark:divide-slate-800">
                <thead class="sticky top-0 z-10 bg-slate-50/95 text-[0.7rem] uppercase tracking-[0.2em] text-slate-500 backdrop-blur dark:bg-slate-900/90">
                    <tr>
                        <th class="px-6 py-3 text-left">Fecha</th>
                        <th class="px-6 py-3 text-left">Categoría</th>
                        <th class="px-6 py-3 text-left">Método</th>
                        <th class="px-6 py-3 text-left">Descripción</th>
                        <th class="px-6 py-3 text-right">Monto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if (empty($recent)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-10">
                                <div class="flex flex-col items-center justify-center gap-3 text-center">
                                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-brand-500">
                                        <?= lucide_icon('inbox', 'h-6 w-6') ?>
                                    </span>
                                    <div class="space-y-1">
                                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Aún no registras movimientos.</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 text-balance">Registra tu primer movimiento para visualizarlo en tu tablero.</p>
                                    </div>
                                    <a href="/App-Control-Gastos/public/transacciones#registro" class="inline-flex items-center gap-2 rounded-full bg-brand-600 px-5 py-2.5 text-xs font-semibold text-white transition hover:bg-brand-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                                        <?= lucide_icon('plus-circle', 'h-4 w-4') ?>
                                        Registrar movimiento
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent as $item): ?>
                            <tr class="transition hover:bg-brand-50/40 dark:hover:bg-slate-800/40">
                                <td class="whitespace-nowrap px-6 py-3 font-semibold text-slate-600 dark:text-slate-200">
                                    <?= date('d/m/Y', strtotime($item['happened_on'])) ?>
                                </td>
                                <td class="px-6 py-3 text-slate-600 dark:text-slate-200">
                                    <?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="whitespace-nowrap px-6 py-3 text-slate-500 dark:text-slate-300">
                                    <?= ucfirst($item['payment_method']) ?>
                                </td>
                                <td class="px-6 py-3 text-slate-400 dark:text-slate-400">
                                    <?= htmlspecialchars($item['description'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="px-6 py-3 text-right font-semibold <?= $item['type'] === 'income' ? 'text-brand-600 dark:text-info' : 'text-danger' ?>">
                                    <?= $item['type'] === 'income' ? '+' : '-' ?>
                                    <?= number_format((float) $item['amount'], 2) ?> <?= $currency ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const data = <?= json_encode($trend, JSON_UNESCAPED_UNICODE) ?>;
    const ctx = document.getElementById('dashboardTrendChart');
    if (!ctx || !data || !data.labels) {
        return;
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Ingresos',
                    data: data.income,
                    borderColor: '#38BDF8',
                    backgroundColor: 'rgba(56, 189, 248, 0.2)',
                    tension: 0.35,
                    fill: true,
                },
                {
                    label: 'Gastos',
                    data: data.expense,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.18)',
                    tension: 0.35,
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                    },
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    borderColor: 'rgba(148, 163, 184, 0.25)',
                    borderWidth: 1,
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148, 163, 184, 0.15)',
                    },
                    ticks: {
                        color: '#64748B',
                        callback(value) {
                            return `${value}`;
                        },
                    },
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#94A3B8',
                    },
                },
            },
        }
    });
})();
</script>
