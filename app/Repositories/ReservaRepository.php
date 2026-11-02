<?php

require_once __DIR__ . '/../Models/reserva/index.php';

class ReservaRepository
{
    public function __construct(private PDO $conn) {}

    public function buscarReservasAtivas(string $numeroReserva): ?array
    {
        $statement = $this->conn->prepare(
            'SELECT id_reserva, numero_reserva, id_cliente, id_estacionamento,
                    id_acomodacao, id_status_reserva, status, data_checkin,
                    data_checkout, quantidade_clientes, valor_total, data_criacao
             FROM reserva
             WHERE numero_reserva = :numero_reserva
               AND status = :status'
        );

        $statement->execute([
            ':numero_reserva' => $numeroReserva,
            ':status' => 'ativo',
        ]);

        $reservaEncontrada = $statement->fetch(PDO::FETCH_ASSOC);

        return $reservaEncontrada ?: null;
    }
}