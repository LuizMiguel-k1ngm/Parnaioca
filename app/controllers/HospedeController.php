<?php

require_once __DIR__ . '/../config/conn.php';
require_once __DIR__ . '/../Models/cliente/index.php';

$nome = trim($_POST['nome'] ?? '');
$cpf = trim($_POST['cpf'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$cep = trim($_POST['cep'] ?? '');
$dataNascimento = trim($_POST['data_nascimento'] ?? '');
$estado = trim($_POST['estado'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$rg = trim($_POST['rg'] ?? '');
$endereco = trim($_POST['endereco'] ?? '');
$numero = trim($_POST['numero'] ?? '');
$pais = trim($_POST['pais'] ?? '');
$observacao = trim($_POST['observacoes'] ?? '');

$erros = [];

if ($nome === '') {
    $erros[] = 'O nome é obrigatório.';
}

if ($cpf === '') {
    $erros[] = 'O CPF é obrigatório.';
} elseif (strlen(preg_replace('/\D/', '', $cpf)) !== 11) {
    $erros[] = 'O CPF deve conter 11 dígitos.';
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = 'O e-mail informado é inválido.';
}

if ($cep !== '' && strlen(preg_replace('/\D/', '', $cep)) !== 8) {
    $erros[] = 'O CEP deve conter 8 dígitos.';
}

if ($dataNascimento !== '') {
    $data = DateTime::createFromFormat('Y-m-d', $dataNascimento);

    if (!$data || $data->format('Y-m-d') !== $dataNascimento) {
        $erros[] = 'A data de nascimento é inválida.';
    }
}

if ($estado !== '' && strlen($estado) !== 2) {
    $erros[] = 'O estado deve possuir 2 caracteres.';
}

if ($numero !== '' && filter_var($numero, FILTER_VALIDATE_INT) === false) {
    $erros[] = 'O número do endereço deve ser inteiro.';
}

if (!empty($erros)) {
    $_SESSION['hospede_erros'] = $erros;
    header('Location: /panaoica/cadastro/hospede');
    exit;
}

$cpf = preg_replace('/\D/', '', $cpf);
$cep = preg_replace('/\D/', '', $cep);
$cep = $cep === '' ? null : substr($cep, 0, 5) . '-' . substr($cep, 5);
$numero = $numero === '' ? null : (int) $numero;
$dataNascimento = $dataNascimento === '' ? null : $dataNascimento;
$rg = $rg === '' ? null : $rg;
$endereco = $endereco === '' ? null : $endereco;
$pais = $pais === '' ? null : $pais;
$observacao = $observacao === '' ? null : $observacao;

try {
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    Hospede::criar($conn, [
        'nome' => $nome,
        'data_nascimento' => $dataNascimento,
        'cpf' => $cpf,
        'email' => $email === '' ? null : $email,
        'telefone' => $telefone === '' ? null : $telefone,
        'cep' => $cep,
        'estado' => $estado === '' ? null : strtoupper($estado),
        'cidade' => $cidade === '' ? null : $cidade,
        'rg' => $rg,
        'endereco' => $endereco,
        'numero' => $numero,
        'pais' => $pais,
        'observacao' => $observacao,
    ]);
} catch (PDOException $exception) {
    $_SESSION['hospede_erros'] = $exception->getCode() === '23000'
        ? ['Já existe um hóspede cadastrado com este CPF.']
        : ['Não foi possível cadastrar o hóspede.'];

    header('Location: /panaoica/cadastro/hospede');
    exit;
}

header('Location: /panaoica/cadastro/hospede?sucesso=1');
exit;