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
        <p class="text-xs uppercase tracking-[0.35em] text-slate-400 font-semibold">Crea tu cuenta</p>
        <h1 class="text-3xl font-semibold text-brand-700">Configura tu espacio de control financiero</h1>
        <p class="text-slate-500 leading-relaxed">
            Personaliza tus datos básicos para recibir recomendaciones, alertas y estadísticas adaptadas a tu estilo de vida.
        </p>
        <div class="flex flex-wrap gap-3 text-xs text-slate-400">
            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5">
                <span class="h-2 w-2 rounded-full bg-brand-500"></span>
                Sincronización segura
            </span>
            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5">
                <span class="h-2 w-2 rounded-full bg-brand-500"></span>
                Panel listo en minutos
            </span>
        </div>
    </header>

    <form action="/App-Control-Gastos/public/registro" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <div class="space-y-2 md:col-span-2">
            <label for="name" class="text-sm font-semibold text-slate-600">Nombre completo</label>
            <div class="input-icon">
                <?= $icon('user-round') ?>
                <input id="name" name="name" type="text" required autocomplete="name"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Nombre y apellidos">
            </div>
        </div>

        <div class="space-y-2">
            <label for="phone" class="text-sm font-semibold text-slate-600">Teléfono</label>
            <div class="input-icon">
                <?= $icon('phone') ?>
                <input id="phone" name="phone" type="tel" required
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="+52 55 0000 0000">
            </div>
        </div>

        <div class="space-y-2">
            <label for="occupation" class="text-sm font-semibold text-slate-600">Ocupacion</label>
            <div class="input-icon">
                <?= $icon('briefcase-business') ?>
                <input id="occupation" name="occupation" type="text" required
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Ej. Analista financiero">
            </div>
        </div>

        <div class="space-y-2 md:col-span-2">
            <label for="email" class="text-sm font-semibold text-slate-600">Correo electrónico</label>
            <div class="input-icon">
                <?= $icon('mail') ?>
                <input id="email" name="email" type="email" required autocomplete="email"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="correo@ejemplo.com">
            </div>
        </div>

        <div class="space-y-2">
            <label for="register_password" class="text-sm font-semibold text-slate-600 flex items-center justify-between">
                <span>Contraseña</span>
                <button type="button" data-password-toggle="register_password" class="text-xs text-brand-600 font-semibold hover:underline">Mostrar</button>
            </label>
            <div class="input-icon">
                <?= $icon('lock') ?>
                <input id="register_password" name="password" type="password" required minlength="8"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Crea una contraseña segura">
            </div>
            <p class="text-xs text-slate-400">Debe contener al menos una mayúscula, un número y un carácter especial.</p>
        </div>

        <div class="space-y-2">
            <label for="register_password_confirmation" class="text-sm font-semibold text-slate-600 flex items-center justify-between">
                <span>Confirmar contraseña</span>
                <button type="button" data-password-toggle="register_password_confirmation" class="text-xs text-brand-600 font-semibold hover:underline">Mostrar</button>
            </label>
            <div class="input-icon">
                <?= $icon('lock') ?>
                <input id="register_password_confirmation" name="password_confirmation" type="password" required minlength="8"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Repite tu contraseña">
            </div>
        </div>

        <div class="md:col-span-2">
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-600 text-white font-semibold px-6 py-3 shadow-floating hover:bg-brand-700 transition transition-press">
                <?= $icon('user-plus', 'h-5 w-5') ?>
                Crear cuenta
            </button>
        </div>
    </form>

    <footer class="text-sm text-center text-slate-500 space-y-2">
        <p>
            ¿Ya tienes cuenta?
            <a href="/App-Control-Gastos/public/login" class="text-brand-600 font-semibold hover:underline transition">
                Inicia sesión
            </a>
        </p>
        <p class="text-xs text-slate-400">
            Al registrarte aceptas nuestros términos de uso y la protección de tus datos con estándares bancarios.
        </p>
    </footer>
</section>
