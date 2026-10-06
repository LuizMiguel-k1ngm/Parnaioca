<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: /panaoica/login');
    exit;
}

require_once __DIR__ . '/../../../config/conn.php';

$funcionarios = $conn->query(
    "SELECT f.id_funcionario, f.nome, f.cpf, c.nome AS cargo,
            ul.id_usuario, ul.usuario, ul.status AS acesso_status
     FROM funcionario f
     INNER JOIN cargo c ON c.id_cargo = f.id_cargo
     LEFT JOIN usuario_login ul ON ul.id_funcionario = f.id_funcionario
     WHERE f.status = 'ativo'
     ORDER BY f.nome"
)->fetchAll(PDO::FETCH_ASSOC);

$erros = $_SESSION['acesso_erros'] ?? [];
$sucesso = isset($_GET['sucesso']);
unset($_SESSION['acesso_erros']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de acesso | Parnaioca</title>

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
                        Acesso cadastrado com sucesso.
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
                        <h1 class="h3 mb-1">Cadastro de acesso</h1>
                        <p class="text-secondary mb-0">Crie as credenciais para o funcionário acessar o sistema.</p>
                    </div>
                    <a href="/panaoica/inicio" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Voltar
                    </a>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 p-4 pb-0">
                        <h2 class="h5 mb-1">
                            <i class="bi bi-person-lock me-2 text-primary"></i>Novo acesso
                        </h2>
                        <p class="text-secondary mb-0">Vincule um usuário a um funcionário ativo.</p>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="/panaoica/cadastro/acesso">
                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <label for="id_funcionario" class="form-label">Funcionário</label>
                                    <select class="form-select" id="id_funcionario" name="id_funcionario" required>
                                        <option value="">Selecione o funcionário</option>
                                        <?php foreach ($funcionarios as $funcionario): ?>
                                            <?php if (empty($funcionario['id_usuario'])): ?>
                                                <option value="<?= (int) $funcionario['id_funcionario'] ?>">
                                                    <?= htmlspecialchars($funcionario['nome'], ENT_QUOTES, 'UTF-8') ?>
                                                    — <?= htmlspecialchars($funcionario['cargo'], ENT_QUOTES, 'UTF-8') ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="form-text">Somente funcionários sem acesso cadastrado aparecem aqui.</div>
                                </div>

                                <div class="col-12 col-lg-6">
                                    <label for="usuario" class="form-label">Nome de usuário</label>
                                    <input type="text" class="form-control" id="usuario" name="usuario"
                                        maxlength="100" placeholder="Ex.: joao.silva" autocomplete="username" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="senha" class="form-label">Senha</label>
                                    <input type="password" class="form-control" id="senha" name="senha"
                                        minlength="8" autocomplete="new-password" required>
                                    <div class="form-text">Use pelo menos 8 caracteres.</div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="senha_confirmacao" class="form-label">Confirmar senha</label>
                                    <input type="password" class="form-control" id="senha_confirmacao"
                                        name="senha_confirmacao" minlength="8" autocomplete="new-password" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Limpar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>Cadastrar acesso
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center gap-3">
                        <div>
                            <h2 class="h5 mb-1"><i class="bi bi-shield-check me-2 text-primary"></i>Acessos cadastrados</h2>
                            <p class="text-secondary mb-0">Confira os usuários vinculados à equipe.</p>
                        </div>
                        <span class="badge text-bg-secondary"><?= count(array_filter($funcionarios, static fn ($funcionario) => !empty($funcionario['id_usuario']))) ?> acessos</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">Funcionário</th>
                                    <th>Cargo</th>
                                    <th>Usuário</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $acessos = array_filter($funcionarios, static fn ($funcionario) => !empty($funcionario['id_usuario']));
                                ?>
                                <?php if (empty($acessos)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-5">
                                            <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                                            Nenhum acesso cadastrado.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($acessos as $funcionario): ?>
                                        <tr>
                                            <td class="px-4"><?= htmlspecialchars($funcionario['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($funcionario['cargo'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td><?= htmlspecialchars($funcionario['usuario'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td>
                                                <?php if ($funcionario['acesso_status'] === 'ativo'): ?>
                                                    <span class="badge text-bg-success">Ativo</span>
                                                <?php else: ?>
                                                    <span class="badge text-bg-secondary">Inativo</span>
                                                <?php endif; ?>
                                            </td>
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

        document.querySelector('form').addEventListener('submit', (event) => {
            const senha = document.getElementById('senha');
            const confirmacao = document.getElementById('senha_confirmacao');

            if (senha.value !== confirmacao.value) {
                confirmacao.setCustomValidity('As senhas precisam ser iguais.');
            } else {
                confirmacao.setCustomValidity('');
            }
        });
    </script>
</body>

</html>
