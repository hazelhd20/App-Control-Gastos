<?php /** @var string $csrfToken */ ?>
<?php // Helper centralizado en views/partials/icons.php ?>
<?php $icon = fn(string $name, string $classes = 'h-4 w-4'): string => __lucide_icon_helper($name, $classes); ?>
<section class="space-y-8">
    <header class="space-y-3">
        <p class="text-xs uppercase tracking-[0.35em] text-slate-400 font-semibold">Recupera tu acceso</p>
        <h1 class="text-3xl font-semibold text-brand-700">Restablece tu contraseña con seguridad</h1>
        <p class="text-slate-500 leading-relaxed">
            Te enviaremos un enlace temporal para que puedas definir una nueva contraseña y continuar con tu seguimiento financiero.
        </p>
    </header>

    <form action="/App-Control-Gastos/public/recuperar" method="POST" class="space-y-6">
        <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

        <div class="space-y-2">
            <label for="email" class="text-sm font-semibold text-slate-600">Correo electrónico registrado</label>
            <div class="input-icon">
                <?= $icon('mail') ?>
                <input id="email" name="email" type="email" required autocomplete="email"
                       class="w-full rounded-2xl border border-slate-200 bg-white/90 px-4 py-3 focus:border-brand-300 focus:ring focus:ring-info/20 transition placeholder:text-slate-400"
                       placeholder="correo@ejemplo.com">
            </div>
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-brand-600 text-white font-semibold px-6 py-3 shadow-floating hover:bg-brand-700 transition transition-press">
            <?= $icon('send', 'h-5 w-5') ?>
            Enviar enlace de recuperación
        </button>
    </form>

    <footer class="text-sm text-center text-slate-500 space-y-2">
        <p>
            ?Recordaste tu contraseña?
            <a href="/App-Control-Gastos/public/login" class="text-brand-600 font-semibold hover:underline transition">
                Inicia sesión
            </a>
        </p>
        <p class="text-xs text-slate-400">
            Si no recibes el correo en pocos minutos, revisa tu carpeta de spam o contacta a soporte.
        </p>
    </footer>
</section>
