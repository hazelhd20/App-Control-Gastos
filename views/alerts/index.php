<?php
/** @var array $alerts */
/** @var array $profile */
/** @var array $trend */

$alerts = $alerts ?? [];
$currency = htmlspecialchars($profile['currency'] ?? 'MXN', ENT_QUOTES, 'UTF-8');
$levelLabels = [
    'danger' => 'Critica',
    'warning' => 'Advertencia',
    'info' => 'Informativa',
];
$levelClasses = [
    'danger' => 'border-danger/70 bg-danger/10 text-danger',
    'warning' => 'border-yellow-300 bg-yellow-50 text-yellow-700',
    'info' => 'border-primary-200 bg-primary-50 text-primary-700',
];
?>

<section class="space-y-10">
    <header class="space-y-2">
        <h1 class="text-3xl font-semibold text-primary-700">Alertas y recordatorios</h1>
        <p class="text-slate-500">Mantente al tanto de tu limite de gastos, tus metas activas y tu actividad reciente.</p>
    </header>

    <section class="grid gap-4">
        <?php if (empty($alerts)): ?>
            <p class="rounded-3xl border border-slate-200 bg-white p-6 text-sm text-slate-500 shadow-sm">
                No tienes alertas activas por el momento. Continua registrando tus movimientos para mantener un seguimiento constante.
            </p>
        <?php else: ?>
            <?php foreach ($alerts as $alert): ?>
                <?php
                $style = $levelClasses[$alert['level']] ?? 'border-slate-200 bg-white text-slate-600';
                $label = $levelLabels[$alert['level']] ?? ucfirst($alert['level']);
                $payload = $alert['payload'] ?? [];
                ?>
                <article class="rounded-3xl border px-5 py-4 shadow-sm <?= $style ?>">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide">
                                <span><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="text-slate-400">&bull;</span>
                                <span><?= date('d/m/Y H:i', strtotime($alert['created_at'])) ?></span>
                            </div>
                            <p class="text-sm font-medium"><?= htmlspecialchars($alert['message'], ENT_QUOTES, 'UTF-8') ?></p>
                            <?php if (!empty($payload)): ?>
                                <ul class="mt-2 text-xs text-slate-500 space-y-1">
                                    <?php foreach ($payload as $key => $value): ?>
                                        <li>
                                            <span class="font-semibold text-slate-600"><?= htmlspecialchars(str_replace('_', ' ', ucfirst($key)), ENT_QUOTES, 'UTF-8') ?>:</span>
                                            <?php if (is_array($value)): ?>
                                                <?= htmlspecialchars(json_encode($value), ENT_QUOTES, 'UTF-8') ?>
                                            <?php else: ?>
                                                <?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') ?>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <p class="text-xs text-slate-400">Estado: <?= $alert['seen_at'] ? 'Atendida' : 'Pendiente' ?></p>
                        </div>
                        <form action="/App-Control-Gastos/public/alertas/marcar" method="POST" class="self-start">
                            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="alert_id" value="<?= (int) $alert['id'] ?>">
                            <button class="text-xs font-semibold text-slate-500 hover:text-primary-600" <?= $alert['seen_at'] ? 'disabled' : '' ?>>
                                <?= $alert['seen_at'] ? 'Alerta atendida' : 'Marcar como leida' ?>
                            </button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-xl font-semibold text-primary-700 mb-4">Actividad de los ultimos meses</h2>
        <canvas id="alertsTrendChart" height="200"></canvas>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-3">
        <h2 class="text-xl font-semibold text-primary-700">Recomendaciones rapidas</h2>
        <ul class="space-y-2 text-sm text-slate-600">
            <li>&bull; Ajusta tu limite mensual en el modulo de perfil si tus ingresos cambiaron.</li>
            <li>&bull; Programa un recordatorio semanal para registrar movimientos y evitar alertas por inactividad.</li>
            <li>&bull; Revisa los reportes para identificar categorias con mayor impacto en tus gastos.</li>
        </ul>
    </section>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const data = <?= json_encode($trend, JSON_UNESCAPED_UNICODE) ?>;
    const ctx = document.getElementById('alertsTrendChart');
    if (!ctx || !data || !data.labels) {
        return;
    }

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Ingresos',
                    data: data.income,
                    backgroundColor: '#38BDF8',
                    borderRadius: 8,
                },
                {
                    label: 'Gastos',
                    data: data.expense,
                    backgroundColor: '#EF4444',
                    borderRadius: 8,
                }
            ]
        },
        options: {
            responsive: true,
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
