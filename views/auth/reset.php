<?php
/** @var string $csrfToken */
/** @var string $token */
?>
<section class="space-y-8">
    <header class="space-y-3">
        <p class="text-xs uppercase tracking-[0.35em] text-slate-400 font-semibold">Nueva contrasena</p>
        <h1 class="text-3xl font-semibold text-brand-700">Protege tu cuenta con una clave renovada</h1>
        <p class="text-slate-500 leading-relaxed">
            Define una contrasena robusta para mantener tus finanzas seguras y retomar el control sin contratiempos.
        </p>
    </header>

    <form action="/App-Control-Gastos/public/restablecer" method="POST" class="space-y-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

        <div class="space-y-2">
            <label for="reset_password" class="text-sm font-semibold text-slate-600 flex items-center justify-between">
                <span>Nueva contrasena</span>
                <button type="button" data-password-toggle="reset_password" class="text-xs text-brand-600 font-semibold hover:underline">Mostrar</button>
            </label>
            <div class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75A4.5 4.5 0 0 0 12 2.25 4.5 4.5 0 0 0 7.5 6.75V10.5m-2.25 0H18.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 20.25v-7.5a2.25 2.25 0 0 1 2.25-2.25Z"/>
                </svg>
                <input id="reset_password" name="password" type="password" required minlength="8"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Crea una contrasena segura">
            </div>
            <p class="text-xs text-slate-400">Debe contener al menos una mayuscula, un numero y un caracter especial.</p>
        </div>

        <div class="space-y-2">
            <label for="reset_password_confirmation" class="text-sm font-semibold text-slate-600 flex items-center justify-between">
                <span>Confirmar contrasena</span>
                <button type="button" data-password-toggle="reset_password_confirmation" class="text-xs text-brand-600 font-semibold hover:underline">Mostrar</button>
            </label>
            <div class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75A4.5 4.5 0 0 0 12 2.25 4.5 4.5 0 0 0 7.5 6.75V10.5m-2.25 0H18.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 20.25v-7.5a2.25 2.25 0 0 1 2.25-2.25Z"/>
                </svg>
                <input id="reset_password_confirmation" name="password_confirmation" type="password" required minlength="8"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Repite tu contrasena">
            </div>
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-600 text-white font-semibold px-6 py-3 shadow-floating hover:bg-brand-700 transition transition-press">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
            Actualizar contrasena
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
