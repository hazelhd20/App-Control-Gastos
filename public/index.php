<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ProfileController;
use App\Controllers\TransactionController;
use App\Controllers\ReportController;
use App\Controllers\AlertController;
use App\Core\Request;
use App\Core\Router;

$container = require __DIR__ . '/../bootstrap/app.php';

$router = new Router();

$router->get('/', [DashboardController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/registro', [AuthController::class, 'showRegister']);
$router->post('/registro', [AuthController::class, 'register']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/recuperar', [AuthController::class, 'showForgot']);
$router->post('/recuperar', [AuthController::class, 'sendResetLink']);
$router->get('/restablecer', [AuthController::class, 'showResetForm']);
$router->post('/restablecer', [AuthController::class, 'resetPassword']);

$router->get('/perfil', [ProfileController::class, 'show']);
$router->post('/perfil', [ProfileController::class, 'update']);
$router->get('/perfil/configuracion-inicial', [ProfileController::class, 'showInitialSetup']);
$router->post('/perfil/configuracion-inicial', [ProfileController::class, 'storeInitialSetup']);

$router->get('/transacciones', [TransactionController::class, 'index']);
$router->post('/transacciones', [TransactionController::class, 'store']);
$router->post('/transacciones/eliminar', [TransactionController::class, 'delete']);

$router->get('/reportes', [ReportController::class, 'index']);
$router->get('/reportes/exportar', [ReportController::class, 'export']);

$router->get('/alertas', [AlertController::class, 'index']);
$router->post('/alertas/marcar', [AlertController::class, 'markSeen']);

$request = new Request();
$router->dispatch($request, $container);
