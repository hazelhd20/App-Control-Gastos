<?php
/** @var array $profile */
/** @var array $overview */
/** @var array $categoryBreakdown */
/** @var array $methodBreakdown */
/** @var array $trend */
/** @var array $filters */
/** @var array $rows */

$currency = htmlspecialchars($profile['currency'], ENT_QUOTES, 'UTF-8');
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

<section class="space-y-10">
    <header class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-semibold text-primary-700">Reportes y estadisticas</h1>
            <p class="text-slate-500">Analiza tu comportamiento financiero con filtros y exportaciones personalizadas.</p>
        </div>
        <div class="flex gap-3">
            <a href="/App-Control-Gastos/public/reportes/exportar?<?= $exportQuery ?>&format=csv" class="px-4 py-2 rounded-xl bg-white border border-primary-200 text-primary-600 text-sm font-semibold shadow-sm hover:bg-primary-50 transition">Descargar CSV</a>
            <a href="/App-Control-Gastos/public/reportes/exportar?<?= $exportQuery ?>&format=xlsx" class="px-4 py-2 rounded-xl bg-primary-500 text-white text-sm font-semibold shadow-lg shadow-primary-500/30 hover:bg-primary-600 transition">Descargar Excel</a>
        </div>
    </header>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
        <form method="GET" class="grid md:grid-cols-5 gap-4 items-end">
            <div class="space-y-1">
                <label for="from" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Desde</label>
                <input id="from" name="from" type="date" value="<?= $from ?>" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 focus:border-primary-300 focus:ring focus:ring-primary-100">
            </div>
            <div class="space-y-1">
                <label for="to" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Hasta</label>
                <input id="to" name="to" type="date" value="<?= $to ?>" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 focus:border-primary-300 focus:ring focus:ring-primary-100">
            </div>
            <div class="space-y-1">
                <label for="type" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Tipo</label>
                <select id="type" name="type" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 focus:border-primary-300 focus:ring focus:ring-primary-100">
                    <option value="">Todos</option>
                    <option value="income" <?= $typeFilter === 'income' ? 'selected' : '' ?>>Ingresos</option>
                    <option value="expense" <?= $typeFilter === 'expense' ? 'selected' : '' ?>>Gastos</option>
                </select>
            </div>
            <div class="space-y-1">
                <label for="category" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Categoria</label>
                <input id="category" name="category" type="text" value="<?= $categoryFilter ?>" placeholder="Ej. Transporte" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 focus:border-primary-300 focus:ring focus:ring-primary-100">
            </div>
            <div class="space-y-1">
                <label for="payment_method" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Metodo</label>
                <select id="payment_method" name="payment_method" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 focus:border-primary-300 focus:ring focus:ring-primary-100">
                    <option value="">Todos</option>
                    <option value="efectivo" <?= $methodFilter === 'efectivo' ? 'selected' : '' ?>>Efectivo</option>
                    <option value="tarjeta" <?= $methodFilter === 'tarjeta' ? 'selected' : '' ?>>Tarjeta</option>
                    <option value="transferencia" <?= $methodFilter === 'transferencia' ? 'selected' : '' ?>>Transferencia</option>
                    <option value="otro" <?= $methodFilter === 'otro' ? 'selected' : '' ?>>Otro</option>
                </select>
            </div>
            <div class="md:col-span-5 flex justify-end gap-3">
                <button type="submit" class="px-5 py-2 rounded-xl bg-primary-500 text-white text-sm font-semibold shadow hover:bg-primary-600 transition">Aplicar filtros</button>
                <a href="/App-Control-Gastos/public/reportes" class="px-5 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-500 hover:border-primary-200 hover:text-primary-600 transition">Limpiar</a>
            </div>
        </form>
    </section>

    <section class="grid gap-6 lg:grid-cols-4">
        <article class="card-glass rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Ingresos base</p>
            <p class="text-3xl font-bold text-primary-600"><?= number_format($overview['base_income'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Ingreso mensual registrado en tu perfil.</p>
        </article>
        <article class="card-glass rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Ingresos registrados</p>
            <p class="text-3xl font-bold text-primary-600"><?= number_format($overview['registered_income'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Ingresos adicionales dentro del periodo.</p>
        </article>
        <article class="card-glass rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Gastos del periodo</p>
            <p class="text-3xl font-bold text-danger"><?= number_format($overview['expenses'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Suma de tus egresos filtrados.</p>
        </article>
        <article class="card-glass rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Resultado neto</p>
            <p class="text-3xl font-bold <?= $overview['net'] >= 0 ? 'text-primary-600' : 'text-danger' ?>"><?= number_format($overview['net'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Ingresos menos gastos en el periodo.</p>
        </article>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-primary-700 mb-4">Tendencia mensual</h2>
            <canvas id="trendChart" height="200"></canvas>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-primary-700 mb-4">Gastos por metodo de pago</h2>
            <?php if (empty($methodData)): ?>
                <p class="text-sm text-slate-400">Aun no hay datos suficientes para este periodo.</p>
            <?php else: ?>
                <canvas id="methodChart" height="200"></canvas>
            <?php endif; ?>
        </article>
    </section>

    <section class="grid gap-6 lg:grid-cols-2">
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-primary-700 mb-4">Distribucion de gastos por categoria</h2>
            <?php if (empty($expenseCategories)): ?>
                <p class="text-sm text-slate-400">Registra mas movimientos para ver este grafico.</p>
            <?php else: ?>
                <canvas id="expenseChart" height="200"></canvas>
            <?php endif; ?>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-semibold text-primary-700 mb-4">Distribucion de ingresos</h2>
            <?php if (empty($incomeCategories)): ?>
                <p class="text-sm text-slate-400">No hay ingresos registrados en el periodo.</p>
            <?php else: ?>
                <canvas id="incomeChart" height="200"></canvas>
            <?php endif; ?>
        </article>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        <h2 class="text-xl font-semibold text-primary-700">Detalle de movimientos filtrados</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-left">Tipo</th>
                        <th class="px-4 py-3 text-left">Categoria</th>
                        <th class="px-4 py-3 text-left">Descripcion</th>
                        <th class="px-4 py-3 text-left">Metodo</th>
                        <th class="px-4 py-3 text-right">Monto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-400">No se encontraron movimientos con los filtros seleccionados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr class="hover:bg-primary-50/40 transition">
                                <td class="px-4 py-3 text-slate-600 font-semibold"><?= htmlspecialchars($row['happened_on'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-slate-600"><?= strtoupper(htmlspecialchars($row['type'], ENT_QUOTES, 'UTF-8')) ?></td>
                                <td class="px-4 py-3 text-slate-600"><?= htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-slate-400"><?= htmlspecialchars($row['description'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-slate-500"><?= htmlspecialchars($row['payment_method'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-right font-semibold <?= $row['type'] === 'income' ? 'text-primary-600' : 'text-danger' ?>"><?= number_format((float) $row['amount'], 2) ?> <?= $currency ?></td>
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

    renderPie('expenseChart', expenseData, ['#0072FF', '#66AAFF', '#99C6FF', '#338EFF', '#002E66']);
    renderPie('incomeChart', incomeData, ['#38BDF8', '#0EA5E9', '#0284C7', '#0369A1', '#0C4A6E']);

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
                        backgroundColor: '#0072FF',
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
