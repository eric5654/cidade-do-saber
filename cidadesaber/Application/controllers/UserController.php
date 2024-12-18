<?php

namespace Application\Controllers;

require_once 'Application/Models/User.php'; // Incluindo o modelo User

use Application\Models\User; // Importando o Model User

class UserController
{
    // Exibe o formulário de cadastro
    public function cadastro()
    {
        // Verifica se há mensagens de erro ou sucesso na sessão
        $error_message = $_SESSION['error'] ?? '';
        $success_message = $_SESSION['success'] ?? '';
        unset($_SESSION['error'], $_SESSION['success']); // Limpa a mensagem após exibir

        // Exibe a view de cadastro com mensagens
        require_once 'Application/views/user/cadastro.php';
    }

    // Processa os dados do formulário e realiza o cadastro
    public function save()
    {
        // 1. Captura os dados do formulário
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING) ?? null;
        $cep = $_POST['cep'] ?? null;
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) ?? null;
        $telefone = $_POST['telefone'] ?? null;
        $senha = $_POST['senha'] ?? null;
        $confirmarSenha = $_POST['confirmar-senha'] ?? null;

        // 2. Valida os dados
        if (empty($name) || empty($cep) || empty($email) || empty($telefone) || empty($senha) || empty($confirmarSenha)) {
            $_SESSION['error'] = "Todos os campos são obrigatórios!";
            header("Location: /user/cadastro");
            exit;
        }

        // Valida se as senhas coincidem
        if ($senha !== $confirmarSenha) {
            $_SESSION['error'] = "As senhas não coincidem!";
            header("Location: /user/cadastro");
            exit;
        }

        // 3. Criptografa a senha
        $hashedPassword = password_hash($senha, PASSWORD_BCRYPT);

        // 4. Cria uma instância do Model User
        $userModel = new User();

        // 5. Chama o método do Model para salvar o usuário
        $result = $userModel->cadastrarUsuario($name, $email, $hashedPassword, $cep, $telefone);

        // 6. Redireciona com mensagens de sucesso ou erro
        if ($result) {
            $_SESSION['success'] = "Cadastro realizado com sucesso!";
            header("Location: /user/matricula");
        } else {
            $_SESSION['error'] = "Erro ao salvar no banco de dados!";
            header("Location: /user/cadastro");
        }

        exit;
    }
}
