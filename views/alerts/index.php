<?php
/** @var array $alerts */
/** @var array $profile */
/** @var array $trend */
/** @var string $csrfToken */

$alerts = $alerts ?? [];
$currency = htmlspecialchars($profile['currency'] ?? 'MXN', ENT_QUOTES, 'UTF-8');

$levelLabels = [
    'danger' => 'Critica',
    'warning' => 'Advertencia',
    'info' => 'Informativa',
];

$levelClasses = [
    'danger' => 'border-danger/70 bg-danger/5 text-danger',
    'warning' => 'border-yellow-300 bg-yellow-50 text-yellow-700',
    'info' => 'border-brand-200 bg-brand-50 text-brand-700',
];

$levelBadges = [
    'danger' => 'bg-danger text-white',
    'warning' => 'bg-yellow-500 text-white',
    'info' => 'bg-brand-500 text-white',
];

$levelIcons = [
    'danger' => '!',
    'warning' => '!',
    'info' => 'i',
];

$pendingCount = 0;
$dangerCount = 0;
$warningCount = 0;
$infoCount = 0;

foreach ($alerts as $alert) {
    if (empty($alert['seen_at'])) {
        $pendingCount++;
    }

    $level = $alert['level'] ?? '';
    if ($level === 'danger') {
        $dangerCount++;
    } elseif ($level === 'warning') {
        $warningCount++;
    } elseif ($level === 'info') {
        $infoCount++;
    }
}
?>

