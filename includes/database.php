<?php
class Database {
    private $pdo;

    public function __construct() {
        try {
            if (DB_TYPE === 'mysql') {
                $dsn = "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=".DB_CHARSET;
                $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } else {
                if (!file_exists(dirname(DB_PATH))) {
                    mkdir(dirname(DB_PATH), 0755, true);
                }
                $this->pdo = new PDO('sqlite:'.DB_PATH);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->exec('PRAGMA foreign_keys = ON');
            }
            $this->initializeDatabase();
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    private function initializeDatabase() {
        // Create tables if they don't exist
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                role TEXT NOT NULL,
                first_name TEXT,
                last_name TEXT,
                reset_token TEXT,
                reset_expires TEXT
            );
            
            // Create password_reset_tokens table
            CREATE TABLE IF NOT EXISTS password_reset_tokens (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                token TEXT NOT NULL,
                expires_at TEXT NOT NULL,
                FOREIGN KEY(user_id) REFERENCES users(id)
            )
        ");
        
        // Add more table creation statements as needed
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        
        // Convert array to named parameters if needed
        if (!empty($params) && array_keys($params) === range(0, count($params) - 1)) {
            $namedParams = [];
            foreach ($params as $i => $value) {
                $namedParams[":param".($i+1)] = $value;
            }
            $params = $namedParams;
        }
        
        $stmt->execute($params);
        return $stmt;
    }
    
    public function fetch($stmt) {
        return $stmt->fetch();
    }
    
    public function fetchAll($stmt) {
        return $stmt->fetchAll();
    }
}
?>
