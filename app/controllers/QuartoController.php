<?php

date_default_timezone_set('America/Sao_Paulo');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: /panaoica/login');
    exit;
}

require_once __DIR__ . '/../config/conn.php';
require_once __DIR__ . '/../Models/quarto/index.php';
require_once __DIR__ . '/../Repositories/QuartoRepository.php';

if (($_POST['acao'] ?? '') === 'editar') {
    $id = filter_var($_POST['id_acomodacao'] ?? null, FILTER_VALIDATE_INT);
    $nome = trim($_POST['nome'] ?? '');
    $numeroQuarto = trim($_POST['numero_quarto'] ?? '');
    $tipoAcomodacao = trim($_POST['tipo_acomodacao'] ?? '');
    $capacidade = trim($_POST['capacidade'] ?? '');
    $valorDiaria = trim($_POST['valor_diaria'] ?? '');
    $status = trim($_POST['status'] ?? '');

    $erros = [];

    if ($id === false || $id < 1) {
        $erros[] = 'Quarto inválido.';
    }

    if ($nome === '') {
        $erros[] = 'O nome da acomodação é obrigatório.';
    }

    if ($numeroQuarto === '' || filter_var($numeroQuarto, FILTER_VALIDATE_INT) === false || (int) $numeroQuarto < 1) {
        $erros[] = 'O número do quarto deve ser um inteiro maior que zero.';
    }

    if ($tipoAcomodacao === '') {
        $erros[] = 'O tipo da acomodação é obrigatório.';
    }

    if ($capacidade === '' || filter_var($capacidade, FILTER_VALIDATE_INT) === false || (int) $capacidade < 1) {
        $erros[] = 'A capacidade deve ser um inteiro maior que zero.';
    }

    if ($valorDiaria === '' || !is_numeric($valorDiaria) || (float) $valorDiaria < 0) {
        $erros[] = 'O valor da diária deve ser um número maior ou igual a zero.';
    }

    if (!in_array($status, ['ativo', 'inativo'], true)) {
        $erros[] = 'O status informado é inválido.';
    }

    if (!empty($erros)) {
        $_SESSION['quarto_erros'] = $erros;
        header('Location: /panaoica/cadastro/quarto');
        exit;
    }

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $quarto = new Quarto(
            $nome,
            (int) $numeroQuarto,
            $tipoAcomodacao,
            (int) $capacidade,
            (float) $valorDiaria,
            $status,
            null
        );

        (new QuartoRepository($conn))->atualizarQuarto($id, $quarto);
    } catch (PDOException $exception) {
        $_SESSION['quarto_erros'] = $exception->getCode() === '23000'
            ? ['Já existe outro quarto cadastrado com este número.']
            : ['Não foi possível atualizar o quarto.'];

        header('Location: /panaoica/cadastro/quarto');
        exit;
    }

    header('Location: /panaoica/cadastro/quarto?sucesso=1');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$numeroQuarto = trim($_POST['numero_quarto'] ?? '');
$tipoAcomodacao = trim($_POST['tipo_acomodacao'] ?? '');
$capacidade = trim($_POST['capacidade'] ?? '');
$valorDiaria = trim($_POST['valor_diaria'] ?? '');
$status = trim($_POST['status'] ?? '');

$_SESSION['quarto_nome'] = $nome;
$_SESSION['quarto_numero'] = $numeroQuarto;
$_SESSION['quarto_tipo'] = $tipoAcomodacao;
$_SESSION['quarto_capacidade'] = $capacidade;
$_SESSION['quarto_valor'] = $valorDiaria;
$_SESSION['quarto_status'] = $status;

$erros = [];

if ($nome === '') {
    $erros[] = 'O nome da acomodação é obrigatório.';
}

if ($numeroQuarto === '' || filter_var($numeroQuarto, FILTER_VALIDATE_INT) === false || (int) $numeroQuarto < 1) {
    $erros[] = 'O número do quarto deve ser um inteiro maior que zero.';
}

if ($tipoAcomodacao === '') {
    $erros[] = 'O tipo da acomodação é obrigatório.';
}

if ($capacidade === '' || filter_var($capacidade, FILTER_VALIDATE_INT) === false || (int) $capacidade < 1) {
    $erros[] = 'A capacidade deve ser um inteiro maior que zero.';
}

if ($valorDiaria === '' || !is_numeric($valorDiaria) || (float) $valorDiaria < 0) {
    $erros[] = 'O valor da diária deve ser um número maior ou igual a zero.';
}

if (!in_array($status, ['ativo', 'inativo'], true)) {
    $erros[] = 'O status informado é inválido.';
}

if (!empty($erros)) {
    $_SESSION['quarto_erros'] = $erros;
    header('Location: /panaoica/cadastro/quarto');
    exit;
}

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $quarto = new Quarto(
        $nome,
        (int) $numeroQuarto,
        $tipoAcomodacao,
        (int) $capacidade,
        (float) $valorDiaria,
        $status,
        date('Y-m-d H:i:s')
    );

    $repository = new QuartoRepository($conn);
    $repository->criarQuarto($quarto);
} catch (PDOException $exception) {
    $_SESSION['quarto_erros'] = $exception->getCode() === '23000'
        ? ['Já existe um quarto cadastrado com este número.']
        : ['Não foi possível cadastrar o quarto.'];

    header('Location: /panaoica/cadastro/quarto');
    exit;
}

unset(
    $_SESSION['quarto_nome'],
    $_SESSION['quarto_numero'],
    $_SESSION['quarto_tipo'],
    $_SESSION['quarto_capacidade'],
    $_SESSION['quarto_valor'],
    $_SESSION['quarto_status'],
    $_SESSION['quarto_erros']
);

header('Location: /panaoica/cadastro/quarto?sucesso=1');
exit;
