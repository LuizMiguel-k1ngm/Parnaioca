<?php

class Hospede
{
    public static function criar(PDO $conn, array $dados): void
    {
        $sql = 'INSERT INTO cliente
            (
                nome,
                data_nascimento,
                cpf,
                email,
                telefone,
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
                :estado,
                :cidade,
                :rg,
                :endereco,
                :numero,
                :pais,
                :observacao
            )';

        $statement = $conn->prepare($sql);
        $statement->execute([
            ':nome' => $dados['nome'],
            ':data_nascimento' => $dados['data_nascimento'],
            ':cpf' => $dados['cpf'],
            ':email' => $dados['email'],
            ':telefone' => $dados['telefone'],
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
