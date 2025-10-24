<?php
/** @var array $profile */
/** @var array $summary */
/** @var array $filters */
/** @var array $transactions */
/** @var array $expenseCategories */
/** @var array $incomeCategories */
/** @var array $paymentMethods */
/** @var array $old */
/** @var string $csrfToken */

$old ??= [];

// Helper centralizado en views/partials/icons.php

$lucideIcon = fn(string $name, string $classes = 'h-4 w-4'): string => __lucide_icon_helper($name, $classes);

$currency = htmlspecialchars($profile['currency'], ENT_QUOTES, 'UTF-8');
$limitUsage = $summary['limit'] > 0 ? min(100, $summary['limit_usage']) : 0;
$overLimit = $summary['over_limit'] ?? false;
$filtersMonth = $filters['month'] ?? date('Y-m');

$oldValue = function (string $field, mixed $default = '') use ($old) {
    return $old[$field] ?? $default;
};

?>

<section class="space-y-10" data-limit-alert="<?= $overLimit ? '1' : '0' ?>">
    <header class="space-y-2">
        <h1 class="text-3xl font-semibold text-brand-700">Tus movimientos financieros</h1>
        <p class="text-slate-500">Registra gastos e ingresos para mantener un control claro de tus recursos.</p>
    </header>

    <section class="grid gap-6 lg:grid-cols-4">
        <article class="surface-card rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Ingresos base (perfil)</p>
            <p class="text-3xl font-bold text-brand-600"><?= number_format($summary['base_income'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Ingreso mensual y adicional registrados en tu perfil.</p>
        </article>
        <article class="surface-card rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Ingresos adicionales</p>
            <p class="text-3xl font-bold text-brand-600"><?= number_format($summary['additional_income'], 2) ?> <?= $currency ?></p>
            <p class="text-xs text-slate-400">Ingresos registrados durante el periodo seleccionado.</p>
        </article>
        <article class="surface-card rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Gastos del periodo</p>
            <p class="text-3xl font-bold <?= $overLimit ? 'text-danger' : 'text-brand-600' ?>">
                <?= number_format($summary['total_expenses'], 2) ?> <?= $currency ?>
            </p>
            <p class="text-xs text-slate-400">Total de egresos registrados este mes.</p>
        </article>
        <article class="surface-card rounded-3xl p-6 space-y-2">
            <p class="text-sm text-slate-500 font-semibold">Saldo disponible</p>
            <p class="text-3xl font-bold <?= $summary['available'] < 0 ? 'text-danger' : 'text-brand-600' ?>">
                <?= number_format($summary['available'], 2) ?> <?= $currency ?>
            </p>
            <p class="text-xs text-slate-400">Resultado de tus ingresos menos gastos.</p>
        </article>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <p class="text-sm font-semibold text-slate-600">Uso del límite mensual de gastos</p>
            <span class="text-sm font-semibold <?= $overLimit ? 'text-danger' : 'text-brand-600' ?>">
                <?= $limitUsage ?>%
            </span>
        </div>
        <div class="h-3 rounded-full bg-slate-100 overflow-hidden" role="progressbar" aria-valuenow="<?= $limitUsage ?>" aria-valuemin="0" aria-valuemax="100" aria-label="Uso del límite mensual">
            <div class="h-full rounded-full <?= $overLimit ? 'bg-danger' : 'bg-brand-600' ?>" style="width: <?= $limitUsage ?>%;"></div>
        </div>
        <div class="flex items-center justify-between text-xs text-slate-500">
            <span>Límite definido: <?= number_format($summary['limit'], 2) ?> <?= $currency ?></span>
            <span><?= $overLimit ? 'Atención: excediste tu límite.' : 'Aún estás dentro de tu límite.' ?></span>
        </div>
    </section>

    <section class="space-y-6">
        <article id="registro" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6 scroll-mt-24">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-brand-700">Registrar movimiento</h2>
                <span class="text-xs font-semibold text-brand-500 bg-brand-100 px-3 py-1 rounded-full">Nuevo</span>
            </div>

            <form action="/App-Control-Gastos/public/transacciones" method="POST" class="space-y-5">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

                <?php
                $selectedType = $oldValue('type', 'expense');
                $expenseActive = $selectedType === 'expense';
                $incomeActive = $selectedType === 'income';
                $expenseClasses = $expenseActive
                    ? 'border-brand-300 bg-brand-50/80 text-brand-700 shadow-sm ring-1 ring-brand-200'
                    : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-brand-200 hover:text-brand-600';
                $incomeClasses = $incomeActive
                    ? 'border-accent-400/60 bg-accent-100 text-brand-700 shadow-sm ring-1 ring-accent-400/50'
                    : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-brand-200 hover:text-brand-600';
                ?>

                <div class="space-y-2">
                    <div class="grid gap-3 sm:grid-cols-2 text-sm font-semibold" role="radiogroup" aria-label="Tipo de movimiento">
                        <label class="flex items-start gap-3 px-4 py-3 rounded-2xl border transition focus-within:ring-2 focus-within:ring-brand-200 <?= $expenseClasses ?>">
                            <input id="type_expense" class="sr-only" type="radio" name="type" value="expense" <?= $expenseActive ? 'checked' : '' ?>>
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-brand-100 text-brand-600">
                                <?= $lucideIcon('minus', 'h-5 w-5') ?>
                            </span>
                            <span class="flex flex-col gap-1">
                                <span>Gasto</span>
                                <span class="text-xs font-normal text-slate-400">Pagos, compras o egresos del periodo.</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-3 px-4 py-3 rounded-2xl border transition focus-within:ring-2 focus-within:ring-brand-200 <?= $incomeClasses ?>">
                            <input id="type_income" class="sr-only" type="radio" name="type" value="income" <?= $incomeActive ? 'checked' : '' ?>>
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-accent-100 text-brand-600">
                                <?= $lucideIcon('plus', 'h-5 w-5') ?>
                            </span>
                            <span class="flex flex-col gap-1">
                                <span>Ingreso</span>
                                <span class="text-xs font-normal text-slate-400">Dinero que suma a tu balance disponible.</span>
                            </span>
                        </label>
                    </div>
                    <p class="text-xs text-slate-400">Selecciona qué tipo de movimiento deseas registrar.</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-600">Categoría</label>
                    <select name="category" data-category-select class="w-full rounded-2xl border-slate-200 bg-white px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20">
                        <option value="">Selecciona una categoría</option>
                        <optgroup label="Gastos">
                            <?php foreach ($expenseCategories as $category): ?>
                                <?php $name = htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                                <option value="<?= $name ?>" data-category-type="expense" <?= $oldValue('category') === $category['name'] ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                        <optgroup label="Ingresos">
                            <?php foreach ($incomeCategories as $category): ?>
                                <?php $name = htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>
                                <option value="<?= $name ?>" data-category-type="income" <?= $oldValue('category') === $category['name'] ? 'selected' : '' ?>><?= $name ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    </select>
                    <p class="text-xs text-slate-400">¿No encuentras tu categoría? Ingresa una nueva abajo.</p>
                </div>

                <div class="space-y-2">
                    <label for="category_new" class="text-sm font-semibold text-slate-600">Nueva categoría (opcional)</label>
                    <input id="category_new" name="category_new" type="text"
                           value="<?= htmlspecialchars($oldValue('category_new'), ENT_QUOTES, 'UTF-8') ?>"
                           class="w-full rounded-2xl border-slate-200 bg-white px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20">
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="amount" class="text-sm font-semibold text-slate-600">Monto</label>
                        <input id="amount" name="amount" type="number" step="0.01" min="0"
                               value="<?= htmlspecialchars($oldValue('amount'), ENT_QUOTES, 'UTF-8') ?>"
                               required
                               class="w-full rounded-2xl border-slate-200 bg-white px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20">
                    </div>
                    <div class="space-y-2">
                        <label for="payment_method" class="text-sm font-semibold text-slate-600">Método de pago</label>
                        <select id="payment_method" name="payment_method"
                                class="w-full rounded-2xl border-slate-200 bg-white px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20">
                            <option value="">Selecciona una opción</option>
                            <?php foreach ($paymentMethods as $method): ?>
                                <option value="<?= $method ?>" <?= $oldValue('payment_method') === $method ? 'selected' : '' ?>>
                                    <?= ucfirst($method) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label for="happened_on" class="text-sm font-semibold text-slate-600">Fecha</label>
                        <input id="happened_on" name="happened_on" type="date"
                               value="<?= htmlspecialchars($oldValue('happened_on', date('Y-m-d')), ENT_QUOTES, 'UTF-8') ?>"
                               required
                               class="w-full rounded-2xl border-slate-200 bg-white px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20">
                    </div>
                    <div class="space-y-2 md:col-span-1">
                        <label for="description" class="text-sm font-semibold text-slate-600">Descripción</label>
                        <input id="description" name="description" type="text"
                               value="<?= htmlspecialchars($oldValue('description'), ENT_QUOTES, 'UTF-8') ?>"
                               maxlength="255"
                               class="w-full rounded-2xl border-slate-200 bg-white px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 rounded-2xl bg-brand-600 text-white font-semibold shadow-lg shadow-floating hover:bg-brand-700 transition">
                        Registrar movimiento
                    </button>
                </div>
            </form>
        </article>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
        <form method="GET" class="grid md:grid-cols-5 gap-4 items-end">
            <div class="space-y-1">
                <label for="month" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Mes</label>
                <input id="month" name="month" type="month"
                       value="<?= htmlspecialchars($filtersMonth, ENT_QUOTES, 'UTF-8') ?>"
                       class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 focus:border-brand-300 focus:ring focus:ring-info/20">
            </div>
            <div class="space-y-1">
                <label for="filter_type" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Tipo</label>
                <select id="filter_type" name="type"
                        class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 focus:border-brand-300 focus:ring focus:ring-info/20">
                    <option value="">Todos</option>
                    <option value="income" <?= ($filters['type'] ?? '') === 'income' ? 'selected' : '' ?>>Ingresos</option>
                    <option value="expense" <?= ($filters['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Gastos</option>
                </select>
            </div>
            <div class="space-y-1">
                <label for="filter_category" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Categoría</label>
                <input id="filter_category" name="category" type="text"
                       value="<?= htmlspecialchars($filters['category'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       placeholder="Ej. Transporte"
                       class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 focus:border-brand-300 focus:ring focus:ring-info/20">
            </div>
            <div class="space-y-1">
                <label for="filter_method" class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Método</label>
                <select id="filter_method" name="payment_method"
                        class="w-full rounded-xl border-slate-200 bg-white px-3 py-2 focus:border-brand-300 focus:ring focus:ring-info/20">
                    <option value="">Cualquiera</option>
                    <?php foreach ($paymentMethods as $method): ?>
                        <option value="<?= $method ?>" <?= ($filters['payment_method'] ?? '') === $method ? 'selected' : '' ?>><?= ucfirst($method) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 px-4 py-2 rounded-xl bg-brand-600 text-white text-sm font-semibold shadow hover:bg-brand-700 transition">
                    Aplicar filtros
                </button>
                <a href="/App-Control-Gastos/public/transacciones" class="px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-500 hover:border-brand-200 hover:text-brand-600 transition">
                    Borrar filtros
                </a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3 text-left">Fecha</th>
                        <th class="px-4 py-3 text-left">Categoría</th>
                        <th class="px-4 py-3 text-left">Descripción</th>
                        <th class="px-4 py-3 text-center">Método</th>
                        <th class="px-4 py-3 text-right">Monto</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-10">
                                <div class="flex flex-col items-center justify-center gap-4 text-center">
                                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-brand-500">
                                        <?= $lucideIcon('inbox', 'h-6 w-6') ?>
                                    </span>
                                    <div class="space-y-1">
                                        <p class="text-sm font-semibold text-slate-600">No encontramos movimientos con los filtros aplicados.</p>
                                        <p class="text-xs text-slate-400">Registra un nuevo ingreso o gasto, o intenta limpiar los filtros.</p>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <a class="inline-flex items-center justify-center gap-2 rounded-full bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-brand-700 transition" href="#registro">
                                            <?= $lucideIcon('plus-circle', 'h-4 w-4') ?>
                                            Registrar movimiento
                                        </a>
                                        <a class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-500 hover:border-brand-200 hover:text-brand-600 transition" href="/App-Control-Gastos/public/transacciones">
                                            Quitar filtros
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr class="hover:bg-brand-50/40 transition">
                                <td class="px-4 py-3 text-slate-600 font-semibold"><?= date('d/m/Y', strtotime($transaction['happened_on'])) ?></td>
                                <td class="px-4 py-3 text-slate-600"><?= htmlspecialchars($transaction['category'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-slate-400"><?= htmlspecialchars($transaction['description'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-4 py-3 text-center text-slate-500"><?= ucfirst($transaction['payment_method']) ?></td>
                                <td class="px-4 py-3 text-right font-semibold <?= $transaction['type'] === 'income' ? 'text-brand-600' : 'text-danger' ?>">
                                    <?= $transaction['type'] === 'income' ? '+' : '-' ?><?= number_format($transaction['amount'], 2) ?> <?= $currency ?>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form action="/App-Control-Gastos/public/transacciones/eliminar" method="POST" onsubmit="return confirm('¿Deseas eliminar este registro?');">
                                        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="transaction_id" value="<?= (int) $transaction['id'] ?>">
                                        <button class="inline-flex items-center gap-1 text-xs font-semibold text-danger hover:underline" aria-label="Eliminar registro">
                                            <?= $lucideIcon('trash-2', 'h-3.5 w-3.5') ?>
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</section>

