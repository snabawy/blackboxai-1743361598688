<?php
require_once __DIR__.'/config.php';
require_once __DIR__.'/includes/database.php';
require_once __DIR__.'/includes/auth_functions.php';
require_once __DIR__.'/includes/security_functions.php';

$security = new Security();
$security->secureSessionStart();
$auth = new Auth();
$db = new Database();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    // Check if email exists
    $user = $db->query("SELECT * FROM users WHERE email = ?", [$email])->fetch();
    
    if ($user) {
        // Generate reset token (in a real app, this would be emailed)
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // SQLite compatible query
        $db->query(
            "UPDATE users SET reset_token = :token, reset_expires = :expires WHERE id = :id",
            ['token' => $token, 'expires' => $expires, 'id' => $user['id']]
        );
        
        $message = "Password reset link has been sent to your email";
    } else {
        $error = "No account found with that email";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SchoolSync AI - Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold text-center mb-6">Reset Password</h1>
            
            <?php if ($message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="email">Email</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-3 py-2 border rounded-md">
                </div>
                
                <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                    Send Reset Link
                </button>
                
                <div class="mt-4 text-center">
                    <a href="/login.php" class="text-blue-600 hover:text-blue-800 text-sm">
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>