<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
	<div class="container-fluid">
		<button class="navbar-toggler me-2" type="button" data-bs-toggle="offcanvas"
			data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Abrir menu">
			<span class="navbar-toggler-icon"></span>
		</button>

		<a class="navbar-brand fw-semibold" href="/Parnaioca/inicio">Parnaioca</a>

		<div class="d-flex align-items-center ms-auto text-white">
			<span class="me-3">
				Olá, <?= htmlspecialchars($_SESSION['usuario'] ?? 'usuário', ENT_QUOTES, 'UTF-8') ?>
			</span>
			<a class="btn btn-outline-light btn-sm" href="/Parnaioca/login">Sair</a>
		</div>
	</div>
</nav>
