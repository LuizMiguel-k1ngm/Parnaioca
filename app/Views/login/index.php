<?php
$error = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">

            <div class="col-12 col-sm-8 col-md-6 col-lg-4">

                <div class="card shadow border-0">
                    <div class="card-body p-4">

                        <h2 class="text-center mb-4">
                            Login
                        </h2>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/Parnaioca/app/controllers/UsuarioController.php">

                            <div class="mb-3">
                                <label for="usuario" class="form-label">
                                    Usuário
                                </label>

                                <input type="text" name="usuario" class="form-control" id="usuario"
                                    placeholder="Digite seu usuário" autocomplete="username" required>
                            </div>


                            <div class="mb-3">
                                <label for="senha" class="form-label">
                                    Senha
                                </label>

                                <input type="password" name="senha" class="form-control" id="senha"
                                    placeholder="Digite sua senha" autocomplete="current-password" required>
                            </div>


                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="lembrar">

                                <label class="form-check-label" for="lembrar">
                                    Lembrar-me
                                </label>
                            </div>


                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Entrar
                                </button>
                            </div>

                        </form>

                        <div class="text-center mt-3">
                            <a href="#" class="text-decoration-none">
                                Esqueci minha senha
                            </a>
                        </div>

                        <hr>

                        <p class="text-center mb-0">
                            Não possui uma conta?
                            <a href="#" class="text-decoration-none">
                                Criar conta
                            </a>
                        </p>

                    </div>
                </div>

            </div>

        </div>
    </div>

</body>

</html>