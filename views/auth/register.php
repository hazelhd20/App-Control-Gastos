<?php /** @var string $csrfToken */ ?>
<section class="space-y-10">
    <header class="space-y-3">
        <p class="text-xs uppercase tracking-[0.35em] text-slate-400 font-semibold">Crea tu cuenta</p>
        <h1 class="text-3xl font-semibold text-brand-700">Configura tu espacio de control financiero</h1>
        <p class="text-slate-500 leading-relaxed">
            Personaliza tus datos basicos para recibir recomendaciones, alertas y estadisticas adaptadas a tu estilo de vida.
        </p>
        <div class="flex flex-wrap gap-3 text-xs text-slate-400">
            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5">
                <span class="h-2 w-2 rounded-full bg-brand-500"></span>
                Sincronizacion segura
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
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2.25c-5.25 0-7.5 3-7.5 4.5v1.5a.75.75 0 0 0 .75.75h13.5a.75.75 0 0 0 .75-.75v-1.5c0-1.5-2.25-4.5-7.5-4.5Z"/>
                </svg>
                <input id="name" name="name" type="text" required autocomplete="name"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Nombre y apellidos">
            </div>
        </div>

        <div class="space-y-2">
            <label for="phone" class="text-sm font-semibold text-slate-600">Telefono</label>
            <div class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75 9 13.5m0 0 2.25-2.25M9 13.5l-1.5 4.5m4.5-12L21 13.5m0 0-1.5 4.5m1.5-4.5L12 4.5"/>
                </svg>
                <input id="phone" name="phone" type="tel" required
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="+52 55 0000 0000">
            </div>
        </div>

        <div class="space-y-2">
            <label for="occupation" class="text-sm font-semibold text-slate-600">Ocupacion</label>
            <div class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 8.25A2.25 2.25 0 0 1 6.75 6h10.5a2.25 2.25 0 0 1 2.25 2.25v12a.75.75 0 0 1-1.22.58L12 15.75l-6.28 5.08a.75.75 0 0 1-1.22-.58Z"/>
                </svg>
                <input id="occupation" name="occupation" type="text" required
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Ej. Analista financiero">
            </div>
        </div>

        <div class="space-y-2 md:col-span-2">
            <label for="email" class="text-sm font-semibold text-slate-600">Correo electronico</label>
            <div class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6.75v10.5A2.25 2.25 0 0 0 5.25 19.5h13.5A2.25 2.25 0 0 0 21 17.25V6.75m-18 0 9 6 9-6m-18 0A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75"/>
                </svg>
                <input id="email" name="email" type="email" required autocomplete="email"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="correo@ejemplo.com">
            </div>
        </div>

        <div class="space-y-2">
            <label for="register_password" class="text-sm font-semibold text-slate-600 flex items-center justify-between">
                <span>Contrasena</span>
                <button type="button" data-password-toggle="register_password" class="text-xs text-brand-600 font-semibold hover:underline">Mostrar</button>
            </label>
            <div class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75A4.5 4.5 0 0 0 12 2.25 4.5 4.5 0 0 0 7.5 6.75V10.5m-2.25 0H18.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 20.25v-7.5a2.25 2.25 0 0 1 2.25-2.25Z"/>
                </svg>
                <input id="register_password" name="password" type="password" required minlength="8"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Crea una contrasena segura">
            </div>
            <p class="text-xs text-slate-400">Debe contener al menos una mayuscula, un numero y un caracter especial.</p>
        </div>

        <div class="space-y-2">
            <label for="register_password_confirmation" class="text-sm font-semibold text-slate-600 flex items-center justify-between">
                <span>Confirmar contrasena</span>
                <button type="button" data-password-toggle="register_password_confirmation" class="text-xs text-brand-600 font-semibold hover:underline">Mostrar</button>
            </label>
            <div class="input-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75A4.5 4.5 0 0 0 12 2.25 4.5 4.5 0 0 0 7.5 6.75V10.5m-2.25 0H18.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 20.25v-7.5a2.25 2.25 0 0 1 2.25-2.25Z"/>
                </svg>
                <input id="register_password_confirmation" name="password_confirmation" type="password" required minlength="8"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="Repite tu contrasena">
            </div>
        </div>

        <div class="md:col-span-2">
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-600 text-white font-semibold px-6 py-3 shadow-floating hover:bg-brand-700 transition transition-press">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 12.75l6 6 9-13.5"/>
                </svg>
                Crear cuenta
            </button>
        </div>
    </form>

    <footer class="text-sm text-center text-slate-500 space-y-2">
        <p>
            ?Ya tienes cuenta?
            <a href="/App-Control-Gastos/public/login" class="text-brand-600 font-semibold hover:underline transition">
                Inicia sesion
            </a>
        </p>
        <p class="text-xs text-slate-400">
            Al registrarte aceptas nuestros terminos de uso y la proteccion de tus datos con estandares bancarios.
        </p>
    </footer>
</section>
