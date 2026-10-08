<?php

require_once __DIR__ . '/../Models/frigobar/index.php';

class FrigobarRepository
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function buscarAcomodacoesAtivas(): array
    {
        $statement = $this->conn->query(
            "SELECT id_acomodacao, nome, numero_quarto
             FROM acomodacao
             WHERE status = 'ativo'
             ORDER BY numero_quarto"
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarStatusAtivos(): array
    {
        $statement = $this->conn->query(
            "SELECT id_status_frigobar, descricao
             FROM status_frigobar
             WHERE status = 'ativo'
             ORDER BY descricao"
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarFrigobares(): array
    {
        $statement = $this->conn->query(
            "SELECT f.id_acomodacao, f.numero_identificacao, f.status,
                    a.nome, a.numero_quarto, s.descricao AS status_descricao
             FROM frigobar f
             INNER JOIN acomodacao a ON a.id_acomodacao = f.id_acomodacao
             INNER JOIN status_frigobar s ON s.id_status_frigobar = f.id_status_frigobar
             ORDER BY a.numero_quarto"
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criarFrigobar(Frigobar $frigobar): void
    {
        $statement = $this->conn->prepare(
            'INSERT INTO frigobar (id_acomodacao, id_status_frigobar, numero_identificacao)
             VALUES (:id_acomodacao, :id_status_frigobar, :numero_identificacao)'
        );

        $dados = $frigobar->dados();
        $statement->execute([
            ':id_acomodacao' => $dados['id_acomodacao'],
            ':id_status_frigobar' => $dados['id_status_frigobar'],
            ':numero_identificacao' => $dados['numero_identificacao'],
        ]);
    }
}