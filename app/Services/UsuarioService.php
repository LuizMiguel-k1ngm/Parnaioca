<?php

require_once __DIR__ . '/../Repositories/UsuarioRepository.php';

class UsuarioService
{
	public function __construct(private UsuarioRepository $usuarioRepository)
	{
	}

	public function autenticar(string $usuario, string $senha): ?array
	{
		if ($usuario === '' || $senha === '') {
			return null;
		}

		$usuarioEncontrado = $this->usuarioRepository->buscarAtivoPorUsuario($usuario);

		if (!$usuarioEncontrado || !password_verify($senha, $usuarioEncontrado['senha_hash'])) {
			return null;
		}

		return $usuarioEncontrado;
	}
}