<section class="space-y-12">
    <header class="rounded-3xl border border-brand-200/60 bg-gradient-to-r from-brand-500/20 via-brand-500/10 to-brand-500/30 px-6 py-8 sm:px-8 sm:py-10 shadow-floating">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-3 text-brand-700">
                <p class="text-xs font-semibold uppercase tracking-[0.32em] text-brand-500/80">Centro de alertas</p>
                <h1 class="text-3xl font-semibold text-brand-700">Alertas y recordatorios</h1>
                <p class="text-sm text-brand-600/80 max-w-3xl">
                    Revisa tus alertas activas, identifica prioridades y mantente al dia con tus metas y limite de gastos.
                </p>
            </div>
            <div class="grid gap-3 sm:grid-cols-3 text-sm">
                <div class="rounded-2xl border border-white/60 bg-white/90 px-5 py-4 text-brand-600 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-brand-400 font-semibold">Alertas activas</p>
                    <p class="mt-1 text-2xl font-semibold text-brand-700"><?= count($alerts) ?></p>
                    <p class="text-xs text-brand-500/80">Total registradas</p>
                </div>
                <div class="rounded-2xl border border-white/60 bg-white/90 px-5 py-4 text-danger shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-danger/70 font-semibold">Pendientes</p>
                    <p class="mt-1 text-2xl font-semibold text-danger"><?= $pendingCount ?></p>
                    <p class="text-xs text-danger/70">Sin marcar como atendidas</p>
                </div>
                <div class="rounded-2xl border border-white/60 bg-white/90 px-5 py-4 text-yellow-600 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-yellow-600/70 font-semibold">Criticas</p>
                    <p class="mt-1 text-2xl font-semibold text-yellow-600"><?= $dangerCount ?></p>
                    <p class="text-xs text-yellow-600/70">Alertas de mayor prioridad</p>
                </div>
            </div>
        </div>
    </header>

    <section class="grid gap-8 lg:grid-cols-3">
        <article class="lg:col-span-2 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h2 class="text-xl font-semibold text-slate-700">Panel de alertas</h2>
                <?php if (!empty($alerts)): ?>
                    <div class="flex items-center gap-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                        <span class="px-3 py-1 rounded-full border border-danger/60 text-danger">Criticas <?= $dangerCount ?></span>
                        <span class="px-3 py-1 rounded-full border border-yellow-300 text-yellow-700">Advertencias <?= $warningCount ?></span>
                        <span class="px-3 py-1 rounded-full border border-brand-300 text-brand-600">Informativas <?= $infoCount ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (empty($alerts)): ?>
                <div class="rounded-3xl border border-slate-200 bg-white px-6 py-8 sm:p-8 text-center shadow-sm">
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full border border-brand-200 text-brand-600">
                        
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700">Todo en orden</h3>
                    <p class="mt-2 text-sm text-slate-500">
                        No hay alertas activas. Continua registrando tus movimientos y ajusta tus preferencias en el modulo de perfil cuando lo necesites.
                    </p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($alerts as $alert): ?>
                        <?php
                        $level = $alert['level'] ?? 'info';
                        $style = $levelClasses[$level] ?? 'border-slate-200 bg-white text-slate-600';
                        $badgeStyle = $levelBadges[$level] ?? 'bg-brand-500 text-white';
                        $icon = $levelIcons[$level] ?? 'i';
                        $label = $levelLabels[$level] ?? ucfirst($level);
                        $payload = $alert['payload'] ?? [];
                        ?>
                        <article class="group rounded-3xl border px-5 py-5 sm:px-6 sm:py-6 shadow-sm transition hover:shadow-md <?= $style ?>">
                            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold <?= $badgeStyle ?>">
                                            <?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wide">
                                                <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                                            </p>
                                            <p class="text-xs text-slate-400">
                                                <?= date('d/m/Y H:i', strtotime($alert['created_at'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium text-slate-700">
                                        <?= htmlspecialchars($alert['message'], ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                    <?php if (!empty($payload)): ?>
                                        <div class="rounded-2xl border border-white/60 bg-white/80 px-4 py-3 text-xs shadow-inner">
                                            <p class="font-semibold text-slate-600 mb-2">Detalle</p>
                                            <dl class="grid gap-2 sm:grid-cols-2">
                                                <?php foreach ($payload as $key => $value): ?>
                                                    <div class="flex items-start gap-2 text-slate-500">
                                                        <dt class="font-semibold text-slate-600">
                                                            <?= htmlspecialchars(str_replace('_', ' ', ucfirst($key)), ENT_QUOTES, 'UTF-8') ?>:
                                                        </dt>
                                                        <dd>
                                                            <?= htmlspecialchars(is_array($value) ? json_encode($value) : (string) $value, ENT_QUOTES, 'UTF-8') ?>
                                                        </dd>
                                                    </div>
                                                <?php endforeach; ?>
                                            </dl>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex flex-col items-start gap-3 md:items-end">
                                    <span class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-500">
                                        <?= $alert['seen_at'] ? 'Atendida' : 'Pendiente' ?>
                                    </span>
                                    <form action="/App-Control-Gastos/public/alertas/marcar" method="POST">
                                        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="alert_id" value="<?= (int) $alert['id'] ?>">
                                        <button class="rounded-full border border-transparent px-4 py-2 md:px-5 md:py-2.5 text-xs md:text-sm font-semibold text-brand-600 transition hover:border-brand-200 hover:bg-brand-50 disabled:cursor-not-allowed disabled:text-slate-400" <?= $alert['seen_at'] ? 'disabled' : '' ?>>
                                            <?= $alert['seen_at'] ? 'Alerta atendida' : 'Marcar como leida' ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </article>

        <aside class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-7 shadow-sm">
                <h2 class="text-lg font-semibold text-brand-700 mb-4">Actividad de los ultimos meses</h2>
                <canvas id="alertsTrendChart" height="200"></canvas>
            </div>

            <div class="rounded-3xl border border-brand-200 bg-brand-50/70 p-6 sm:p-7 shadow-sm space-y-3">
                <h3 class="text-lg font-semibold text-brand-700">Recomendaciones rapidas</h3>
                <ul class="space-y-2 text-sm text-brand-700">
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-2 w-2 rounded-full bg-brand-500"></span>
                        Ajusta tu limite mensual desde el perfil cuando tus ingresos cambien.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-2 w-2 rounded-full bg-brand-500"></span>
                        Agenda un recordatorio semanal para capturar movimientos y evitar inactividad.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1 h-2 w-2 rounded-full bg-brand-500"></span>
                        Usa los filtros de reportes para encontrar categorias con mayor impacto.
                    </li>
                </ul>
            </div>
        </aside>
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
