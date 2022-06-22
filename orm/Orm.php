<?php

class Orm
{
    protected $table;
    protected $id;
    protected $db;

    public function __construct(string $table, string $id, PDO $db)
    {
        $this->table = $table;
        $this->id = $id;
        $this->db = $db;
    }

    // All
    public function getAll(string $prefix = '')
    {
        $prefix = $this->assemblyPrefix($prefix);
        $stmt = $this->db->prepare("SELECT * FROM {$prefix}{$this->table}");
        if (!$stmt->execute()) {
            throw new Exception($stmt->errorInfo()[2]);
        }
        return $stmt->fetchAll();
    }

    // Get by id
    public function getById(int $id, string $prefix = '')
    {
        $prefix = $this->assemblyPrefix($prefix);

        $stmt = $this->db->prepare("SELECT * FROM {$prefix}{$this->table} WHERE $this->id = :$this->id LIMIT 1");
        $stmt->bindValue(":$this->id", $id);
        if (!$stmt->execute()) {
            throw new Exception($stmt->errorInfo()[2]);
        }
        return $stmt->fetch();
    }

    // Delete
    public function deleteById(int $id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->id} = :{$this->id}");
        $stmt->bindValue(":{$this->id}", $id);
        if (!$stmt->execute()) {
            throw new Exception($stmt->errorInfo()[2]);
        }

        return $id;
    }

    // Update
    public function updateById(int $id, array $data)
    {
        $sql = "UPDATE {$this->table} SET ";
        foreach ($data as $key => $value) {
            $sql .= "$key = :$key, ";
        }
        $sql = trim(trim($sql), ',');
        $sql .= " WHERE {$this->id} = :{$this->id}";

        $stmt = $this->db->prepare($sql);

        foreach ($data as $key => $rowValue) {
            if (gettype($rowValue) === 'boolean') {
                $stmt->bindValue(":{$key}", $rowValue, PDO::PARAM_BOOL);
            } else {
                $stmt->bindValue(":{$key}", $rowValue);
            }
        }
        $stmt->bindValue(":{$this->id}", $id);

        if (!$stmt->execute()) {
            throw new Exception($stmt->errorInfo()[2]);
        }

        return $id;
    }

    // Insert
    public function insert(array $data, bool $audit = true)
    {
        // Insert Params
        $sql = "INSERT INTO {$this->table} (";
        foreach ($data as $key => $value) {
            $sql .= "{$key}, ";
        }
        $sql = trim(trim($sql), ',');

        // Insert Values
        $sql .= ") VALUES (";
        foreach ($data as $key => $value) {
            $sql .= ":{$key}, ";
        }
        $sql = trim(trim($sql), ',');
        $sql .= ')';

        // Statement
        $stmt = $this->db->prepare($sql);

        // Iinsert Params
        $sql = "INSERT INTO {$this->table} (";
        foreach ($data as $key => $value) {
            $sql .= "{$key}, ";
        }
        $sql = trim(trim($sql), ',');

        // Insert Values
        $sql .= ") VALUES (";
        foreach ($data as $key => $value) {
            $sql .= ":{$key}, ";
        }
        $sql = trim(trim($sql), ',');
        $sql .= ')';

        // Statement
        $stmt = $this->db->prepare($sql);

        foreach ($data as $key => $rowValue) {
            if (gettype($rowValue) === 'boolean') {
                $stmt->bindValue(":{$key}", $rowValue, PDO::PARAM_BOOL);
            } else {
                $stmt->bindValue(":{$key}", $rowValue);
            }
        }

        if (!$stmt->execute()) {
            throw new Exception($stmt->errorInfo()[2]);
        }
        return $this->db->lastInsertId();
    }

    protected function assemblyPrefix(string $prefix)
    {
        if (strlen($prefix) > 0) {
            return $prefix . '_';
        } else {
            return '';
        }
    }
}
