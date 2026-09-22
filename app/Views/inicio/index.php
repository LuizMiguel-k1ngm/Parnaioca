<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início | Parnaioca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="/panaoica/app/Assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <?php require __DIR__ . '/../include/navbar.php'; ?>

    <div class="d-flex">
        <?php require __DIR__ . '/../include/sidebar.php'; ?>

        <main class="main-content flex-grow-1 p-3 p-md-4">
            <div class="container-fluid">
                <div class="mb-4">
                    <h1 class="h3 mb-1">Início</h1>
                    <p class="text-secondary mb-0">Acompanhe as principais informações da pousada.</p>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-secondary small">Hóspedes</div>
                                <div class="display-6 fw-semibold">0</div>
                                <span class="text-secondary small">Cadastros realizados</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-secondary small">Quartos</div>
                                <div class="display-6 fw-semibold">0</div>
                                <span class="text-secondary small">Quartos cadastrados</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="text-secondary small">Reservas</div>
                                <div class="display-6 fw-semibold">0</div>
                                <span class="text-secondary small">Reservas em andamento</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="h5">Acesso rápido</h2>
                        <p class="text-secondary">Escolha uma opção no menu lateral para começar.</p>
                        <a class="btn btn-primary" href="/panaoica/cadastro">Novo cadastro</a>
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

        sidebarToggles.forEach((sidebarToggle) => sidebarToggle.addEventListener('click', () => {
            const isCollapsed = sidebarMenu.classList.toggle('sidebar-collapsed');
            sidebarToggles.forEach((toggle) => {
                toggle.setAttribute('aria-expanded', String(!isCollapsed));
                const icon = toggle.querySelector('i');
                if (icon) {
                    icon.classList.toggle('bi-chevron-left', !isCollapsed);
                    icon.classList.toggle('bi-chevron-right', isCollapsed);
                }
            });
        }));
    </script>
</body>

</html>