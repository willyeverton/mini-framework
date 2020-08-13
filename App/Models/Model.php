<?php

namespace App\Models;

abstract class Model
{
    protected $table;
    protected $fillable;

    private $select;
    private $where = '';
    private $join = '';

    private $db;
    private $driver = "mysql";
    private $host   = "127.0.0.1";
    private $port   = "3306";
    private $dbname = "default";

    public function __construct()
    {
        $this->db = new \PDO(
            "$this->driver:
            host=$this->host:
            $this->port;
            dbname=$this->dbname",
            "root", "");
    }

    public static function fetchAll()
    {
        $model = new static();

        $query = "SELECT * FROM $model->table";
        return $model->db->query($query);
    }

    public static function find(int $id)
    {
        $model = new static();
        $query = "SELECT * FROM $model->table WHERE id=:id";

        $stmt = $model->db->prepare($query);
        $stmt->bindParam(":id",$id);

        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function select(array $fields = null)
    {
        $fields = $fields ?? $this->fillable;

        $this->select = "SELECT ". implode(', ', array_values($fields));
        $this->select = rtrim($this->select, ", ");
        $this->select .= " FROM $this->table ";

        return $this;
    }

    public function join(string $table, string $on, string $join = 'INNER')
    {
        $this->join = "$join JOIN $table ON $on ";

        return $this;
    }

    public function where(array $fields, string $condition = '=', string $operator = 'AND')
    {
        $operator = strtoupper($operator);

        if(empty($this->where))
            $this->where = 'WHERE ';

        foreach ($fields as $field => $value) {

            if($this->where != 'WHERE ')
                $this->where .= "$operator ";

            if(is_string($value))
                $this->where .= "$field $condition '$value' ";
            else
                $this->where .= "$field $condition $value ";
        }

        return $this;
    }

    public function get()
    {
        try {
            if(empty($this->select))
                $this->select();

            $query = $this->select . $this->join . $this->where;

            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);

        } catch (\PDOException $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    private function checkFillable(array $params){

        $diff = array_diff_key($params, array_flip($this->fillable));
        if($diff) {
            $diff = implode(',', array_keys($diff));
            throw new \Exception("$diff not is fillable");
        }
    }

    public static function insert(array $params)
    {
        try {
            $model = new static();
            $model->checkFillable($params);

            $query = sprintf(
                "INSERT INTO $model->table (%s) VALUES (%s)",
                implode(', ', array_keys($params)),
                ':' . implode(', :', array_keys($params))
            );
            $stmt = $model->db->prepare($query);
            foreach ($params as $field => $value) {
                $stmt->bindParam(":$field", $value);
            }
            $stmt->execute();
            return $model->db->lastInsertId();

        } catch (\PDOException $ex) {
           throw new \Exception($ex->getMessage());
        }
    }

    public static function update(int $id, array $params)
    {
        try {
            $model = new static();
            $model->checkFillable($params);

            $values = '';
            $query = "UPDATE $model->table SET %s WHERE id = :id";

            foreach ($params as $field => $value) {
                $values .= "$field = $value, ";
            }
            $values = rtrim($values, ", ");
            $query = sprintf($query, $values);

            $stmt = $model->db->prepare($query);
            $stmt->bindParam(":id",$id);

            $stmt->execute();
            return $stmt->rowCount();

        } catch (\PDOException $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public static function delete(int $id)
    {
        $model = new static();
        $query = "DELETE FROM $model->table WHERE id = :id";

        $stmt = $model->db->prepare($query);
        $stmt->bindParam(":id",$id);

        $stmt->execute();
        return $stmt->rowCount();
    }
}