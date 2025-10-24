<?php /** @var string $csrfToken */ ?>
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-semibold text-primary-700 mb-2">Bienvenido de nuevo</h1>
        <p class="text-slate-500">Ingresa tus datos para acceder a tu panel financiero.</p>
    </div>

    <form action="/App-Control-Gastos/public/login" method="POST" class="space-y-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <div class="space-y-2">
            <label for="email" class="text-sm font-semibold text-slate-600">Correo electronico</label>
            <input id="email" name="email" type="email" required autocomplete="email"
                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <label for="password" class="text-sm font-semibold text-slate-600">Contrasena</label>
                <button type="button" data-password-toggle="password"
                        class="text-xs text-primary-600 font-semibold hover:underline">
                    Mostrar
                </button>
            </div>
            <input id="password" name="password" type="password" required minlength="8"
                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                <span class="text-slate-500">Recordarme</span>
            </label>
            <a href="/App-Control-Gastos/public/recuperar" class="text-primary-600 hover:underline font-semibold">?Olvidaste tu contrasena?</a>
        </div>

        <button type="submit" class="w-full px-5 py-3 rounded-2xl bg-primary-500 text-white font-semibold shadow-lg shadow-primary-500/30 hover:bg-primary-600 transition">
            Iniciar sesion
        </button>
    </form>

    <p class="text-sm text-center text-slate-500">
        ?Aun no tienes una cuenta?
        <a href="/App-Control-Gastos/public/registro" class="text-primary-600 font-semibold hover:underline">Registrate aqui</a>
    </p>
</div>
