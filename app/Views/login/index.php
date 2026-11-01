<?php
$error = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Entrar | Parnaioca</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --login-ink: #1d2925;
            --login-muted: #708078;
            --login-accent: #1f6b55;
            --login-accent-dark: #185543;
            --login-border: #dce5e0;
            --login-surface: #ffffff;
            --login-background: #f4f7f5;
        }

        body {
            min-height: 100vh;
            color: var(--login-ink);
            background:
                radial-gradient(circle at 12% 10%, rgba(196, 220, 207, 0.45), transparent 30rem),
                var(--login-background);
        }

        .login-shell {
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            padding: 2.75rem;
            border: 1px solid rgba(220, 229, 224, 0.9);
            border-radius: 1.25rem;
            background: var(--login-surface);
            box-shadow: 0 1.5rem 4rem rgba(39, 67, 55, 0.09);
        }

        .brand-logo {
            position: relative;
            width: 240px;
            height: 145px;
            margin: -1rem auto 0.5rem;
            overflow: hidden;
        }

        .brand-logo img {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 520px;
            max-width: none;
            transform: translate(-50%, -50%);
        }

        .login-title {
            margin-bottom: 0.5rem;
            font-size: 1.75rem;
            letter-spacing: -0.03em;
        }

        .login-subtitle {
            margin-bottom: 2rem;
            color: var(--login-muted);
            font-size: 0.95rem;
        }

        .form-label {
            margin-bottom: 0.5rem;
            color: var(--login-ink);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .form-control {
            min-height: 3rem;
            border: 1px solid var(--login-border);
            border-radius: 0.7rem;
            color: var(--login-ink);
            background-color: #fbfcfb;
        }

        .form-control::placeholder {
            color: #a4b0aa;
        }

        .form-control:focus {
            border-color: var(--login-accent);
            box-shadow: 0 0 0 0.2rem rgba(31, 107, 85, 0.12);
            background-color: #fff;
        }

        .login-options {
            color: var(--login-muted);
            font-size: 0.85rem;
        }

        .form-check-input {
            border-color: #bdcbc3;
        }

        .form-check-input:checked {
            border-color: var(--login-accent);
            background-color: var(--login-accent);
        }

        .btn-login {
            min-height: 3rem;
            border: 0;
            border-radius: 0.7rem;
            color: #fff;
            background-color: var(--login-accent);
            font-weight: 600;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .btn-login:hover,
        .btn-login:focus-visible {
            color: #fff;
            background-color: var(--login-accent-dark);
        }

        .btn-login:active {
            transform: translateY(1px);
        }

        .login-link {
            color: var(--login-accent);
            font-weight: 600;
        }

        .login-link:hover {
            color: var(--login-accent-dark);
        }

        .login-footer {
            color: var(--login-muted);
            font-size: 0.85rem;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.5rem;
                border-radius: 1rem;
            }
        }
    </style>
</head>

<body>

    <main class="login-shell d-flex align-items-center justify-content-center">
        <section class="login-card" aria-labelledby="login-title">
            <header>
                <div class="brand-logo">
                    <img src="/panaoica/app/Assets/images/logo%20parnaioca.png" alt="Parnaioca Hotel & Resort">
                </div>
                <h1 id="login-title" class="login-title mt-2">Bem-vindo de volta</h1>
                <p class="login-subtitle">Acesse o painel para continuar.</p>
            </header>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 px-3 mb-4" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/panaoica/login">

                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuário</label>

                    <input type="text" name="usuario" class="form-control" id="usuario"
                        placeholder="Digite seu usuário" autocomplete="username" required>
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>

                    <input type="password" name="senha" class="form-control" id="senha"
                        placeholder="Digite sua senha" autocomplete="current-password" required>
                </div>

                <div class="login-options d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="lembrar">

                        <label class="form-check-label" for="lembrar">Lembrar-me</label>
                    </div>

                    <a href="#" class="login-link text-decoration-none">Esqueci minha senha</a>
                </div>

                <button type="submit" class="btn btn-login w-100">Entrar</button>
            </form>

            <p class="login-footer text-center mt-4 mb-0">
                Não possui uma conta?
                <a href="#" class="login-link text-decoration-none">Criar conta</a>
            </p>
        </section>
    </main>

</body>

</html>