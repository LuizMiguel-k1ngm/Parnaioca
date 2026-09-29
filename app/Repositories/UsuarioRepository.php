<?php

require_once __DIR__ . '/../Models/cliente/index.php';

class UsuarioRepository
{
	public function __construct(private PDO $conn)
	{
	}

	public function buscarAtivoPorUsuario(string $usuario): ?array
	{
		$statement = $this->conn->prepare(
			'SELECT id_usuario, usuario, senha_hash, id_funcionario
			 FROM usuario_login
			 WHERE usuario = :usuario AND status = "ativo"'
		);
		$statement->execute(['usuario' => $usuario]);

		$usuarioEncontrado = $statement->fetch(PDO::FETCH_ASSOC);

		return $usuarioEncontrado ?: null;
	}

	public function criarHospede(Hospede $hospede): void
	{
		$sql = 'INSERT INTO cliente
			(
				nome,
				data_nascimento,
				cpf,
				email,
				telefone,
				cep,
				estado,
				cidade,
				rg,
				endereco,
				numero,
				pais,
				observacao
			)
			VALUES
			(
				:nome,
				:data_nascimento,
				:cpf,
				:email,
				:telefone,
				:cep,
				:estado,
				:cidade,
				:rg,
				:endereco,
				:numero,
				:pais,
				:observacao
			)';

		$dados = $hospede->dados();
		$statement = $this->conn->prepare($sql);
		$statement->execute([
			':nome' => $dados['nome'],
			':data_nascimento' => $dados['data_nascimento'],
			':cpf' => $dados['cpf'],
			':email' => $dados['email'],
			':telefone' => $dados['telefone'],
			':cep' => $dados['cep'],
			':estado' => $dados['estado'],
			':cidade' => $dados['cidade'],
			':rg' => $dados['rg'],
			':endereco' => $dados['endereco'],
			':numero' => $dados['numero'],
			':pais' => $dados['pais'],
			':observacao' => $dados['observacao'],
		]);
	}
}
