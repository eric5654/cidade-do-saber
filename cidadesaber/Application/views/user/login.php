<?php
session_start();

// Conectar ao banco de dados
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mvc', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    // Verificar se o usuário existe no banco de dados
    $stmt = $pdo->prepare("SELECT id, senha FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        // Login bem-sucedido
        $_SESSION['user_id'] = $user['id'];
        header('Location: ../home/Matricula/matricula.php');
        exit;
    } else {
        $error_message = "Email ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cidade do Saber - Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFFFFF;
            margin: 0;
            padding: 0;
        }
        
        .title {
            color: #547799;
            text-align: center;
            font-size: 2.5em;
            margin: 40px 0;
        }

        .form-container {
            background: #F8F8F8;
            padding: 40px;
            border-radius: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 1000px;
            margin: 120px auto;
        }

        .form-container h1 {
            color: #3C5D74;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 7px solid #3C5D74;
            padding-bottom: 20px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #3C5D74;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-group input {
            width: 90%;
            padding: 15px;
            border: none;
            border-radius: 30px;
            background-color: #E0EEEE;
            color: #3C5D74;
            font-size: 16px;
        }

        .form-row {
            display: flex;
            gap: 500px;
        }

        .btn-submit {
            width: 270px;
            padding: 15px;
            background-color: rgba(0, 167, 250, 0.76);
            border: 3px solid rgba(109, 109, 109, 0.25);
            color: #fff;
            border-radius: 35px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            margin: 40px auto;
        }

        .btn-submit:hover {
            background-color: rgba(0, 147, 220, 0.76);
        }

        .message {
            text-align: center;
            font-size: 16px;
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1 class="title">Cidade do Saber</h1>
    <div class="form-container">
        <h1>Login</h1>

        <?php if (!empty($error_message)): ?>
            <div class="message">
                <?= $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-row">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Insira o Email:" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" placeholder="Insira a senha" required>
            </div>
            <button type="submit" class="btn-submit">Continuar</button>
        </form>
    </div>
</body>
</html>
