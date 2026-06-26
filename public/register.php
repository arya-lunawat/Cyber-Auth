<?php

require_once '../app/Controllers/AuthController.php';
require_once '../app/Helpers/functions.php';

$errors = [];
$success = false;

$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    $auth = new AuthController();

    $result = $auth->register(
        $username,
        $email,
        $password,
        $confirmPassword
    );

    $success = $result['success'];
    $errors = $result['errors'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - CyberAuth</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>Register</h1>

<?php if ($success): ?>

    <p style="color:green;">
        Registration successful.
    </p>

<?php endif; ?>

<?php if (!empty($errors)): ?>

    <div style="color:red;">

        <?php foreach ($errors as $error): ?>

            <p><?= e($error) ?></p>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<div class="center">

<form method="POST" class="login-box">

    <h2>Register</h2>

    <input type="text" name="username" placeholder="Username" value="<?= e($username) ?>">

    <input type="email" name="email" placeholder="Email" value="<?= e($email) ?>">

    <input type="password" name="password" placeholder="Password">

    <input type="password" name="confirm_password" placeholder="Confirm Password">

    <button type="submit">Register</button>

</form>

</div>
</body>
</html>