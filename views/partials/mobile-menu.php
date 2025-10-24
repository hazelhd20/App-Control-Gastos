<?php
/** @var \App\Core\Auth|null $auth */
/** @var string|null $csrfToken */

$auth ??= null;
$csrfToken ??= '';
$isAuthenticated = $auth?->check() ?? false;
?>
<div class="md:hidden hidden" data-mobile-menu>
    <div class="px-6 py-4 bg-white border-b border-slate-200 space-y-4">
        <?php if ($isAuthenticated): ?>
            <a href="/App-Control-Gastos/public" class="block text-slate-600 font-semibold hover:text-primary-600">Panel</a>
            <a href="/App-Control-Gastos/public/perfil" class="block text-slate-600 font-semibold hover:text-primary-600">Perfil</a>
            <a href="/App-Control-Gastos/public/transacciones" class="block text-slate-600 font-semibold hover:text-primary-600">Movimientos</a>
            <a href="/App-Control-Gastos/public/reportes" class="block text-slate-600 font-semibold hover:text-primary-600">Reportes</a>
            <a href="/App-Control-Gastos/public/alertas" class="block text-slate-600 font-semibold hover:text-primary-600">Alertas</a>
            <form action="/App-Control-Gastos/public/logout" method="POST" class="pt-4 border-t border-slate-100">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <button class="w-full px-4 py-2 rounded-xl bg-white text-primary-600 border border-primary-200 font-semibold shadow-sm hover:bg-primary-50 transition">
                    Cerrar sesion
                </button>
            </form>
        <?php else: ?>
            <a href="/App-Control-Gastos/public/login" class="block text-slate-600 font-semibold hover:text-primary-600">Iniciar sesion</a>
            <a href="/App-Control-Gastos/public/registro" class="block text-slate-600 font-semibold hover:text-primary-600">Registrarse</a>
        <?php endif; ?>
    </div>
</div>
