<?php /** @var string $csrfToken */ ?>
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-semibold text-primary-700 mb-2">Recupera tu acceso</h1>
        <p class="text-slate-500">Te enviaremos un enlace temporal para restablecer tu contrasena.</p>
    </div>

    <form action="/App-Control-Gastos/public/recuperar" method="POST" class="space-y-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <div class="space-y-2">
            <label for="email" class="text-sm font-semibold text-slate-600">Correo electronico</label>
            <input id="email" name="email" type="email" required autocomplete="email"
                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:border-primary-300 focus:ring focus:ring-primary-100 px-4 py-3">
        </div>

        <button type="submit" class="w-full px-5 py-3 rounded-2xl bg-primary-500 text-white font-semibold shadow-lg shadow-primary-500/30 hover:bg-primary-600 transition">
            Enviar enlace de recuperacion
        </button>
    </form>

    <p class="text-sm text-center text-slate-500">
        ?Recordaste tu contrasena?
        <a href="/App-Control-Gastos/public/login" class="text-primary-600 font-semibold hover:underline">Inicia sesion</a>
    </p>
</div>
