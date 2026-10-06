<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: /panaoica/login');
    exit;
}

require_once __DIR__ . '/../../../config/conn.php';

$itens = $conn->query(
    "SELECT id_item, nome, quantidade_estoque, valor
     FROM item
     WHERE status = 'ativo'
     ORDER BY nome"
)->fetchAll(PDO::FETCH_ASSOC);

$erros = $_SESSION['movimentacao_erros'] ?? [];
$sucesso = isset($_GET['sucesso']);
unset($_SESSION['movimentacao_erros']);

$estoqueTotal = array_sum(array_column($itens, 'quantidade_estoque'));
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimentação de estoque | Parnaioca</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/panaoica/app/Assets/css/style.css" rel="stylesheet">

    <style>
        .estoque-hero {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            color: #fff;
        }

        .estoque-hero .icon-box {
            width: 3.25rem;
            height: 3.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .85rem;
            background: rgba(255, 255, 255, .16);
            font-size: 1.6rem;
        }

        .summary-card {
            border-left: 3px solid #198754;
        }

        .summary-card .summary-icon {
            width: 2.5rem;
            height: 2.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .65rem;
            background: #e8f5ee;
            color: #198754;
        }
    </style>
</head>

<body class="bg-light">
    <?php require __DIR__ . '/../../include/navbar.php'; ?>

    <div class="d-flex">
        <?php require __DIR__ . '/../../include/sidebar.php'; ?>

        <main class="main-content flex-grow-1 p-3 p-md-4">
            <div class="container-fluid">
                <?php if ($sucesso): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Movimentação registrada com sucesso.
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
                        <h1 class="h3 mb-1">Movimentação de estoque</h1>
                        <p class="text-secondary mb-0">Registre entradas e saídas dos itens do estoque.</p>
                    </div>
                    <a href="/panaoica/inicio" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>
                </div>

                <div class="card estoque-hero border-0 shadow-sm mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <span class="icon-box"><i class="bi bi-arrow-left-right"></i></span>
                                    <span class="text-uppercase small fw-semibold opacity-75">Controle de estoque</span>
                                </div>
                                <h2 class="h4 mb-2">Atualize o saldo dos itens</h2>
                                <p class="mb-0 opacity-75">
                                    Lance reposições, perdas e saídas para manter o estoque sempre atualizado.
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <span class="badge rounded-pill bg-white text-success px-3 py-2">
                                    <i class="bi bi-boxes me-1"></i><?= count($itens) ?> itens ativos
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-12 col-xl-8">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 p-4 pb-0">
                                <h2 class="h5 mb-1">
                                    <i class="bi bi-plus-circle me-2 text-success"></i>Nova movimentação
                                </h2>
                                <p class="text-secondary mb-0">Informe o tipo e a quantidade movimentada.</p>
                            </div>
                            <div class="card-body p-4">
                                <form method="post" action="/panaoica/cadastro/movimentacao">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-7">
                                            <label for="id_item" class="form-label">Item</label>
                                            <select class="form-select" id="id_item" name="id_item" required>
                                                <option value="">Selecione o item</option>
                                                <?php foreach ($itens as $item): ?>
                                                    <option value="<?= (int) $item['id_item'] ?>">
                                                        <?= htmlspecialchars($item['nome'], ENT_QUOTES, 'UTF-8') ?>
                                                        — saldo: <?= (int) $item['quantidade_estoque'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-5">
                                            <label for="tipo" class="form-label">Tipo de movimentação</label>
                                            <select class="form-select" id="tipo" name="tipo" required>
                                                <option value="">Selecione</option>
                                                <option value="entrada">Entrada</option>
                                                <option value="saida">Saída</option>
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label for="quantidade" class="form-label">Quantidade</label>
                                            <input type="number" class="form-control" id="quantidade" name="quantidade"
                                                min="1" step="1" placeholder="Ex.: 10" required>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label for="valor_unitario" class="form-label">Valor unitário</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="valor_unitario"
                                                    name="valor_unitario" min="0" step="0.01" placeholder="0,00">
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label for="data_movimentacao" class="form-label">Data e hora</label>
                                            <input type="datetime-local" class="form-control" id="data_movimentacao"
                                                name="data_movimentacao">
                                        </div>

                                        <div class="col-12">
                                            <label for="observacao" class="form-label">Observação <span class="text-secondary fw-normal">(opcional)</span></label>
                                            <textarea class="form-control" id="observacao" name="observacao" rows="2"
                                                maxlength="255" placeholder="Ex.: Reposição recebida do fornecedor"></textarea>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="reset" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>Limpar
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check-lg me-1"></i>Registrar movimentação
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 p-4 pb-0">
                                <h2 class="h5 mb-1"><i class="bi bi-bar-chart-line me-2 text-success"></i>Resumo do estoque</h2>
                                <p class="text-secondary mb-0">Visão rápida dos itens cadastrados.</p>
                            </div>
                            <div class="card-body p-4 d-flex flex-column gap-3">
                                <div class="summary-card rounded bg-light p-3 d-flex align-items-center gap-3">
                                    <span class="summary-icon"><i class="bi bi-box-seam"></i></span>
                                    <div>
                                        <div class="small text-secondary">Itens ativos</div>
                                        <strong class="fs-5"><?= count($itens) ?></strong>
                                    </div>
                                </div>
                                <div class="summary-card rounded bg-light p-3 d-flex align-items-center gap-3">
                                    <span class="summary-icon"><i class="bi bi-stack"></i></span>
                                    <div>
                                        <div class="small text-secondary">Unidades em estoque</div>
                                        <strong class="fs-5"><?= $estoqueTotal ?></strong>
                                    </div>
                                </div>
                                <div class="alert alert-info border-0 mb-0 mt-auto">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Saídas não podem ultrapassar o saldo disponível do item.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h2 class="h5 mb-1"><i class="bi bi-clock-history me-2 text-success"></i>Últimas movimentações</h2>
                            <p class="text-secondary mb-0">Acompanhe as alterações recentes do estoque.</p>
                        </div>
                        <span class="badge text-bg-secondary">0 registros</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Item</th>
                                    <th>Tipo</th>
                                    <th>Quantidade</th>
                                    <th>Valor unitário</th>
                                    <th>Data</th>
                                    <th class="text-end px-4">Observação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-secondary py-5">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        Nenhuma movimentação registrada.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebarMenu = document.getElementById('sidebarMenu');
        const sidebarToggles = [
            document.getElementById('sidebarToggle'),
            document.getElementById('navbarSidebarToggle')
        ].filter(Boolean);

        sidebarToggles.forEach((sidebarToggle) => {
            sidebarToggle.addEventListener('click', () => {
                const isCollapsed = sidebarMenu.classList.toggle('sidebar-collapsed');

                sidebarToggles.forEach((toggle) => {
                    toggle.setAttribute('aria-expanded', String(!isCollapsed));
                    const icon = toggle.querySelector('i');

                    if (icon) {
                        icon.classList.toggle('bi-chevron-left', !isCollapsed);
                        icon.classList.toggle('bi-chevron-right', isCollapsed);
                    }
                });
            });
        });
    </script>
</body>

</html>
