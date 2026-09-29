<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: /panaoica/login');
    exit;
}

require_once __DIR__ . '/../../../config/conn.php';
require_once __DIR__ . '/../../../Repositories/QuartoRepository.php';

$quartosAtivos = (new QuartoRepository($conn))->buscarQuartosAtivos();


?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de quartos | Parnaioca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/panaoica/app/Assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/dt/dt-3.1.1/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/v/dt/dt-3.1.1/datatables.min.js"></script>
</head>
<body class="bg-light">
    <?php require __DIR__ . '/../../include/navbar.php'; ?>

    <div class="d-flex">
        <?php require __DIR__ . '/../../include/sidebar.php'; ?>

        <main class="main-content flex-grow-1 p-3 p-md-4">
            <div class="container-fluid">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <h1 class="h3 mb-1">Cadastro de quartos</h1>
                        <p class="text-secondary mb-0">Registre e consulte os quartos da pousada.</p>
                    </div>
                    <a href="/panaoica/inicio" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 p-4 pb-0">
                        <h2 class="h5 mb-1"><i class="bi bi-door-open me-2 text-primary"></i>Novo quarto</h2>
                        <p class="text-secondary mb-0">Preencha os dados da acomodação.</p>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="/panaoica/cadastro/quarto">
                            <div class="row g-3">
                                <div class="col-12 col-md-8">
                                    <label for="nome" class="form-label">Nome da acomodação</label>
                                    <input type="text" class="form-control" id="nome" name="nome" maxlength="250" placeholder="Ex.: Suíte Jardim" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="numero_quarto" class="form-label">Número do quarto</label>
                                    <input type="number" class="form-control" id="numero_quarto" name="numero_quarto" min="1" placeholder="Ex.: 101" required>
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="tipo_acomodacao" class="form-label">Tipo de acomodação</label>
                                    <select class="form-select" id="tipo_acomodacao" name="tipo_acomodacao" required>
                                        <option value="">Selecione</option>
                                        <option value="Quarto">Quarto</option>
                                        <option value="Suíte">Suíte</option>
                                        <option value="Chalé">Chalé</option>
                                        <option value="Apartamento">Apartamento</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label for="capacidade" class="form-label">Capacidade</label>
                                    <input type="number" class="form-control" id="capacidade" name="capacidade" min="1" placeholder="Hóspedes" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="valor_diaria" class="form-label">Valor da diária</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control" id="valor_diaria" name="valor_diaria" min="0" step="0.01" placeholder="0,00" required>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="ativo" selected>Ativo</option>
                                        <option value="inativo">Inativo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="reset" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise me-1"></i>Limpar</button>
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Cadastrar quarto</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 p-4">
                        <h2 class="h5 mb-0"><i class="bi bi-buildings me-2 text-primary"></i>Quartos cadastrados</h2>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="tabelaQuartos" class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-2">Nome</th>
                                        <th>Número</th>
                                        <th>Tipo</th>
                                        <th>Capacidade</th>
                                        <th>Diária</th>
                                        <th>Status</th>
                                        <th class="text-end px-4">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($quartosAtivos)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-secondary py-4">
                                                Nenhum quarto ativo encontrado.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($quartosAtivos as $quarto): ?>
                                            <tr>
                                                <td class="px-2">
                                                    <?= htmlspecialchars($quarto['nome'], ENT_QUOTES, 'UTF-8') ?>
                                                </td>
                                                <td><?= htmlspecialchars($quarto['numero_quarto'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars($quarto['tipo_acomodacao'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars($quarto['capacidade'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                                 <td><?= htmlspecialchars($quarto['valor_diaria'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                                  <td><?= htmlspecialchars($quarto['status'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                                <td class="text-end px-4">
                                                    <button class="badge text-bg-danger"><i class="bi bi-trash"></i></button>
                                                    <button class="badge text-bg-warning"><i class="bi bi-pencil-square"></i></button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        new DataTable('#tabelaQuartos', {
            paging: true,
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            language: {
                search: 'Buscar:',
                lengthMenu: 'Mostrar _MENU_ registros',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                infoEmpty: 'Nenhum registro encontrado',
                emptyTable: 'Nenhum quarto cadastrado.',
                zeroRecords: 'Nenhum quarto encontrado',
                paginate: { first: 'Primeiro', last: 'Último', next: 'Próximo', previous: 'Anterior' }
            }
        });

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
