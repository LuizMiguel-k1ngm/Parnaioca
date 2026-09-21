<?php

if (empty($_SESSION['usuario'])) {
    return [];
}

return [
    'GET /inicio' => __DIR__ . '/../app/Views/inicio/index.php',
];
