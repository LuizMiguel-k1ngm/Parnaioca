<?php

require_once __DIR__ . '/../Models/quarto/index.php';

class UsuarioRepository
{
	public function __construct(private PDO $conn)
	{
	}

	public function buscarQuartosAtivos(): array
	{
		$statement = $this->conn->query(
			"SELECT id_acomodacao, nome, numero_quarto, capacidade, valor_diaria
			 FROM acomodacao
			 WHERE status = 'ativo'
			 ORDER BY numero_quarto"
		);

		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}

	public function criarQuarto(Quarto $quarto): void
	{
		$sql = 'INSERT INTO acomodacao
			(
				nome,
				numero_quarto,
				tipo_acomodacao,
				capacidade,
				valor_diaria,
				status,
				data_cadastro
                
			)
			VALUES
			(
				:nome,
				:numero_quarto,
				:tipo_acomodacao,
				:capacidade,
				:valor_diaria,
				:status,
				:data_cadastro
				
			)';

		$dados = $quarto->dados();
		$statement = $this->conn->prepare($sql);
		$statement->execute([
			':nome' => $dados['nome'],
			':numero_quarto' => $dados['numero_quarto'],
			':tipo_acomodacao' => $dados['tipo_acomodacao'],
			':capacidade' => $dados['capacidade'],
			':valor_diaria' => $dados['valor_diaria'],
			':status' => $dados['status'],
			':data_cadastro' => $dados['data_cadastro']
		]);
	}



}
