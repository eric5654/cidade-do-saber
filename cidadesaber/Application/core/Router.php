<?php
namespace Application\Core;

class Router
{
    // Armazena as rotas da aplicação
    private $routes = [];

    // Registra uma rota
    public function addRoute($url, $controller, $method)
    {
        $this->routes[$url] = ['controller' => $controller, 'method' => $method];
    }

    // Processa a URL atual e executa a ação do controlador
    public function run()
    {
        // Obtém a URL da requisição
        $url = trim($_SERVER['REQUEST_URI'], '/'); // Remove barras extras

        // Verifica se a rota existe
        if (array_key_exists($url, $this->routes)) {
            $controllerName = $this->routes[$url]['controller'];
            $methodName = $this->routes[$url]['method'];

            // Cria uma instância do controlador
            $controller = new $controllerName();

            // Chama o método correspondente
            $controller->$methodName();
        } else {
            echo "Erro 404: Página não encontrada!";
        }
    }
}
