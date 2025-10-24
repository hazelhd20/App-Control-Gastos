<?php
/** @var array $profile */
/** @var array $summary */
/** @var array $trend */
/** @var array $topCategories */
/** @var array $paymentBreakdown */
/** @var array \ */
/** @var array \ */
/** @var int|null $daysSinceLastMovement */

$currency = htmlspecialchars($profile['currency'], ENT_QUOTES, 'UTF-8');
$media = is_array($profile['spending_media']) ? $profile['spending_media'] : json_decode($profile['spending_media'] ?? '[]', true);
$goalTypeLabels = [
    'save' => 'Ahorrar',
    'debt' => 'Pagar deudas',
    'control' => 'Controlar gastos',
    'other' => 'Otro objetivo',
];
$goalLabel = $goalTypeLabels[$profile['goal_type']] ?? 'Objetivo activo';
\ = \['limit'] > 0 ? \['limit_usage'] : 0;\n\ = \ ?? [];
?>

<section class="space-y-10">
    <header class="bg-gradient-to-r from-primary-500/90 to-primary-700 rounded-3xl p-8 md:p-10 text-white shadow-xl">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-3">
                <p class="uppercase tracking-[0.35em] text-xs text-white/70 font-semibold">Resumen general</p>
                <h1 class="text-3xl font-semibold">Hola, <?= htmlspecialchars($profile['name'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="text-white/80 max-w-2xl">Visualiza tus avances del mes, detecta oportunidades de ahorro y da seguimiento a tus metas financieras.</p>
                <?php if ($daysSinceLastMovement !== null && $daysSinceLastMovement >= 7): ?>
                    <div class="mt-2 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 text-xs font-semibold text-white/80 border border-white/20">
                        Han pasado <?= $daysSinceLastMovement ?> dias sin registrar movimientos. Mantener un seguimiento frecuente mejora tu control.
                    </div>
                <?php endif; ?>
            </div>
            <div class="rounded-2xl bg-white/15 px-5 py-4 text-sm backdrop-blur border border-white/10 shadow-lg max-w-sm">
                <p class="text-white/70">Objetivo actual</p>
                <p class="text-lg font-semibold"><?= htmlspecialchars($goalLabel, ENT_QUOTES, 'UTF-8') ?></p>
                <?php if (!empty($profile['goal_description'])): ?>
                    <p class="text-white/75 mt-1"><?= htmlspecialchars($profile['goal_description'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <?php if (!empty($alerts)): ?>
        <section class="grid gap-4">
            <?php foreach ($alerts as $alert): ?>
                <?php
                $style = $alertStyles[$alert['level']] ?? 'border-slate-200 bg-white text-slate-600';
                $payload = $alert['payload'] ?? [];
                ?>
                <article class="rounded-3xl border px-5 py-4 shadow-sm <?= $style ?>">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide">Alerta <?= strtoupper($alert['level']) ?></p>
                            <p class="mt-2 text-sm font-medium"><?= htmlspecialchars($alert['message'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php if (!empty($payload)): ?>
                                <ul class="mt-2 text-xs text-slate-500 space-y-1">
                                    <?php foreach ($payload as $key => $value): ?>
                                        <li><span class="font-semibold text-slate-600"><?= htmlspecialchars(str_replace('_', ' ', ucfirst($key)), ENT_QUOTES, 'UTF-8') ?>:</span> <?= htmlspecialchars(is_array($value) ? json_encode($value) : (string) $value, ENT_QUOTES, 'UTF-8') ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <form action="/App-Control-Gastos/public/alertas/marcar" method="POST">
                            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="alert_id" value="<?= (int) $alert['id'] ?>">
                            <button class="text-xs font-semibold text-slate-500 hover:text-primary-600" <?= $alert['seen_at'] ? 'disabled' : '' ?>><?= $alert['seen_at'] ? 'Atendida' : 'Marcar como leida' ?></button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <section class="grid gap-6 md:grid-cols-4">
        <article class="card-glass rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Ingresos base</p>
            <p class="text-3xl font-bold text-primary-600"><?= number_format($summary['base_income'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Ingreso mensual y adicional configurados en tu perfil.</p>
        </article>
        <article class="card-glass rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Ingresos registrados</p>
            <p class="text-3xl font-bold text-primary-600"><?= number_format($summary['registered_income'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Ingresos adicionales durante este mes.</p>
        </article>
        <article class="card-glass rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Gastos del mes</p>
            <p class="text-3xl font-bold text-danger"><?= number_format($summary['expenses'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Egresos registrados en el periodo actual.</p>
        </article>
        <article class="card-glass rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Saldo disponible</p>
            <p class="text-3xl font-bold <?= $summary['available'] >= 0 ? 'text-primary-600' : 'text-danger' ?>"><?= number_format($summary['available'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Resultado de ingresos menos gastos.</p>
        </article>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <p class="text-sm font-semibold text-slate-600">Uso del limite mensual</p>
            <span class="text-sm font-semibold <?= $summary['over_limit'] ? 'text-danger' : 'text-primary-600' ?>"><?= $limitUsage ?>%</span>
        </div>
        <div class="h-3 rounded-full bg-slate-100 overflow-hidden">
            <div class="h-full rounded-full <?= $summary['over_limit'] ? 'bg-danger' : 'bg-primary-500' ?>" style="width: <?= $limitUsage ?>%;"></div>
        </div>
        <div class="flex items-center justify-between text-xs text-slate-500">
            <span>Limite mensual: <?= number_format($summary['limit'], 2) ?> <?= $currency ?></span>
            <span><?= $summary['over_limit'] ? 'Superaste tu limite, revisa tus gastos.' : 'Aun estas dentro de tu limite planificado.' ?></span>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-primary-700">Tendencia de ingresos y gastos</h2>
                <a href="/App-Control-Gastos/public/reportes" class="text-sm font-semibold text-primary-600 hover:underline">Ver reportes</a>
            </div>
            <canvas id="dashboardTrendChart" height="220"></canvas>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <h2 class="text-xl font-semibold text-primary-700">Acciones rapidas</h2>
            <ul class="space-y-3 text-sm text-slate-600">
                <li class="flex gap-3">
                    <span class="text-primary-500 font-semibold">1.</span>
                    Registra un nuevo movimiento para mantener tus datos al dia.
                </li>
                <li class="flex gap-3">
                    <span class="text-primary-500 font-semibold">2.</span>
                    Ajusta tu limite de gastos en el modulo de perfil si tus ingresos cambiaron.
                </li>
                <li class="flex gap-3">
                    <span class="text-primary-500 font-semibold">3.</span>
                    Revisa los reportes para detectar categorias con mayor impacto.
                </li>
            </ul>
            <div class="flex flex-wrap gap-3">
                <a href="/App-Control-Gastos/public/transacciones" class="px-4 py-2 rounded-xl bg-primary-500 text-white text-sm font-semibold shadow hover:bg-primary-600 transition">Registrar movimiento</a>
                <a href="/App-Control-Gastos/public/perfil" class="px-4 py-2 rounded-xl border border-primary-200 text-primary-600 text-sm font-semibold hover:bg-primary-50 transition">Actualizar perfil</a>
            </div>
        </article>
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-primary-700 mb-3">Top categorias de gasto</h2>
            <?php if (empty($topCategories)): ?>
                <p class="text-sm text-slate-400">Aun no hay datos suficientes en este mes.</p>
            <?php else: ?>
                <ul class="space-y-3">
                    <?php foreach ($topCategories as $item): ?>
                        <li class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 font-semibold"><?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="text-sm text-danger font-semibold">-<?= number_format((float) $item['total'], 2) ?> <?= $currency ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-primary-700 mb-3">Metodos de pago mas usados</h2>
            <?php if (empty($paymentBreakdown)): ?>
                <p class="text-sm text-slate-400">Registra movimientos para visualizar tus metodos de pago.</p>
            <?php else: ?>
                <ul class="space-y-3">
                    <?php foreach ($paymentBreakdown as $item): ?>
                        <li class="flex items-center justify-between">
                            <span class="text-sm text-slate-600 font-semibold"><?= ucfirst($item['payment_method']) ?></span>
                            <span class="text-sm text-danger font-semibold">-<?= number_format((float) $item['total'], 2) ?> <?= $currency ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </article>
        <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-primary-700 mb-3">Medios de gasto configurados</h2>
            <?php if (empty($media)): ?>
                <p class="text-sm text-slate-400">Configura tus medios de gasto en el modulo de perfil.</p>
            <?php else: ?>
                <ul class="space-y-3 text-sm text-slate-600">
                    <?php foreach ($media as $item): ?>
                        <li class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-primary-500"></span>
                            <?= htmlspecialchars(ucfirst($item), ENT_QUOTES, 'UTF-8') ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </article>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        <h2 class="text-xl font-semibold text-primary-700">Movimientos recientes</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-left">Categoria</th>
                        <th class="px-4 py-3 text-left">Metodo</th>
                        <th class="px-4 py-3 text-left">Descripcion</th>
                        <th class="px-4 py-3 text-right">Monto</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($recent)): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-400">Aun no registras movimientos.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recent as $item): ?>
                            <tr class="hover:bg-primary-50/40 transition">
                                <td class="px-4 py-3 text-slate-600 font-semibold"><?= date('d/m/Y', strtotime($item['happened_on'])) ?></td>
                                <td class="px-4 py-3 text-slate-600"><?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-slate-500"><?= ucfirst($item['payment_method']) ?></td>
                                <td class="px-4 py-3 text-slate-400"><?= htmlspecialchars($item['description'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-right font-semibold <?= $item['type'] === 'income' ? 'text-primary-600' : 'text-danger' ?>"><?= $item['type'] === 'income' ? '+' : '-' ?><?= number_format((float) $item['amount'], 2) ?> <?= $currency ?></td>
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
                    backgroundColor: 'rgba(56, 189, 248, 0.15)',
                    tension: 0.3,
                    fill: true,
                },
                {
                    label: 'Gastos',
                    data: data.expense,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.15)',
                    tension: 0.3,
                    fill: true,
                }
            ]
        },
        options: {
            plugins: {
                legend: { position: 'bottom' },
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
})();
</script>
