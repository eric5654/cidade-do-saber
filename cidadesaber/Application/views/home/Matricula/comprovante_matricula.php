<?php
// Conectar ao banco de dados usando PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mvc', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Verificar se o ID da matrícula foi passado na URL
if (isset($_GET['matricula_id'])) {
    // Validar o ID da matrícula
    $matricula_id = filter_var($_GET['matricula_id'], FILTER_VALIDATE_INT);

    if (!$matricula_id) {
        $error_message = "ID de matrícula inválido.";
    } else {
        // Buscar as informações da matrícula no banco de dados
        try {
            // Consulta para buscar os dados da matrícula
            $stmt = $pdo->prepare("
                SELECT m.id_matricula, u.name, u.email, c.nome_curso, m.turno, m.data_matricula
                FROM matriculas m
                JOIN user u ON m.cod_aluno = u.id
                JOIN curso c ON m.cod_curso = c.cod_curso
                WHERE m.id_matricula = ?
            ");
            $stmt->execute([$matricula_id]);

            // Verificar se encontrou a matrícula
            if ($stmt->rowCount() > 0) {
                $matricula = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error_message = "Matrícula não encontrada.";
            }
        } catch (PDOException $e) {
            $error_message = "Erro ao buscar matrícula: " . $e->getMessage();
        }
    }
} else {
    $error_message = "ID da matrícula não especificado.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprovante de Matrícula</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .comprovante-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            width: 90%;
            max-width: 900px;
        }

        .comprovante-container h2 {
            text-align: center;
            color: #3d9dd9;
            font-size: 2.2em;
            margin-bottom: 20px;
        }

        .comprovante-container p {
            font-size: 1.1em;
            line-height: 1.6;
            margin: 10px 0;
        }

        .info {
            font-weight: bold;
            color: #3d9dd9;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .comprovante-container p span {
            color: #3d9dd9;
        }
    </style>

</head>

<body>

    <div class="comprovante-container">
        <h2>Comprovante de Matrícula</h2>

        <?php if (isset($error_message)): ?>
            <div class="error"><?= $error_message ?></div>
        <?php elseif (isset($matricula)): ?>
            <p><span class="info">Nome:</span> <?= htmlspecialchars($matricula['name']) ?></p>
            <p><span class="info">Email:</span> <?= htmlspecialchars($matricula['email']) ?></p>
            <p><span class="info">Curso:</span> <?= htmlspecialchars($matricula['nome_curso']) ?></p>
            <p><span class="info">Turno:</span> <?= htmlspecialchars($matricula['turno']) ?></p>
            <p><span class="info">Data da Matrícula:</span> <?= htmlspecialchars($matricula['data_matricula']) ?></p>
        <?php endif; ?>
    </div>

</body>

</html>