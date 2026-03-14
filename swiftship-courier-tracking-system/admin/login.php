<?php
// admin/login.php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $stmt = $pdo->prepare("SELECT id, username, password FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - SwiftShip Couriers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { background-color: #f4f6f9; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-card { width: 100%; max-width: 400px; padding: 2rem; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); background: white; }
    </style>
</head>
<body>

<div class="login-card text-center">
    <h3 class="mb-4 fw-bold text-primary"><i class="fas fa-user-shield"></i> Admin Login</h3>
    
    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="mb-3">
            <input type="text" class="form-control form-control-lg" name="username" placeholder="Username" required autofocus>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" required>
        </div>
        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-sign-in-alt"></i> Login</button>
        </div>
    </form>
    
    <div class="mt-4 pt-3 border-top text-muted small">
        <a href="../public/index.php" class="text-decoration-none">← Back to Public Site</a>
    </div>
</div>

</body>
</html>
