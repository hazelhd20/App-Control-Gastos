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
?>

<section class="space-y-10">
    <header class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-semibold text-primary-700">Hola, <?= htmlspecialchars($user['name'] ?? 'Usuario', ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="text-slate-500 mt-2">Administra tus datos y manten tu plan financiero siempre actualizado.</p>
        </div>
        <div class="flex gap-3">
            <a href="/App-Control-Gastos/public/transacciones" class="px-5 py-3 rounded-2xl bg-white text-primary-600 border border-primary-200 font-semibold shadow-sm hover:bg-primary-50 transition">
                Registrar movimiento
            </a>
            <a href="/App-Control-Gastos/public/reportes" class="px-5 py-3 rounded-2xl bg-primary-500 text-white font-semibold shadow-lg shadow-primary-500/30 hover:bg-primary-600 transition">
                Ver reportes
            </a>
        </div>
    </header>

    <section class="grid gap-6 lg:grid-cols-3">
        <article class="card-glass rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Ingreso total mensual</p>
            <p class="text-3xl font-bold text-primary-600"><?= number_format((float) $summary['income_total'], 2) ?> <?= htmlspecialchars($summary['currency'], ENT_QUOTES, 'UTF-8') ?></p>
            <p class="text-xs text-slate-400">Incluye ingresos adicionales registrados.</p>
        </article>

        <article class="card-glass rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Limite de gastos</p>
            <p class="text-3xl font-bold text-primary-600"><?= number_format((float) $summary['limit'], 2) ?> <?= htmlspecialchars($summary['currency'], ENT_QUOTES, 'UTF-8') ?></p>
            <p class="text-xs text-slate-400">Recibiras alertas al acercarte a este monto.</p>
        </article>

        <article class="card-glass rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Medios de gasto</p>
            <p class="text-base font-semibold text-primary-600"><?= htmlspecialchars(implode(', ', array_map('ucfirst', $summary['media'])), ENT_QUOTES, 'UTF-8') ?></p>
            <p class="text-xs text-slate-400">Puedes ajustar tus medios cuando lo necesites.</p>
        </article>
    </section>

    <section class="card-glass rounded-3xl p-8 space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-primary-700">Actualizar informacion</h2>
                <p class="text-slate-500 text-sm mt-1">Modifica tus datos personales, ingresos y objetivos desde aqui.</p>
            </div>
        </div>

        <form action="/App-Control-Gastos/public/perfil" method="POST" class="space-y-8">
            <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <section class="space-y-4">
                <h3 class="text-lg font-semibold text-primary-600">Datos personales</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-semibold text-slate-600">Nombre completo</label>
                        <input id="name" name="name" type="text" required
                               value="<?= htmlspecialchars($value('name'), ENT_QUOTES, 'UTF-8') ?>"
                               class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
                    </div>
                    <div class="space-y-2">
                        <label for="phone" class="text-sm font-semibold text-slate-600">Telefono</label>
                        <input id="phone" name="phone" type="tel" required
                               value="<?= htmlspecialchars($value('phone'), ENT_QUOTES, 'UTF-8') ?>"
                               class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
                    </div>
                    <div class="space-y-2">
                        <label for="occupation" class="text-sm font-semibold text-slate-600">Ocupacion</label>
                        <input id="occupation" name="occupation" type="text" required
                               value="<?= htmlspecialchars($value('occupation'), ENT_QUOTES, 'UTF-8') ?>"
                               class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
                    </div>
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-semibold text-slate-600">Correo electronico</label>
                        <input id="email" name="email" type="email" required
                               value="<?= htmlspecialchars($value('email'), ENT_QUOTES, 'UTF-8') ?>"
                               class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <h3 class="text-lg font-semibold text-primary-600">Ingresos y configuracion global</h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="monthly_income" class="text-sm font-semibold text-slate-600">Ingreso mensual</label>
                        <input id="monthly_income" name="monthly_income" type="number" step="0.01" min="0" required
                               value="<?= htmlspecialchars($value('monthly_income'), ENT_QUOTES, 'UTF-8') ?>"
                               data-income-input
                               class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
                    </div>
                    <div class="space-y-2">
                        <label for="extra_income" class="text-sm font-semibold text-slate-600">Ingreso adicional</label>
                        <input id="extra_income" name="extra_income" type="number" step="0.01" min="0"
                               value="<?= htmlspecialchars($value('extra_income'), ENT_QUOTES, 'UTF-8') ?>"
                               class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
                    </div>
                    <div class="space-y-2">
                        <label for="start_date" class="text-sm font-semibold text-slate-600">Fecha de inicio</label>
                        <input id="start_date" name="start_date" type="date" required
                               value="<?= htmlspecialchars($value('start_date'), ENT_QUOTES, 'UTF-8') ?>"
                               class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
                    </div>
                    <div class="space-y-2">
                        <label for="currency" class="text-sm font-semibold text-slate-600">Moneda</label>
                        <select id="currency" name="currency" required
                                class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
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
                        <?php foreach ($mediaOptions as $media): ?>
                            <?php
                            $label = ucfirst($media);
                            if ($media === 'otros') {
                                $label = 'Otros medios';
                            }
                            ?>
                            <label class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-slate-200 bg-white hover:border-primary-200 transition">
                                <input type="checkbox" name="spending_media[]" value="<?= $media ?>"
                                       <?= in_array($media, $selectedMedia, true) ? 'checked' : '' ?>
                                       class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-slate-600 font-semibold"><?= $label ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <h3 class="text-lg font-semibold text-primary-600">Objetivos y limites</h3>

                <div class="space-y-4">
                    <select name="goal_type" data-goal-select required
                            class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
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
                                   class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
                            <label for="goal_meta_months" class="text-sm font-semibold text-slate-600 mt-4">Tiempo estimado (meses)</label>
                            <input id="goal_meta_months" name="goal_meta_months" type="number" min="1"
                                   value="<?= htmlspecialchars($value('goal_meta_months'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
                        </div>

                        <div data-goal-container="debt" class="<?= $value('goal_type') === 'debt' ? '' : 'hidden' ?> space-y-2">
                            <label for="debt_total_amount" class="text-sm font-semibold text-slate-600">Monto total de la deuda</label>
                            <input id="debt_total_amount" name="debt_total_amount" type="number" step="0.01" min="0"
                                   value="<?= htmlspecialchars($value('debt_total_amount'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
                        </div>

                        <div data-goal-container="other" class="<?= $value('goal_type') === 'other' ? '' : 'hidden' ?> space-y-2 md:col-span-2">
                            <label for="goal_description" class="text-sm font-semibold text-slate-600">Describe tu objetivo</label>
                            <textarea id="goal_description" name="goal_description" rows="3"
                                      class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3"><?= htmlspecialchars($value('goal_description'), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <label class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-slate-200 bg-white hover:border-primary-200 transition">
                        <input type="radio" name="spending_limit_mode" value="manual" <?= $value('spending_limit_mode', 'manual') === 'manual' ? 'checked' : '' ?>>
                        <span class="text-slate-600 font-semibold">Definir manualmente</span>
                    </label>
                    <label class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-slate-200 bg-white hover:border-primary-200 transition">
                        <input type="radio" name="spending_limit_mode" value="auto" <?= $value('spending_limit_mode', 'manual') === 'auto' ? 'checked' : '' ?>>
                        <span class="text-slate-600 font-semibold">Calcular automaticamente</span>
                    </label>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="spending_limit_amount" class="text-sm font-semibold text-slate-600">Limite mensual</label>
                        <input id="spending_limit_amount" name="spending_limit_amount" type="number" step="0.01" min="0"
                               value="<?= htmlspecialchars($value('spending_limit_amount'), ENT_QUOTES, 'UTF-8') ?>"
                               data-limit-input
                               class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
                    </div>
                    <div class="space-y-2">
                        <label for="auto_limit_ratio" class="text-sm font-semibold text-slate-600">Porcentaje del ingreso</label>
                        <div class="flex items-center gap-3">
                            <input id="auto_limit_ratio" name="auto_limit_ratio" type="number" step="0.05" min="0.3" max="0.9"
                                   value="<?= htmlspecialchars($value('auto_limit_ratio', 0.7), ENT_QUOTES, 'UTF-8') ?>"
                                   data-limit-ratio
                                   class="w-full rounded-2xl border-slate-200 bg-white focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
                            <button type="button" data-auto-limit
                                    class="px-4 py-2 rounded-2xl bg-white text-primary-600 border border-primary-200 font-semibold shadow-sm hover:bg-primary-50 transition">
                                Recalcular
                            </button>
                        </div>
                        <p class="text-xs text-slate-400">Ajusta entre 30% y 90% segun tu estilo de vida.</p>
                    </div>
                </div>
            </section>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 rounded-2xl bg-primary-500 text-white font-semibold shadow-lg shadow-primary-500/30 hover:bg-primary-600 transition">
                    Guardar cambios
                </button>
            </div>
        </form>
    </section>

    <?php if ($debtPlan): ?>
        <section class="card-glass rounded-3xl p-8 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-primary-700">Plan sugerido para saldar tu deuda</h2>
                    <p class="text-slate-500 text-sm mt-1">Generado automaticamente con base en tus ingresos y tu objetivo.</p>
                </div>
                <span class="px-4 py-1 rounded-full bg-primary-100 text-primary-700 text-sm font-semibold">
                    Pago mensual: <?= number_format($debtPlan['monthly_payment'], 2) ?> <?= htmlspecialchars($summary['currency'], ENT_QUOTES, 'UTF-8') ?>
                </span>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                <div class="rounded-2xl border border-primary-100 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Monto total de la deuda</p>
                    <p class="text-2xl font-semibold text-primary-600 mt-2"><?= number_format($profile['debt_total_amount'], 2) ?> <?= htmlspecialchars($summary['currency'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <div class="rounded-2xl border border-primary-100 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Meses sugeridos</p>
                    <p class="text-2xl font-semibold text-primary-600 mt-2"><?= (int) $debtPlan['recommended_months'] ?> meses</p>
                </div>
                <div class="rounded-2xl border border-primary-100 bg-white p-5 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-400 font-semibold">Presupuesto disponible estimado</p>
                    <p class="text-2xl font-semibold text-primary-600 mt-2"><?= number_format($debtPlan['available_budget'], 2) ?> <?= htmlspecialchars($summary['currency'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-primary-100 p-6 space-y-3">
                <p class="text-sm font-semibold text-primary-600">Recomendaciones:</p>
                <ul class="space-y-2 text-sm text-slate-600 list-disc list-inside">
                    <?php foreach ($debtPlan['tips'] as $tip): ?>
                        <li><?= htmlspecialchars($tip, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    <?php endif; ?>
</section>
