<?php

namespace Application\Models;

use PDO;

class Curso
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCursos(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM curso");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function verificarMatricula(int $user_id, int $curso_id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM matriculas WHERE cod_aluno = ? AND cod_curso = ?");
        $stmt->execute([$user_id, $curso_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function matricular(int $user_id, int $curso_id, string $turno, string $status, string $observacoes, int $cod_turma): int
    {
        // Verifica se o aluno já está matriculado
        $matriculaExistente = $this->verificarMatricula($user_id, $curso_id);
        
        if ($matriculaExistente) {
            throw new Exception("Você já está matriculado neste curso.");
        }

        // Caso não esteja matriculado, realiza a inserção
        $stmt = $this->pdo->prepare("INSERT INTO matriculas (cod_aluno, cod_curso, turno, data_matricula, status, observacoes, cod_turma) 
                                     VALUES (?, ?, ?, NOW(), ?, ?, ?)");
        $stmt->execute([$user_id, $curso_id, $turno, $status, $observacoes, $cod_turma]);
        return $this->pdo->lastInsertId();
    }

    public function criarAlunoSeNaoExistir(int $user_id): void
    {
        // Verifica se o aluno já existe na tabela 'aluno'
        $stmt = $this->pdo->prepare("SELECT * FROM aluno WHERE cod_aluno = ?");
        $stmt->execute([$user_id]);
        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

        // Se o aluno não existir, insira-o na tabela 'aluno'
        if (!$aluno) {
            $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $name = $user['name']; 
            $email = $user['email'];
            $telefone_celular = $user['telefone_celular'];
            $cep = $user['cep'];

            $stmt = $this->pdo->prepare("INSERT INTO aluno (nome_aluno, email, telefone_celular, cep, user_id) 
                                        VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $telefone_celular, $cep, $user_id]);
        }
    }
}
?>
