<?php

class Hospede
{
    private string $nome;
    private ?string $dataNascimento;
    private string $cpf;
    private ?string $email;
    private ?string $telefone;
    private ?string $cep;
    private ?string $estado;
    private ?string $cidade;
    private ?string $rg;
    private ?string $endereco;
    private ?int $numero;
    private ?string $pais;
    private ?string $observacao;

    public function __construct(
        string $nome,
        ?string $dataNascimento,
        string $cpf,
        ?string $email,
        ?string $telefone,
        ?string $cep,
        ?string $estado,
        ?string $cidade,
        ?string $rg,
        ?string $endereco,
        ?int $numero,
        ?string $pais,
        ?string $observacao
    ) {
        $this->nome = $nome;
        $this->dataNascimento = $dataNascimento;
        $this->cpf = $cpf;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->cep = $cep;
        $this->estado = $estado;
        $this->cidade = $cidade;
        $this->rg = $rg;
        $this->endereco = $endereco;
        $this->numero = $numero;
        $this->pais = $pais;
        $this->observacao = $observacao;
    }

    public function dados(): array
    {
        return [
            'nome' => $this->nome,
            'data_nascimento' => $this->dataNascimento,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'cep' => $this->cep,
            'estado' => $this->estado,
            'cidade' => $this->cidade,
            'rg' => $this->rg,
            'endereco' => $this->endereco,
            'numero' => $this->numero,
            'pais' => $this->pais,
            'observacao' => $this->observacao,
        ];
    }
}
