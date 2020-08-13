<?php

namespace App\Models;

abstract class Model
{
    protected $table;
    protected $fillable;

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

    public function fetchAll()
    {
        $query = "SELECT * FROM $this->table";
        return $this->db->query($query);
    }

    public function find($id)
    {
        $query = "SELECT * FROM $this->table WHERE id=:id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id",$id);

        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function findByWhere(array $where)
    {
        $query = "SELECT * FROM $this->table WHERE ";

        foreach ($where as $field => $value) {
            $query .= "$field = '$value' AND ";
        }
        $query = rtrim($query, "AND ");

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    private function checkFillable(array $params){

        $diff = array_diff_key($params, $this->fillable);
        if($diff) {
            $diff = implode(',', array_keys($diff));
            throw new \Exception("$diff not is fillable");
        }
    }

    public function insert(array $params)
    {
        $this->checkFillable($params);

        $query = sprintf(
            "INSERT INTO $this->table (%s) VALUES (%s)",
            implode(', ', array_keys($params)),
            ':' . implode(', :', array_keys($params))
        );
        $stmt = $this->db->prepare($query);
        foreach ($params as $field => $value) {
            $stmt->bindParam(":$field", $value);
        }
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function update($id, array $params)
    {
        $this->checkFillable($params);

        $values = '';
        $query = "UPDATE $this->table SET %s WHERE id = :id";

        foreach ($params as $field => $value) {
            $values .= "$field = $value, ";
        }
        $values = rtrim($values, ", ");
        $query = sprintf($query, $values);

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id",$id);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $query = "DELETE FROM $this->table WHERE id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id",$id);

        $stmt->execute();
        return $stmt->rowCount();
    }
}