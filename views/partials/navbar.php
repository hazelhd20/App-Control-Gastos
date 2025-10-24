<?php
/** @var \App\Core\Auth|null $auth */
/** @var \App\Core\Session|null $session */
/** @var string|null $csrfToken */

$auth ??= null;
$session ??= null;
$csrfToken ??= '';
$user = $auth?->user();
?>
<header class="bg-white/80 backdrop-blur shadow-sm border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 py-4 flex items-center justify-between gap-8">
        <a href="/App-Control-Gastos/public" class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-primary-500 text-white font-semibold shadow-floating">
                CG
            </span>
            <div>
                <p class="text-lg font-semibold text-primary-700">Control de Gastos</p>
                <p class="text-sm text-slate-500">Equilibrio financiero inteligente</p>
            </div>
        </a>

        <nav class="hidden md:flex items-center gap-6 text-sm text-slate-600">
            <?php if ($auth->check()): ?>
                <a href="/App-Control-Gastos/public" class="hover:text-primary-600 transition">Panel</a>
                <a href="/App-Control-Gastos/public/perfil" class="hover:text-primary-600 transition">Perfil</a>
                <a href="/App-Control-Gastos/public/transacciones" class="hover:text-primary-600 transition">Movimientos</a>
                <a href="/App-Control-Gastos/public/reportes" class="hover:text-primary-600 transition">Reportes</a>
                <a href="/App-Control-Gastos/public/alertas" class="hover:text-primary-600 transition">Alertas</a>
            <?php else: ?>
                <a href="/App-Control-Gastos/public/login" class="hover:text-primary-600 transition">Iniciar sesion</a>
                <a href="/App-Control-Gastos/public/registro" class="hover:text-primary-600 transition">Registrarse</a>
            <?php endif; ?>
        </nav>

        <div class="flex items-center gap-3">
            <?php if ($auth->check()): ?>
                <div class="hidden md:block text-right">
                    <p class="text-sm font-semibold text-primary-700"><?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="text-xs text-slate-500"><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <form action="/App-Control-Gastos/public/logout" method="POST" class="hidden md:block">
                    <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <button class="px-4 py-2 rounded-full bg-white text-primary-600 border border-primary-200 font-semibold shadow-sm hover:bg-primary-50 transition">
                        Cerrar sesion
                    </button>
                </form>

                <button class="md:hidden flex items-center justify-center w-10 h-10 rounded-full bg-primary-100 text-primary-600" data-mobile-nav>
                    <span class="sr-only">Menu</span>
                    &#9776;
                </button>
            <?php else: ?>
                <a href="/App-Control-Gastos/public/login" class="px-4 py-2 rounded-full bg-white text-primary-600 border border-primary-200 font-semibold shadow-sm hover:bg-primary-50 transition">
                    Acceder
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>
