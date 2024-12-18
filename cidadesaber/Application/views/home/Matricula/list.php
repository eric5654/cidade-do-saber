<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Matrículas</title>
    <style>
        /* Estilo básico para a tabela de matrículas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .header {
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Lista de Matrículas</h1>
        <p>Confira abaixo as matrículas realizadas pelo aluno.</p>
    </div>

    <?php if (empty($matriculas)) : ?>
        <p>Não há matrículas registradas para este aluno.</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Turma</th>
                    <th>Data da Matrícula</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matriculas as $matricula) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($matricula['curso']); ?></td>
                        <td><?php echo htmlspecialchars($matricula['turma']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($matricula['data_matricula'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
