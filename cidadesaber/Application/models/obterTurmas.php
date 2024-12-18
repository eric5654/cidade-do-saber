<?php
namespace Application\Models;

class Turma {
    private $pdo;

    // Construtor para passar a conexão PDO
    public function __construct(\PDO $pdo) {  // Referencie a classe PDO corretamente
        $this->pdo = $pdo;
    }

    // Método para obter turmas por código de curso
    public function obterTurmasPorCurso($cod_curso) {
        $sql = 'SELECT * FROM turmas WHERE cod_curso = :cod_curso';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':cod_curso', $cod_curso, \PDO::PARAM_INT);  // Use a classe PDO corretamente
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);  // Use a constante PDO::FETCH_ASSOC corretamente
    }
}
