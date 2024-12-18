<?php

namespace Application\core;

/**
 * Esta classe é responsável por instanciar um model e chamar a view correta
 * passando os dados que serão usados.
 */
abstract class Controller
{
    
    /**
     * Este método é responsável por chamar uma determinada view (página).
     *
     * @param string $model O model que será instanciado para ser usado na view
     * @return object Instância do model
     */
    protected function model($model)
    {
        $classe = 'Application\\models\\' . $model;
        
        // Verifica se a classe do modelo existe
        if (class_exists($classe)) {
            return new $classe();
        } else {
            throw new \Exception("Model {$model} não encontrado.");
        }
    }

    /**
     * Este método é responsável por chamar uma determinada view (página).
     *
     * @param string $view A view que será chamada
     * @param array $data Os dados que serão passados para a view
     * @param string|null $message Mensagem opcional para ser exibida na view
     */
    protected function view(string $view, $data = [], $message = null)
    {
        $viewFile = '../Application/views/' . $view . '.php';
        
        // Verifica se o arquivo da view existe
        if (file_exists($viewFile)) {
            // Extrai os dados para a view
            extract($data);
            // Passa a mensagem, se houver
            $message ? $message : null;

            // Carrega a view
            require $viewFile;
        } else {
            throw new \Exception("View {$view} não encontrada.");
        }
    }

    /**
     * Este método é herdado por todas as classes filhas e é chamado quando
     * o controlador ou método informado pelo usuário não for encontrado.
     */
    public function pageNotFound()
    {
        $this->view('erro404');
    }
}
