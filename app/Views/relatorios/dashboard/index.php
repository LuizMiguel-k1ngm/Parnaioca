<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: /panaoica/login');
    exit;
}

require_once __DIR__ . '/../../../config/conn.php';

$indicadores = [
    'hospedes' => (int) $conn->query("SELECT COUNT(*) FROM cliente WHERE status = 'ativo'")->fetchColumn(),
    'reservas' => (int) $conn->query(
        "SELECT COUNT(*)
         FROM reserva r
         INNER JOIN status_reserva s ON s.id_status_reserva = r.id_status_reserva
         WHERE s.codigo IN ('CONFIRMADA', 'CHECKIN', 'HOSPEDADO')"
    )->fetchColumn(),
    'acomodacoes' => (int) $conn->query("SELECT COUNT(*) FROM acomodacao WHERE status = 'ativo'")->fetchColumn(),
    'itens' => (int) $conn->query("SELECT COUNT(*) FROM item WHERE status = 'ativo'")->fetchColumn(),
    'estoque' => (int) $conn->query("SELECT COALESCE(SUM(quantidade_estoque), 0) FROM item WHERE status = 'ativo'")->fetchColumn(),
    'frigobares' => (int) $conn->query("SELECT COUNT(*) FROM frigobar WHERE status = 'ativo'")->fetchColumn(),
];

$reservasRecentes = $conn->query(
    "SELECT r.data_checkin, r.data_checkout, r.valor_total,
            c.nome AS cliente, a.numero_quarto, s.descricao AS status_descricao,
            s.codigo AS status_codigo
     FROM reserva r
     INNER JOIN cliente c ON c.id_cliente = r.id_cliente
     INNER JOIN acomodacao a ON a.id_acomodacao = r.id_acomodacao
     INNER JOIN status_reserva s ON s.id_status_reserva = r.id_status_reserva
     ORDER BY r.data_criacao DESC
     LIMIT 6"
)->fetchAll(PDO::FETCH_ASSOC);

$statusReservas = $conn->query(
    "SELECT s.descricao, s.codigo, COUNT(r.id_reserva) AS total
     FROM status_reserva s
     LEFT JOIN reserva r ON r.id_status_reserva = s.id_status_reserva
     WHERE s.status = 'ativo'
     GROUP BY s.id_status_reserva, s.descricao, s.codigo
     ORDER BY total DESC, s.descricao"
)->fetchAll(PDO::FETCH_ASSOC);

$totalReservas = array_sum(array_column($statusReservas, 'total'));
$dataAtual = (new DateTimeImmutable())->format('d/m/Y');

$statusClasses = [
    'PENDENTE' => 'warning',
    'CONFIRMADA' => 'primary',
    'CHECKIN' => 'info',
    'HOSPEDADO' => 'success',
    'CHECKOUT' => 'secondary',
    'CANCELADA' => 'danger',
    'FINALIZADA' => 'dark',
];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Parnaioca</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/panaoica/app/Assets/css/style.css" rel="stylesheet">

    <style>
        .dashboard-hero {
            background: linear-gradient(135deg, #0d6efd 0%, #084298 100%);
            color: #fff;
        }

        .dashboard-hero .icon-box,
        .metric-icon {
            width: 3rem;
            height: 3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .8rem;
        }

        .dashboard-hero .icon-box {
            background: rgba(255, 255, 255, .16);
            font-size: 1.45rem;
        }

        .metric-card {
            border: 0;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1) !important;
        }

        .metric-icon {
            background: #e7f1ff;
            color: #0d6efd;
            font-size: 1.25rem;
        }

        .metric-icon.success {
            background: #e8f5ee;
            color: #198754;
        }

        .metric-icon.warning {
            background: #fff3cd;
            color: #997404;
        }

        .status-bar {
            height: .5rem;
            border-radius: 999px;
        }
    </style>
</head>

