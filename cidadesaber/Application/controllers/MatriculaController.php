<?php
namespace Application\Controllers;

use Application\Models\Matricula;
use Application\Core\Database;

class MatriculaController
{
    private $pdo;
    private $matricula;

    public function __construct()
    {
        // Inicializa a conexão com o banco de dados
        $db = new Database();
        $this->pdo = $db->getConnection();

        // Instancia o modelo de Matrícula
        $this->matricula = new Matricula($this->pdo);
    }

    public function inscreverAluno($user_id, $curso_id, $turno)
    {
        // Verifica se o aluno já está matriculado no curso
        if ($this->matricula->verificarMatriculaExistente($user_id, $curso_id)) {
            return "Você já está matriculado neste curso.";
        }

        // Verifica se há vagas restantes
        $arquivo_vagas = 'vagas.txt';
        $vagas_restantes = $this->matricula->getVagasRestantes($arquivo_vagas);

        if ($vagas_restantes > 0) {
            // Decrementa as vagas e atualiza o arquivo
            $vagas_restantes--;
            $this->matricula->atualizarVagasRestantes($arquivo_vagas, $vagas_restantes);

            // Realiza a matrícula
            $status = 'Ativo';
            $observacoes = '';
            $cod_turma = 1; // Pode ser dinâmico conforme a lógica do seu sistema

            $this->matricula->matricularAluno($user_id, $curso_id, $turno, $status, $observacoes, $cod_turma);
            return "Você foi matriculado com sucesso! Vagas restantes: " . $vagas_restantes;
        } else {
            return "Não há vagas disponíveis neste curso.";
        }
    }
}
