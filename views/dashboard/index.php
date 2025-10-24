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
    'danger' => ['border' => 'border-danger/50', 'bg' => 'bg-rose-50 dark:bg-rose-900/30', 'text' => 'text-danger', 'accent' => 'bg-danger/20'],
    'warning' => ['border' => 'border-yellow-300', 'bg' => 'bg-amber-50 dark:bg-amber-900/30', 'text' => 'text-amber-700 dark:text-amber-300', 'accent' => 'bg-amber-200/60'],
    'info' => ['border' => 'border-info/40', 'bg' => 'bg-sky-50 dark:bg-sky-900/30', 'text' => 'text-brand-600 dark:text-info', 'accent' => 'bg-info/30'],
];
?>

<section class="space-y-12">
    <header class="gradient-card p-10 md:p-12 shadow-floating overflow-hidden relative">
        <div class="absolute right-10 top-10 hidden md:block">
            <div class="h-32 w-32 rounded-full bg-white/15 border border-white/20 flex items-center justify-center text-white/70 text-xs uppercase tracking-[0.4em]">
                <?= htmlspecialchars(strtoupper($currency), ENT_QUOTES, 'UTF-8') ?>
            </div>
        </div>
        <div class="space-y-8 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="space-y-4 max-w-2xl">
                    <p class="text-xs uppercase tracking-[0.35em] text-white/70 font-semibold">Panel financiero</p>
                    <h1 class="text-3xl sm:text-4xl font-semibold leading-tight">
                        Hola, <?= htmlspecialchars($profile['name'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?>
                    </h1>
                    <p class="text-white/80 leading-relaxed">
                        Observa tus resultados del mes, controla el uso de tu limite y avanza hacia <?= htmlspecialchars(strtolower($goalLabel), ENT_QUOTES, 'UTF-8') ?> con decisiones basadas en datos.
                    </p>
                    <?php if ($daysSinceLastMovement !== null && $daysSinceLastMovement >= 7): ?>
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/15 px-4 py-2 text-xs font-semibold text-white/80">
                            <span class="inline-flex h-2 w-2 rounded-full bg-white"></span>
                            Han pasado <?= $daysSinceLastMovement ?> dias sin registrar movimientos. Actualiza tus datos para mantener el panorama preciso.
                        </div>
                    <?php endif; ?>
                </div>
                <div class="bg-white/15 rounded-3xl border border-white/25 backdrop-blur-xl px-6 py-5 max-w-sm shadow-floating space-y-3 text-sm">
                    <p class="text-white/70 uppercase tracking-wide text-xs font-semibold">Objetivo en curso</p>
                    <p class="text-lg font-semibold"><?= htmlspecialchars($goalLabel, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php if (!empty($profile['goal_description'])): ?>
                        <p class="text-white/75">
                            <?= htmlspecialchars($profile['goal_description'], ENT_QUOTES, 'UTF-8') ?>
                        </p>
                    <?php endif; ?>
                    <div class="flex items-center gap-3 text-xs text-white/70">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-full border border-white/25">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l3 1.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                        </span>
                        Mantente al dia actualizando tus ingresos y limites para obtener alertas oportunas.
                    </div>
                </div>
            </div>
        </div>
    </header>

    <?php if (!empty($alerts)): ?>
        <section class="space-y-4">
            <?php foreach ($alerts as $alert): ?>
                <?php
                $palette = $alertPalette[$alert['level']] ?? ['border' => 'border-slate-200/70', 'bg' => 'bg-white dark:bg-slate-900/70', 'text' => 'text-slate-600 dark:text-slate-300', 'accent' => 'bg-slate-200/60'];
                $payload = $alert['payload'] ?? [];
                ?>
                <article class="surface-card rounded-3xl border <?= $palette['border'] ?> <?= $palette['bg'] ?> px-6 py-5 shadow-soft transition hover-lift">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex-1 space-y-2">
                            <div class="inline-flex items-center gap-2 rounded-full <?= $palette['accent'] ?> px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-100">
                                <span class="inline-flex h-2 w-2 rounded-full bg-current"></span>
                                Alerta <?= strtoupper($alert['level']) ?>
                            </div>
                            <p class="text-sm font-semibold <?= $palette['text'] ?>">
                                <?= htmlspecialchars($alert['message'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <?php if (!empty($payload)): ?>
                                <dl class="grid sm:grid-cols-2 gap-3 text-xs text-slate-500 dark:text-slate-300">
                                    <?php foreach ($payload as $key => $value): ?>
                                        <div class="bg-white/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-700/40 rounded-2xl px-3 py-2">
                                            <dt class="text-[0.65rem] uppercase tracking-wide font-semibold text-slate-400 dark:text-slate-500">
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
                            <button class="inline-flex items-center gap-2 rounded-full border border-slate-200/70 dark:border-slate-700/60 px-4 py-2 text-xs font-semibold text-brand-600 dark:text-info hover:border-brand-200 transition <?= $alert['seen_at'] ? 'opacity-60 pointer-events-none' : '' ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l6 6L21 6"/>
                                </svg>
                                <?= $alert['seen_at'] ? 'Alerta atendida' : 'Marcar como leida' ?>
                            </button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 hover-lift transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Ingresos base</p>
                    <p class="mt-3 text-3xl font-bold text-brand-700 dark:text-info">
                        <?= number_format($summary['base_income'], 2) ?> <?= $currency ?>
                    </p>
                </div>
                <span class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21a9 9 0 1 0-9-9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 7.5V12l2.25 1.125"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-xs text-slate-500">Incluye tus ingresos mensuales y adicionales configurados en el perfil.</p>
        </article>

        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 hover-lift transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Ingresos registrados</p>
                    <p class="mt-3 text-3xl font-bold text-brand-700 dark:text-info">
                        <?= number_format($summary['registered_income'], 2) ?> <?= $currency ?>
                    </p>
                </div>
                <span class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12a9 9 0 1 1 9 9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 12h9M12 7.5v9"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-xs text-slate-500">Dinero capturado en el periodo actual, perfecto para actualizar tus flujos.</p>
        </article>

        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 hover-lift transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Gastos del mes</p>
                    <p class="mt-3 text-3xl font-bold text-danger">
                        <?= number_format($summary['expenses'], 2) ?> <?= $currency ?>
                    </p>
                </div>
                <span class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m3 3 18 18M9.75 9.75 4.5 14.25m15 0A7.5 7.5 0 0 0 9.75 9.75"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-xs text-slate-500">Total de egresos registrados. Analiza tus categorias para mejorar cada rubro.</p>
        </article>

        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 hover-lift transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Saldo disponible</p>
                    <p class="mt-3 text-3xl font-bold <?= $summary['available'] >= 0 ? 'text-brand-700 dark:text-info' : 'text-danger' ?>">
                        <?= number_format($summary['available'], 2) ?> <?= $currency ?>
                    </p>
                </div>
                <span class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8.25v7.5m-3.75-3.75h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-4 text-xs text-slate-500">Resultado entre ingresos y gastos. Ideal para planear tus proximos objetivos.</p>
        </article>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.8fr_1fr]">
        <article class="chart-card bg-white dark:bg-slate-900/80 border border-slate-200/70 dark:border-slate-800/60 shadow-soft">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-brand-700 dark:text-info">Tendencia de ingresos y gastos</h2>
                    <p class="text-sm text-slate-500 mt-1">Identifica patrones y toma acciones preventivas ante los picos de gasto.</p>
                </div>
                <a href="/App-Control-Gastos/public/reportes" class="inline-flex items-center gap-2 rounded-full border border-brand-200/70 text-brand-600 px-4 py-2 text-xs font-semibold hover:bg-brand-50 transition">
                    Ver reportes
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m9 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
            <canvas id="dashboardTrendChart" height="240"></canvas>
        </article>

        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 space-y-6 shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Uso del limite mensual</p>
                    <p class="mt-2 text-lg font-semibold text-brand-700 dark:text-info"><?= $limitUsagePercent ?>%</p>
                </div>
                <div class="relative h-28 w-28">
                    <svg width="112" height="112">
                        <circle class="text-slate-200/60 dark:text-slate-700/40" stroke="currentColor" stroke-width="10" fill="transparent" r="<?= $ringRadius ?>" cx="56" cy="56"></circle>
                        <circle class="text-brand-500" stroke="currentColor" stroke-width="10" fill="transparent" r="<?= $ringRadius ?>" cx="56" cy="56" stroke-dasharray="<?= $ringCircumference ?>" stroke-dashoffset="<?= $ringOffset ?>" stroke-linecap="round" class="progress-ring"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-xs font-semibold text-slate-500">
                        <span>Limite</span>
                        <span><?= number_format($summary['limit'], 2) ?> <?= $currency ?></span>
                    </div>
                </div>
            </div>
            <p class="text-sm text-slate-500">
                <?= $summary['over_limit']
                    ? 'Superaste tu limite planificado. Revisa las categorias con mayor impacto y define acciones inmediatas.'
                    : 'Estas dentro de tu limite mensual. Mantente atento a los proximos movimientos para conservar el equilibrio.' ?>
            </p>
            <div class="space-y-2 text-xs text-slate-500">
                <div class="flex items-center justify-between">
                    <span>Gastos acumulados</span>
                    <span><?= number_format($summary['expenses'], 2) ?> <?= $currency ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <span>Ingresos disponibles</span>
                    <span><?= number_format($summary['available'], 2) ?> <?= $currency ?></span>
                </div>
            </div>
            <a href="/App-Control-Gastos/public/perfil" class="inline-flex items-center gap-2 rounded-full bg-brand-600 text-white px-4 py-2 text-xs font-semibold hover:bg-brand-700 transition transition-press">
                Ajustar limite
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12h18M3 12l6-6m-6 6 6 6"/>
                </svg>
            </a>
        </article>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.5fr_1fr]">
        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 space-y-5 shadow-soft">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-brand-700 dark:text-info">Acciones sugeridas</h2>
                <span class="badge-soft">Productividad</span>
            </div>
            <ul class="space-y-4 text-sm text-slate-600 dark:text-slate-300">
                <li class="flex gap-3">
                    <span class="icon-circle shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    Registra tus movimientos recientes para mantener actualizado el balance del periodo.
                </li>
                <li class="flex gap-3">
                    <span class="icon-circle shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l4 2"/>
                        </svg>
                    </span>
                    Activa recordatorios para anticiparte a los picos de gasto.
                </li>
                <li class="flex gap-3">
                    <span class="icon-circle shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4.5h18m-18 6h18m-18 6h18"/>
                        </svg>
                    </span>
                    Explora los reportes comparativos para detectar oportunidades de ahorro sostenido.
                </li>
            </ul>
            <div class="flex flex-wrap gap-3">
                <a href="/App-Control-Gastos/public/transacciones" class="inline-flex items-center gap-2 rounded-full bg-brand-600 text-white px-4 py-2 text-xs font-semibold hover:bg-brand-700 transition transition-press">
                    Registrar movimiento
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6"/>
                    </svg>
                </a>
                <a href="/App-Control-Gastos/public/alertas" class="inline-flex items-center gap-2 rounded-full border border-brand-200/70 text-brand-600 px-4 py-2 text-xs font-semibold hover:bg-brand-50 transition">
                    Ver alertas
                </a>
            </div>
        </article>

        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 shadow-soft">
            <h2 class="text-lg font-semibold text-brand-700 dark:text-info mb-3">Metodos de gasto configurados</h2>
            <?php if (empty($media)): ?>
                <p class="text-sm text-slate-500">Configura tus medios de gasto para visualizar comparativas por canal.</p>
            <?php else: ?>
                <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-200">
                    <?php foreach ($media as $item): ?>
                        <li class="flex items-center justify-between rounded-2xl border border-slate-200/70 dark:border-slate-800/60 px-4 py-2">
                            <span class="font-semibold"><?= htmlspecialchars(ucfirst($item), ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="text-xs text-slate-400 uppercase tracking-wide">Activo</span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </article>
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 shadow-soft">
            <h2 class="text-lg font-semibold text-brand-700 dark:text-info mb-4">Top categorias de gasto</h2>
            <?php if (empty($topCategories)): ?>
                <p class="text-sm text-slate-500">Aun no hay datos suficientes este mes. Registra tus gastos para comenzar a analizarlos.</p>
            <?php else: ?>
                <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-200">
                    <?php foreach ($topCategories as $item): ?>
                        <li class="flex items-center justify-between rounded-2xl border border-slate-200/60 dark:border-slate-700/50 px-4 py-2">
                            <span class="font-semibold"><?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="text-danger font-semibold">-<?= number_format((float) $item['total'], 2) ?> <?= $currency ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </article>

        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 shadow-soft">
            <h2 class="text-lg font-semibold text-brand-700 dark:text-info mb-4">Metodos de pago mas usados</h2>
            <?php if (empty($paymentBreakdown)): ?>
                <p class="text-sm text-slate-500">Registra movimientos para visualizar tus metodos de pago preferidos.</p>
            <?php else: ?>
                <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-200">
                    <?php foreach ($paymentBreakdown as $item): ?>
                        <li class="flex items-center justify-between rounded-2xl border border-slate-200/60 dark:border-slate-700/50 px-4 py-2">
                            <span class="font-semibold"><?= ucfirst($item['payment_method']) ?></span>
                            <span class="text-brand-700 dark:text-info font-semibold"><?= number_format((float) $item['total'], 2) ?> <?= $currency ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </article>

        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 shadow-soft">
            <h2 class="text-lg font-semibold text-brand-700 dark:text-info mb-4">Historial de consumo</h2>
            <?php if (empty($recent)): ?>
                <p class="text-sm text-slate-500">Aun no registras movimientos recientes.</p>
            <?php else: ?>
                <ul class="list-timeline space-y-4 text-sm text-slate-600 dark:text-slate-200 max-h-72 overflow-y-auto pr-2">
                    <?php foreach ($recent as $item): ?>
                        <li class="relative pl-3">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold"><?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?></p>
                                <span class="<?= $item['type'] === 'income' ? 'text-brand-600 dark:text-info' : 'text-danger' ?> font-semibold">
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
    </section>

    <section class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 shadow-soft space-y-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-brand-700 dark:text-info">Movimientos recientes</h2>
                <p class="text-sm text-slate-500">Consulta tu historial mas cercano y valida que todo este en orden.</p>
            </div>
            <a href="/App-Control-Gastos/public/transacciones" class="inline-flex items-center gap-2 rounded-full border border-brand-200/70 text-brand-600 px-4 py-2 text-xs font-semibold hover:bg-brand-50 transition">
                Gestionar movimientos
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m9 5 7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="overflow-x-auto rounded-2xl border border-slate-200/60 dark:border-slate-800/60">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/70 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-left">Categoria</th>
                        <th class="px-4 py-3 text-left">Metodo</th>
                        <th class="px-4 py-3 text-left">Descripcion</th>
                        <th class="px-4 py-3 text-right">Monto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if (empty($recent)): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-400">Aun no registras movimientos.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent as $item): ?>
                            <tr class="hover:bg-brand-50/40 dark:hover:bg-slate-800/40 transition">
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-200 font-semibold">
                                    <?= date('d/m/Y', strtotime($item['happened_on'])) ?>
                                </td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-200">
                                    <?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-300">
                                    <?= ucfirst($item['payment_method']) ?>
                                </td>
                                <td class="px-4 py-3 text-slate-400 dark:text-slate-400">
                                    <?= htmlspecialchars($item['description'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td class="px-4 py-3 text-right font-semibold <?= $item['type'] === 'income' ? 'text-brand-600 dark:text-info' : 'text-danger' ?>">
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
