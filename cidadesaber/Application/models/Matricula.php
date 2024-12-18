<?php
namespace Application\Models;





class Matricula
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Verifica se o aluno já está matriculado no curso
    public function verificarMatriculaExistente($cod_aluno, $cod_curso)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM matriculas WHERE cod_aluno = ? AND cod_curso = ?");
        $stmt->execute([$cod_aluno, $cod_curso]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Insere a matrícula na tabela matriculas
    public function matricularAluno($cod_aluno, $cod_curso, $turno, $status, $observacoes, $cod_turma)
    {
        $stmt = $this->pdo->prepare("INSERT INTO matriculas (cod_aluno, cod_curso, turno, data_matricula, status, observacoes, cod_turma) 
                                     VALUES (?, ?, ?, NOW(), ?, ?, ?)");
        return $stmt->execute([$cod_aluno, $cod_curso, $turno, $status, $observacoes, $cod_turma]);
    }

    // Lê o número de vagas restantes do arquivo
    // Lê o número de vagas restantes do banco de dados
public function getVagasRestantes($cod_curso)
{
    $stmt = $this->pdo->prepare("SELECT vagas_disponiveis FROM vagas WHERE cod_curso = ?");
    $stmt->execute([$cod_curso]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? (int) $result['vagas_disponiveis'] : 0;
}

// Atualiza o número de vagas restantes no banco de dados
public function atualizarVagasRestantes($cod_curso, $vagas_restantes)
{
    $stmt = $this->pdo->prepare("UPDATE vagas SET vagas_disponiveis = ? WHERE cod_curso = ?");
    $stmt->execute([$vagas_restantes, $cod_curso]);
}


    // Atualiza o número de vagas restantes no arquivo
}