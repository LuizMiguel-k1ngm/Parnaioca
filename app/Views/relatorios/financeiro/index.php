<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: /panaoica/login');
    exit;
}

require_once __DIR__ . '/../../../config/conn.php';

$inicio = $_GET['inicio'] ?? (new DateTimeImmutable('first day of this month'))->format('Y-m-d');
$fim = $_GET['fim'] ?? (new DateTimeImmutable())->format('Y-m-d');

$dataInicio = DateTimeImmutable::createFromFormat('!Y-m-d', $inicio);
$dataFim = DateTimeImmutable::createFromFormat('!Y-m-d', $fim);

if (!$dataInicio || !$dataFim || $dataInicio > $dataFim) {
    $inicio = (new DateTimeImmutable('first day of this month'))->format('Y-m-d');
    $fim = (new DateTimeImmutable())->format('Y-m-d');
    $dataInicio = DateTimeImmutable::createFromFormat('!Y-m-d', $inicio);
    $dataFim = DateTimeImmutable::createFromFormat('!Y-m-d', $fim);
}

$inicioDateTime = $inicio . ' 00:00:00';
$fimDateTime = $fim . ' 23:59:59';

$resumoStatement = $conn->prepare(
    "SELECT
        COALESCE(SUM(CASE WHEN s.codigo <> 'CANCELADA' THEN r.valor_total ELSE 0 END), 0) AS hospedagem,
        COUNT(CASE WHEN s.codigo <> 'CANCELADA' THEN r.id_reserva END) AS reservas,
        COALESCE(AVG(CASE WHEN s.codigo <> 'CANCELADA' THEN r.valor_total END), 0) AS ticket_medio
     FROM reserva r
     INNER JOIN status_reserva s ON s.id_status_reserva = r.id_status_reserva
     WHERE r.data_criacao BETWEEN :inicio AND :fim"
);
$resumoStatement->execute(['inicio' => $inicioDateTime, 'fim' => $fimDateTime]);
$resumo = $resumoStatement->fetch(PDO::FETCH_ASSOC);

$consumoStatement = $conn->prepare(
    "SELECT COALESCE(SUM(cf.total), 0) AS consumo, COUNT(cf.id_consumo) AS lancamentos
     FROM consumo_frigobar cf
     WHERE cf.data_consumo BETWEEN :inicio AND :fim"
);
$consumoStatement->execute(['inicio' => $inicioDateTime, 'fim' => $fimDateTime]);
$consumo = $consumoStatement->fetch(PDO::FETCH_ASSOC);

$receitaTotal = (float) $resumo['hospedagem'] + (float) $consumo['consumo'];

$receitasHospedagemStatement = $conn->prepare(
    "SELECT r.data_criacao, r.valor_total, c.nome AS cliente,
            a.numero_quarto, s.descricao AS status_descricao
     FROM reserva r
     INNER JOIN cliente c ON c.id_cliente = r.id_cliente
     INNER JOIN acomodacao a ON a.id_acomodacao = r.id_acomodacao
     INNER JOIN status_reserva s ON s.id_status_reserva = r.id_status_reserva
     WHERE r.data_criacao BETWEEN :inicio AND :fim
       AND s.codigo <> 'CANCELADA'
     ORDER BY r.data_criacao DESC
     LIMIT 8"
);
$receitasHospedagemStatement->execute(['inicio' => $inicioDateTime, 'fim' => $fimDateTime]);
$receitasHospedagem = $receitasHospedagemStatement->fetchAll(PDO::FETCH_ASSOC);

$receitasConsumoStatement = $conn->prepare(
    "SELECT cf.data_consumo, cf.quantidade, cf.valor_unitario_pago, cf.total,
            i.nome AS item, c.nome AS cliente, a.numero_quarto
     FROM consumo_frigobar cf
     INNER JOIN item i ON i.id_item = cf.id_item
     INNER JOIN reserva r ON r.id_reserva = cf.id_reserva
     INNER JOIN cliente c ON c.id_cliente = r.id_cliente
     INNER JOIN acomodacao a ON a.id_acomodacao = r.id_acomodacao
     WHERE cf.data_consumo BETWEEN :inicio AND :fim
     ORDER BY cf.data_consumo DESC
     LIMIT 8"
);
$receitasConsumoStatement->execute(['inicio' => $inicioDateTime, 'fim' => $fimDateTime]);
$receitasConsumo = $receitasConsumoStatement->fetchAll(PDO::FETCH_ASSOC);

