<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de hóspedes | Parnaioca</title>

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
                        <h1 class="h3 mb-1">Cadastro de hóspedes</h1>
                        <p class="text-secondary mb-0">
                            Registre e consulte os hóspedes da pousada.
                        </p>
                    </div>

                    <a href="/panaoica/inicio" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Voltar
                    </a>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 p-4 pb-0">
                        <h2 class="h5 mb-1">
                            <i class="bi bi-person-plus me-2 text-primary"></i>
                            Novo hóspede
                        </h2>
                        <p class="text-secondary mb-0">
                            Preencha os dados abaixo para cadastrar um hóspede.
                        </p>
                    </div>

                    <div class="card-body p-4">
                        <form method="post" action="/panaoica/cadastro/hospedes">
                            <div class="row g-3">
                                <div class="col-12 col-md-8">
                                    <label for="nome" class="form-label">Nome completo</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nome"
                                        name="nome"
                                        placeholder="Digite o nome completo"
                                        required>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="cpf"
                                        name="cpf"
                                        placeholder="000.000.000-00"
                                        required>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="rg" class="form-label">RG</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="rg"
                                        name="rg"
                                        placeholder="Digite o RG">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="data_nascimento" class="form-label">
                                        Data de nascimento
                                    </label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="data_nascimento"
                                        name="data_nascimento">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input
                                        type="tel"
                                        class="form-control"
                                        id="telefone"
                                        name="telefone"
                                        placeholder="(00) 00000-0000"
                                        required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        placeholder="hospede@email.com">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="cep" class="form-label">CEP</label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="cep"
                                            name="cep"
                                            inputmode="numeric"
                                            maxlength="9"
                                            placeholder="00000-000">
                                        <button
                                            type="button"
                                            class="btn btn-outline-primary"
                                            id="buscarCep"
                                            title="Buscar endereço pelo CEP">
                                            <i class="bi bi-search me-1" aria-hidden="true"></i>
                                            Buscar CEP
                                        </button>
                                    </div>
                                    <div id="cepFeedback" class="form-text" role="status"></div>
                                </div>

                                <div class="col-12 col-md-8">
                                    <label for="endereco" class="form-label">Endereço</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="endereco"
                                        name="endereco"
                                        placeholder="Rua, avenida ou estrada">
                                </div>

                                <div class="col-12 col-md-4">
                                    <label for="numero" class="form-label">Número</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="numero"
                                        name="numero"
                                        placeholder="Número">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="cidade"
                                        name="cidade"
                                        placeholder="Digite a cidade">
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select" id="estado" name="estado">
                                        <option value="">Selecione</option>
                                        <option value="AC">AC</option>
                                        <option value="AL">AL</option>
                                        <option value="AP">AP</option>
                                        <option value="AM">AM</option>
                                        <option value="BA">BA</option>
                                        <option value="CE">CE</option>
                                        <option value="DF">DF</option>
                                        <option value="ES">ES</option>
                                        <option value="GO">GO</option>
                                        <option value="MA">MA</option>
                                        <option value="MT">MT</option>
                                        <option value="MS">MS</option>
                                        <option value="MG">MG</option>
                                        <option value="PA">PA</option>
                                        <option value="PB">PB</option>
                                        <option value="PR">PR</option>
                                        <option value="PE">PE</option>
                                        <option value="PI">PI</option>
                                        <option value="RJ">RJ</option>
                                        <option value="RN">RN</option>
                                        <option value="RS">RS</option>
                                        <option value="RO">RO</option>
                                        <option value="RR">RR</option>
                                        <option value="SC">SC</option>
                                        <option value="SP">SP</option>
                                        <option value="SE">SE</option>
                                        <option value="TO">TO</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-3">
                                    <label for="pais" class="form-label">País</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="pais"
                                        name="pais"
                                        value="Brasil">
                                </div>

                                <div class="col-12">
                                    <label for="observacoes" class="form-label">Observações</label>
                                    <textarea
                                        class="form-control"
                                        id="observacoes"
                                        name="observacoes"
                                        rows="3"
                                        placeholder="Informações adicionais"></textarea>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="reset" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                                    Limpar
                                </button>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-1"></i>
                                    Cadastrar hóspede
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 p-4">
                        <div class="d-flex justify-content-between align-items-center gap-3">
                            <h2 class="h5 mb-0">
                                <i class="bi bi-people me-2 text-primary"></i>
                                Hóspedes cadastrados
                            </h2>

                            <span class="badge text-bg-secondary">0 registros</span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4">Nome</th>
                                        <th>CPF</th>
                                        <th>Telefone</th>
                                        <th>E-mail</th>
                                        <th class="text-end px-4">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-5">
                                            <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                                            Nenhum hóspede cadastrado.
                                        </td>
                                    </tr>
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

        const cepInput = document.getElementById('cep');
        const buscarCepButton = document.getElementById('buscarCep');
        const cepFeedback = document.getElementById('cepFeedback');

        const formatarCep = (value) => {
            const digits = value.replace(/\D/g, '').slice(0, 8);
            return digits.length > 5
                ? `${digits.slice(0, 5)}-${digits.slice(5)}`
                : digits;
        };

        const buscarCep = async () => {
            const cep = cepInput.value.replace(/\D/g, '');

            cepInput.value = formatarCep(cep);

            if (cep.length !== 8) {
                cepFeedback.textContent = 'Digite um CEP válido com 8 dígitos.';
                cepFeedback.className = 'form-text text-danger';
                cepInput.focus();
                return;
            }

            buscarCepButton.disabled = true;
            cepFeedback.textContent = 'Buscando endereço...';
            cepFeedback.className = 'form-text text-secondary';

            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);

                if (!response.ok) {
                    throw new Error('Falha na consulta');
                }

                const data = await response.json();

                if (data.erro) {
                    throw new Error('CEP não encontrado');
                }

                document.getElementById('endereco').value = data.logradouro || '';
                document.getElementById('cidade').value = data.localidade || '';
                document.getElementById('estado').value = data.uf || '';

                cepFeedback.textContent = 'Endereço preenchido com sucesso.';
                cepFeedback.className = 'form-text text-success';
            } catch (error) {
                cepFeedback.textContent = error.message === 'CEP não encontrado'
                    ? error.message
                    : 'Não foi possível consultar o CEP. Tente novamente.';
                cepFeedback.className = 'form-text text-danger';
            } finally {
                buscarCepButton.disabled = false;
            }
        };

        cepInput.addEventListener('input', () => {
            cepInput.value = formatarCep(cepInput.value);
        });

        cepInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                buscarCep();
            }
        });

        buscarCepButton.addEventListener('click', buscarCep);

    </script>
</body>

</html>