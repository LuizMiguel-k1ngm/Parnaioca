<aside
    class="sidebar bg-white border-end"
    id="sidebarMenu">
    <div class="d-flex flex-column p-3 h-100">

        <div class="d-flex justify-content-end mb-3">
            <button
                type="button"
                class="btn btn-light border sidebar-toggle"
                id="sidebarToggle"
                aria-expanded="true"
                aria-controls="sidebarMenu"
                aria-label="Expandir ou recolher menu"
                title="Expandir/recolher menu">
                <i class="bi bi-list" aria-hidden="true"></i>
            </button>
        </div>

        <div class="sidebar-label text-uppercase text-secondary small fw-semibold mb-2">
            Navegação
        </div>

        <nav class="nav nav-pills flex-column gap-1">
            <a class="nav-link link-dark" href="/panaoica/inicio" title="Início">
                <i class="bi bi-house-door me-2" aria-hidden="true"></i>
                <span class="sidebar-label">Início</span>
            </a>

            <div class="sidebar-group">
                <button class="nav-link link-dark sidebar-group-toggle" type="button" data-bs-toggle="collapse"
                    data-bs-target="#cadastroMenu" aria-expanded="true" aria-controls="cadastroMenu" title="Cadastro">
                    <i class="bi bi-person-plus me-2" aria-hidden="true"></i>
                    <span class="sidebar-label">Cadastro</span>
                    <i class="bi bi-chevron-down ms-auto sidebar-label" aria-hidden="true"></i>
                </button>
                <div class="collapse  sidebar-submenu" id="cadastroMenu">
                    <a class="nav-link link-dark" href="/panaoica/cadastro/hospede" title="Hóspede">
                        <i class="bi bi-person me-2" aria-hidden="true"></i><span class="sidebar-label">Hóspede</span>
                    </a>
                    <a class="nav-link link-dark" href="/panaoica/cadastro/quarto" title="Quarto">
                        <i class="bi bi-door-open me-2" aria-hidden="true"></i><span class="sidebar-label">Quarto</span>
                    </a>
                    <a class="nav-link link-dark" href="/panaoica/cadastro/frigobar" title="Frigobar">
                        <i class="bi bi-cup-hot me-2" aria-hidden="true"></i><span class="sidebar-label">Frigobar</span>
                    </a>
                    <a class="nav-link link-dark" href="/panaoica/cadastro/consumo" title="Consumo">
                        <i class="bi bi-cart-plus me-2" aria-hidden="true"></i><span class="sidebar-label">Consumo</span>
                    </a>
                    <a class="nav-link link-dark" href="/panaoica/cadastro/itens" title="Itens">
                        <i class="bi bi-box-seam me-2" aria-hidden="true"></i><span class="sidebar-label">Itens</span>
                    </a>
                    <a class="nav-link link-dark" href="/panaoica/cadastro/acesso" title="Acesso">
                        <i class="bi bi-key me-2" aria-hidden="true"></i><span class="sidebar-label">Acesso</span>
                    </a>
                </div>
            </div>

            <div class="sidebar-group">
                <button class="nav-link link-dark sidebar-group-toggle" type="button" data-bs-toggle="collapse"
                    data-bs-target="#relatoriosMenu" aria-expanded="true" aria-controls="relatoriosMenu" title="Relatórios">
                    <i class="bi bi-bar-chart-line me-2" aria-hidden="true"></i>
                    <span class="sidebar-label">Relatórios</span>
                    <i class="bi bi-chevron-down ms-auto sidebar-label" aria-hidden="true"></i>
                </button>
                <div class="collapse sidebar-submenu" id="relatoriosMenu">
                    <a class="nav-link link-dark" href="/panaoica/relatorios/financeiro" title="Financeiro">
                        <i class="bi bi-cash-coin me-2" aria-hidden="true"></i><span class="sidebar-label">Financeiro</span>
                    </a>
                    <a class="nav-link link-dark" href="/panaoica/relatorios/dashboard" title="Dashboard">
                        <i class="bi bi-speedometer2 me-2" aria-hidden="true"></i><span class="sidebar-label">Dashboard</span>
                    </a>
                </div>
            </div>

            <div class="sidebar-group">
                <button class="nav-link link-dark sidebar-group-toggle" type="button" data-bs-toggle="collapse"
                    data-bs-target="#estoqueMenu" aria-expanded="true" aria-controls="estoqueMenu" title="Estoque">
                    <i class="bi bi-boxes me-2" aria-hidden="true"></i>
                    <span class="sidebar-label">Estoque</span>
                    <i class="bi bi-chevron-down ms-auto sidebar-label" aria-hidden="true"></i>
                </button>
                <div class="collapse  sidebar-submenu" id="estoqueMenu">
                    <a class="nav-link link-dark" href="/panaoica/cadastro/produto" title="Produtos">
                        <i class="bi bi-box me-2" aria-hidden="true"></i><span class="sidebar-label">Produtos</span>
                    </a>
                    <a class="nav-link link-dark" href="/panaoica/cadastro/movimentacao" title="Movimentações">
                        <i class="bi bi-arrow-left-right me-2" aria-hidden="true"></i><span class="sidebar-label">Movimentações</span>
                    </a>
                </div>
            </div>
        </nav>

    </div>
</aside>