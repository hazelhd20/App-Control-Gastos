<?php /** @var string $csrfToken */ ?>
<?php if (!function_exists('__lucide_icon_helper')): ?>
    <?php
    function __lucide_icon_helper(string $name, string $classes = 'h-4 w-4'): string
    {
        return '<span class="' . htmlspecialchars($classes, ENT_QUOTES, 'UTF-8') . '" data-lucide="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" aria-hidden="true"></span>';
    }
    ?>
<?php endif; ?>
<?php $icon = fn(string $name, string $classes = 'h-4 w-4'): string => __lucide_icon_helper($name, $classes); ?>
<section class="space-y-10">
    <header class="space-y-3">
        <h1 class="text-3xl font-semibold text-brand-700">Accede a tu centro financiero</h1>
        <p class="text-slate-500 leading-relaxed">
            Visualiza tu balance, controla cada categoría y toma decisiones respaldadas por datos claros.
        </p>
        <ul class="flex flex-wrap items-center gap-3 text-xs text-slate-400">
            <li class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5">
                <span class="h-2 w-2 rounded-full bg-brand-500"></span>
                Seguridad cifrada
            </li>
            <li class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5">
                <span class="h-2 w-2 rounded-full bg-brand-500"></span>
                Datos en tiempo real
            </li>
        </ul>
    </header>

    <form action="/App-Control-Gastos/public/login" method="POST" class="space-y-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <div class="space-y-2">
            <label for="email" class="text-sm font-semibold text-slate-600">Correo electrónico</label>
            <div class="input-icon">
                <?= $icon('mail') ?>
                <input id="email" name="email" type="email" required autocomplete="email"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 text-slate-900 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="usuario@correo.com">
            </div>
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <label for="password" class="text-sm font-semibold text-slate-600">Contraseña</label>
                <button type="button" data-password-toggle="password"
                        class="text-xs text-brand-600 font-semibold hover:underline transition">
                    Mostrar
                </button>
            </div>
            <div class="input-icon">
                <?= $icon('lock') ?>
                <input id="password" name="password" type="password" required minlength="8"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 text-slate-900 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Escribe tu contraseña">
            </div>
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-slate-500">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500 transition">
                Recordarme en este dispositivo
            </label>
            <a href="/App-Control-Gastos/public/recuperar" class="text-brand-600 font-semibold hover:underline transition">
                ?Olvidaste tu contraseña?
            </a>
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-600 text-white font-semibold px-5 py-3 shadow-floating hover:bg-brand-700 transition transition-press">
            <?= $icon('log-in', 'h-5 w-5') ?>
            Iniciar sesión
        </button>
    </form>

    <footer class="text-sm text-center text-slate-500 space-y-2">
        <p>
            ?Aun no tienes una cuenta?
            <a href="/App-Control-Gastos/public/registro" class="text-brand-600 font-semibold hover:underline transition">
                Registrate gratis
            </a>
        </p>
        <p class="text-xs text-slate-400">
            Protegemos tu informacion con protocolos de seguridad bancarios y autenticacion avanzada.
        </p>
    </footer>
</section>
