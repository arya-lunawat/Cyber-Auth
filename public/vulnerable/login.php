<?php

require_once '../../app/Services/SessionManager.php';
require_once '../../app/Controllers/VulnerableAuthController.php';

SessionManager::start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $controller =
        new VulnerableAuthController();

    $result =
        $controller->login(
            $_POST['username'],
            $_POST['password']
        );

    if ($result['success']) {

        header(
            'Location: /dashboard.php'
        );

        exit;
    }

    $error = $result['message'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vulnerable Login</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<h1>🚨 Vulnerable Login</h1>

<p>
This page intentionally demonstrates
SQL Injection vulnerabilities.
</p>

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

<hr>

<h3>Working SQL Injection Payloads</h3>

<pre>
admin' OR 1=1 #

admin' OR 'a'='a' #

admin' OR 1=1 --
</pre>

<hr>

<h4>Why some payloads don't work</h4>

<p>
Not every SQL Injection payload works against every query.
The success of an injection depends on:
</p>

<ul>
    <li>Query structure</li>
    <li>Database engine (MySQL)</li>
    <li>Comment syntax</li>
    <li>Operator precedence (AND vs OR)</li>
</ul>

<p>
CyberAuth demonstrates real-world behavior where attackers often need
to test multiple payloads before finding one that successfully bypasses
authentication.
</p>