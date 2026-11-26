<div class="modal fade" id="modalEditarHospede" tabindex="-1" aria-labelledby="modalEditarHospedeLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="modalEditarHospedeLabel">
                    <i class="bi bi-pencil-square me-2 text-primary"></i>Editar hóspede
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form method="post" action="/panaoica/cadastro/hospedes/editar">
                <div class="modal-body">
                    <input type="hidden" name="acao" value="editar">
                    <input type="hidden" name="id_cliente" id="editar_id_cliente">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label for="editar_nome_hospede" class="form-label">Nome completo</label>
                            <input type="text" class="form-control" id="editar_nome_hospede" name="nome" maxlength="250"
                                required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="editar_cpf_hospede" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="editar_cpf_hospede" name="cpf"
                                pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" maxlength="14" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="editar_rg_hospede" class="form-label">RG</label>
                            <input type="text" class="form-control" id="editar_rg_hospede" name="rg">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="editar_data_nascimento" class="form-label">Data de nascimento</label>
                            <input type="date" class="form-control" id="editar_data_nascimento" name="data_nascimento">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="editar_telefone_hospede" class="form-label">Telefone</label>
                            <input type="tel" class="form-control" id="editar_telefone_hospede" name="telefone">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="editar_email_hospede" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="editar_email_hospede" name="email">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="editar_cep_hospede" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="editar_cep_hospede" name="cep" maxlength="9">
                        </div>
                        <div class="col-12 col-md-8">
                            <label for="editar_endereco_hospede" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="editar_endereco_hospede" name="endereco">
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="editar_numero_hospede" class="form-label">Número</label>
                            <input type="number" class="form-control" id="editar_numero_hospede" name="numero" min="0">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="editar_cidade_hospede" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="editar_cidade_hospede" name="cidade">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="editar_estado_hospede" class="form-label">Estado</label>
                            <input type="text" class="form-control" id="editar_estado_hospede" name="estado"
                                maxlength="2">
                        </div>
                        <div class="col-12 col-md-3">
                            <label for="editar_pais_hospede" class="form-label">País</label>
                            <input type="text" class="form-control" id="editar_pais_hospede" name="pais">
                        </div>
                        <div class="col-12">
                            <label for="editar_observacoes_hospede" class="form-label">Observações</label>
                            <textarea class="form-control" id="editar_observacoes_hospede" name="observacoes"
                                rows="2"></textarea>
                        </div>
                        <div class="col-12 col-md-4">
                            <label for="editar_status_hospede" class="form-label">Status</label>
                            <select class="form-select" id="editar_status_hospede" name="status">
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i>Salvar alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
