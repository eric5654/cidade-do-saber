<?php
namespace Application\core;

 class Message implements ITriggerMessage
{
    /** @var string */
    private $message;

    /** @var string */
    private $type;

    /** @var string */
    private $lang; // Propriedade para armazenar o idioma

    // Array de traduções para diferentes tipos de mensagens
    private static $translations = [
        'en' => [
            ITriggerMessage::SUCCESS => 'Operation completed successfully!',
            ITriggerMessage::ERROR => 'An error occurred during the operation.',
            ITriggerMessage::WARNING => 'Warning: operation may have consequences.'
        ],
        'pt' => [
            ITriggerMessage::SUCCESS => 'Operação realizada com sucesso!',
            ITriggerMessage::ERROR => 'Erro ao realizar operação.',
            ITriggerMessage::WARNING => 'Aviso: operação pode ter consequências.'
        ],
        // Outros idiomas podem ser adicionados aqui
    ];

    /**
     * Construtor privado
     * @param string $type ITriggerMessage::SUCCESS, ITriggerMessage::ERROR, ITriggerMessage::WARNING
     * @param string $message Texto da mensagem
     * @param string $lang Idioma (por exemplo, "pt" ou "en")
     */
    private function __construct(string $type, string $message, string $lang = 'pt')
    {
        $this->type = $type;
        $this->message = $message;
        $this->lang = $lang;
    }

    /**
     * Retorna a mensagem traduzida
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Retorna o tipo da mensagem
     * @return string Success, Error, Warning
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Cria uma mensagem de sucesso
     * @param string $message
     * @param string $lang Idioma (default é "pt")
     * @return \Application\core\Message
     */
    public static function success(string $message, string $lang = 'pt'): Message
    {
        $translatedMessage = self::translate(ITriggerMessage::SUCCESS, $lang, $message);
        return new Message(ITriggerMessage::SUCCESS, $translatedMessage, $lang);
    }

    /**
     * Cria uma mensagem de erro
     * @param string $message
     * @param string $lang Idioma (default é "pt")
     * @return \Application\core\Message
     */
    public static function error(string $message, string $lang = 'pt'): Message
    {
        $translatedMessage = self::translate(ITriggerMessage::ERROR, $lang, $message);
        return new Message(ITriggerMessage::ERROR, $translatedMessage, $lang);
    }

    /**
     * Cria uma mensagem de aviso
     * @param string $message
     * @param string $lang Idioma (default é "pt")
     * @return \Application\core\Message
     */
    public static function warning(string $message, string $lang = 'pt'): Message
    {
        $translatedMessage = self::translate(ITriggerMessage::WARNING, $lang, $message);
        return new Message(ITriggerMessage::WARNING, $translatedMessage, $lang);
    }

    /**
     * Método de tradução que verifica a tradução no idioma desejado
     * @param string $type Tipo da mensagem (success, error, warning)
     * @param string $lang Idioma desejado (default "pt")
     * @param string $defaultMessage Mensagem padrão caso não haja tradução
     * @return string
     */
    private static function translate(string $type, string $lang, string $defaultMessage): string
    {
        // Verifica se existe tradução para o tipo e idioma
        if (isset(self::$translations[$lang][$type])) {
            return self::$translations[$lang][$type];
        }

        // Se não encontrar tradução, retorna a mensagem padrão
        return $defaultMessage;
    }
}
