<?php
    class DatabaseTable(){

    private $table;
    private $pdo;
    private $this->primaryKey;
    
    public function __construct($pdo, $table, $this->primaryKey) {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

// Generic function find any records from any table by searching any column for any value

// old
    function find($pdo, $table, $field, $value) {
        $stmt = $pdo->prepare('SELECT * FROM ' . $table . ' WHERE ' . $field . ' = :value');
        $values = [
        'value' => $value
        ];
        $stmt->execute($values);
        return $stmt->fetchAll();
    }
//new
    public function find($field, $value) {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE ' . $field . ' = :value');
        $criteria = [
        'value' => $value
        ];
        $stmt->execute($criteria);
        return $stmt->fetchAll();
        }

// Generic function to find all records from any table
//old
    function findAll($pdo, $table) {
        $stmt = $pdo->prepare('SELECT * FROM ' . $table );
        $stmt->execute();
        return $stmt->fetchAll();
        }

// new
    public function findAll() {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table );
        $stmt->execute();
        return $stmt->fetchAll();
    }

//insert
//old
function insert($pdo, $table, $record) {
    $keys = array_keys($record);
    $values = implode(', ', $keys);
    $valuesWithColon = implode(', :', $keys);
    $query = 'INSERT INTO ' . $table . ' (' . $values . ') VALUES (:' . $valuesWithColon . ')';
    $stmt = $pdo->prepare($query);
    $stmt->execute($record);
}
// new
    public function insert($record) {
        $keys = array_keys($record);
        $values = implode(', ', $keys);
        $valuesWithColon = implode(', :', $keys);
        $query = 'INSERT INTO ' . $this->table . ' (' . $values . ') VALUES (:' . $valuesWithColon . ')';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($record);
    }

//Delete function
//old
function delete($pdo, $table, $field, $value) {
    $stmt = $pdo->prepare('DELETE FROM ' . $table . ' WHERE ' . $field . ' = :value');
    $criteria = [
    'value' => $value
    ];
    $stmt->execute($criteria);
}
// new
    public function delete($field, $value) {
        $stmt = $this->pdo->prepare('DELETE FROM ' . $this->table . ' WHERE ' . $field . ' = :value');
        $criteria = [
        'value' => $value
        ];
        $stmt->execute($criteria);
    }

//Update function
//old
function update($pdo, $table, $record, $primaryKey) {
    $query = 'UPDATE ' . $table . ' SET ';

    $parameters = [];

    foreach ($record as $key => $value) {
    $parameters[] = $key . ' = :' .$key;
    }

    $query .= implode(', ', $parameters);
    $query .= ' WHERE ' . $primaryKey . ' = :primaryKey';
    $record['primaryKey'] = $record[$primaryKey];

    $stmt = $pdo->prepare($query);
    $stmt->execute($record);
}
// new
    public function update($record) {
        $query = 'UPDATE ' . $this->table . ' SET ';

        $parameters = [];

        foreach ($record as $key => $value) {
        $parameters[] = $key . ' = :' .$key;
        }

        $query .= implode(', ', $parameters);
        $query .= ' WHERE ' . $this->primaryKey . ' = :primaryKey';
        $record['primaryKey'] = $record[$this->primaryKey];

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($record);
    }

// Save Functions
//old
function save($pdo, $table, $record, $primaryKey) {
    try {
    insert($pdo, $table, $record);
    }
    catch (Exception $e) {
    update($pdo, $table, $record, $primaryKey);
    }
    }
// new
function save($record) {
    try {
    insert($this->pdo, $this->table, $this->record);
    }
    catch (Exception $e) {
    update($this->pdo, $this->table, $this->record, $this->primaryKey);
    }
    }
    }
?>