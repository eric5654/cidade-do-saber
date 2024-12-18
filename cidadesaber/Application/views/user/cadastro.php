<?php
session_start();
$curso = isset($_GET['curso']) ? $_GET['curso'] : '';

// Conectar ao banco de dados
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mvc', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Variáveis de resposta
$error_message = "";
$success_message = "";

// Lógica de cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_GET['login'])) {
    // Sanitização dos dados do formulário
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $cep = $_POST['cep'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telefone_celular = filter_var($_POST['telefone_celular'], FILTER_SANITIZE_STRING); // Alterado para telefone_celular
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar-senha'];
    $endereco = filter_var($_POST['endereco'], FILTER_SANITIZE_STRING);
    $bairro = filter_var($_POST['bairro'], FILTER_SANITIZE_STRING);
    $cidade = filter_var($_POST['cidade'], FILTER_SANITIZE_STRING);
    $estado = filter_var($_POST['estado'], FILTER_SANITIZE_STRING);
    $cpf = $_POST['cpf'];

    // Validação dos campos
    if (empty($name) || empty($cep) || empty($email) || empty($telefone_celular) || empty($senha) || empty($confirmar_senha) || empty($endereco) || empty($bairro) || empty($cidade) || empty($estado) || empty($cpf)) {
        $error_message = "Todos os campos são obrigatórios.";
    }

    // Validação do formato do CPF (ex: 000.000.000-00)
    if (!preg_match("/^\d{3}\.\d{3}\.\d{3}-\d{2}$/", $cpf)) {
        $error_message = "CPF inválido. Use o formato 000.000.000-00.";
    }

    // Validação do formato do CEP (ex: 00000-000)
    if (!preg_match("/^\d{5}-\d{3}$/", $cep)) {
        $error_message = "CEP inválido. Use o formato 00000-000.";
    }

    // Validação do formato do telefone (ex: (XX) XXXXX-XXXX)
    if (!preg_match("/^\(\d{2}\) \d{5}-\d{4}$/", $telefone_celular)) {
        $error_message = "Telefone inválido. Use o formato (XX) XXXXX-XXXX.";
    }

    // Validação das senhas
    if ($senha !== $confirmar_senha) {
        $error_message = "As senhas não coincidem.";
    }

    // Verificar se o email já está cadastrado
    if (empty($error_message)) {
        $stmt = $pdo->prepare("SELECT id FROM user WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $error_message = "O email já está cadastrado.";
        }
    }

    // Se não houver erro, insere no banco
    if (empty($error_message)) {
        // Hash da senha para segurança
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir no banco de dados
        try {
            $sql = "INSERT INTO user (name, email, cep, telefone_celular, senha, endereco, bairro, cidade, estado, cpf) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $email, $cep, $telefone_celular, $senha_hash, $endereco, $bairro, $cidade, $estado, $cpf]);

            // Mensagem de sucesso
            $success_message = "Cadastro realizado com sucesso. Você será redirecionado para o login.";
            header("refresh:3;url=login.php"); // Redireciona após 3 segundos
            exit();
        } catch (PDOException $e) {
            $error_message = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cidade do Saber - Cadastro</title>
    <style>
        /* Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header.cabecalho {
            background-color: #3d9dd9;
            text-align: center;
            padding: 20px 0;
        }

        header .titulo {
            color: white;
            font-size: 2.5em;
            margin: 0;
        }

        /* Formulário de Cadastro */
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .form-container {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 30px;
            padding: 30px;
            max-width: 900px;
            width: 100%;
        }

        h1 {
            text-align: center;
            font-size: 2em;
            color: #3d9dd9;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
            width: 500px;
        }

        .form-group label {
            font-size: 1.1em;
            margin-bottom: 5px;
            display: block;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            border: 2px solid #ddd;
            border-radius: 5px;
            margin-top: 5px;
        }

        .form-group input:focus {
            border-color: #3d9dd9;
            outline: none;
        }

        .btn-submit {
            background-color: #3d9dd9;
            color: white;
            border: none;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 1.1em;
            border-radius: 5px;
            width: 100%;
            transition: background-color 0.3s, transform 0.3s;
            margin-top: 20px;
        }

        .btn-submit:hover {
            background-color: #3173a3;
            transform: scale(1.05);
        }

        /* Link de Login */
        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link p {
            font-size: 1.1em;
        }

        .login-link a {
            color: #3d9dd9;
            text-decoration: none;
            font-weight: bold;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Rodapé */
        footer {
            background-color: #3173a3;
            color: white;
            padding: 30px 0;
            text-align: center;
        }

        footer nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        footer nav ul li {
            display: inline-block;
        }

        footer nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.1em;
            transition: color 0.3s;
        }

        footer nav ul li a:hover {
            color: #3d9dd9;
        }

        .social-media {
            margin-top: 20px;
        }

        .social-media a {
            margin: 0 10px;
            text-decoration: none;
            color: white;
        }

        .social-media a img {
            width: 30px;
            height: 30px;
            transition: transform 0.3s;
        }

        .social-media a img:hover {
            transform: scale(1.1);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }

            .form-group {
                width: 100%;
            }

            .form-container {
                padding: 20px;
            }

            h1 {
                font-size: 1.8em;
            }

            .btn-submit {
                padding: 15px;
            }

            footer nav ul {
                flex-direction: column;
                gap: 10px;
            }

            footer nav ul li {
                text-align: center;
            }
        }
        </style>
</head>

<body>
    <header class="cabecalho">
        <h1 class="titulo">Cidade do Saber</h1>
    </header>

    <!-- Exibindo as mensagens de erro ou sucesso -->
    <?php if (!empty($error_message)): ?>
        <div class="message error">
            <?= $error_message; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="message success">
            <?= $success_message; ?>
        </div>
    <?php endif; ?>

    <main>
        <section class="form-container">
            <h1>Cadastro</h1>
            <form action="" method="post">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="name" placeholder="Insira o nome:" required>
                </div>
                <div class="form-group">
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" placeholder="Insira o CPF" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Insira o Email:" required>
                </div>
                <div class="form-group">
                    <label for="telefone_celular">Telefone:</label>
                    <input type="tel" id="telefone_celular" name="telefone_celular" placeholder="DDD+Telefone" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha:</label>
                    <input type="password" id="senha" name="senha" placeholder="Crie uma senha:" required>
                </div>
                <div class="form-group">
                    <label for="confirmar-senha">Confirmar Senha:</label>
                    <input type="password" id="confirmar-senha" name="confirmar-senha" placeholder="Confirme sua senha:" required>
                </div>
                
                <div class="form-group">
                    <label for="cep">CEP:</label>
                    <input type="text" id="cep" name="cep" placeholder="Insira o CEP:" required>
                </div>

                <!-- Novos campos de endereço -->
                <div class="form-group">
                    <label for="endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" placeholder="Insira o endereço" required>
                </div>

                <div class="form-group">
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" name="bairro" placeholder="Insira o bairro" required>
                </div>

                <div class="form-group">
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" name="cidade" placeholder="Insira a cidade" required>
                </div>

                <div class="form-group">
                    <label for="estado">Estado:</label>
                    <input type="text" id="estado" name="estado" placeholder="Insira o estado" required>
                </div>

                <button type="submit" class="btn-submit">Cadastrar</button>
            </form>
            <div class="login-link">
                <p>Já possui uma conta? <a href="login.php">Faça login</a></p>
            </div>
        </section>
    </main>

    <footer>
        <nav>
            <ul>
                <li><a href="#">Sobre</a></li>
                <li><a href="#">Contato</a></li>
                <li><a href="#">Ajuda</a></li>
            </ul>
        </nav>
        <div class="social-media">
            <a href="#"><img src="img/facebook.png" alt="Facebook"></a>
            <a href="#"><img src="img/twitter.png" alt="Twitter"></a>
            <a href="#"><img src="img/instagram.png" alt="Instagram"></a>
        </div>
    </footer>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>