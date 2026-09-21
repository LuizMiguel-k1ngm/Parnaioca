<?php

include 'vendor/autoload.php';

session_start();

if (empty($_SESSION['usuario'])) {
    header('Location: app/Views/login/index.php');
    exit;
}

require 'app/Views/login/index.php';
