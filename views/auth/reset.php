<?php
/** @var string $csrfToken */
/** @var string $token */

// Helper centralizado en views/partials/icons.php

$icon = fn(string $name, string $classes = 'h-4 w-4'): string => __lucide_icon_helper($name, $classes);
?>
<section class="space-y-8">
    <header class="space-y-3">
        <p class="text-xs uppercase tracking-[0.35em] text-slate-400 font-semibold">Nueva contraseña</p>
        <h1 class="text-3xl font-semibold text-brand-700">Protege tu cuenta con una clave renovada</h1>
        <p class="text-slate-500 leading-relaxed">
            Define una contraseña robusta para mantener tus finanzas seguras y retomar el control sin contratiempos.
        </p>
    </header>

    <form action="/App-Control-Gastos/public/restablecer" method="POST" class="space-y-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

        <div class="space-y-2">
            <label for="reset_password" class="text-sm font-semibold text-slate-600 flex items-center justify-between">
                <span>Nueva contraseña</span>
                <button type="button" data-password-toggle="reset_password" class="text-xs text-brand-600 font-semibold hover:underline">Mostrar</button>
            </label>
            <div class="input-icon">
                <?= $icon('lock') ?>
                <input id="reset_password" name="password" type="password" required minlength="8"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Crea una contraseña segura">
            </div>
            <p class="text-xs text-slate-400">Debe contener al menos una mayuscula, un numero y un caracter especial.</p>
        </div>

        <div class="space-y-2">
            <label for="reset_password_confirmation" class="text-sm font-semibold text-slate-600 flex items-center justify-between">
                <span>Confirmar contraseña</span>
                <button type="button" data-password-toggle="reset_password_confirmation" class="text-xs text-brand-600 font-semibold hover:underline">Mostrar</button>
            </label>
            <div class="input-icon">
                <?= $icon('lock') ?>
                <input id="reset_password_confirmation" name="password_confirmation" type="password" required minlength="8"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Repite tu contraseña">
            </div>
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-600 text-white font-semibold px-6 py-3 shadow-floating hover:bg-brand-700 transition transition-press">
            <?= $icon('shield-check', 'h-5 w-5') ?>
            Actualizar contraseña
        </button>
    </form>

    <footer class="text-sm text-center text-slate-500 space-y-2">
        <p>
            ?Listo para volver?
            <a href="/App-Control-Gastos/public/login" class="text-brand-600 font-semibold hover:underline transition">
                Inicia sesion
            </a>
        </p>
        <p class="text-xs text-slate-400">
            Recuerda cerrar sesion en dispositivos compartidos para mantener tus datos protegidos.
        </p>
    </footer>
</section>
