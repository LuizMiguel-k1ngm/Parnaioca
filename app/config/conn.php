<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'parnaoica';
$dns = "mysql:host=$host;dbname=$dbname";

try {
    $conn = new PDO($dns, $username, $password);
} catch (PDOException $e) {
    echo 'erro ao conectar' . $e->getMessage();
}
