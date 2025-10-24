<?php
/** @var string $content */

use App\Core\Session;

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
    <script defer src="/App-Control-Gastos/public/assets/js/app.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-100 via-white to-slate-50 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 text-slate-900 dark:text-slate-100 antialiased transition-colors duration-300">
    <div class="app-grid bg-transparent" data-app-grid>
        <?php include __DIR__ . '/../partials/navbar.php'; ?>

        <div class="flex flex-col min-h-screen bg-soft-spot/40 dark:bg-transparent">
            <header class="sticky top-0 z-30 border-b border-slate-200/70 dark:border-slate-700/60 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl">
                <div class="px-5 lg:px-10 py-4 flex items-center gap-4 justify-between">
                    <div class="flex items-center gap-3">
                        <button class="lg:hidden inline-flex items-center justify-center rounded-full border border-slate-200/80 dark:border-slate-700/60 bg-white/80 dark:bg-slate-900/80 px-3 py-2 shadow-sm hover:border-brand-300 transition" data-mobile-nav aria-label="Abrir navegacion">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-slate-400 dark:text-slate-500 font-semibold">Panel</p>
                            <h1 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-slate-100">
                                <?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>
                            </h1>
                        </div>
                    </div>

                    <div class="hidden md:flex items-center gap-3 flex-1">
                        <div class="relative flex-1 max-w-md">
                            <input type="search" placeholder="Buscar movimientos, categorias o reportes..." class="w-full rounded-full border border-slate-200/80 dark:border-slate-700/60 bg-white/90 dark:bg-slate-900/80 px-5 py-3 pl-12 text-sm focus:border-brand-300 focus:ring focus:ring-info/20 transition" aria-label="Buscar en la aplicacion">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.65 6.65a7.5 7.5 0 0 0 10 10Z"/>
                            </svg>
                        </div>
                        <a href="/App-Control-Gastos/public/transacciones" class="hidden xl:inline-flex items-center gap-2 rounded-full bg-brand-600 text-white text-sm font-semibold px-4 py-3 shadow-floating transition hover:bg-brand-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nuevo movimiento
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <button class="inline-flex items-center gap-2 rounded-full border border-slate-200/80 dark:border-slate-700/70 bg-white/90 dark:bg-slate-900/80 px-4 py-2 text-sm font-semibold transition hover:border-brand-200 hover:text-brand-600 dark:hover:border-info/40 dark:hover:text-info/90" data-theme-toggle type="button" aria-label="Alternar modo oscuro">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1.5m6.36.64-1.06 1.06M21 12h-1.5m-.64 6.36-1.06-1.06M12 21v-1.5m-6.36-.64 1.06-1.06M4.5 12H3m.64-6.36 1.06 1.06M12 8.25A3.75 3.75 0 1 0 15.75 12 3.75 3.75 0 0 0 12 8.25Z"/>
                            </svg>
                            <span data-theme-toggle-label>Modo oscuro</span>
                        </button>
                        <?php if ($auth->check()): ?>
                            <div class="hidden sm:flex flex-col text-right">
                                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($user['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="text-xs text-slate-500 dark:text-slate-400"><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <form action="/App-Control-Gastos/public/logout" method="POST" class="hidden sm:block">
                                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                                <button class="rounded-full bg-white/90 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-700/60 px-4 py-2 text-sm font-semibold text-brand-600 dark:text-info hover:border-brand-300 hover:text-brand-700 transition">
                                    Cerrar sesion
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
                </div>
            </header>

            <?php include __DIR__ . '/../partials/mobile-menu.php'; ?>

            <main class="flex-1 w-full px-5 sm:px-8 lg:px-12 py-10">
                <div class="max-w-7xl mx-auto w-full space-y-6">
                    <?php if ($flashSuccess): ?>
                        <div class="rounded-3xl border border-success/30 bg-emerald-50 text-brand-700 px-6 py-4 shadow-soft toast"
                             role="status" aria-live="polite">
                            <?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($flashError): ?>
                        <div class="rounded-3xl border border-danger/40 bg-rose-50 text-danger px-6 py-4 shadow-soft toast"
                             role="alert" aria-live="assertive">
                            <?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($flashInfo): ?>
                        <div class="rounded-3xl border border-info/30 bg-sky-50 text-brand-600 px-6 py-4 shadow-soft toast"
                             role="status" aria-live="polite">
                            <?= htmlspecialchars($flashInfo, ENT_QUOTES, 'UTF-8') ?>
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
