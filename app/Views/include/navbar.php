<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
	<div class="container-fluid">
		<button class="navbar-toggler me-2" type="button" id="navbarSidebarToggle"
			aria-controls="sidebarMenu" aria-expanded="true" aria-label="Alternar sidebar" title="Alternar sidebar">
			<i class="bi bi-layout-sidebar-inset"></i>
		</button>

		<a class="navbar-brand fw-semibold" href="/Parnaioca/inicio">Parnaioca</a>

		<div class="d-flex align-items-center ms-auto text-white">
			<span class="me-3">
				<i class="bi bi-person-circle me-1" aria-hidden="true"></i>
				Olá, <?= htmlspecialchars($_SESSION['usuario'] ?? 'usuário', ENT_QUOTES, 'UTF-8') ?>
			</span>
			<a class="btn btn-outline-light btn-sm" href="/Parnaioca/login" title="Sair">
				<i class="bi bi-box-arrow-right" aria-hidden="true"></i>
				<span class="d-none d-sm-inline">Sair</span>
			</a>
		</div>
	</div>
</nav>
