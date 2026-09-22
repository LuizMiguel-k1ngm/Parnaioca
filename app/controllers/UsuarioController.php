<?php

session_start();
require_once dirname(__DIR__) . '/Config/conn.php';

$loginUrl = '/panaoica/login';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $loginUrl . '?msg=' . urlencode('Acesso não autorizado. Faça login.'));
    exit;
}

$usuario = trim($_POST['usuario'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($usuario === '' || $senha === '') {
    header('Location: ' . $loginUrl . '?msg=' . urlencode('Informe usuário e senha.'));
    exit;
}


try {
    $stmt = $conn->prepare(
        'SELECT id_usuario, usuario, senha_hash, id_funcionario
         FROM usuario_login
         WHERE usuario = :usuario AND status = "ativo" '
    );
    $stmt->execute(['usuario' => $usuario]);
    $usuarioEncontrado = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
    header('Location: ' . $loginUrl . '?msg=' . urlencode('Não foi possível realizar o login.'));
    exit;
}

if (!$usuarioEncontrado || !password_verify($senha, $usuarioEncontrado['senha_hash'])) {
    header('Location: ' . $loginUrl . '?msg=' . urlencode('Usuário ou senha incorretos.'));
    exit;
}

session_regenerate_id(true);

$_SESSION['usuario'] = $usuarioEncontrado['usuario'];
$_SESSION['id_usuario'] = $usuarioEncontrado['id_usuario'];
$_SESSION['id_funcionario'] = $usuarioEncontrado['id_funcionario'];
$_SESSION['tempo'] = time();

header('Location: /panaoica/inicio');
exit;
