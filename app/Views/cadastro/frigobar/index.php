<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: /panaoica/login');
    exit;
}

require_once __DIR__ . '/../../../config/conn.php';
require_once __DIR__ . '/../../../Repositories/FrigobarRepository.php';

$repository = new FrigobarRepository($conn);
$acomodacoes = $repository->buscarAcomodacoesAtivas();
$statusFrigobar = $repository->buscarStatusAtivos();
$frigobares = $repository->buscarFrigobares();
$erros = $_SESSION['frigobar_erros'] ?? [];
$sucesso = isset($_GET['sucesso']);
$acomodacaoSelecionada = $_SESSION['frigobar_acomodacao'] ?? '';
$statusSelecionado = $_SESSION['frigobar_status'] ?? '';
$identificacao = $_SESSION['frigobar_identificacao'] ?? '';
unset($_SESSION['frigobar_erros'], $_SESSION['frigobar_acomodacao'], $_SESSION['frigobar_status'], $_SESSION['frigobar_identificacao']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de frigobares | Parnaioca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/panaoica/app/Assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php require __DIR__ . '/../../include/navbar.php'; ?>

    <div class="d-flex">
        <?php require __DIR__ . '/../../include/sidebar.php'; ?>

        <main class="main-content flex-grow-1 p-3 p-md-4">
            <div class="container-fluid">
                <?php if ($sucesso): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Frigobar cadastrado com sucesso.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>Confira os dados informados:</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($erros as $erro): ?>
                                <li><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <h1 class="h3 mb-1">Cadastro de frigobares</h1>
                        <p class="text-secondary mb-0">Associe um frigobar a cada acomodação da pousada.</p>
                    </div>
                    <a href="/panaoica/inicio" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 p-4 pb-0">
                        <h2 class="h5 mb-1"><i class="bi bi-cup-hot me-2 text-primary"></i>Novo frigobar</h2>
                        <p class="text-secondary mb-0">Informe a acomodação e a situação atual do equipamento.</p>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="/panaoica/cadastro/frigobar">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="id_acomodacao" class="form-label">Acomodação</label>
                                    <select class="form-select" id="id_acomodacao" name="id_acomodacao" required>
                                        <option value="">Selecione a acomodação</option>
                                        <?php foreach ($acomodacoes as $acomodacao): ?>
                                            <option value="<?= (int) $acomodacao['id_acomodacao'] ?>" <?= (string) $acomodacaoSelecionada === (string) $acomodacao['id_acomodacao'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($acomodacao['numero_quarto'] . ' - ' . $acomodacao['nome'], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="id_status_frigobar" class="form-label">Status do frigobar</label>
                                    <select class="form-select" id="id_status_frigobar" name="id_status_frigobar" required>
                                        <option value="">Selecione</option>
                                        <?php foreach ($statusFrigobar as $status): ?>
                                            <option value="<?= (int) $status['id_status_frigobar'] ?>" <?= (string) $statusSelecionado === (string) $status['id_status_frigobar'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($status['descricao'], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="numero_identificacao" class="form-label">Identificação</label>
                                    <input type="text" class="form-control" id="numero_identificacao" name="numero_identificacao" maxlength="50" value="<?= htmlspecialchars($identificacao, ENT_QUOTES, 'UTF-8') ?>" placeholder="Ex.: FR-101">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="reset" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i>Limpar</button>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Cadastrar frigobar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center gap-3">
                        <h2 class="h5 mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Frigobares cadastrados</h2>
                        <span class="badge text-bg-secondary"><?= count($frigobares) ?> registros</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Acomodação</th>
                                    <th>Número</th>
                                    <th>Identificação</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($frigobares)): ?>
                                    <tr><td colspan="4" class="text-center text-secondary py-4">Nenhum frigobar cadastrado.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($frigobares as $frigobar): ?>
                                        <tr>
                                            <td class="px-4"><?= htmlspecialchars($frigobar['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= (int) $frigobar['numero_quarto'] ?></td>
                                            <td><?= htmlspecialchars($frigobar['numero_identificacao'] ?: '-', ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><span class="badge <?= $frigobar['status'] === 'ativo' ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= htmlspecialchars($frigobar['status_descricao'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>