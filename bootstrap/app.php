<?php

use App\Core\Config;
use App\Core\Container;
use App\Core\Database;
use App\Core\Session;
use App\Core\Auth;
use App\Services\MailService;
use App\Services\ReportService;
use App\Services\AlertService;

$vendorAutoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($vendorAutoload)) {
    require_once $vendorAutoload;
}

spl_autoload_register(static function (string $class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});

$config = new Config(require __DIR__ . '/../config/config.php');
date_default_timezone_set($config->get('app.timezone', 'UTC'));
setlocale(LC_ALL, $config->get('app.locale', 'es_MX'));
mb_internal_encoding('UTF-8');

$container = new Container();
$container->set(Config::class, $config);

$container->set(Database::class, static function () use ($config) {
    return Database::make($config->get('database'));
});

$container->set(Session::class, static function () {
    return new Session();
});

$container->set(Auth::class, static function (Container $container) {
    return new Auth(
        $container->get(Session::class),
        $container->get(Database::class),
    );
});

$container->set(MailService::class, static function (Container $container) {
    return new MailService($container->get(Config::class));
});

$container->set(ReportService::class, static function (Container $container) {
    return new ReportService($container->get(Database::class));
});

$container->set(AlertService::class, static function (Container $container) {
    return new AlertService($container->get(Database::class));
});

return $container;