$formatarMoeda = static fn (float $valor): string => 'R$ ' . number_format($valor, 2, ',', '.');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório financeiro | Parnaioca</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/panaoica/app/Assets/css/style.css" rel="stylesheet">

    <style>
        .financeiro-hero {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            color: #fff;
        }

        .financeiro-hero .icon-box,
        .metric-icon {
            width: 3rem;
            height: 3rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: .8rem;
        }

        .financeiro-hero .icon-box {
            background: rgba(255, 255, 255, .16);
            font-size: 1.45rem;
        }

        .metric-icon {
            background: #e8f5ee;
            color: #198754;
            font-size: 1.25rem;
        }

        .metric-icon.primary {
            background: #e7f1ff;
            color: #0d6efd;
        }

        .metric-icon.warning {
            background: #fff3cd;
            color: #997404;
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
                        <h1 class="h3 mb-1">Relatório financeiro</h1>
                        <p class="text-secondary mb-0">Acompanhe as receitas de hospedagem e consumo.</p>
                    </div>
                    <a href="/panaoica/inicio" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <form method="get" action="/panaoica/relatorios/financeiro" class="row g-3 align-items-end">
                            <div class="col-12 col-md-4">
                                <label for="inicio" class="form-label">Data inicial</label>
                                <input type="date" class="form-control" id="inicio" name="inicio" value="<?= htmlspecialchars($inicio, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="fim" class="form-label">Data final</label>
                                <input type="date" class="form-control" id="fim" name="fim" value="<?= htmlspecialchars($fim, ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel me-1"></i>Aplicar período
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card financeiro-hero border-0 shadow-sm mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="row align-items-center g-4">
                            <div class="col-lg-8">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <span class="icon-box"><i class="bi bi-cash-coin"></i></span>
                                    <span class="text-uppercase small fw-semibold opacity-75">Resultado do período</span>
                                </div>
                                <h2 class="h4 mb-2"><?= $formatarMoeda($receitaTotal) ?></h2>
                                <p class="mb-0 opacity-75">
                                    Receita registrada entre <?= $dataInicio->format('d/m/Y') ?> e <?= $dataFim->format('d/m/Y') ?>.
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end">
                                <span class="badge rounded-pill bg-white text-success px-3 py-2">
                                    <i class="bi bi-calendar-range me-1"></i>Período selecionado
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="metric-icon"><i class="bi bi-cash-stack"></i></span>
                                    <span class="small text-secondary">Total</span>
                                </div>
                                <div class="text-secondary small">Receita no período</div>
                                <strong class="fs-4"><?= $formatarMoeda($receitaTotal) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="metric-icon primary"><i class="bi bi-house-door"></i></span>
                                    <span class="small text-secondary">Hospedagem</span>
                                </div>
                                <div class="text-secondary small">Receita de reservas</div>
                                <strong class="fs-4"><?= $formatarMoeda((float) $resumo['hospedagem']) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="metric-icon warning"><i class="bi bi-cup-hot"></i></span>
                                    <span class="small text-secondary">Frigobar</span>
                                </div>
                                <div class="text-secondary small">Receita de consumo</div>
                                <strong class="fs-4"><?= $formatarMoeda((float) $consumo['consumo']) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="metric-icon primary"><i class="bi bi-receipt"></i></span>
                                    <span class="small text-secondary">Reservas</span>
                                </div>
                                <div class="text-secondary small">Ticket médio</div>
                                <strong class="fs-4"><?= $formatarMoeda((float) $resumo['ticket_medio']) ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-xl-7">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="h5 mb-1"><i class="bi bi-house-door me-2 text-primary"></i>Receitas de hospedagem</h2>
                                    <p class="text-secondary mb-0"><?= (int) $resumo['reservas'] ?> reserva(s) considerada(s).</p>
                                </div>
                                <span class="badge text-bg-primary"><?= $formatarMoeda((float) $resumo['hospedagem']) ?></span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="px-4">Hóspede</th>
                                            <th>Quarto</th>
                                            <th>Data</th>
                                            <th class="text-end px-4">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($receitasHospedagem)): ?>
                                            <tr><td colspan="4" class="text-center text-secondary py-5"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Nenhuma receita encontrada.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($receitasHospedagem as $receita): ?>
                                                <tr>
                                                    <td class="px-4 fw-semibold"><?= htmlspecialchars($receita['cliente'], ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td>Quarto <?= (int) $receita['numero_quarto'] ?></td>
                                                    <td><?= (new DateTimeImmutable($receita['data_criacao']))->format('d/m/Y') ?></td>
                                                    <td class="text-end px-4 fw-semibold"><?= $formatarMoeda((float) $receita['valor_total']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-5">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="h5 mb-1"><i class="bi bi-cup-hot me-2 text-success"></i>Consumos de frigobar</h2>
                                    <p class="text-secondary mb-0"><?= (int) $consumo['lancamentos'] ?> lançamento(s) no período.</p>
                                </div>
                                <span class="badge text-bg-success"><?= $formatarMoeda((float) $consumo['consumo']) ?></span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="px-4">Item</th>
                                            <th>Quarto</th>
                                            <th class="text-end px-4">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($receitasConsumo)): ?>
                                            <tr><td colspan="3" class="text-center text-secondary py-5"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Nenhum consumo encontrado.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($receitasConsumo as $receita): ?>
                                                <tr>
                                                    <td class="px-4"><?= htmlspecialchars($receita['item'], ENT_QUOTES, 'UTF-8') ?><small class="d-block text-secondary"><?= (int) $receita['quantidade'] ?> unidade(s)</small></td>
                                                    <td>Quarto <?= (int) $receita['numero_quarto'] ?></td>
                                                    <td class="text-end px-4 fw-semibold"><?= $formatarMoeda((float) $receita['total']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
