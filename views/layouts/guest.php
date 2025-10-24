<?php
/** @var string $content */

use App\Core\Session;

$session = $session ?? new Session();
$flashSuccess = $session->getFlash('success');
$flashError = $session->getFlash('error');
$flashInfo = $session->getFlash('info');
$csrfToken = $session->token();
$pageTitle = $title ?? 'Control de Gastos';
?>
<!DOCTYPE html>
<html lang="es" data-theme="light" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#F0F5FF',
                            100: '#E0EBFF',
                            200: '#C1D7FE',
                            300: '#9AC3FD',
                            400: '#6DA7FA',
                            500: '#3C82F6',
                            600: '#1E3A8A',
                            700: '#162C68',
                            800: '#102050',
                            900: '#0A1538',
                        },
                        accent: {
                            100: '#DCFCE7',
                            200: '#BBF7D0',
                            500: '#22C55E',
                        },
                        danger: '#EF4444',
                        info: '#38BDF8',
                    },
                    fontFamily: {
                        sans: ['"Inter"', 'ui-sans-serif', 'system-ui'],
                    },
                    boxShadow: {
                        floating: '0 24px 48px -28px rgba(15, 23, 42, 0.35)',
                    },
                }
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/App-Control-Gastos/public/assets/css/app.css">
    <script defer src="/App-Control-Gastos/public/assets/js/app.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-slate-200 text-slate-900 antialiased">
    <div class="relative min-h-screen flex items-center justify-center py-10 px-4">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-32 -left-24 h-72 w-72 rounded-full bg-brand-500/15 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-accent-200/30 blur-3xl"></div>
        </div>

        <div class="relative w-full max-w-6xl bg-white/80 border border-slate-200/60 shadow-[0_40px_80px_-48px_rgba(15,23,42,0.4)] rounded-[2.5rem] overflow-hidden backdrop-blur-xl">
            <div class="grid lg:grid-cols-[1.1fr_1fr] min-h-[620px]">
                <div class="hidden lg:flex flex-col justify-between p-12 bg-gradient-to-br from-brand-700 via-brand-600 to-brand-500 text-white relative">
                    <div class="flex items-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-3xl bg-white text-brand-600 text-xl font-semibold shadow-floating">
                            CG
                        </span>
                        <div>
                            <p class="text-lg font-semibold">Control de Gastos</p>
                            <p class="text-sm text-white/70">Gestion inteligente de tu dinero</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h2 class="text-3xl font-semibold leading-tight">Visualiza tus metas, ahorra con estrategia y mantente siempre informado.</h2>
                        <p class="text-base text-white/80 leading-relaxed">
                            Simplificamos la administracion de tus finanzas personales con un panel intuitivo, reportes visuales y alertas que anticipan cada movimiento importante.
                        </p>
                        <div class="flex items-center gap-5">
                            <div class="h-16 w-16 rounded-3xl bg-white/15 border border-white/20 flex items-center justify-center text-2xl font-bold shadow-floating">24/7</div>
                            <div class="text-sm text-white/80">
                                Monitoreo en tiempo real, sincronizacion segura y compatibilidad completa en dispositivos moviles.
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/20 rounded-3xl p-6 border border-white/30 shadow-floating backdrop-blur-2xl">
                        <p class="text-xs uppercase tracking-[0.35em] text-white/70 font-semibold">Resumen destacado</p>
                        <div class="mt-4 grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-white/70 text-xs">Balance mensual</p>
                                <p class="text-lg font-semibold text-white">+$1,820</p>
                            </div>
                            <div>
                                <p class="text-white/70 text-xs">Categorias clave</p>
                                <p class="text-lg font-semibold text-white">5 activas</p>
                            </div>
                            <div>
                                <p class="text-white/70 text-xs">Objetivo</p>
                                <p class="text-lg font-semibold text-white">Ahorro 35%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col justify-center p-8 sm:p-12 bg-white/90 backdrop-blur-xl">
                    <a href="/App-Control-Gastos/public" class="lg:hidden inline-flex items-center gap-3 mb-8">
                        <span class="flex h-11 w-11 items-center justify-center rounded-3xl bg-brand-600 text-white font-semibold shadow-floating">
                            CG
                        </span>
                        <div>
                            <p class="text-lg font-semibold text-brand-700">Control de Gastos</p>
                            <p class="text-sm text-slate-500">Gestion inteligente de tu dinero</p>
                        </div>
                    </a>

                    <?php if ($flashSuccess): ?>
                        <div class="mb-6 rounded-2xl border border-accent-200 bg-accent-100/70 px-6 py-4 text-brand-700 shadow-soft toast">
                            <?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($flashError): ?>
                        <div class="mb-6 rounded-2xl border border-danger/30 bg-rose-50 px-6 py-4 text-danger shadow-soft toast">
                            <?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($flashInfo): ?>
                        <div class="mb-6 rounded-2xl border border-info/25 bg-sky-50 px-6 py-4 text-brand-600 shadow-soft toast">
                            <?= htmlspecialchars($flashInfo, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
