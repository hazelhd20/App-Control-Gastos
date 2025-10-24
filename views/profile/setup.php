<?php
/** @var array $currencies */
/** @var array $mediaOptions */
/** @var array $old */
/** @var string $csrfToken */

$old ??= [];

$oldValue = function (string $field, mixed $default = '') use ($old) {
    return $old[$field] ?? $default;
};

$selectedMedia = $old['spending_media'] ?? [];
?>
<section class="space-y-8">
    <header class="space-y-2">
        <h1 class="text-3xl font-semibold text-brand-700">Configura tu perfil financiero</h1>
        <p class="text-slate-500">Completa este paso para personalizar recomendaciones, limites y alertas.</p>
    </header>

    <form action="/App-Control-Gastos/public/perfil/configuracion-inicial" method="POST" class="grid gap-10">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <section class="surface-card rounded-3xl p-8 space-y-6">
            <h2 class="text-xl font-semibold text-brand-700">Ingresos y moneda</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="monthly_income" class="text-sm font-semibold text-slate-600">Ingreso mensual</label>
                    <input id="monthly_income" name="monthly_income" type="number" step="0.01" min="0" required
                           value="<?= htmlspecialchars($oldValue('monthly_income'), ENT_QUOTES, 'UTF-8') ?>"
                           data-income-input
                           class="w-full rounded-2xl border-slate-200 bg-white focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                </div>

                <div class="space-y-2">
                    <label for="extra_income" class="text-sm font-semibold text-slate-600">Ingreso adicional mensual (opcional)</label>
                    <input id="extra_income" name="extra_income" type="number" step="0.01" min="0"
                           value="<?= htmlspecialchars($oldValue('extra_income'), ENT_QUOTES, 'UTF-8') ?>"
                           class="w-full rounded-2xl border-slate-200 bg-white focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                </div>

                <div class="space-y-2">
                    <label for="start_date" class="text-sm font-semibold text-slate-600">Fecha de inicio del control financiero</label>
                    <input id="start_date" name="start_date" type="date" required
                           value="<?= htmlspecialchars($oldValue('start_date', date('Y-m-d')), ENT_QUOTES, 'UTF-8') ?>"
                           class="w-full rounded-2xl border-slate-200 bg-white focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                </div>

                <div class="space-y-2">
                    <label for="currency" class="text-sm font-semibold text-slate-600">Moneda</label>
                    <select id="currency" name="currency" required
                            class="w-full rounded-2xl border-slate-200 bg-white focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                        <?php foreach ($currencies as $currency): ?>
                            <option value="<?= $currency ?>" <?= $oldValue('currency', 'MXN') === $currency ? 'selected' : '' ?>>
                                <?= $currency ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </section>

        <section class="surface-card rounded-3xl p-8 space-y-6">
            <h2 class="text-xl font-semibold text-brand-700">Medios de gasto</h2>
            <p class="text-slate-500 text-sm">Selecciona los metodos que utilizas con frecuencia. Esto nos ayudara a segmentar tus reportes.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <?php foreach ($mediaOptions as $media): ?>
                    <?php
                    $label = ucfirst($media);
                    if ($media === 'otros') {
                        $label = 'Otros medios';
                    }
                    ?>
                    <label class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-slate-200 bg-white hover:border-brand-200 transition">
                        <input type="checkbox" name="spending_media[]" value="<?= $media ?>"
                               <?= in_array($media, $selectedMedia, true) ? 'checked' : '' ?>
                               class="rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        <span class="text-slate-600 font-semibold"><?= $label ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="surface-card rounded-3xl p-8 space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-brand-700">Objetivo principal</h2>
                    <p class="text-slate-500 text-sm">Personaliza recomendaciones y recordatorios segun tu objetivo.</p>
                </div>
            </div>

            <div class="space-y-4">
                <select name="goal_type" data-goal-select required
                        class="w-full rounded-2xl border-slate-200 bg-white focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                    <option value="" disabled <?= $oldValue('goal_type') ? '' : 'selected' ?>>Selecciona un objetivo</option>
                    <option value="save" <?= $oldValue('goal_type') === 'save' ? 'selected' : '' ?>>Ahorrar</option>
                    <option value="debt" <?= $oldValue('goal_type') === 'debt' ? 'selected' : '' ?>>Pagar deudas</option>
                    <option value="control" <?= $oldValue('goal_type') === 'control' ? 'selected' : '' ?>>Controlar gastos</option>
                    <option value="other" <?= $oldValue('goal_type') === 'other' ? 'selected' : '' ?>>Otro</option>
                </select>

                <div class="space-y-4">
                    <div data-goal-container="save" class="<?= $oldValue('goal_type') === 'save' ? '' : 'hidden' ?> grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="goal_meta_amount" class="text-sm font-semibold text-slate-600">Meta de ahorro</label>
                            <input id="goal_meta_amount" name="goal_meta_amount" type="number" step="0.01" min="0"
                                   value="<?= htmlspecialchars($oldValue('goal_meta_amount'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full rounded-2xl border-slate-200 bg-white focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                        </div>
                        <div class="space-y-2">
                            <label for="goal_meta_months" class="text-sm font-semibold text-slate-600">Tiempo estimado (meses)</label>
                            <input id="goal_meta_months" name="goal_meta_months" type="number" min="1"
                                   value="<?= htmlspecialchars($oldValue('goal_meta_months'), ENT_QUOTES, 'UTF-8') ?>"
                                   class="w-full rounded-2xl border-slate-200 bg-white focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                        </div>
                    </div>

                    <div data-goal-container="debt" class="<?= $oldValue('goal_type') === 'debt' ? '' : 'hidden' ?> space-y-2">
                        <label for="debt_total_amount" class="text-sm font-semibold text-slate-600">Monto total de la deuda</label>
                        <input id="debt_total_amount" name="debt_total_amount" type="number" step="0.01" min="0"
                               value="<?= htmlspecialchars($oldValue('debt_total_amount'), ENT_QUOTES, 'UTF-8') ?>"
                               class="w-full rounded-2xl border-slate-200 bg-white focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                    </div>

                    <div data-goal-container="other" class="<?= $oldValue('goal_type') === 'other' ? '' : 'hidden' ?> space-y-2">
                        <label for="goal_description" class="text-sm font-semibold text-slate-600">Describe tu objetivo</label>
                        <textarea id="goal_description" name="goal_description" rows="3"
                                  class="w-full rounded-2xl border-slate-200 bg-white focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3"><?= htmlspecialchars($oldValue('goal_description'), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                </div>
            </div>
        </section>

        <section class="surface-card rounded-3xl p-8 space-y-6">
            <h2 class="text-xl font-semibold text-brand-700">Limite mensual de gastos</h2>
            <p class="text-slate-500 text-sm">Ajusta un tope para recibir alertas cuando estes cerca de excederte.</p>

            <div class="grid gap-6 md:grid-cols-2">
                <label class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-slate-200 bg-white hover:border-brand-200 transition">
                    <input type="radio" name="spending_limit_mode" value="manual" <?= $oldValue('spending_limit_mode', 'auto') === 'manual' ? 'checked' : '' ?>>
                    <span class="text-slate-600 font-semibold">Definirlo manualmente</span>
                </label>

                <label class="flex items-center gap-3 px-4 py-3 rounded-2xl border border-slate-200 bg-white hover:border-brand-200 transition">
                    <input type="radio" name="spending_limit_mode" value="auto" <?= $oldValue('spending_limit_mode', 'auto') === 'auto' ? 'checked' : '' ?>>
                    <span class="text-slate-600 font-semibold">Calcular automaticamente con base en mis ingresos</span>
                </label>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-2">
                    <label for="spending_limit_amount" class="text-sm font-semibold text-slate-600">Limite mensual</label>
                    <input id="spending_limit_amount" name="spending_limit_amount" type="number" step="0.01" min="0"
                           value="<?= htmlspecialchars($oldValue('spending_limit_amount'), ENT_QUOTES, 'UTF-8') ?>"
                           data-limit-input
                           class="w-full rounded-2xl border-slate-200 bg-white focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                </div>

                <div class="space-y-2">
                    <label for="auto_limit_ratio" class="text-sm font-semibold text-slate-600">Porcentaje sugerido del ingreso</label>
                    <div class="flex items-center gap-3">
                        <input id="auto_limit_ratio" name="auto_limit_ratio" type="number" step="0.05" min="0.3" max="0.9"
                               value="<?= htmlspecialchars($oldValue('auto_limit_ratio', 0.7), ENT_QUOTES, 'UTF-8') ?>"
                               data-limit-ratio
                               class="w-full rounded-2xl border-slate-200 bg-white focus:border-brand-300 focus:ring focus:ring-info/20 px-4 py-3">
                        <button type="button" data-auto-limit
                                class="px-4 py-2 rounded-2xl bg-white text-brand-600 border border-brand-200 font-semibold shadow-sm hover:bg-brand-50 transition">
                            Calcular
                        </button>
                    </div>
                    <p class="text-xs text-slate-400">Recomendado entre 60% y 80% de tu ingreso mensual disponible.</p>
                </div>
            </div>
        </section>

        <div class="flex justify-end">
            <button type="submit" class="px-8 py-3 rounded-2xl bg-brand-600 text-white font-semibold shadow-lg shadow-floating hover:bg-brand-700 transition">
                Guardar configuracion inicial
            </button>
        </div>
    </form>
</section>


