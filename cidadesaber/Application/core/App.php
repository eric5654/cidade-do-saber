<?php

namespace Application\core;

use Exception;

/**
 * Esta classe é responsável por obter da URL o controlador, método (ação) e os parâmetros
 * e verificar a existência dos mesmos.
 */
class App
{
    protected $controller = 'HomeController'; // Controlador padrão
    protected $method = 'index';    // Método padrão
    protected $params = [];         // Parâmetros padrão
    protected $page404 = false;

    public function __construct()
    {
        try {
            $URL_ARRAY = $this->parseUrl();
            $this->getControllerFromUrl($URL_ARRAY);
            $this->getMethodFromUrl($URL_ARRAY);
            $this->getParamsFromUrl($URL_ARRAY);

            // Chama o método do controlador passando os parâmetros
            call_user_func_array([$this->controller, $this->method], $this->params);
        } catch (Exception $e) {
            // Caso algum erro ocorra, exibe uma mensagem de erro e encerra a execução.
            echo 'Erro: ' . $e->getMessage();
        }
    }

    /**
     * Este método pega as informações da URL (após o domínio do site) e retorna esses dados.
     * @return array
     */
    private function parseUrl(): array
    {
        $REQUEST_URI = explode('/', substr($_SERVER['REQUEST_URI'], 1));
        return array_filter($REQUEST_URI); // Remove itens vazios, como as barras finais
    }

    /**
     * Verifica e instancia o controlador com base na URL.
     * @param array $url
     */
    private function getControllerFromUrl($url): void
    {
        if (!empty($url[0])) {
            $controllerFile = '../Application/controllers/' . ucfirst($url[0]) . '.php';
            if (file_exists($controllerFile)) {
                $this->controller = ucfirst($url[0]);
            } else {
                throw new Exception('Controlador não encontrado.');
            }
        }

        require_once '../Application/controllers/' . $this->controller . '.php';
        $this->controller = '\\Application\\controllers\\' . $this->controller;
        $this->controller = new $this->controller();
    }

    /**
     * Verifica e define o método a ser chamado no controlador.
     * @param array $url
     */
    private function getMethodFromUrl($url): void
    {
        if (!empty($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
            } else {
                throw new Exception('Método não encontrado no controlador.');
            }
        }
    }

    /**
     * Define os parâmetros da URL, se houver.
     * @param array $url
     */
    private function getParamsFromUrl($url): void
    {
        if (count($url) > 2) {
            $this->params = array_slice($url, 2);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
            $this->params = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
            $this->params = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        }
    }

    /**
     * Gera a URL base da aplicação.
     * @param string $uri
     * @return string
     */
    public static function baseUrl($uri = ''): string {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); // Obtém o diretório base sem a barra final
        
        // Evita duplicação no início da URL
        $uri = ltrim($uri, '/');
        return $protocol . '://' . $host . '/' . $uri;
        
    }}   // Garante que a URL gerada não tenha duplicação
   
  
