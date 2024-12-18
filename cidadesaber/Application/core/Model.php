<?php
namespace Application\core;

#####################
# Layer Super Type  #
#####################
// Padrão de design usado em programação orientada a objetos para encapsular comportamento comum em todo um conjunto de objetos.

/**
 * O modelo de dados representa a estrutura e o comportamento dos dados no sistema, 
 * será usado em classes para gerenciar a lógica de negócios e as operações de manipulação de dados.
 */
abstract class Model
{

    /**
     * Dados de manipulação da class 
     * @var object|null 
     * */
    protected $data;

    /** @var \PDOException|null */
    protected $fail;

    /** 
     * Mensagem de retorno para os usuários
     * @var \Application\core\Message|null 
     */
    protected $message;

    /*
     * Garantir o Stateless mantendo os atributos dinamicos como privados
     */
    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new \stdClass();
        }
        $this->data->$name = $value;
    }

    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /**
     * @return \stdClass|null
     */
    public function __get($name)
    {
        return($this->data->$name ?? null);
    }


    /**
     * Retorna o message para usuário
     * @return \Application\core\Message|null
     */
    public function Message(): ?\Application\core\Message
    {
        return $this->message;
    }

    /**
     * Returna as falhas de persistência para tratamento
     * @return \PDOException | null
     */
    public function Fail(): ?\PDOException
    {
        return $this->fail;
    }

    /**
     * Retorna dados de manipulação da class 
     * @return object|null 
     */
    public function Data(): ?object
    {
        return $this->data;
    }

    protected function create(string $entity, array $data): ?int
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
            $query = "INSERT INTO {$entity} ({$columns}) VALUES ({$values})";
            $stmt = Database::getInstance()->prepare($query);
            $stmt->execute($this->filter($data));

            return Database::getInstance()->lastInsertId();
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }
    /**
     * @param string $entity    Tabela do banco de dados
     * @param array  $data      Dado a ser atualizado na tabela
     * @param string $terms     Termos que determirá a condição de atualização
     * @param string $params    Parâmetros da condição (utilizado em parseString)
     */
    protected function update(string $entity, array $data, string $terms, string $params): ?int
    {
        try {
            $dataSet = [];
            foreach ($data as $bind => $value) {
                $dataSet[] = "{$bind} = :{$bind}";
            }
            $dataSet = implode(", ", $dataSet);

            $query = "UPDATE {$entity} SET {$dataSet} WHERE {$terms}";

            parse_str($params, $params);    //Na passagem da parametros, é necessário transforma-lo em um array associativo

            $stmt = Database::getInstance()->prepare($query);
            $stmt->execute($this->filter(array_merge($data, $params)));
            return($stmt->rowCount() ?? 1);

        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * @param string $entity    Tabela do banco de dados
     * @param string $terms     Termos que determirá a condição de deleção
     * @param string $params    Parâmetros da condição (utilizado em parseString)
     */
    protected function delete(string $entity, string $terms, string $params): ?int
    {
        try {
            $query = "DELETE FROM {$entity} WHERE {$terms}";
            //exit(var_dump($query));

            $stmt = Database::getInstance()->prepare($query);
            parse_str($params, $params);
            // var_dump($params);
            $stmt->execute($params);

            return($stmt->rowCount() ?? 1);

        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }

    }
    /**
     * @return \PDOException | null
     */
    protected function read(string $select, string $params = null): ?\PDOStatement
    {
        try {
            $stmt = Database::getInstance()->prepare($select);
            if ($params) {
                parse_str($params, $params);  //Se houve passagem da parametros, é necessário transforma-lo em um array associativo
                foreach ($params as $key => $value) {                    
                    $type = (is_numeric($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
                    $stmt->bindValue(":{$key}", $value, $type);
                }
            }
            $stmt->execute();
            return $stmt;
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            return null;
        }
    }

    /**
     * Garante que os campos que não podem ser manipulado não serão usados
     */
    protected function safe(): ?array
    {
        $safe = (array) $this->data;
        foreach (static::$safe as $unset) {
            unset($safe[$unset]);
        }
        return $safe;
    }
    /**
     * Realizar a manutenção do dados antes de realizar as manipulações
     */
    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS));

        }
        return $filter;
    }
}