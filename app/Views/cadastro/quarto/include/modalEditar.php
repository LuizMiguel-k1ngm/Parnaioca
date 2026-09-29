<div class="modal fade" id="modalEditarQuarto" tabindex="-1" aria-labelledby="modalEditarQuartoLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title fs-5" id="modalEditarQuartoLabel">
					<i class="bi bi-pencil-square me-2 text-primary"></i>Editar quarto
				</h2>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<form method="post" action="/panaoica/cadastro/quarto/editar">
				<div class="modal-body">
					<input type="hidden" name="acao" value="editar">
					<input type="hidden" name="id_acomodacao" id="editar_id_acomodacao">

					<div class="row g-3">
						<div class="col-12">
							<label for="editar_nome" class="form-label">Nome da acomodação</label>
							<input type="text" class="form-control" id="editar_nome" name="nome" maxlength="250">
						</div>
						<div class="col-6">
							<label for="editar_numero_quarto" class="form-label">Número</label>
							<input type="number" class="form-control" id="editar_numero_quarto" name="numero_quarto" min="1"  >
						</div>
						<div class="col-6">
							<label for="editar_capacidade" class="form-label">Capacidade</label>
							<input type="number" class="form-control" id="editar_capacidade" name="capacidade" min="1">
						</div>
						<div class="col-12">
							<label for="editar_tipo_acomodacao" class="form-label">Tipo de acomodação</label>
							<select class="form-select" id="editar_tipo_acomodacao" name="tipo_acomodacao" >
								<option value="Quarto">Quarto</option>
								<option value="Suíte">Suíte</option>
								<option value="Chalé">Chalé</option>
								<option value="Apartamento">Apartamento</option>
							</select>
						</div>
						<div class="col-6">
							<label for="editar_valor_diaria" class="form-label">Valor da diária</label>
							<div class="input-group">
								<span class="input-group-text">R$</span>
								<input type="number" class="form-control" id="editar_valor_diaria" name="valor_diaria" min="0" step="0.01" required>
							</div>
						</div>
						<div class="col-6">
							<label for="editar_status" class="form-label">Status</label>
							<select class="form-select" id="editar_status" name="status" required>
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