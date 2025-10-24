<?php /** @var string $csrfToken */ ?>
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-semibold text-primary-700 mb-2">Crea tu cuenta</h1>
        <p class="text-slate-500">Configura tu espacio personal para controlar tus finanzas.</p>
    </div>

    <form action="/App-Control-Gastos/public/registro" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <div class="space-y-2 md:col-span-2">
            <label for="name" class="text-sm font-semibold text-slate-600">Nombre completo</label>
            <input id="name" name="name" type="text" required autocomplete="name"
                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
        </div>

        <div class="space-y-2">
            <label for="phone" class="text-sm font-semibold text-slate-600">Telefono</label>
            <input id="phone" name="phone" type="tel" required
                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
        </div>

        <div class="space-y-2">
            <label for="occupation" class="text-sm font-semibold text-slate-600">Ocupacion</label>
            <input id="occupation" name="occupation" type="text" required
                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
        </div>

        <div class="space-y-2 md:col-span-2">
            <label for="email" class="text-sm font-semibold text-slate-600">Correo electronico</label>
            <input id="email" name="email" type="email" required autocomplete="email"
                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
        </div>

        <div class="space-y-2">
            <label for="password" class="text-sm font-semibold text-slate-600 flex items-center justify-between">
                <span>Contrasena</span>
                <button type="button" data-password-toggle="register_password" class="text-xs text-primary-600 font-semibold hover:underline">Mostrar</button>
            </label>
            <input id="register_password" name="password" type="password" required minlength="8"
                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
            <p class="text-xs text-slate-400">Debe contener al menos una mayuscula, un numero y un caracter especial.</p>
        </div>

        <div class="space-y-2">
            <label for="password_confirmation" class="text-sm font-semibold text-slate-600 flex items-center justify-between">
                <span>Confirmar contrasena</span>
                <button type="button" data-password-toggle="register_password_confirmation" class="text-xs text-primary-600 font-semibold hover:underline">Mostrar</button>
            </label>
            <input id="register_password_confirmation" name="password_confirmation" type="password" required minlength="8"
                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
        </div>

        <div class="md:col-span-2">
            <button type="submit" class="w-full px-5 py-3 rounded-2xl bg-primary-500 text-white font-semibold shadow-lg shadow-primary-500/30 hover:bg-primary-600 transition">
                Crear cuenta
            </button>
        </div>
    </form>

    <p class="text-sm text-center text-slate-500">
        ?Ya tienes cuenta?
        <a href="/App-Control-Gastos/public/login" class="text-primary-600 font-semibold hover:underline">Inicia sesion</a>
    </p>
</div>
