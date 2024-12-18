<?php
// Conexão com o banco de dados
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mvc', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// Obtém as matrículas, status, CPF, Endereço e a quantidade de vagas
$stmt = $pdo->prepare("SELECT m.id_matricula, a.nome_aluno, a.email, a.cpf, a.endereco, 
                              c.nome_curso AS curso_nome, c.vagas_disponiveis, m.status AS matricula_status, m.cod_aluno, m.cod_curso
                        FROM matriculas m
                        JOIN curso c ON m.cod_curso = c.cod_curso
                        JOIN aluno a ON m.cod_aluno = a.cod_aluno");
$stmt->execute();
$matriculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Processa a atualização do status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status']) && isset($_POST['id_matricula'])) {
    $new_status = $_POST['status'];
    $id_matricula = $_POST['id_matricula'];

    // Recupera o status atual e o código do curso
    $matricula_stmt = $pdo->prepare("SELECT cod_curso, status FROM matriculas WHERE id_matricula = ?");
    $matricula_stmt->execute([$id_matricula]);
    $matricula = $matricula_stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($matricula) {
        $cod_curso = $matricula['cod_curso'];
        $old_status = $matricula['status'];

        // Se o status for alterado de "Aprovado" para outro status, aumenta as vagas no curso
        if ($old_status == 'Aprovado' && $new_status != 'Aprovado') {
            // Recupera o número de vagas disponíveis antes de alterar o status
            $curso_stmt = $pdo->prepare("SELECT vagas_disponiveis FROM curso WHERE cod_curso = ?");
            $curso_stmt->execute([$cod_curso]);
            $curso = $curso_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($curso) {
                $vagas_atualizadas = $curso['vagas_disponiveis'] + 1;
                // Atualiza as vagas no curso
                $update_vagas_stmt = $pdo->prepare("UPDATE curso SET vagas_disponiveis = ? WHERE cod_curso = ?");
                $update_vagas_stmt->execute([$vagas_atualizadas, $cod_curso]);
            }
        } 

        // Se o status for alterado para "Aprovado", diminui as vagas no curso
        if ($new_status == 'Aprovado') {
            // Atualiza as vagas no curso, subtraindo 1
            $curso_stmt = $pdo->prepare("SELECT vagas_disponiveis FROM curso WHERE cod_curso = ?");
            $curso_stmt->execute([$cod_curso]);
            $curso = $curso_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($curso) {
                $vagas_atualizadas = $curso['vagas_disponiveis'] - 1;
                // Atualiza as vagas no curso
                $update_vagas_stmt = $pdo->prepare("UPDATE curso SET vagas_disponiveis = ? WHERE cod_curso = ?");
                $update_vagas_stmt->execute([$vagas_atualizadas, $cod_curso]);
            }
        }

        // Atualiza o status da matrícula na tabela 'matriculas'
        $update_stmt = $pdo->prepare("UPDATE matriculas SET status = ? WHERE id_matricula = ?");
        $update_stmt->execute([$new_status, $id_matricula]);

        // Redireciona para evitar reenvio de formulário
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status de Matrícula</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex overflow-hidden">
    <main class="flex-1 p-6 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Status das Matrículas</h1>
        </div>

        <!-- Tabela de Status de Matrículas -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <table class="w-full table-auto text-left">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-sm font-medium text-gray-600">ID Matrícula</th>
                            <th class="px-4 py-2 text-sm font-medium text-gray-600">Nome Aluno</th>
                            <th class="px-4 py-2 text-sm font-medium text-gray-600">Email</th>
                            <th class="px-4 py-2 text-sm font-medium text-gray-600">Curso</th>
                            <th class="px-4 py-2 text-sm font-medium text-gray-600">Quantidade de Vagas</th>
                            <th class="px-4 py-2 text-sm font-medium text-gray-600">CPF</th>
                            <th class="px-4 py-2 text-sm font-medium text-gray-600">Endereço</th>
                            <th class="px-4 py-2 text-sm font-medium text-gray-600">Status</th>
                            <th class="px-4 py-2 text-sm font-medium text-gray-600">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($matriculas) > 0) {
                            foreach ($matriculas as $matricula) {
                                echo "<tr class='border-b'>";
                                echo "<td class='px-4 py-2'>{$matricula['id_matricula']}</td>";
                                echo "<td class='px-4 py-2'>{$matricula['nome_aluno']}</td>";
                                echo "<td class='px-4 py-2'>{$matricula['email']}</td>";
                                echo "<td class='px-4 py-2'>{$matricula['curso_nome']}</td>";
                                echo "<td class='px-4 py-2'>{$matricula['vagas_disponiveis']}</td>";
                                echo "<td class='px-4 py-2'>{$matricula['cpf']}</td>";
                                echo "<td class='px-4 py-2'>{$matricula['endereco']}</td>";
                                echo "<td class='px-4 py-2'>";

                                switch ($matricula['matricula_status']) {
                                    case 'Aprovado':
                                        echo "<span class='text-green-500 font-semibold'>Aprovado</span>";
                                        break;
                                    case 'Andamento':
                                        echo "<span class='text-yellow-500 font-semibold'>Andamento</span>";
                                        break;
                                    case 'Recusado':
                                        echo "<span class='text-red-500 font-semibold'>Recusado</span>";
                                        break;
                                    default:
                                        echo "<span class='text-gray-500'>Não especificado</span>";
                                        break;
                                }

                                echo "</td>";

                                // Formulário para alterar o status
                                echo "<td class='px-4 py-2'>";
                                echo "<form method='POST' action='' class='flex items-center gap-2'>";
                                echo "<input type='hidden' name='id_matricula' value='{$matricula['id_matricula']}'>";
                                echo "<select name='status' class='bg-gray-200 p-2 rounded text-sm'>";
                                echo "<option value='Aprovado'" . ($matricula['matricula_status'] == 'Aprovado' ? ' selected' : '') . ">Aprovado</option>";
                                echo "<option value='Andamento'" . ($matricula['matricula_status'] == 'Andamento' ? ' selected' : '') . ">Andamento</option>";
                                echo "<option value='Recusado'" . ($matricula['matricula_status'] == 'Recusado' ? ' selected' : '') . ">Recusado</option>";
                                echo "</select>";
                                echo "<button type='submit' class='bg-blue-500 text-white p-2 rounded text-sm'>Atualizar</button>";
                                echo "</form>";
                                echo "</td>";

                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center py-4'>Nenhuma matrícula encontrada.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
