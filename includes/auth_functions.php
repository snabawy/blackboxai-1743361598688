<?php
require_once __DIR__.'/new_database.php';

class Auth {
    private $db;

    public function __construct() {
        $this->db = new NewDatabase();
    }

    public function login($email, $password) {
        try {
            $stmt = $this->db->query(
                "SELECT * FROM users WHERE email = :email",
                ['email' => $email]
            );
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['email'] = $user['email'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_destroy();
    }
}
?>