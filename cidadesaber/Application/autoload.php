<?php
// autoload.php

// Função para autoload das classes
function my_autoloader($class)
{
    $classPath = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php'; // Caminho baseado no namespace

    if (file_exists($classPath)) {
        require_once $classPath;
    } else {
        // Caso não encontre a classe
        echo "Classe $class não encontrada!";
    }
}

// Registrar a função de autoload
spl_autoload_register('my_autoloader');
?>
