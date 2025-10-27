<?php
/** @var array $profile */
/** @var array $overview */
/** @var array $categoryBreakdown */
/** @var array $methodBreakdown */
/** @var array $trend */
/** @var array $filters */
/** @var array $rows */

$currency = htmlspecialchars($profile['currency'], ENT_QUOTES, 'UTF-8');

// Helper centralizado en views/partials/icons.php

$icon = fn(string $name, string $classes = 'h-4 w-4'): string => __lucide_icon_helper($name, $classes);
$from = htmlspecialchars($filters['from'], ENT_QUOTES, 'UTF-8');
$to = htmlspecialchars($filters['to'], ENT_QUOTES, 'UTF-8');
$typeFilter = htmlspecialchars($filters['type'] ?? '', ENT_QUOTES, 'UTF-8');
$categoryFilter = htmlspecialchars($filters['category'] ?? '', ENT_QUOTES, 'UTF-8');
$methodFilter = htmlspecialchars($filters['payment_method'] ?? '', ENT_QUOTES, 'UTF-8');

$expenseCategories = array_map(static function ($item) {
    return [
        'category' => $item['category'],
        'total' => (float) $item['total'],
    ];
}, $categoryBreakdown['expense'] ?? []);

$incomeCategories = array_map(static function ($item) {
    return [
        'category' => $item['category'],
        'total' => (float) $item['total'],
    ];
}, $categoryBreakdown['income'] ?? []);

$methodData = array_map(static function ($item) {
    return [
        'payment_method' => $item['payment_method'],
        'total' => (float) $item['total'],
    ];
}, $methodBreakdown ?? []);

$exportQuery = http_build_query([
    'from' => $filters['from'],
    'to' => $filters['to'],
    'type' => $filters['type'],
    'category' => $filters['category'],
    'payment_method' => $filters['payment_method'],
]);
?>

