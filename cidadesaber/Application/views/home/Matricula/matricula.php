<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redireciona para a página de login se o usuário não estiver logado
    exit();
}

$user_id = $_SESSION['user_id']; // ID do usuário logado

// Conexão com o banco de dados
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mvc', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Verifica se o usuário existe na tabela 'user'
$stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Se o usuário não existir na tabela 'user', insira-o na tabela 'aluno'
if (!$user) {
    die("Usuário não encontrado.");
}

// Verifica se o aluno já existe na tabela 'aluno'
$stmt = $pdo->prepare("SELECT * FROM aluno WHERE cod_aluno = ?");
$stmt->execute([$user_id]);
$aluno = $stmt->fetch(PDO::FETCH_ASSOC);

// Se o aluno não existir, insira-o na tabela 'aluno'
if (!$aluno) {
    // Dados do aluno a partir da tabela 'user'
    $name = $user['name']; 
    $email = $user['email']; // Supondo que o email esteja na coluna 'email' da tabela 'user'
    $telefone_celular = $user['telefone_celular']; // Supondo que o telefone esteja na coluna 'telefone_celular'
    $cep = $user['cep']; // Supondo que o CEP esteja na coluna 'cep' da tabela 'user'
    $endereco = $user['endereco']; // Novo campo de endereço
    $cpf = $user['cpf']; // Novo campo de CPF

    try {
      $stmt = $pdo->prepare("INSERT INTO aluno (nome_aluno, email, telefone_celular, cep, endereco, cpf, user_id) 
      VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt->execute([$name, $email, $telefone_celular, $cep, $endereco, $cpf, $user_id]);
    } catch (PDOException $e) {
        die("Erro ao inserir aluno: " . $e->getMessage());
    }
}

// Obtém os cursos disponíveis e a quantidade de vagas
$stmt = $pdo->query("SELECT * FROM curso");
$cursos = $stmt->fetchAll(PDO::FETCH_OBJ);

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $curso_id = $_POST['cod_curso'];
    $turno = $_POST['turno'];

    // Verifica se o usuário já está matriculado neste curso
    $stmt = $pdo->prepare("SELECT * FROM matriculas WHERE cod_aluno = ? AND cod_curso = ?");
    $stmt->execute([$user_id, $curso_id]);
    $matriculaExistente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($matriculaExistente) {
        // Se já estiver matriculado, exibe uma mensagem de erro
        $error_message = "Você já está matriculado neste curso.";
    } else {
        // Verifica se ainda há vagas disponíveis para o curso
        $stmt = $pdo->prepare("SELECT vagas_disponiveis FROM curso WHERE cod_curso = ?");
        $stmt->execute([$curso_id]);
        $curso = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($curso && $curso['vagas_disponiveis'] > 0) {
            // Decrementa o número de vagas restantes
            $vagas_restantes = $curso['vagas_disponiveis'] - 1;

            // Atualiza o número de vagas restantes no banco de dados
            $update_vagas_stmt = $pdo->prepare("UPDATE curso SET vagas_disponiveis = ? WHERE cod_curso = ?");
            $update_vagas_stmt->execute([$vagas_restantes, $curso_id]);

            // Adiciona a matrícula na tabela 'matriculas'
            $status = 'Ativo'; // Status da matrícula
            $observacoes = ''; // Campo de observações (vazio por enquanto)
            $cod_turma = 1; // Código da turma, esse valor pode ser alterado conforme a lógica do seu sistema

            try {
                // Insere a matrícula na tabela 'matriculas'
                $stmt = $pdo->prepare("INSERT INTO matriculas (cod_aluno, cod_curso, turno, data_matricula, status, observacoes, cod_turma) 
                                        VALUES (?, ?, ?, NOW(), ?, ?, ?)");
                $stmt->execute([$user_id, $curso_id, $turno, $status, $observacoes, $cod_turma]);

                // Obtém o ID da matrícula inserida
                $matricula_id = $pdo->lastInsertId();

                // Redireciona para a página de comprovante de matrícula
                header("Location: comprovante_matricula.php?matricula_id=" . $matricula_id);
                exit();
            } catch (PDOException $e) {
                // Se houver um erro na inserção da matrícula, exibe a mensagem de erro
                $error_message = "Erro ao realizar matrícula: " . $e->getMessage();
            }
        } else {
            // Se não houver vagas, exibe a mensagem
            $error_message = "Não há vagas disponíveis neste curso.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Matrícula - Cidade do Saber</title>
  <style>
    /* Estilos gerais para o body */
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
      color: #333;
    }

    /* Cabeçalho */
    .header {
      background-color: #3d9dd9;
      color: white;
      text-align: center;
      padding: 20px 0;
    }

    .header h1 {
      font-size: 2.5em;
      margin: 0;
    }

    /* Container principal */
    .main-container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 80vh;
      padding: 2rem;
    }

    /* Cartão do Formulário */
    .card {
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 600px;
    }

    .card h2 {
      text-align: center;
      color: #3d9dd9;
      font-size: 2em;
      margin-bottom: 20px;
    }

    /* Estilo do formulário */
    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: bold;
      color: #333;
      margin-bottom: 5px;
    }

    .form-group select {
      width: 100%;
      padding: 10px;
      font-size: 1em;
      border-radius: 5px;
      border: 1px solid #ccc;
      background-color: #f9f9f9;
    }

    .form-group select:focus {
      border-color: #3d9dd9;
      background-color: #fff;
      outline: none;
    }

    /* Botão de submit */
    .form-group button {
      background-color: #3d9dd9;
      color: white;
      padding: 15px 20px;
      border: none;
      font-size: 1.1em;
      width: 100%;
      cursor: pointer;
      border-radius: 5px;
      transition: background-color 0.3s, transform 0.3s;
    }

    .form-group button:hover {
      background-color: #3173a3;
      transform: scale(1.05);
    }

    /* Estilo para mensagens de erro */
    .form-group .error-message {
      color: red;
      font-size: 0.9em;
      text-align: center;
    }

    /* Responsividade para dispositivos móveis */
    @media (max-width: 768px) {
      .header h1 {
        font-size: 2em;
      }

      .card {
        padding: 20px;
        width: 90%;
      }

      .card h2 {
        font-size: 1.8em;
      }

      .form-group select,
      .form-group button {
        font-size: 1em;
      }
    }
  </style>

</head>

<body>
  <div class="header">
    <h1>Cidade do Saber</h1>
  </div>
  <div class="main-container">
    <div class="card">
      <h2>Escolha seu Curso e Turno</h2>

      <?php if (isset($error_message)): ?>
        <div style="color: red;"><?= $error_message ?></div>
      <?php endif; ?>

      <form action="matricula.php" method="POST">
        <div class="form-group">
          <label>Curso:</label>
          <select name="cod_curso" id="curso-select" required>
            <option value="">Selecione o curso</option>
            <?php foreach ($cursos as $curso): ?>
              <option value="<?= $curso->cod_curso ?>"><?= $curso->nome_curso ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label>Turno:</label>
          <select name="turno" id="turno-select" required>
            <option value="">Selecione o turno</option>
            <option value="Manhã">Manhã</option>
            <option value="Tarde">Tarde</option>
            <option value="Noite">Noite</option>
          </select>
        </div>

        <div class="form-group">
          <button type="submit">Realizar Matrícula</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>