<body class="bg-light">
    <?php require __DIR__ . '/../../include/navbar.php'; ?>

    <div class="d-flex">
        <?php require __DIR__ . '/../../include/sidebar.php'; ?>

        <main class="main-content flex-grow-1 p-3 p-md-4">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <h1 class="h3 mb-1">Dashboard</h1>
                        <p class="text-secondary mb-0">Visão geral da operação da pousada.</p>
                    </div>
                    <span class="badge rounded-pill text-bg-light border px-3 py-2">
                        <i class="bi bi-calendar3 me-1"></i>Atualizado em <?= $dataAtual ?>
                    </span>
                </div>

                <div class="card dashboard-hero border-0 shadow-sm mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <span class="icon-box"><i class="bi bi-speedometer2"></i></span>
                                    <span class="text-uppercase small fw-semibold opacity-75">Resumo operacional</span>
                                </div>
                                <h2 class="h4 mb-2">Acompanhe o que acontece hoje</h2>
                                <p class="mb-0 opacity-75">
                                    Consulte rapidamente a ocupação, as reservas e a situação do estoque.
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <a href="/panaoica/cadastro/movimentacao" class="btn btn-light text-primary">
                                    <i class="bi bi-arrow-left-right me-1"></i>Movimentar estoque
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card metric-card shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="metric-icon"><i class="bi bi-calendar-check"></i></span>
                                    <span class="small text-secondary">Ativas</span>
                                </div>
                                <div class="text-secondary small">Reservas em andamento</div>
                                <strong class="fs-3"><?= $indicadores['reservas'] ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card metric-card shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="metric-icon success"><i class="bi bi-people"></i></span>
                                    <span class="small text-secondary">Ativos</span>
                                </div>
                                <div class="text-secondary small">Hóspedes cadastrados</div>
                                <strong class="fs-3"><?= $indicadores['hospedes'] ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card metric-card shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="metric-icon warning"><i class="bi bi-door-open"></i></span>
                                    <span class="small text-secondary">Disponíveis</span>
                                </div>
                                <div class="text-secondary small">Acomodações cadastradas</div>
                                <strong class="fs-3"><?= $indicadores['acomodacoes'] ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card metric-card shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="metric-icon"><i class="bi bi-box-seam"></i></span>
                                    <span class="small text-secondary">No estoque</span>
                                </div>
                                <div class="text-secondary small">Unidades de itens</div>
                                <strong class="fs-3"><?= $indicadores['estoque'] ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-12 col-xl-8">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="h5 mb-1"><i class="bi bi-calendar3 me-2 text-primary"></i>Reservas recentes</h2>
                                    <p class="text-secondary mb-0">Últimas reservas registradas no sistema.</p>
                                </div>
                                <a href="/panaoica/cadastro/quarto" class="btn btn-sm btn-outline-primary">Acomodações</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="px-4">Hóspede</th>
                                            <th>Quarto</th>
                                            <th>Período</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($reservasRecentes)): ?>
                                            <tr>
                                                <td colspan="4" class="text-center text-secondary py-5">
                                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                                    Nenhuma reserva registrada.
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($reservasRecentes as $reserva): ?>
                                                <?php $statusClass = $statusClasses[$reserva['status_codigo']] ?? 'secondary'; ?>
                                                <tr>
                                                    <td class="px-4 fw-semibold"><?= htmlspecialchars($reserva['cliente'], ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td>Quarto <?= (int) $reserva['numero_quarto'] ?></td>
                                                    <td class="small">
                                                        <?= (new DateTimeImmutable($reserva['data_checkin']))->format('d/m/Y') ?>
                                                        a <?= (new DateTimeImmutable($reserva['data_checkout']))->format('d/m/Y') ?>
                                                    </td>
                                                    <td><span class="badge text-bg-<?= $statusClass ?>"><?= htmlspecialchars($reserva['status_descricao'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 p-4">
                                <h2 class="h5 mb-1"><i class="bi bi-pie-chart me-2 text-primary"></i>Status das reservas</h2>
                                <p class="text-secondary mb-0">Distribuição atual.</p>
                            </div>
                            <div class="card-body p-4">
                                <?php if (empty($statusReservas) || $totalReservas === 0): ?>
                                    <div class="text-center text-secondary py-4">
                                        <i class="bi bi-bar-chart fs-3 d-block mb-2"></i>
                                        Nenhuma reserva para exibir.
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($statusReservas as $status): ?>
                                        <?php $percentual = $totalReservas > 0 ? ($status['total'] / $totalReservas) * 100 : 0; ?>
                                        <?php $statusClass = $statusClasses[$status['codigo']] ?? 'secondary'; ?>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span><?= htmlspecialchars($status['descricao'], ENT_QUOTES, 'UTF-8') ?></span>
                                                <strong><?= (int) $status['total'] ?></strong>
                                            </div>
                                            <div class="progress status-bar">
                                                <div class="progress-bar bg-<?= $statusClass ?>" style="width: <?= $percentual ?>%" role="progressbar" aria-label="<?= htmlspecialchars($status['descricao'], ENT_QUOTES, 'UTF-8') ?>"></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <a href="/panaoica/cadastro/hospede" class="card border-0 shadow-sm text-decoration-none h-100">
                            <div class="card-body p-4 d-flex align-items-center gap-3">
                                <span class="metric-icon"><i class="bi bi-person-plus"></i></span>
                                <div><strong class="d-block text-dark">Cadastrar hóspede</strong><span class="small text-secondary">Adicionar novo cliente</span></div>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-md-4">
                        <a href="/panaoica/cadastro/frigobar" class="card border-0 shadow-sm text-decoration-none h-100">
                            <div class="card-body p-4 d-flex align-items-center gap-3">
                                <span class="metric-icon success"><i class="bi bi-cup-hot"></i></span>
                                <div><strong class="d-block text-dark">Gerenciar frigobares</strong><span class="small text-secondary"><?= $indicadores['frigobares'] ?> ativos</span></div>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-md-4">
                        <a href="/panaoica/cadastro/itens" class="card border-0 shadow-sm text-decoration-none h-100">
                            <div class="card-body p-4 d-flex align-items-center gap-3">
                                <span class="metric-icon warning"><i class="bi bi-box-seam"></i></span>
                                <div><strong class="d-block text-dark">Consultar itens</strong><span class="small text-secondary"><?= $indicadores['itens'] ?> itens ativos</span></div>
                            </div>
                        </a>
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
