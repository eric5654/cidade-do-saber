<?php

require_once __DIR__ . '/../../Core/Database.php';

use Application\Core\Database;

try {
    $db = Database::getInstance();
    echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}