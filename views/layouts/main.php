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
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
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
                            700: '#004499',
                            800: '#002E66',
                            900: '#001733',
                        },
                        danger: '#EF4444',
                        accent: '#38BDF8',
                    },
                    fontFamily: {
                        sans: ['"Poppins"', 'ui-sans-serif', 'system-ui'],
                    },
                    boxShadow: {
                        floating: '0 20px 40px -24px rgba(0, 114, 255, 0.45)',
                    },
                }
            }
        };
    </script>
    <link rel="stylesheet" href="/App-Control-Gastos/public/assets/css/app.css">
    <script defer src="/App-Control-Gastos/public/assets/js/app.js"></script>
</head>
<body class="bg-slate-50 min-h-screen font-sans text-slate-900">
    <div class="relative min-h-screen flex flex-col">
        <?php include __DIR__ . '/../partials/navbar.php'; ?>
        <?php include __DIR__ . '/../partials/mobile-menu.php'; ?>

        <main class="flex-1 w-full mx-auto px-6 sm:px-10 lg:px-16 py-12 max-w-7xl">
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
                <div class="mb-6 rounded-xl border border-accent/40 bg-white px-6 py-4 text-slate-700 shadow-sm">
                    <?= htmlspecialchars($flashInfo, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </main>

        <?php include __DIR__ . '/../partials/footer.php'; ?>
    </div>
</body>
</html>
