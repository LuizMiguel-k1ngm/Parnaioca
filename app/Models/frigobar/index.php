<?php

class Frigobar
{
    private int $idAcomodacao;
    private int $idStatusFrigobar;
    private ?string $numeroIdentificacao;

    public function __construct(
        int $idAcomodacao,
        int $idStatusFrigobar,
        ?string $numeroIdentificacao
    ) {
        $this->idAcomodacao = $idAcomodacao;
        $this->idStatusFrigobar = $idStatusFrigobar;
        $this->numeroIdentificacao = $numeroIdentificacao;
    }

    public function dados(): array
    {
        return [
            'id_acomodacao' => $this->idAcomodacao,
            'id_status_frigobar' => $this->idStatusFrigobar,
            'numero_identificacao' => $this->numeroIdentificacao,
        ];
    }
}