<div class="modal fade" id="modalExcluirQuarto" tabindex="-1" aria-labelledby="modalExcluirQuartoLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="modalExcluirQuartoLabel">
                    <i class="bi bi-trash me-2 text-danger"></i>Excluir quarto
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <form method="post" action="/panaoica/cadastro/quarto/excluir">
                <div class="modal-body">
                    <input type="hidden" name="acao" value="excluir">
                    <input type="hidden" name="id_acomodacao" id="excluir_id_acomodacao">
                    <p class="mb-0">
                        Tem certeza que deseja excluir o quarto
                        <strong id="excluir_nome_quarto"></strong>?
                    </p>
                    <p class="text-secondary small mt-2 mb-0">
                        O quarto ficará inativo e não aparecerá na lista de quartos disponíveis.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                    Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
