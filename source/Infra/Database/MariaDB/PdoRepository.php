<?php

declare(strict_types=1);

namespace Source\Infra\Database\MariaDB;

use Exception;
use PDOException;
use PDO;
use Source\Framework\Support\Message;
use Source\Framework\Core\Connect;
use Source\Framework\Core\Transaction;

abstract class PdoRepository
{
    /** @var object|null */
    protected $data;

    /** @var PDOException|null */
    protected $fail;

    /** @var Message|null */
    protected $message;

    /** @var string */
    protected $query;

    /** @var array */
    protected $params;

    /** @var string */
    protected $entityAlias;
    
    /** @var array  */
    protected $join;

    /** @var string */
    protected $order;

    /** @var string */
    protected $group;

    /** @var int */
    protected $limit;

    /** @var int */
    protected $offset;

    /** @var string $entity database table */
    protected $entity;

    /** @var array $protected no update or create */
    protected $protected;

    /** @var array $entity database table */
    protected $required;

    /**
     * Model constructor.
     * @param string $entity database table name
     * @param array $protected table protected columns
     * @param array $required table required columns
     */
    public function __construct(string $entity, array $protected, array $required)
    {
        $this->entity = $entity;
        $this->protected = $protected;
        $this->protected = array_merge($protected, ['created_at', "updated_at"]);
        $this->required = $required;
        $this->message = new Message();
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new \stdClass();
        }

        $this->data->$name = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /**
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        return ($this->data->$name ?? null);
    }

    /**
     * @return null|object
     */
    public function data(): ?object
    {
        return $this->data;
    }

    /**
     * @return PDOException
     */
    public function fail(): ?PDOException
    {
        return $this->fail;
    }

    /**
     * @return Message|null
     */
    public function message(): ?Message
    {
        return $this->message;
    }

    /**
     * Cria um alias para a tabela principal da query
     * 
     * @param string $entityAlias
    */
    public function entityAlias(string $alias): PdoRepository
    {
        $this->entityAlias = $alias;
        return $this;
    }

    /**
     * @param string $type
     * @param string $alias
     * @param object $class
     * @param string $term
    */
    public function join(string $type = "INNER JOIN", string $alias, object $class, string $term): PdoRepository
    {
        $this->join[] = "{$type} {$class->entity} {$alias} ON {$term}";
        return $this;
    }

    /**
     * @param null|string $terms
     * @param null|string $params
     * @param string $columns
     * @return PdoRepository|mixed
     */
    public function find(?string $terms = null, ?string $params = null, string $columns = "*"): PdoRepository
    {
        if ($terms) {
            $this->query = "SELECT {$columns} FROM {$this->entity} {$this->entityAlias} " . (!empty($this->join) ? implode(' ', $this->join) : '') . " WHERE {$terms}";
            parse_str($params, $this->params);
            return $this;
        }

        $this->query = "SELECT {$columns} FROM {$this->entity} {$this->entityAlias} " . (!empty($this->join) ? implode(' ', $this->join) : '');
        return $this;
    }

    /**
     * @param int $id
     * @param string $columns
     * @param string $field
     * @return null|mixed|PdoRepository
     */
    public function findById(int $id, string $columns = "*", string $field = "ID"): ?PdoRepository
    {
        $find = $this->find("{$field} = :{$field}", "{$field}={$id}", $columns);
        return $find->fetch();
    }

    /**
     * @param string $columnOrder
     * @return PdoRepository
     */
    public function order(string $columnOrder): PdoRepository
    {
        $this->order = " ORDER BY {$columnOrder}";
        return $this;
    }

    /**
     * @param string $columnOrder
     * @return PdoRepository
     */
    public function group(string $columnGroup): PdoRepository
    {
        $this->group = " GROUP BY {$columnGroup}";
        return $this;
    }

    /**
     * @param int $limit
     * @return PdoRepository
     */
    public function limit(int $limit): PdoRepository
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    /**
     * @param int $offset
     * @return PdoRepository
     */
    public function offset(int $offset): PdoRepository
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    /**
     * @param bool $all
     * @return null|array|mixed|PdoRepository
     */
    public function fetch(bool $all = false): ?PdoRepository
    {
        try {
            if(!empty($this->params)) {
                foreach ($this->params as $key => $value) {
                    $this->query = str_replace(":{$key}", $value, $this->query);
                }
            }

            $conn = (empty(Transaction::get()) 
                ? Connect::getInstance()
                : Transaction::get()
            );

            $stmt = $conn->query($this->query . $this->group . $this->order . $this->limit . $this->offset);

            if ($all) {
                $response = $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
                return $response;
            }

            $response = $stmt->fetchObject(static::class);
            return ($response ? $response : null);
        } catch (PDOException $exception) {
            $this->fail = $exception;

            if(!empty(Transaction::get())) {
                throw new Exception($exception->getMessage());
            }

            return null;
        }
    }

