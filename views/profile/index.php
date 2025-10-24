<?php
/** @var array $user */
/** @var array $profile */
/** @var array $summary */
/** @var array $mediaOptions */
/** @var array $currencies */
/** @var array $old */
/** @var string $csrfToken */

$old ??= [];

$value = function (string $field, mixed $default = '') use ($old, $profile, $user) {
    if (array_key_exists($field, $old)) {
        return $old[$field];
    }

    return match ($field) {
        'name' => $user['name'] ?? $default,
        'phone' => $user['phone'] ?? $default,
        'occupation' => $user['occupation'] ?? $default,
        'email' => $user['email'] ?? $default,
        'monthly_income' => $profile['monthly_income'] ?? $default,
        'extra_income' => $profile['extra_income'] ?? $default,
        'start_date' => $profile['start_date'] ?? $default,
        'currency' => $profile['currency'] ?? $default,
        'goal_type' => $profile['goal_type'] ?? $default,
        'goal_description' => $profile['goal_description'] ?? $default,
        'goal_meta_amount' => $profile['goal_meta_amount'] ?? $default,
        'goal_meta_months' => $profile['goal_meta_months'] ?? $default,
        'debt_total_amount' => $profile['debt_total_amount'] ?? $default,
        'spending_limit_mode' => $profile['spending_limit_mode'] ?? $default,
        'spending_limit_amount' => $profile['spending_limit_amount'] ?? $default,
        'auto_limit_ratio' => $profile['auto_limit_ratio'] ?? $default,
        default => $default,
    };
};

$selectedMedia = $old['spending_media'] ?? ($profile['spending_media'] ?? []);
$debtPlan = $summary['goal']['debt_plan'] ?? null;
$initialSource = trim((string) ($user['name'] ?? ''));
$userInitial = strtoupper($initialSource !== '' ? substr($initialSource, 0, 1) : 'U');
$goalLabels = [
    'save' => 'Ahorro',
    'debt' => 'Salir de deudas',
    'control' => 'Control de gastos',
    'other' => 'Objetivo personal',
];
$activeGoalLabel = $goalLabels[$profile['goal_type']] ?? ($summary['goal']['label'] ?? 'Objetivo activo');
?>

