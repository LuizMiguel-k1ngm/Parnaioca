<?php
require_once __DIR__ . '/../../../config/seguranca.php';
require_once __DIR__ . '/../../../config/conn.php';

$clientes = $conn->query(
    "SELECT id_cliente, nome
     FROM cliente
     WHERE status = 'ativo'
     ORDER BY nome"
)->fetchAll(PDO::FETCH_ASSOC);

$acomodacoes = $conn->query(
    "SELECT id_acomodacao, nome, numero_quarto, tipo_acomodacao, capacidade, valor_diaria
     FROM acomodacao
     WHERE status = 'ativo'
     ORDER BY numero_quarto"
)->fetchAll(PDO::FETCH_ASSOC);

$statusReservas = $conn->query(
    "SELECT id_status_reserva, descricao
     FROM status_reserva
     WHERE status = 'ativo'
     ORDER BY descricao"
)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de reservas | Parnaioca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/panaoica/app/Assets/css/style.css" rel="stylesheet">
    <style>
        .reservation-summary {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: #fff;
        }

        .reservation-summary .summary-label {
            color: rgba(255, 255, 255, .75);
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .reservation-table th {
            color: #6c757d;
            font-size: .75rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .reservation-table td,
        .reservation-table th {
            padding: 1rem;
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
                        <div class="text-primary small fw-semibold text-uppercase mb-1">Operação</div>
                        <h1 class="h3 mb-1">Cadastro de reservas</h1>
                        <p class="text-secondary mb-0">Agende a hospedagem e acompanhe as reservas da pousada.</p>
                    </div>
                    <a href="/panaoica/inicio" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 p-4 pb-0">
                        <h2 class="h5 mb-1"><i class="bi bi-calendar-plus me-2 text-primary"></i>Nova reserva</h2>
                        <p class="text-secondary mb-0">Informe o hóspede, o período e a acomodação desejada.</p>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="/panaoica/cadastro/reserva">
                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <label for="id_cliente" class="form-label">Hóspede <span class="text-danger">*</span></label>
                                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                                        <option value="">Selecione o hóspede</option>
                                        <?php foreach ($clientes as $cliente): ?>
                                            <option value="<?= (int) $cliente['id_cliente'] ?>">
                                                <?= htmlspecialchars($cliente['nome'], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label for="id_acomodacao" class="form-label">Acomodação <span class="text-danger">*</span></label>
                                    <select class="form-select" id="id_acomodacao" name="id_acomodacao" required>
                                        <option value="">Selecione a acomodação</option>
                                        <?php foreach ($acomodacoes as $acomodacao): ?>
                                            <option value="<?= (int) $acomodacao['id_acomodacao'] ?>"
                                                data-diaria="<?= htmlspecialchars($acomodacao['valor_diaria'], ENT_QUOTES, 'UTF-8') ?>">
                                                Quarto <?= htmlspecialchars($acomodacao['numero_quarto'], ENT_QUOTES, 'UTF-8') ?>
                                                — <?= htmlspecialchars($acomodacao['nome'], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6 col-xl-3">
                                    <label for="data_checkin" class="form-label">Check-in <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="data_checkin" name="data_checkin" required>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label for="data_checkout" class="form-label">Check-out <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="data_checkout" name="data_checkout" required>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label for="quantidade_clientes" class="form-label">Quantidade de hóspedes <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="quantidade_clientes" name="quantidade_clientes"
                                        min="1" value="1" required>
                                </div>
                                <div class="col-12 col-md-6 col-xl-3">
                                    <label for="id_status_reserva" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="id_status_reserva" name="id_status_reserva" required>
                                        <option value="">Selecione</option>
                                        <?php foreach ($statusReservas as $status): ?>
                                            <option value="<?= (int) $status['id_status_reserva'] ?>">
                                                <?= htmlspecialchars($status['descricao'], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="reservation-summary rounded-3 p-3 mt-4">
                                <div class="row align-items-center g-3">
                                    <div class="col">
                                        <div class="summary-label">Resumo da reserva</div>
                                        <div id="reservationSummary" class="fw-semibold">Selecione as datas e a acomodação.</div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <div class="summary-label">Valor estimado</div>
                                        <div id="reservationTotal" class="fs-4 fw-bold">R$ 0,00</div>
                                        <input type="hidden" name="valor_total" id="valor_total" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Limpar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-calendar-check me-1"></i>Cadastrar reserva
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h2 class="h5 mb-1"><i class="bi bi-calendar3 me-2 text-primary"></i>Reservas cadastradas</h2>
                            <p class="text-secondary mb-0">Consulte as reservas e seus respectivos status.</p>
                        </div>
                        <div class="input-group" style="max-width: 280px;">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="search" class="form-control" placeholder="Buscar reserva" aria-label="Buscar reserva">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table reservation-table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Hóspede</th>
                                    <th>Acomodação</th>
                                    <th>Período</th>
                                    <th>Hóspedes</th>
                                    <th>Status</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="text-center text-secondary py-5">
                                        <i class="bi bi-calendar2-x fs-2 d-block mb-2"></i>
                                        Nenhuma reserva cadastrada.
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
        const sidebarToggle = document.getElementById('sidebarToggle');
        const navbarSidebarToggle = document.getElementById('navbarSidebarToggle');
        [sidebarToggle, navbarSidebarToggle].filter(Boolean).forEach((toggle) => {
            toggle.addEventListener('click', () => {
                const collapsed = sidebarMenu.classList.toggle('sidebar-collapsed');
                toggle.setAttribute('aria-expanded', String(!collapsed));
            });
        });

        const acomodacao = document.getElementById('id_acomodacao');
        const checkin = document.getElementById('data_checkin');
        const checkout = document.getElementById('data_checkout');
        const total = document.getElementById('reservationTotal');
        const totalInput = document.getElementById('valor_total');
        const summary = document.getElementById('reservationSummary');

        function atualizarResumo() {
            const inicio = new Date(`${checkin.value}T00:00:00`);
            const fim = new Date(`${checkout.value}T00:00:00`);
            const diaria = Number(acomodacao.selectedOptions[0]?.dataset.diaria || 0);
            const noites = (fim - inicio) / 86400000;
            const valor = noites > 0 ? noites * diaria : 0;

            total.textContent = valor.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
            totalInput.value = valor.toFixed(2);
            summary.textContent = noites > 0 ?
                `${noites} ${noites === 1 ? 'noite' : 'noites'} x ${diaria.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })} por diária` :
                'Selecione datas válidas e a acomodação.';
        }

        [acomodacao, checkin, checkout].forEach((field) => field.addEventListener('change', atualizarResumo));
    </script>
</body>

</html>