<?php
namespace Application\core;

class Database
{
    private $host = 'localhost';
    private $db = 'mvc';
    private $user = 'root';
    private $password = '';
    private $pdo;

    public function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db}";
        try {
            $this->pdo = new \PDO($dsn, $this->user, $this->password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
