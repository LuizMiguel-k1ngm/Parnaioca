<?php

$host = 'locallhost';

$username = '';
$password = '';
$dbname = 'parnaioca';
$dns = "mysql:host=$host;dbname=$dbname";




try {
    $conn = new PDO($dns, $username, $password);
    echo 'Conexao bem sucedida';
} catch (PDOException $e) {
    echo 'erro ao conectar' . $e->getMessage();
}
