<?php

require_once '../app/Controllers/AuthController.php';
require_once '../app/Helpers/functions.php';
require_once 'includes/header.php';

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

<section class="auth-wrapper">

<div class="auth-container">

<h1>Create Account</h1>

<p class="auth-subtitle">

Create a CyberAuth account to explore both
secure authentication and SQL Injection demonstrations.

</p>

<?php if($success): ?>

<div class="alert alert-success">

✅ Registration completed successfully.

<br><br>

<a href="secure/login.php" class="btn btn-success">

Proceed to Secure Login

</a>

</div>

<?php endif; ?>

<?php if(!empty($errors)): ?>

<div class="alert alert-danger">

<strong>Please fix the following:</strong>

<ul style="margin-top:12px; padding-left:20px;">

<?php foreach($errors as $error): ?>

<li><?= e($error) ?></li>

<?php endforeach; ?>

</ul>

</div>

<?php endif; ?>

<form method="POST">

<div class="form-group">

<label>

Username

</label>

<input
type="text"
name="username"
value="<?= e($username) ?>"
placeholder="Enter username"
required>

</div>

<div class="form-group">

<label>

Email Address

</label>

<input
type="email"
name="email"
value="<?= e($email) ?>"
placeholder="Enter email"
required>

</div>

<div class="form-group">

<label>

Password

</label>

<input
type="password"
name="password"
placeholder="Enter password"
required>

<div class="password-hint">

Minimum 8 characters recommended.
Use uppercase, lowercase,
numbers and symbols for stronger security.

</div>

</div>

<div class="form-group">

<label>

Confirm Password

</label>

<input
type="password"
name="confirm_password"
placeholder="Confirm password"
required>

</div>

<button
type="submit"
class="btn btn-primary btn-block">

Create Account

</button>

</form>

<div class="auth-footer">

Already have an account?

<br><br>

<a href="secure/login.php">

Secure Login

</a>

&nbsp; | &nbsp;

<a href="vulnerable/login.php">

Vulnerable Login

</a>

</div>

</div>

</section>

<?php require_once 'includes/footer.php'; ?>