<section class="space-y-12">
    <header class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="space-y-2">
            <p class="text-xs uppercase tracking-[0.35em] text-slate-400 font-semibold">Analitica</p>
            <h1 class="text-3xl font-semibold text-brand-700">Reportes y estadisticas</h1>
            <p class="text-slate-500 leading-relaxed">Analiza tu comportamiento financiero con filtros inteligentes y exportaciones personalizadas.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="/App-Control-Gastos/public/reportes/exportar?<?= $exportQuery ?>&format=csv" class="inline-flex items-center gap-2 rounded-full border border-brand-200/70 bg-white/80 text-brand-600 text-sm font-semibold px-4 py-2 shadow-soft hover:bg-brand-50 transition">
                <?= $icon('file-down', 'h-4 w-4') ?>
                Descargar CSV
            </a>
            <a href="/App-Control-Gastos/public/reportes/exportar?<?= $exportQuery ?>&format=xlsx" class="inline-flex items-center gap-2 rounded-full bg-brand-600 text-white text-sm font-semibold px-4 py-2 shadow-floating transition-all duration-200 ease-out hover:bg-brand-700 active:scale-[0.97] active:ring-2 active:ring-brand-300/40">
                <?= $icon('table', 'h-4 w-4') ?>
                Descargar Excel
            </a>
        </div>
    </header>

    <section class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 bg-white/90 dark:bg-slate-900/70 p-6 sm:p-8 shadow-soft space-y-6">
        <form method="GET" class="grid md:grid-cols-5 gap-4 items-end">
            <div class="space-y-1">
                <label for="from" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Desde</label>
                <input id="from" name="from" type="date" value="<?= $from ?>" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 focus:border-brand-300 focus:ring focus:ring-info/20 transition">
            </div>
            <div class="space-y-1">
                <label for="to" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Hasta</label>
                <input id="to" name="to" type="date" value="<?= $to ?>" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 focus:border-brand-300 focus:ring focus:ring-info/20 transition">
            </div>
            <div class="space-y-1">
                <label for="type" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Tipo</label>
                <select id="type" name="type" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 focus:border-brand-300 focus:ring focus:ring-info/20 transition">
                    <option value="">Todos</option>
                    <option value="income" <?= $typeFilter === 'income' ? 'selected' : '' ?>>Ingresos</option>
                    <option value="expense" <?= $typeFilter === 'expense' ? 'selected' : '' ?>>Gastos</option>
                </select>
            </div>
            <div class="space-y-1">
                <label for="category" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Categoria</label>
                <input id="category" name="category" type="text" value="<?= $categoryFilter ?>" placeholder="Ej. Transporte" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 focus:border-brand-300 focus:ring focus:ring-info/20 transition">
            </div>
            <div class="space-y-1">
                <label for="payment_method" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Metodo</label>
                <select id="payment_method" name="payment_method" class="w-full rounded-2xl border border-slate-200 bg-white/90 px-3 py-2 focus:border-brand-300 focus:ring focus:ring-info/20 transition">
                    <option value="">Todos</option>
                    <option value="efectivo" <?= $methodFilter === 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
                    <option value="tarjeta" <?= $methodFilter === 'tarjeta' ? 'selected' : '' ?>>Tarjeta</option>
                    <option value="transferencia" <?= $methodFilter === 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
                    <option value="otro" <?= $methodFilter === 'otro' ? 'selected' : '' ?>>Otro</option>
                </select>
            </div>
            <div class="md:col-span-5 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-full bg-brand-600 text-white text-sm md:text-base font-semibold px-5 py-2.5 md:px-6 md:py-3 shadow-floating transition-all duration-200 ease-out hover:bg-brand-700 active:scale-[0.97] active:ring-2 active:ring-brand-300/40">
                    <?= $icon('filter', 'h-4 w-4') ?>
                    Aplicar filtros
                </button>
                <a href="/App-Control-Gastos/public/reportes" class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-200/70 text-sm md:text-base font-semibold text-slate-500 px-5 py-2.5 md:px-6 md:py-3 hover:border-brand-200 hover:text-brand-600 transition">
                    Limpiar
                </a>
            </div>
        </form>
    </section>

    <section class="grid gap-6 lg:grid-cols-4">
        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 sm:p-7 space-y-3 transition-all duration-200 ease-out hover:-translate-y-1 hover:shadow-floating">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <p class="text-sm text-slate-500 font-semibold">Ingresos base</p>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-500/10 via-info/10 to-brand-600/20 text-brand-600 ring-1 ring-brand-500/20 shadow-inner dark:from-info/20 dark:via-slate-900/60 dark:to-slate-900/80 dark:text-info">
                    <?= $icon('clock-3') ?>
                </span>
            </div>
            <p class="text-3xl font-bold text-brand-700 dark:text-info"><?= number_format($overview['base_income'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Ingresos recurrentes configurados en tu perfil.</p>
        </article>
        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 sm:p-7 space-y-3 transition-all duration-200 ease-out hover:-translate-y-1 hover:shadow-floating">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <p class="text-sm text-slate-500 font-semibold">Ingresos registrados</p>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-500/10 via-info/10 to-brand-600/20 text-brand-600 ring-1 ring-brand-500/20 shadow-inner dark:from-info/20 dark:via-slate-900/60 dark:to-slate-900/80 dark:text-info">
                    <?= $icon('piggy-bank') ?>
                </span>
            </div>
            <p class="text-3xl font-bold text-brand-700 dark:text-info"><?= number_format($overview['registered_income'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Ingresos adicionales dentro del periodo filtrado.</p>
        </article>
        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 sm:p-7 space-y-3 transition-all duration-200 ease-out hover:-translate-y-1 hover:shadow-floating">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <p class="text-sm text-slate-500 font-semibold">Gastos del periodo</p>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-500/10 via-info/10 to-brand-600/20 text-brand-600 ring-1 ring-brand-500/20 shadow-inner dark:from-info/20 dark:via-slate-900/60 dark:to-slate-900/80 dark:text-info">
                    <?= $icon('trending-down') ?>
                </span>
            </div>
            <p class="text-3xl font-bold text-danger"><?= number_format($overview['expenses'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Suma de egresos segun los filtros actuales.</p>
        </article>
        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 sm:p-7 space-y-3 transition-all duration-200 ease-out hover:-translate-y-1 hover:shadow-floating">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <p class="text-sm text-slate-500 font-semibold">Resultado neto</p>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-brand-500/10 via-info/10 to-brand-600/20 text-brand-600 ring-1 ring-brand-500/20 shadow-inner dark:from-info/20 dark:via-slate-900/60 dark:to-slate-900/80 dark:text-info">
                    <?= $icon('calculator') ?>
                </span>
            </div>
            <p class="text-3xl font-bold <?= $overview['net'] >= 0 ? 'text-brand-700 dark:text-info' : 'text-danger' ?>"><?= number_format($overview['net'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Comparativa entre ingresos y gastos del periodo.</p>
        </article>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <article class="chart-card bg-white dark:bg-slate-900/80 border border-slate-200/70 dark:border-slate-800/60 shadow-soft">
            <h2 class="text-xl font-semibold text-brand-700 dark:text-info mb-4">Tendencia mensual</h2>
            <canvas id="trendChart" height="200"></canvas>
        </article>
        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 bg-white/90 dark:bg-slate-900/70 p-6 sm:p-7 shadow-soft">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-xl font-semibold text-brand-700 dark:text-info">Gastos por metodo de pago</h2>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-info/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-brand-700 dark:bg-info/20 dark:text-info">Comparativa</span>
            </div>
            <?php if (empty($methodData)): ?>
                <p class="text-sm text-slate-500">Aun no hay datos suficientes para este periodo.</p>
            <?php else: ?>
                <canvas id="methodChart" height="200"></canvas>
            <?php endif; ?>
        </article>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 bg-white/90 dark:bg-slate-900/70 p-6 sm:p-7 shadow-soft">
            <h2 class="text-xl font-semibold text-brand-700 dark:text-info mb-4">Distribucion de gastos por categoria</h2>
            <?php if (empty($expenseCategories)): ?>
                <p class="text-sm text-slate-500">Registra mas movimientos para ver este grafico.</p>
            <?php else: ?>
                <canvas id="expenseChart" height="200"></canvas>
            <?php endif; ?>
        </article>
        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 bg-white/90 dark:bg-slate-900/70 p-6 sm:p-7 shadow-soft">
            <h2 class="text-xl font-semibold text-brand-700 dark:text-info mb-4">Distribucion de ingresos</h2>
            <?php if (empty($incomeCategories)): ?>
                <p class="text-sm text-slate-500">No hay ingresos registrados en el periodo.</p>
            <?php else: ?>
                <canvas id="incomeChart" height="200"></canvas>
            <?php endif; ?>
        </article>
    </section>

    <section class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 bg-white/90 dark:bg-slate-900/70 p-6 sm:p-8 shadow-soft space-y-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <h2 class="text-xl font-semibold text-brand-700 dark:text-info">Detalle de movimientos filtrados</h2>
            <span class="text-xs text-slate-400">Periodos: <?= $from ?> &mdash; <?= $to ?></span>
        </div>
        <div class="w-full overflow-x-auto md:overflow-visible rounded-2xl border border-slate-200/60 dark:border-slate-800/60">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/70 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-left">Tipo</th>
                        <th class="px-4 py-3 text-left">Categoria</th>
                        <th class="px-4 py-3 text-left">Descripcion</th>
                        <th class="px-4 py-3 text-left">Metodo</th>
                        <th class="px-4 py-3 text-right">Monto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-400">No se encontraron movimientos con los filtros seleccionados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr class="hover:bg-brand-50/40 dark:hover:bg-slate-800/40 transition">
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-200 font-semibold"><?= htmlspecialchars($row['happened_on'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-200"><?= strtoupper(htmlspecialchars($row['type'], ENT_QUOTES, 'UTF-8')) ?></td>
                                <td class="px-4 py-3 text-slate-600 dark:text-slate-200"><?= htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-slate-400 dark:text-slate-400"><?= htmlspecialchars($row['description'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-300"><?= htmlspecialchars($row['payment_method'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-right font-semibold <?= $row['type'] === 'income' ? 'text-brand-600 dark:text-info' : 'text-danger' ?>"><?= number_format((float) $row['amount'], 2) ?> <?= $currency ?></td>
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
    const trendData = <?= json_encode($trend, JSON_UNESCAPED_UNICODE) ?>;
    const expenseData = <?= json_encode($expenseCategories, JSON_UNESCAPED_UNICODE) ?>;
    const incomeData = <?= json_encode($incomeCategories, JSON_UNESCAPED_UNICODE) ?>;
    const methodData = <?= json_encode($methodData, JSON_UNESCAPED_UNICODE) ?>;

    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendData.labels,
                datasets: [
                    {
                        label: 'Ingresos',
                        data: trendData.income,
                        borderColor: '#38BDF8',
                        backgroundColor: 'rgba(56, 189, 248, 0.2)',
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Gastos',
                        data: trendData.expense,
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.15)',
                        tension: 0.3,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    }

    const renderPie = (ctxId, data, colors) => {
        const ctx = document.getElementById(ctxId);
        if (!ctx || !data.length) {
            return;
        }

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.map(item => item.category ?? item.payment_method),
                datasets: [
                    {
                        data: data.map(item => item.total),
                        backgroundColor: colors,
                        borderWidth: 0,
                    }
                ]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom' },
                }
            }
        });
    };

    renderPie('expenseChart', expenseData, ['#1E3A8A', '#38BDF8', '#0EA5E9', '#22C55E', '#94A3B8']);
    renderPie('incomeChart', incomeData, ['#22C55E', '#3B82F6', '#38BDF8', '#0EA5E9', '#34D399']);

    const methodCtx = document.getElementById('methodChart');
    if (methodCtx && methodData.length) {
        new Chart(methodCtx, {
            type: 'bar',
            data: {
                labels: methodData.map(item => item.payment_method),
                datasets: [
                    {
                        label: 'Total',
                        data: methodData.map(item => item.total),
                        backgroundColor: '#1E3A8A',
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    }
})();
</script>
