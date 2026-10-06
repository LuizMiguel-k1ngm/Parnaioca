<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: /panaoica/login');
    exit;
}

require_once __DIR__ . '/../../../config/conn.php';
require_once __DIR__ . '/../../../Repositories/UsuarioRepository.php';

$clientesAtivos = (new UsuarioRepository($conn))->buscarClientesAtivos();
$erros = $_SESSION['consumo_erros'] ?? [];
$sucesso = isset($_GET['sucesso']);
unset($_SESSION['consumo_erros']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumo do frigobar | Parnaioca</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/panaoica/app/Assets/css/style.css" rel="stylesheet">

    <style>
        .consumo-hero {
            background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
            color: #fff;
        }

        .consumo-hero .icon-box {
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
            border-left: 3px solid #0d6efd;
        }

        .summary-card .summary-icon {
            width: 2.5rem;
            height: 2.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .65rem;
            background: #e7f1ff;
            color: #0d6efd;
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
                        Consumo registrado com sucesso.
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
                        <h1 class="h3 mb-1">Consumo do frigobar</h1>
                        <p class="text-secondary mb-0">Registre os itens consumidos durante a hospedagem.</p>
                    </div>
                    <a href="/panaoica/inicio" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>
                </div>

                <div class="card consumo-hero border-0 shadow-sm mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <span class="icon-box"><i class="bi bi-cup-straw"></i></span>
                                    <span class="text-uppercase small fw-semibold opacity-75">Lançamento de consumo</span>
                                </div>
                                <h2 class="h4 mb-2">Controle rápido e preciso</h2>
                                <p class="mb-0 opacity-75">
                                    Selecione a reserva, informe o item retirado e registre o valor que será cobrado.
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <span class="badge rounded-pill bg-white text-primary px-3 py-2">
                                    <i class="bi bi-shield-check me-1"></i>Dados da hospedagem
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
                                    <i class="bi bi-plus-circle me-2 text-primary"></i>Novo consumo
                                </h2>
                                <p class="text-secondary mb-0">Preencha os dados do item consumido.</p>
                            </div>
                            <div class="card-body p-4">
                                <form method="post" action="/panaoica/cadastro/consumo">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="id_cliente" class="form-label">Hóspede / reserva</label>
                                            <select class="form-select" id="id_cliente" name="id_cliente" required>
                                                <option value="">Selecione o hóspede</option>
                                                <?php foreach ($clientesAtivos as $cliente): ?>
                                                    <option value="<?= (int) $cliente['id_cliente'] ?>">
                                                        <?= htmlspecialchars($cliente['nome'], ENT_QUOTES, 'UTF-8') ?>
                                                        — CPF <?= htmlspecialchars($cliente['cpf'], ENT_QUOTES, 'UTF-8') ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div class="form-text">A reserva vinculada será identificada pelo hóspede.</div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="id_frigobar" class="form-label">Frigobar</label>
                                            <select class="form-select" id="id_frigobar" name="id_frigobar" required>
                                                <option value="">Selecione o frigobar</option>
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="id_item" class="form-label">Item consumido</label>
                                            <select class="form-select" id="id_item" name="id_item" required>
                                                <option value="">Selecione o item</option>
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label for="quantidade" class="form-label">Quantidade</label>
                                            <input type="number" class="form-control" id="quantidade" name="quantidade" min="1" step="1" placeholder="Ex.: 1" required>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label for="valor_unitario_pago" class="form-label">Valor unitário</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" id="valor_unitario_pago" name="valor_unitario_pago" min="0" step="0.01" placeholder="0,00" required>
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4">
                                            <label for="data_consumo" class="form-label">Data e hora</label>
                                            <input type="datetime-local" class="form-control" id="data_consumo" name="data_consumo">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="reset" class="btn btn-outline-secondary">
                                            <i class=" me-1"></i>Limpar
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class=" me-1"></i>Registrar consumo
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 p-4 pb-0">
                                <h2 class="h5 mb-1"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Resumo</h2>
                                <p class="text-secondary mb-0">Acompanhe os lançamentos do dia.</p>
                            </div>
                            <div class="card-body p-4 d-flex flex-column gap-3">
                                <div class="summary-card rounded bg-light p-3 d-flex align-items-center gap-3">
                                    <span class="summary-icon"><i class="bi bi-receipt"></i></span>
                                    <div>
                                        <div class="small text-secondary">Lançamentos hoje</div>
                                        <strong class="fs-5">0</strong>
                                    </div>
                                </div>
                                <div class="summary-card rounded bg-light p-3 d-flex align-items-center gap-3">
                                    <span class="summary-icon"><i class="bi bi-cash-stack"></i></span>
                                    <div>
                                        <div class="small text-secondary">Total consumido hoje</div>
                                        <strong class="fs-5">R$ 0,00</strong>
                                    </div>
                                </div>
                                <div class="alert alert-info border-0 mb-0 mt-auto">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Confira a quantidade antes de confirmar o lançamento.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h2 class="h5 mb-1"><i class="bi bi-clock-history me-2 text-primary"></i>Consumos recentes</h2>
                            <p class="text-secondary mb-0">Últimos itens lançados nas reservas.</p>
                        </div>
                        <span class="badge text-bg-secondary">0 registros</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Hóspede</th>
                                    <th>Quarto</th>
                                    <th>Item</th>
                                    <th>Quantidade</th>
                                    <th>Data</th>
                                    <th class="text-end px-4">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-secondary py-5">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        Nenhum consumo registrado.
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
