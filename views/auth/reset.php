<?php
/** @var string $csrfToken */
/** @var string $token */
?>
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-semibold text-primary-700 mb-2">Crea una nueva contrasena</h1>
        <p class="text-slate-500">Elige una contrasena segura para proteger tu informacion.</p>
    </div>

    <form action="/App-Control-Gastos/public/restablecer" method="POST" class="space-y-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

        <div class="space-y-2">
            <label for="password" class="text-sm font-semibold text-slate-600 flex items-center justify-between">
                <span>Nueva contrasena</span>
                <button type="button" data-password-toggle="reset_password" class="text-xs text-primary-600 font-semibold hover:underline">Mostrar</button>
            </label>
            <input id="reset_password" name="password" type="password" required minlength="8"
                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
            <p class="text-xs text-slate-400">Debe contener al menos una mayuscula, un numero y un caracter especial.</p>
        </div>

        <div class="space-y-2">
            <label for="password_confirmation" class="text-sm font-semibold text-slate-600 flex items-center justify-between">
                <span>Confirmar contrasena</span>
                <button type="button" data-password-toggle="reset_password_confirmation" class="text-xs text-primary-600 font-semibold hover:underline">Mostrar</button>
            </label>
            <input id="reset_password_confirmation" name="password_confirmation" type="password" required minlength="8"
                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
        </div>

        <button type="submit" class="w-full px-5 py-3 rounded-2xl bg-primary-500 text-white font-semibold shadow-lg shadow-primary-500/30 hover:bg-primary-600 transition">
            Actualizar contrasena
        </button>
    </form>

    <p class="text-sm text-center text-slate-500">
        ?Listo para volver?
        <a href="/App-Control-Gastos/public/login" class="text-primary-600 font-semibold hover:underline">Inicia sesion</a>
    </p>
</div>
