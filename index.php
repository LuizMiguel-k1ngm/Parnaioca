<?php

require_once __DIR__ . '/vendor/autoload.php';

session_start();

$basePath = '/panaoica';
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace($basePath, '', $requestPath);
$path = rtrim($path, '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

if ($path === '/inicio' && empty($_SESSION['usuario'])) {
    header('Location: ' . $basePath . '/login');
    exit;
}

$routes = array_merge(
    require __DIR__ . '/routes/web.php',
    require __DIR__ . '/routes/cadastro.php',
    require __DIR__ . '/routes/app.php',
    require __DIR__ . '/routes/relatorio.php',
);

$routeKey = $method . ' ' . $path;

if (!isset($routes[$routeKey])) {
    http_response_code(404);
    echo 'Página não encontrada';
    exit;
}

require $routes[$routeKey];
