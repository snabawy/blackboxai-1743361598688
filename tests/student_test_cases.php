<?php
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../includes/database.php';
require_once __DIR__.'/../includes/auth_functions.php';
require_once __DIR__.'/../includes/security_functions.php';

class StudentTestCases {
    private $db;
    private $auth;
    private $security;

    public function __construct() {
        $this->db = new Database();
        $this->auth = new Auth();
        $this->security = new Security();
    }

    public function testLogin() {
        // Test valid login
        $result = $this->auth->login('student@example.com', 'password123');
        assert($result === true, 'Login with valid credentials failed');
        
        // Test invalid login
        $result = $this->auth->login('student@example.com', 'wrongpassword');
        assert($result === false, 'Login with invalid credentials should fail');
    }

    public function runAllTests() {
        $this->testLogin();
        echo "All student tests completed successfully!";
    }
}

// Run tests
$test = new StudentTestCases();
$test->runAllTests();
?>