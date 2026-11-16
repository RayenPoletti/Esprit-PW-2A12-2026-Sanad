<?php
/**
 * Point d'entrée unique du Back Office (front controller).
 * URL : index.php?page=dashboard, index.php?page=audits, ...
 */
declare(strict_types=1);

// Chargement automatique des classes App\Controllers\... et App\Models\...
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $file = __DIR__ . '/app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

require_once __DIR__ . '/app/helpers.php';
$routes = require __DIR__ . '/app/routes.php';

$page = $_GET['page'] ?? 'dashboard';
if (!isset($routes[$page])) {
    http_response_code(404);
    $page = '404';
}

[$controller, $action] = $routes[$page];
$class = 'App\\Controllers\\' . $controller;
(new $class())->$action();
