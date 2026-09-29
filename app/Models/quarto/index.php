<?php

class Quarto
{

//nome, numero_quarto, tipo_acomodacao, capacidade, valor_diaria, status, data_cadastro
    private string $nome;
    private ?string $numero_quarto;
    private string $tipo_acomodacao;
    private ?int $capacidade;
    private ?float $valor_diaria;
    private ?string $status;
    private ?string $data_cadastro;

    public function __construct(
        string $nome,
        ?string $numero_quarto,
        string $tipo_acomodacao,
        ?int $capacidade,
        ?float $valor_diaria,
        ?string $status,
        ?string $data_cadastro
    ) {
        $this->nome = $nome;
        $this->numero_quarto = $numero_quarto;
        $this->tipo_acomodacao = $tipo_acomodacao;
        $this->capacidade = $capacidade;
        $this->valor_diaria = $valor_diaria;
        $this->status = $status;
        $this->data_cadastro = $data_cadastro;
    }

    public function dados(): array
    {
        return [
            'nome' => $this->nome,
            'numero_quarto' => $this->numero_quarto,
            'tipo_acomodacao' => $this->tipo_acomodacao,
            'capacidade' => $this->capacidade,
            'valor_diaria' => $this->valor_diaria,
            'status' => $this->status,
            'data_cadastro' => $this->data_cadastro
        ];
    }
}