    /**
     * @param string $key
     * @return int
     */
    public function count($key = "id"): int
    {

        if(!empty($this->params)) {
            foreach ($this->params as $key => $value) {
                $this->query = str_replace(":{$key}", $value, $this->query);
            }
        }

        $conn = (empty(Transaction::get()) 
            ? Connect::getInstance($this->clubParams["server"], $this->clubParams["path"])
            : Transaction::get()
        );

        $stmt = $conn->query($this->query);
        $stmt->fetchAll(PDO::FETCH_CLASS, static::class);

        return $stmt->rowCount();
    }

    /**
     * @param array $data
     * @return int|null
     */
    protected function create(array $data): ?int
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));

            $dataBindArray = [];
            foreach ($data as $bind => $value) {
                if (!empty($data[$bind]) || is_int($value)) {
                    $dataBindArray[$bind] = $value;
                } else {
                    $values = str_replace(":{$bind}", "NULL", $values);
                }
            }

            $returnnigId = "";
            if (!empty($this->protected)) {
                $returnnigId = " RETURNING {$this->protected[0]}";
            }

            $conn = (empty(Transaction::get()) 
                ? Connect::getInstance($this->clubParams["server"], $this->clubParams["path"])
                : Transaction::get()
            );

            $stmt = $conn->prepare("INSERT INTO {$this->entity} ({$columns}) VALUES ({$values}) {$returnnigId}");
            $stmt->execute($this->filter($dataBindArray));

            if (!empty($this->protected)) {
                $response = $stmt->fetchObject(static::class);
                return $response->{$this->protected[0]};
            }

            return true;
        } catch (PDOException $exception) {
            $this->fail = $exception;

            if (!empty(Transaction::get())) {
                throw new Exception($exception->getMessage());
            }

            return null;
        }
    }

    /**
     * @param array $data
     * @param string $terms
     * @param string $params
     * @return int|null
     */
    protected function update(array $data, string $terms, string $params): ?int
    {
        try {
            $dataSet = [];
            foreach ($data as $bind => $value) {
                if(!empty($value) || is_int($value)) {
                    $dataSet[] = "{$bind} = :{$bind}";
                } else {
                    $dataSet[] = "{$bind} = NULL";
                    unset($data[$bind]);
                }
            }

            $dataSet = implode(", ", $dataSet);
            parse_str($params, $parameters);

            $conn = (empty(Transaction::get()) 
                ? Connect::getInstance($this->clubParams["server"], $this->clubParams["path"])
                : Transaction::get()
            );

            $stmt = $conn->prepare("UPDATE {$this->entity} SET {$dataSet} WHERE {$terms}");
            $allParameters = array_merge($data, $parameters);

            $stmt->execute($this->filter($allParameters));
            return ($stmt->rowCount() ?? 1);
        } catch (PDOException $exception) {
            $this->fail = $exception;

            if (!empty(Transaction::get())) {
                throw new Exception($exception->getMessage());
            }

            return null;
        }
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->required()) {
            $this->message->warning("Preencha todos os campos para continuar");
            return false;
        }

        /** Update */
        if (!empty($this->ID)) {
            $id = $this->ID;
            $this->update($this->safe(), "ID = :id", "id={$id}");
            if ($this->fail()) {
                $this->message->error("Erro ao atualizar, verifique os dados");
                return false;
            }
        }

        /** Create */
        if (empty($this->ID)) {
            $id = $this->create($this->safe());
            if ($this->fail()) {
                $this->message->error("Erro ao cadastrar, verifique os dados");
                return false;
            }
        }

        $this->data = $this->findById((int) $id)->data();
        return true;
    }

    /**
     * @return int
     */
    public function lastId(): int
    {
        return Connect::getInstance($this->clubParams["server"], $this->clubParams["path"])->query("SELECT MAX(id) as maxId FROM {$this->entity}")->fetch()->maxId + 1;
    }

    /**
     * @param string $terms
     * @param null|string $params
     * @return bool
     */
    public function delete(string $terms, ?string $params): bool
    {
        try {
            $conn = (empty(Transaction::get()) 
                ? Connect::getInstance($this->clubParams["server"], $this->clubParams["path"])
                : Transaction::get()
            );

            $stmt = $conn->prepare("DELETE FROM {$this->entity} WHERE {$terms}");
            if ($params) {
                parse_str($params, $params);
                $stmt->execute($params);
                return true;
            }

            $stmt->execute();
            return true;
        } catch (PDOException $exception) {
            $this->fail = $exception;

            if (!empty(Transaction::get())) {
                throw new Exception($exception->getMessage());
            }

            return false;
        }
    }

    /**
     * @return bool
     */
    public function destroy(): bool
    {
        if (empty($this->{$this->protected[0]})) {
            return false;
        }

        $destroy = $this->delete("{$this->protected[0]} = :id", "id={$this->{$this->protected[0]}}");
        return $destroy;
    }

    /**
     * @return array|null
     */
    protected function safe(): ?array
    {
        $safe = (array)$this->data;
        foreach ($this->protected as $unset) {
            unset($safe[$unset]);
        }
        return $safe;
    }

    /**
     * @param array $data
     * @return array|null
     */
    private function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }
        return $filter;
    }

    /**
     * @return bool
     */
    protected function required(): bool
    {
        $data = (array)$this->data();
        foreach ($this->required as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        return true;
    }
}