<section class="space-y-12">
    <header class="gradient-card p-10 md:p-12 shadow-floating flex flex-col gap-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-brand-600 text-white font-semibold text-2xl shadow-floating avatar-ring">
                    <?= htmlspecialchars($userInitial, ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="space-y-2">
                    <p class="text-xs uppercase tracking-[0.35em] text-slate-500 font-semibold">Perfil personal</p>
                    <h1 class="text-3xl font-semibold leading-tight text-slate-900 dark:text-white">
                        Hola, <?= htmlspecialchars($user['name'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?>
                    </h1>
                    <p class="text-slate-600 dark:text-slate-200 max-w-xl">
                        Administra tus datos, ajusta tus limites y mantÃ©n tus objetivos financieros en sintonia con tu realidad diaria.
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="/App-Control-Gastos/public/transacciones" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-brand-600 shadow-soft hover:border-brand-200 hover:text-brand-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6"/>
                    </svg>
                    Registrar movimiento
                </a>
                <a href="/App-Control-Gastos/public/reportes" class="inline-flex items-center gap-2 rounded-full bg-brand-600 text-white px-5 py-3 text-sm font-semibold shadow-floating hover:bg-brand-700 transition">
                    Ver reportes
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m9 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 text-sm text-slate-600 dark:text-slate-200">
            <div class="panel-elevated px-4 py-3">
                <p class="uppercase tracking-wide text-[0.65rem] font-semibold text-slate-500">Meta actual</p>
                <p class="mt-1 font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($activeGoalLabel, ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="panel-elevated px-4 py-3">
                <p class="uppercase tracking-wide text-[0.65rem] font-semibold text-slate-500">Moneda</p>
                <p class="mt-1 font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($summary['currency'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="panel-elevated px-4 py-3">
                <p class="uppercase tracking-wide text-[0.65rem] font-semibold text-slate-500">Ultima actualizacion</p>
                <p class="mt-1 font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($profile['updated_at'] ?? 'Hoy', ENT_QUOTES, 'UTF-8') ?></p>
            </div>
        </div>
    </header>

    <section class="grid gap-6 lg:grid-cols-3">
        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 space-y-3 hover-lift transition">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500 font-semibold">Ingreso total mensual</p>
                <span class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-brand-700 dark:text-info"><?= number_format((float) $summary['income_total'], 2) ?> <?= htmlspecialchars($summary['currency'], ENT_QUOTES, 'UTF-8') ?></p>
            <p class="text-xs text-slate-400">Incluye ingresos fijos y adicionales registrados.</p>
        </article>

        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 space-y-3 hover-lift transition">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500 font-semibold">Limite de gastos</p>
                <span class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 4.5h15v15h-15zM9 9v6m6-6v6"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-brand-700 dark:text-info"><?= number_format((float) $summary['limit'], 2) ?> <?= htmlspecialchars($summary['currency'], ENT_QUOTES, 'UTF-8') ?></p>
            <p class="text-xs text-slate-400">Recibiras alertas al acercarte a este monto objetivo.</p>
        </article>

        <article class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-6 space-y-3 hover-lift transition">
            <div class="flex items-center justify-between">
                <p class="text-sm text-slate-500 font-semibold">Medios de gasto</p>
                <span class="icon-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4.5h18m-1.5 0-.75 13.5H6.75L6 4.5m5.25 3h1.5m-5.25 3h9"/>
                    </svg>
                </span>
            </div>
            <p class="text-base font-semibold text-brand-700 dark:text-info"><?= htmlspecialchars(implode(', ', array_map('ucfirst', $summary['media'])), ENT_QUOTES, 'UTF-8') ?></p>
            <p class="text-xs text-slate-400">Personaliza tus medios para comparar rendimientos.</p>
        </article>
    </section>

    <section class="surface-card rounded-3xl border border-slate-200/70 dark:border-slate-800/60 p-8 space-y-8 shadow-soft">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-brand-700 dark:text-info">Actualizar informacion</h2>
                <p class="text-slate-500 text-sm mt-1">Modifica tus datos personales, ingresos y objetivos desde aqui.</p>
            </div>
        </div>

        <form action="/App-Control-Gastos/public/perfil" method="POST" class="space-y-8">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <section id="preferencias" class="space-y-4">
                <h3 class="text-lg font-semibold text-brand-600">Datos personales</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-semibold text-slate-600">Nombre completo</label>
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2.25c-5.25 0-7.5 3-7.5 4.5v1.5a.75.75 0 0 0 .75.75h13.5a.75.75 0 0 0 .75-.75v-1.5c0-1.5-2.25-4.5-7.5-4.5Z"/>
                            </svg>
                            <input id="name" name="name" type="text" required
                                   value="<?= htmlspecialchars($value('name'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3 transition">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="phone" class="text-sm font-semibold text-slate-600">Telefono</label>
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75 9 13.5m0 0 2.25-2.25M9 13.5l-1.5 4.5m4.5-12L21 13.5m0 0-1.5 4.5m1.5-4.5L12 4.5"/>
                            </svg>
                            <input id="phone" name="phone" type="tel" required
                                   value="<?= htmlspecialchars($value('phone'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3 transition">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="occupation" class="text-sm font-semibold text-slate-600">Ocupacion</label>
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 8.25A2.25 2.25 0 0 1 6.75 6h10.5a2.25 2.25 0 0 1 2.25 2.25v12a.75.75 0 0 1-1.22.58L12 15.75l-6.28 5.08a.75.75 0 0 1-1.22-.58Z"/>
                            </svg>
                            <input id="occupation" name="occupation" type="text" required
                                   value="<?= htmlspecialchars($value('occupation'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3 transition">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-semibold text-slate-600">Correo electronico</label>
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6.75v10.5A2.25 2.25 0 0 0 5.25 19.5h13.5A2.25 2.25 0 0 0 21 17.25V6.75m-18 0 9 6 9-6m-18 0A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75"/>
                            </svg>
                            <input id="email" name="email" type="email" required
                                   value="<?= htmlspecialchars($value('email'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3 transition">
                        </div>
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <h3 class="text-lg font-semibold text-brand-600">Ingresos y configuracion global</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="monthly_income" class="text-sm font-semibold text-slate-600">Ingreso mensual</label>
                        <input id="monthly_income" name="monthly_income" type="number" step="0.01" min="0" required
                               value="<?= htmlspecialchars($value('monthly_income'), ENT_QUOTES, 'UTF-8') ?>"
                               data-income-input
                               class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                    </div>
                    <div class="space-y-2">
                        <label for="extra_income" class="text-sm font-semibold text-slate-600">Ingreso adicional</label>
                        <input id="extra_income" name="extra_income" type="number" step="0.01" min="0"
                               value="<?= htmlspecialchars($value('extra_income'), ENT_QUOTES, 'UTF-8') ?>"
                               class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                    </div>
                    <div class="space-y-2">
                        <label for="start_date" class="text-sm font-semibold text-slate-600">Fecha de inicio</label>
                        <input id="start_date" name="start_date" type="date" required
                               value="<?= htmlspecialchars($value('start_date'), ENT_QUOTES, 'UTF-8') ?>"
                               class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                    </div>
                    <div class="space-y-2">
                        <label for="currency" class="text-sm font-semibold text-slate-600">Moneda</label>
                        <select id="currency" name="currency" required
                                class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                            <?php foreach ($currencies as $currency): ?>
                                <option value="<?= $currency ?>" <?= $value('currency') === $currency ? 'selected' : '' ?>>
                                    <?= $currency ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-sm font-semibold text-slate-600">Medios de gasto</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <?php
                        $mediaIcons = [
                            'efectivo' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 7.5h15v9h-15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 11.25h15"/>',
                            'tarjeta' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7.5h18v9H3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 11.25h18"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.5 15h3"/>',
                            'transferencia' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 9H9m0 0L6.75 6.75M9 9 6.75 11.25M19.5 15H15m0 0 2.25-2.25M15 15l2.25 2.25"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 18.75h15"/>',
                            'otros' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v12m6-6H6"/>',
                        ];
                        ?>
                        <?php foreach ($mediaOptions as $media): ?>
                            <?php
                            $label = ucfirst($media === 'otros' ? 'Otros medios' : $media);
                            $iconPath = $mediaIcons[$media] ?? $mediaIcons['otros'];
                            ?>
                            <label class="flex items-center justify-between gap-4 px-4 py-3 rounded-2xl border border-slate-200/70 bg-white/90/80 dark:bg-slate-900/60 hover:border-brand-200 transition">
                                <div class="flex items-center gap-3">
                                    <span class="icon-circle">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <?= $iconPath ?>
                                        </svg>
                                    </span>
                                    <span class="text-slate-600 font-semibold"><?= $label ?></span>
                                </div>
                                <input type="checkbox" name="spending_media[]" value="<?= $media ?>"
                                       <?= in_array($media, $selectedMedia, true) ? 'checked' : '' ?>
                                       class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <h3 class="text-lg font-semibold text-brand-600">Objetivos y limites</h3>

                <div class="space-y-4">
                    <select name="goal_type" data-goal-select required
                            class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                        <option value="save" <?= $value('goal_type') === 'save' ? 'selected' : '' ?>>Ahorrar</option>
                        <option value="debt" <?= $value('goal_type') === 'debt' ? 'selected' : '' ?>>Pagar deudas</option>
                        <option value="control" <?= $value('goal_type') === 'control' ? 'selected' : '' ?>>Controlar gastos</option>
                        <option value="other" <?= $value('goal_type') === 'other' ? 'selected' : '' ?>>Otro</option>
                    </select>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div data-goal-container="save" class="<?= $value('goal_type') === 'save' ? '' : 'hidden' ?> space-y-2">
                            <label for="goal_meta_amount" class="text-sm font-semibold text-slate-600">Meta de ahorro</label>
                            <input id="goal_meta_amount" name="goal_meta_amount" type="number" step="0.01" min="0"
                                   value="<?= htmlspecialchars($value('goal_meta_amount'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                            <label for="goal_meta_months" class="text-sm font-semibold text-slate-600 mt-4">Tiempo estimado (meses)</label>
                            <input id="goal_meta_months" name="goal_meta_months" type="number" min="1"
                                   value="<?= htmlspecialchars($value('goal_meta_months'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                        </div>

                        <div data-goal-container="debt" class="<?= $value('goal_type') === 'debt' ? '' : 'hidden' ?> space-y-2">
                            <label for="debt_total_amount" class="text-sm font-semibold text-slate-600">Monto total de la deuda</label>
                            <input id="debt_total_amount" name="debt_total_amount" type="number" step="0.01" min="0"
                                   value="<?= htmlspecialchars($value('debt_total_amount'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                        </div>

                        <div data-goal-container="other" class="<?= $value('goal_type') === 'other' ? '' : 'hidden' ?> space-y-2 md:col-span-2">
                            <label for="goal_description" class="text-sm font-semibold text-slate-600">Describe tu objetivo</label>
                            <textarea id="goal_description" name="goal_description" rows="3"
                                      class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3"><?= htmlspecialchars($value('goal_description'), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <label class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-slate-200/70 bg-white/90/80 dark:bg-slate-900/60 hover:border-brand-200 transition">
                        <input type="radio" name="spending_limit_mode" value="manual" <?= $value('spending_limit_mode', 'manual') === 'manual' ? 'checked' : '' ?> class="text-brand-600 focus:ring-brand-500">
                        <span class="text-slate-600 font-semibold">Definir manualmente</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-slate-200/70 bg-white/90/80 dark:bg-slate-900/60 hover:border-brand-200 transition">
                        <input type="radio" name="spending_limit_mode" value="auto" <?= $value('spending_limit_mode', 'manual') === 'auto' ? 'checked' : '' ?> class="text-brand-600 focus:ring-brand-500">
                        <span class="text-slate-600 font-semibold">Calcular automaticamente</span>
                    </label>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="spending_limit_amount" class="text-sm font-semibold text-slate-600">Limite mensual</label>
                        <input id="spending_limit_amount" name="spending_limit_amount" type="number" step="0.01" min="0"
                               value="<?= htmlspecialchars($value('spending_limit_amount'), ENT_QUOTES, 'UTF-8') ?>"
                               data-limit-input
                               class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                    </div>
                    <div class="space-y-2">
                        <label for="auto_limit_ratio" class="text-sm font-semibold text-slate-600">Porcentaje del ingreso</label>
                        <div class="flex items-center gap-3">
                            <input id="auto_limit_ratio" name="auto_limit_ratio" type="number" step="0.05" min="0.3" max="0.9"
                                   value="<?= htmlspecialchars($value('auto_limit_ratio', 0.7), ENT_QUOTES, 'UTF-8') ?>"
                                   data-limit-ratio
                                   class="w-full rounded-2xl border border-slate-200 bg-white/90/90 focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                            <button type="button" data-auto-limit
                                    class="px-4 py-2 rounded-2xl bg-white/90 text-brand-600 border border-brand-200 font-semibold shadow-sm hover:bg-brand-50 transition">
                                Recalcular
                            </button>
                        </div>
                        <p class="text-xs text-slate-400">Ajusta entre 30% y 90% segun tu estilo de vida.</p>
                    </div>
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-brand-600 text-white font-semibold shadow-floating hover:bg-brand-700 transition transition-press">
                    Guardar cambios
                </button>
            </div>
        </form>
    </section>

    <?php if ($debtPlan): ?>
        <section class="surface-card rounded-3xl p-8 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-brand-700">Plan sugerido para saldar tu deuda</h2>
                    <p class="text-slate-500 text-sm mt-1">Generado automaticamente con base en tus ingresos y tu objetivo.</p>
                </div>
                <span class="px-4 py-1 rounded-full bg-brand-100 text-brand-700 text-sm font-semibold">
                    Pago mensual: <?= number_format($debtPlan['monthly_payment'], 2) ?> <?= htmlspecialchars($summary['currency'], ENT_QUOTES, 'UTF-8') ?>
                </span>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                <div class="rounded-2xl border border-slate-200/70 bg-white/90 p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Monto total de la deuda</p>
                    <p class="text-2xl font-semibold text-brand-600 mt-2"><?= number_format($profile['debt_total_amount'], 2) ?> <?= htmlspecialchars($summary['currency'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <div class="rounded-2xl border border-slate-200/70 bg-white/90 p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Meses sugeridos</p>
                    <p class="text-2xl font-semibold text-brand-600 mt-2"><?= (int) $debtPlan['recommended_months'] ?> meses</p>
                </div>
                <div class="rounded-2xl border border-slate-200/70 bg-white/90 p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Presupuesto disponible estimado</p>
                    <p class="text-2xl font-semibold text-brand-600 mt-2"><?= number_format($debtPlan['available_budget'], 2) ?> <?= htmlspecialchars($summary['currency'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            </div>

            <div class="bg-white/90 rounded-2xl border border-slate-200/70 p-6 space-y-3">
                <p class="text-sm font-semibold text-brand-600">Recomendaciones:</p>
                <ul class="space-y-2 text-sm text-slate-600 list-disc list-inside">
                    <?php foreach ($debtPlan['tips'] as $tip): ?>
                        <li><?= htmlspecialchars($tip, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    <?php endif; ?>
</section>
