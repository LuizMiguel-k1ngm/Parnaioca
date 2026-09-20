<?php
session_start();
include_once 'app/Config/conn.php';


if (empty($_SESSION['usuario'])) {
    header('Location: app/Views/login/index.php');
    exit;
}