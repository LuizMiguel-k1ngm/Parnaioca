<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: /panaoica/login');
    exit;
}

require_once __DIR__ . '/../config/conn.php';
require_once __DIR__ . '/../Repositories/FrigobarRepository.php';

$idAcomodacao = filter_var($_POST['id_acomodacao'] ?? null, FILTER_VALIDATE_INT);
$idStatus = filter_var($_POST['id_status_frigobar'] ?? null, FILTER_VALIDATE_INT);
$numeroIdentificacao = trim($_POST['numero_identificacao'] ?? '');
$erros = [];

$_SESSION['frigobar_acomodacao'] = $idAcomodacao ?: '';
$_SESSION['frigobar_status'] = $idStatus ?: '';
$_SESSION['frigobar_identificacao'] = $numeroIdentificacao;

if ($idAcomodacao === false || $idAcomodacao < 1) {
    $erros[] = 'Selecione uma acomodação válida.';
}

if ($idStatus === false || $idStatus < 1) {
    $erros[] = 'Selecione um status para o frigobar.';
}

if (strlen($numeroIdentificacao) > 50) {
    $erros[] = 'A identificação deve ter no máximo 50 caracteres.';
}

if (!empty($erros)) {
    $_SESSION['frigobar_erros'] = $erros;
    header('Location: /panaoica/cadastro/frigobar');
    exit;
}

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    (new \FrigobarRepository($conn))->criarFrigobar((int) $idAcomodacao, (int) $idStatus, $numeroIdentificacao);
} catch (PDOException $exception) {
    $_SESSION['frigobar_erros'] = $exception->getCode() === '23000'
        ? ['A acomodação já possui um frigobar ou a identificação informada já está em uso.']
        : ['Não foi possível cadastrar o frigobar.'];

    header('Location: /panaoica/cadastro/frigobar');
    exit;
}

unset($_SESSION['frigobar_acomodacao'], $_SESSION['frigobar_status'], $_SESSION['frigobar_identificacao'], $_SESSION['frigobar_erros']);

header('Location: /panaoica/cadastro/frigobar?sucesso=1');
exit;