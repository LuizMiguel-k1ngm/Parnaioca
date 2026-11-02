<?php

class Reserva
{
    private int $idReserva;
    private string $numeroReserva;
    private int $idCliente;
    private int $idEstacionamento;
    private int $idAcomodacao;
    private int $idStatusReserva;
    private string $status;
    private ?string $dataCheckin;
    private ?string $dataCheckout;
    private int $quantidadeClientes;
    private float $valorTotal;
    private ?string $dataCriacao;


    public function __construct(

        int $idReserva,
        string $numeroReserva,
        int $idCliente,
        int $idEstacionamento,
        int $idAcomodacao,
        int $idStatusReserva,
        string $status,
        ?string $dataCheckin,
        ?string $dataCheckout,
        int $quantidadeClientes,
        float $valorTotal,
        ?string $dataCriacao

    ) {
        $this->idReserva = $idReserva;
        $this->numeroReserva = $numeroReserva;
        $this->idCliente = $idCliente;
        $this->idEstacionamento = $idEstacionamento;
        $this->idAcomodacao = $idAcomodacao;
        $this->idStatusReserva = $idStatusReserva;
        $this->status = $status;
        $this->dataCheckin = $dataCheckin;
        $this->dataCheckout = $dataCheckout;
        $this->quantidadeClientes = $quantidadeClientes;
        $this->valorTotal = $valorTotal;
        $this->dataCriacao = $dataCriacao;
    }

    public function dados(): array
    {
        return [
            'id_reserva' => $this->idReserva,
            'numero_reserva' => $this->numeroReserva,
            'id_cliente' => $this->idCliente,
            'id_estacionamento' => $this->idEstacionamento,
            'id_acomodacao' => $this->idAcomodacao,
            'id_status_reserva' => $this->idStatusReserva,
            'status' => $this->status,
            'data_checkin' => $this->dataCheckin,
            'data_checkout' => $this->dataCheckout,
            'quantidade_clientes' => $this->quantidadeClientes,
            'valor_total' => $this->valorTotal,
            'data_criacao' => $this->dataCriacao
        ];
    }
}