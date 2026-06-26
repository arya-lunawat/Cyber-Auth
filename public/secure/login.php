<?php

require_once '../../app/Services/SessionManager.php';
require_once '../../app/Controllers/SecureAuthController.php';

SessionManager::start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $controller =
        new SecureAuthController();

    $result =
        $controller->login(
            $_POST['username'],
            $_POST['password']
        );

    if ($result['success']) {

        header(
            "Location: ../dashboard.php"
        );

        exit;
    }

    $error = $result['message'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secure Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>Secure Login</h1>

<?php if ($error): ?>
<p style="color:red;">
    <?= htmlspecialchars($error) ?>
</p>
<?php endif; ?>

<div class="center">

<form method="POST" class="login-box">

    <h2>Login</h2>

    <input type="text" name="username" placeholder="Username" required>

    <input type="password" name="password" placeholder="Password" required>

    <button type="submit">Login</button>

</form>

</div>

</body>
</html>