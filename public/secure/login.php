<?php

require_once '../../app/Services/SessionManager.php';
require_once '../../app/Controllers/SecureAuthController.php';

SessionManager::start();

if (SessionManager::isLoggedIn()) {
    header("Location: ../dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $controller = new SecureAuthController();

    $result = $controller->login(
        $_POST['username'],
        $_POST['password']
    );

    if ($result['success']) {

        header("Location: ../dashboard.php");
        exit;
    }

    $error = $result['message'];
}

require_once '../includes/header.php';

?>

<section class="auth-wrapper">

    <div class="auth-container">

        <h1>🔒 Secure Login</h1>

        <p class="auth-subtitle">

            This authentication system uses prepared statements,
            password hashing, secure sessions and modern authentication
            practices to protect against SQL Injection attacks.

        </p>

        <?php if ($error): ?>

            <div class="alert alert-danger">

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>

        <form method="POST">

            <div class="form-group">

                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    placeholder="Enter your username"
                    required>

            </div>

            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required>

            </div>

            <button
                type="submit"
                class="btn btn-success btn-block">

                Login Securely

            </button>

        </form>

        <div class="auth-footer">

            Don't have an account?

            <br><br>

            <a href="../register.php">

                Register

            </a>

            &nbsp; | &nbsp;

            <a href="../vulnerable/login.php">

                Try Vulnerable Login →

            </a>

        </div>

        <div class="info-box mt-4">

            <h4>Why is this secure?</h4>

            <ul style="margin-left:20px; color:#cfd4dc; line-height:1.8;">

                <li>Passwords are stored using <strong>password_hash()</strong>.</li>

                <li>Passwords are verified using <strong>password_verify()</strong>.</li>

                <li>Prepared Statements prevent SQL Injection.</li>

                <li>Session IDs are regenerated after login.</li>

                <li>Login attempts are logged for auditing.</li>

            </ul>

        </div>

    </div>

</section>

<?php require_once '../includes/footer.php'; ?>