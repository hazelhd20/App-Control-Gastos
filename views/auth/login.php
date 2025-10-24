<?php /** @var string $csrfToken */ ?>
<section class="space-y-10">
    <header class="space-y-3">
        <h1 class="text-3xl font-semibold text-brand-700">Accede a tu centro financiero</h1>
        <p class="text-slate-500 leading-relaxed">
            Visualiza tu balance, controla cada categoria y toma decisiones respaldadas por datos claros.
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
            <label for="email" class="text-sm font-semibold text-slate-600">Correo electronico</label>
            <div class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6.75v10.5A2.25 2.25 0 0 0 5.25 19.5h13.5A2.25 2.25 0 0 0 21 17.25V6.75m-18 0 9 6 9-6m-18 0A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75"/>
                </svg>
                <input id="email" name="email" type="email" required autocomplete="email"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 text-slate-900 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="usuario@correo.com">
            </div>
        </div>

        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <label for="password" class="text-sm font-semibold text-slate-600">Contrasena</label>
                <button type="button" data-password-toggle="password"
                        class="text-xs text-brand-600 font-semibold hover:underline transition">
                    Mostrar
                </button>
            </div>
            <div class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75A4.5 4.5 0 0 0 12 2.25 4.5 4.5 0 0 0 7.5 6.75V10.5m-2.25 0H18.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 20.25v-7.5a2.25 2.25 0 0 1 2.25-2.25Z"/>
                </svg>
                <input id="password" name="password" type="password" required minlength="8"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 text-slate-900 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Escribe tu contrasena">
            </div>
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-slate-500">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-brand-600 focus:ring-brand-500 transition">
                Recordarme en este dispositivo
            </label>
            <a href="/App-Control-Gastos/public/recuperar" class="text-brand-600 font-semibold hover:underline transition">
                ?Olvidaste tu contrasena?
            </a>
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-600 text-white font-semibold px-5 py-3 shadow-floating hover:bg-brand-700 transition transition-press">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 12a8.25 8.25 0 1 1 16.5 0 8.25 8.25 0 0 1-16.5 0Zm8.25-4.5v6l3.75 1.5"/>
            </svg>
            Iniciar sesion
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
