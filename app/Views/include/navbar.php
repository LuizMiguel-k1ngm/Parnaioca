<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
	<div class="container-fluid">
		<a class="navbar-brand navbar-logo" href="/panaoica/inicio" aria-label="Ir para o início">
			<img src="/panaoica/app/Assets/images/logo%20parnaioca.png" alt="Parnaioca Hotel & Resort">
		</a>

		<div class="d-flex align-items-center ms-auto navbar-user">
			<span class="me-3">
				<i class="bi bi-person-circle me-1" aria-hidden="true"></i>
				Olá, <?= htmlspecialchars($_SESSION['usuario'] ?? 'usuário', ENT_QUOTES, 'UTF-8') ?>
			</span>
			<a class="btn btn-outline-primary btn-sm" href="/panaoica/logout" title="Sair">
				<i class="bi bi-box-arrow-right" aria-hidden="true"></i>
				<span class="d-none d-sm-inline">Sair</span>
			</a>
		</div>
	</div>
</nav>