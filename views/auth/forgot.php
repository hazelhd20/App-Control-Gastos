<?php /** @var string $csrfToken */ ?>
<section class="space-y-8">
    <header class="space-y-3">
        <p class="text-xs uppercase tracking-[0.35em] text-slate-400 font-semibold">Recupera tu acceso</p>
        <h1 class="text-3xl font-semibold text-brand-700">Restablece tu contrasena con seguridad</h1>
        <p class="text-slate-500 leading-relaxed">
            Te enviaremos un enlace temporal para que puedas definir una nueva contrasena y continuar con tu seguimiento financiero.
        </p>
    </header>

    <form action="/App-Control-Gastos/public/recuperar" method="POST" class="space-y-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <div class="space-y-2">
            <label for="email" class="text-sm font-semibold text-slate-600">Correo electronico registrado</label>
            <div class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6.75v10.5A2.25 2.25 0 0 0 5.25 19.5h13.5A2.25 2.25 0 0 0 21 17.25V6.75m-18 0 9 6 9-6m-18 0A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75"/>
                </svg>
                <input id="email" name="email" type="email" required autocomplete="email"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="correo@ejemplo.com">
            </div>
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-600 text-white font-semibold px-6 py-3 shadow-floating hover:bg-brand-700 transition transition-press">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8.25 12 3l9 5.25-9 5.25-9-5.25Zm0 0v7.5L12 21l9-5.25v-7.5"/>
            </svg>
            Enviar enlace de recuperacion
        </button>
    </form>

    <footer class="text-sm text-center text-slate-500 space-y-2">
        <p>
            ?Recordaste tu contrasena?
            <a href="/App-Control-Gastos/public/login" class="text-brand-600 font-semibold hover:underline transition">
                Inicia sesion
            </a>
        </p>
        <p class="text-xs text-slate-400">
            Si no recibes el correo en pocos minutos, revisa tu carpeta de spam o contacta a soporte.
        </p>
    </footer>
</section>
