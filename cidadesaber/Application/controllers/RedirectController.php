<?php
// Application/controllers/RedirectController.php

class RedirectController {
    public function redirectToCadastro() {
        if (isset($_POST['matricule-se'])) {
            // Obtém o nome do curso enviado pelo formulário
            $curso = $_POST['curso'];

            // Redireciona para a página de cadastro, passando o curso via URL
            header("Location: ../views/user/cadastro.php?curso=" . urlencode($curso));
            exit(); // Encerra o script após o redirecionamento
        }
    }
}
?>
