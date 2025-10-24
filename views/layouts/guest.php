<?php
/** @var string $content */

use App\Core\Session;

$session = $session ?? new Session();
$flashSuccess = $session->getFlash('success');
$flashError = $session->getFlash('error');
$flashInfo = $session->getFlash('info');
$csrfToken = $session->token();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Control de Gastos' ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#E6F1FF',
                            100: '#CCE3FF',
                            200: '#99C6FF',
                            300: '#66AAFF',
                            400: '#338EFF',
                            500: '#0072FF',
                            600: '#005BCC',
                        },
                        danger: '#EF4444',
                    },
                    fontFamily: {
                        sans: ['"Poppins"', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="/App-Control-Gastos/public/assets/css/app.css">
    <script defer src="/App-Control-Gastos/public/assets/js/app.js"></script>
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-primary-100 min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-5xl mx-auto grid gap-10 lg:grid-cols-2 items-center">
        <div class="bg-white rounded-3xl shadow-xl border border-primary-100 px-10 py-12 card-glass">
            <a href="/App-Control-Gastos/public" class="inline-flex items-center gap-3 mb-8">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-primary-500 text-white font-semibold shadow-floating">
                    CG
                </span>
                <div>
                    <p class="text-xl font-semibold text-primary-700">Control de Gastos</p>
                    <p class="text-sm text-slate-500">Organiza, visualiza y alcanza tus objetivos.</p>
                </div>
            </a>

            <?php if ($flashSuccess): ?>
                <div class="mb-6 rounded-xl border border-primary-200 bg-primary-50 px-6 py-4 text-primary-800 shadow-sm">
                    <?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($flashError): ?>
                <div class="mb-6 rounded-xl border border-danger/70 bg-danger/10 px-6 py-4 text-danger shadow-sm">
                    <?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($flashInfo): ?>
                <div class="mb-6 rounded-xl border border-primary-100 bg-white px-6 py-4 text-slate-700 shadow-sm">
                    <?= htmlspecialchars($flashInfo, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>

        <div class="hidden lg:flex flex-col items-start gap-6">
            <div class="bg-white/70 rounded-3xl p-8 shadow-floating border border-primary-100 backdrop-blur">
                <h2 class="text-2xl font-semibold text-primary-700 mb-4">Control inteligente de tus finanzas</h2>
                <p class="text-slate-600 leading-relaxed">
                    Registra todos tus movimientos, establece limites personalizados y recibe alertas para mantenerte en el camino hacia tus metas financieras.
                </p>
            </div>
            <div class="flex items-center gap-4">
                <div class="h-20 w-20 rounded-2xl bg-primary-200/60 flex items-center justify-center text-primary-700 text-3xl font-bold shadow-floating">
                    360&deg;
                </div>
                <div>
                    <p class="text-lg font-semibold text-primary-700">Vision completa</p>
                    <p class="text-slate-500 text-sm">
                        Dashboards intuitivos, reportes descargables y seguimiento de tus metas en un solo lugar.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
