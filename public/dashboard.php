<?php

require_once '../app/Services/SessionManager.php';
require_once '../app/Middleware/AuthMiddleware.php';
require_once '../app/Models/User.php';

AuthMiddleware::handle();

$userModel = new User();

$user = $userModel->findById(
    SessionManager::userId()
);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - CyberAuth</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>CyberAuth Dashboard</h1>

<hr>

<h2>Welcome <?= htmlspecialchars($user['username']) ?></h2>

<p>
    <strong>Email:</strong>
    <?= htmlspecialchars($user['email']) ?>
</p>

<p>
    <strong>Role:</strong>
    <?= htmlspecialchars($user['role']) ?>
</p>

<p>
    <strong>Last Login:</strong>
    <?= $user['last_login'] ?? 'Never' ?>
</p>

<p>
    <strong>Login Method:</strong>
    <?= $_SESSION['login_method'] ?? 'Unknown' ?>
</p>

<p>
    <strong>Current Session:</strong>
    <?= session_id() ?>
</p>

<hr>

<?php if ($user['role'] === 'admin'): ?>

    <h3>Admin Controls</h3>

    <a href="/admin/index.php">
        Admin Panel
    </a>

    <br><br>

<?php endif; ?>

<a href="/logout.php">
    Logout
</a>

</body>
</html>