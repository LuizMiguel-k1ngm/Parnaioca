<div class="modal fade" id="modalExcluirHospede" tabindex="-1" aria-labelledby="modalExcluirHospedeLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="modalExcluirHospedeLabel">
                    <i class="bi bi-trash me-2 text-danger"></i>Excluir hóspede
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form method="post" action="/panaoica/cadastro/hospedes/excluir">
                <div class="modal-body">
                    <input type="hidden" name="acao" value="excluir">
                    <input type="hidden" name="id_cliente" id="excluir_id_cliente">
                    <p class="mb-0">Tem certeza que deseja excluir o hóspede
                        <strong id="excluir_nome_hospede"></strong>?</p>
                    <p class="text-secondary small mt-2 mb-0">
                        O hóspede ficará inativo e não aparecerá na lista de hóspedes ativos.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
