<?php

namespace Application\Models;

class User
{
    // Método para cadastrar um novo usuário no banco
    public function cadastrarUsuario($name, $email, $hashedPassword, $cep, $telefone)
    {
        // Lógica para conectar ao banco de dados e salvar o usuário
        // Este é um exemplo fictício e você deve ajustar conforme o seu banco de dados

        // Supondo que a conexão já tenha sido estabelecida
        $db = new \PDO("mysql:host=localhost;dbname=cidade_do_saber", "root", "");

        $query = "INSERT INTO usuarios (nome, email, senha, cep, telefone) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        return $stmt->execute([$name, $email, $hashedPassword, $cep, $telefone]);
    }
}
