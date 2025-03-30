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

    // Session handler methods
    public static function open($savePath, $sessionName) {
        return true;
    }

    public static function close() {
        return true;
    }

    public static function read($id) {
        $db = new self();
        $stmt = $db->query("SELECT data FROM sessions WHERE session_id = ?", [$id]);
        $data = $stmt->fetchColumn();
        return $data ?: '';
    }

    public static function write($id, $data) {
        error_log("Session write attempt for ID: $id");
        $db = new self();
        $timestamp = time();
        $result = $db->query(
            "REPLACE INTO sessions (session_id, data, timestamp) VALUES (?, ?, ?)",
            [$id, $data, $timestamp]
        ) !== false;
        error_log("Session write result: " . ($result ? "success" : "failed"));
        if (!$result) {
            error_log("PDO Error: " . json_encode($db->pdo->errorInfo()));
        }
        return $result;
    }

    public static function destroy($id) {
        $db = new self();
        return $db->query("DELETE FROM sessions WHERE session_id = ?", [$id]) !== false;
    }

    public static function gc($maxlifetime) {
        $db = new self();
        $old = time() - $maxlifetime;
        return $db->query("DELETE FROM sessions WHERE timestamp < ?", [$old]) !== false;
    }
}
?>