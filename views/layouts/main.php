<?php
/** @var string $content */
include_once __DIR__ . '/../partials/icons.php';

use App\Core\Session;

$headersSent = headers_sent();
if (!$headersSent) {
    header('Content-Type: text/html; charset=UTF-8');
}

$session = $session ?? new Session();
$flashSuccess = $session->getFlash('success');
$flashError = $session->getFlash('error');
$flashInfo = $session->getFlash('info');
$csrfToken = $session->token();
$user = $auth?->user();
$initialSource = trim((string) ($user['name'] ?? ''));
$userInitial = strtoupper($initialSource !== '' ? substr($initialSource, 0, 1) : 'U');
$pageTitle = $title ?? 'Control de Gastos';
?>
<!DOCTYPE html>
<html lang="es" data-theme="light" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
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
                            400: '#4ADE80',
                            500: '#22C55E',
                            600: '#16A34A',
                        },
                        slate: {
                            950: '#020617',
                        },
                        success: '#22C55E',
                        danger: '#EF4444',
                        info: '#38BDF8',
                    },
                    fontFamily: {
                        sans: ['"Inter"', '"Poppins"', 'ui-sans-serif', 'system-ui'],
                    },
                    boxShadow: {
                        floating: '0 24px 48px -28px rgba(15, 23, 42, 0.35)',
                        soft: '0 18px 40px -30px rgba(15, 23, 42, 0.25)',
                    },
                    borderRadius: {
                        xl: '1.25rem',
                        '2xl': '1.75rem',
                        '3xl': '2.25rem',
                    },
                    backgroundImage: {
                        'hero-gradient': 'linear-gradient(160deg, rgba(30, 58, 138, 0.95), rgba(56, 189, 248, 0.92))',
                        'soft-spot': 'radial-gradient(circle at top left, rgba(56, 189, 248, 0.22), transparent 55%)',
                    },
                }
            }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="/App-Control-Gastos/public/assets/css/app.css">
    <script defer src="/App-Control-Gastos/public/assets/vendor/lucide.min.js"></script>
    <script defer src="/App-Control-Gastos/public/assets/js/app.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-slate-50 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 text-slate-900 dark:text-slate-100 antialiased transition-colors duration-300">
    <a href="#main-content" class="skip-link focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400">Saltar al contenido principal</a>
    <div class="app-grid bg-transparent" data-app-grid>
        <?php include __DIR__ . '/../partials/navbar.php'; ?>

        <div class="flex flex-col min-h-screen bg-soft-spot/40 dark:bg-transparent">
            <header class="sticky top-0 z-30 border-b border-slate-200/70 dark:border-slate-700/60 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl">
                <div class="px-5 lg:px-10 py-4 space-y-4">
                    <div class="flex items-center gap-4 justify-between">
                        <div class="flex items-center gap-3">
                            <button class="lg:hidden inline-flex items-center justify-center rounded-full border border-slate-200/80 dark:border-slate-700/60 bg-white/80 dark:bg-slate-900/80 px-3 py-2 shadow-sm hover:border-brand-300 transition" data-mobile-nav aria-controls="mobileMenu" aria-expanded="false" aria-label="Abrir navegación">
                                <span class="h-5 w-5 text-slate-600 dark:text-slate-200" data-lucide="menu" aria-hidden="true"></span>
                            </button>
                            <div>
                                <p class="text-xs uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500 font-semibold">Panel</p>
                                <h1 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-slate-100">
                                    <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>
                                </h1>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="button" class="inline-flex items-center justify-center rounded-full border border-slate-200/80 dark:border-slate-700/70 bg-white/90 dark:bg-slate-900/80 p-2.5 text-sm font-semibold transition hover:border-brand-200 hover:text-brand-600 dark:hover:border-info/40 dark:hover:text-info/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300" data-theme-toggle data-theme-state="light" aria-label="Cambiar a modo oscuro" aria-pressed="false" title="Cambiar a modo oscuro">
                                <span class="theme-icon theme-icon--light h-5 w-5 text-slate-500 dark:text-slate-300 transition-transform duration-300" data-lucide="sun" aria-hidden="true"></span>
                                <span class="theme-icon theme-icon--dark hidden h-5 w-5 text-slate-500 dark:text-slate-300 transition-transform duration-300" data-lucide="moon-star" aria-hidden="true"></span>
                                <span class="sr-only" data-theme-toggle-label>Modo oscuro</span>
                            </button>
                        <?php if ($auth->check()): ?>
                            <div class="hidden sm:flex flex-col text-right">
                                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="text-xs text-slate-500 dark:text-slate-400"><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <form action="/App-Control-Gastos/public/logout" method="POST" class="hidden sm:block">
                                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                <button class="rounded-full bg-white/90 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-700/60 px-4 py-2 text-sm font-semibold text-brand-600 dark:text-info hover:border-brand-300 hover:text-brand-700 transition">
                                    Cerrar sesión
                                </button>
                            </form>
                            <div class="h-10 w-10 shrink-0 rounded-full bg-brand-600 text-white font-semibold flex items-center justify-center shadow-floating avatar-ring">
                                <?= htmlspecialchars($userInitial, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php else: ?>
                            <a href="/App-Control-Gastos/public/login" class="inline-flex items-center gap-2 rounded-full bg-brand-600 text-white px-4 py-2 text-sm font-semibold shadow-floating hover:bg-brand-700 transition">
                                Acceder
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                        <div class="md:max-w-xl w-full">
                            <label class="flex flex-col gap-1 text-xs font-semibold text-slate-500 dark:text-slate-400" for="global-search">
                                Búsqueda rápida
                                <span class="relative inline-flex">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 dark:text-slate-500" data-lucide="search" aria-hidden="true"></span>
                                    <input id="global-search" type="search" placeholder="Buscar por nombre o categoría" class="w-full rounded-full border border-slate-200/80 dark:border-slate-700/60 bg-white/90 dark:bg-slate-900/80 px-5 py-3 pl-12 text-sm focus:border-brand-300 focus:ring focus:ring-info/20 transition" aria-label="Buscar en la aplicación">
                                </span>
                            </label>
                        </div>
                        <a href="/App-Control-Gastos/public/transacciones" class="inline-flex items-center justify-center gap-2 self-start md:self-auto rounded-full bg-brand-600 text-white text-sm font-semibold px-4 py-3 shadow-floating transition hover:bg-brand-700">
                            <span class="h-4 w-4 text-white" data-lucide="plus" aria-hidden="true"></span>
                            Nuevo movimiento
                        </a>
                    </div>
                </div>
            </header>

            <?php include __DIR__ . '/../partials/mobile-menu.php'; ?>

            <main id="main-content" class="flex-1 w-full px-5 sm:px-8 lg:px-12 py-10" tabindex="-1">
                <div class="max-w-7xl mx-auto w-full space-y-6">
                    <?php if ($flashSuccess): ?>
                        <div class="ui-toast ui-toast--success" role="alert" aria-live="assertive" data-toast data-autohide="4000">
                            <div class="ui-toast__body">
                                <span class="ui-toast__icon" aria-hidden="true" data-lucide="check-circle-2"></span>
                                <span class="ui-toast__message"><?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <button type="button" class="ui-toast__close" data-dismiss-toast aria-label="Cerrar notificación">
                                <span class="h-4 w-4" data-lucide="x" aria-hidden="true"></span>
                            </button>
                            <span class="ui-toast__timer" data-toast-timer aria-hidden="true"></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($flashError): ?>
                        <div class="ui-toast ui-toast--danger" role="alert" aria-live="assertive" data-toast>
                            <div class="ui-toast__body">
                                <span class="ui-toast__icon" aria-hidden="true" data-lucide="alert-circle"></span>
                                <span class="ui-toast__message"><?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <button type="button" class="ui-toast__close" data-dismiss-toast aria-label="Cerrar notificación">
                                <span class="h-4 w-4" data-lucide="x" aria-hidden="true"></span>
                            </button>
                            <span class="ui-toast__timer" data-toast-timer aria-hidden="true"></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($flashInfo): ?>
                        <div class="ui-toast ui-toast--info" role="status" aria-live="polite" data-toast data-autohide="4500">
                            <div class="ui-toast__body">
                                <span class="ui-toast__icon" aria-hidden="true" data-lucide="info"></span>
                                <span class="ui-toast__message"><?= htmlspecialchars($flashInfo, ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <button type="button" class="ui-toast__close" data-dismiss-toast aria-label="Cerrar notificación">
                                <span class="h-4 w-4" data-lucide="x" aria-hidden="true"></span>
                            </button>
                            <span class="ui-toast__timer" data-toast-timer aria-hidden="true"></span>
                        </div>
                    <?php endif; ?>

                    <?= $content ?>
                </div>
            </main>

            <?php include __DIR__ . '/../partials/footer.php'; ?>
        </div>
    </div>
</body>
</html>
