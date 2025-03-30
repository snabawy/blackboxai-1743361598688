<?php
class NewDatabase {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO('sqlite:'.DB_PATH);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->exec('PRAGMA foreign_keys = ON');
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        
        // Bind parameters by type
        foreach ($params as $key => $value) {
            $param = is_int($key) ? $key + 1 : $key;
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($param, $value, $type);
        }
        
        $stmt->execute();
        return $stmt;
    }

    public function fetch($stmt) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function fetchAll($stmt) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>