<?php

namespace App\Models;

class Model
{
    private $table;
    private $db;
    private $driver = "mysql";
    private $host   = "127.0.0.1";
    private $port   = "3306";
    private $dbname = "default";

    public function __construct($table)
    {
        $this->table = $table;

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

    public function insert(array $params)
    {
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