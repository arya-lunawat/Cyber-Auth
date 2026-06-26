<?php

require_once '../app/Services/SessionManager.php';
require_once '../app/Middleware/AuthMiddleware.php';
require_once '../app/Models/User.php';

AuthMiddleware::handle();

$userModel = new User();

$user = $userModel->findById(
    SessionManager::userId()
);

require_once 'includes/header.php';

?>

<section class="dashboard-header">

    <div>
        <br>

        <h1>Welcome back, <?= htmlspecialchars($user['username']) ?></h1>

        <p>

            You are successfully authenticated in CyberAuth.

        </p>

    </div>

</section>


<div class="profile-card">

    <div class="avatar">

        <?= strtoupper(substr($user['username'],0,1)) ?>

    </div>

    <div class="profile-info">

        <h3>

            <?= htmlspecialchars($user['username']) ?>

        </h3>

        <p>

            <strong>Email:</strong>

            <?= htmlspecialchars($user['email']) ?>

        </p>

        <p>

            <strong>Role:</strong>

            <?= ucfirst(htmlspecialchars($user['role'])) ?>

        </p>

        <p>

            <strong>Last Login:</strong>

            <?= $user['last_login'] ?? 'Never' ?>

        </p>

        <p>

            <strong>Session ID:</strong>

            <?= session_id() ?>

        </p>

        <p>

            <strong>Login Method:</strong>

            <?php if(($_SESSION['login_method'] ?? '') === 'secure'): ?>

                <span class="login-method login-secure">

                    Secure Login

                </span>

            <?php else: ?>

                <span class="login-method login-vulnerable">

                    Vulnerable Login

                </span>

            <?php endif; ?>

        </p>

    </div>

</div>


<div class="quick-actions">

    <div class="action-card">

        <h3>Secure Login</h3>

        <p>

            Demonstrates password hashing,
            prepared statements and secure authentication.

        </p>

        <br>

        <a href="secure/login.php" class="btn btn-success">

            Open

        </a>

    </div>


    <div class="action-card">

        <h3>Vulnerable Login</h3>

        <p>

            Demonstrates SQL Injection attacks
            using intentionally insecure code.

        </p>

        <br>

        <a href="vulnerable/login.php" class="btn btn-danger">

            Open

        </a>

    </div>


    <?php if($user['role'] === 'admin'): ?>

    <div class="action-card">

        <h3>Admin Dashboard</h3>

        <p>

            View attack logs,
            users,
            login history,
            and security statistics.

        </p>

        <br>

        <a href="admin/index.php" class="btn btn-primary">

            Open Dashboard

        </a>

    </div>

    <?php endif; ?>


    <div class="action-card">

        <h3>Logout</h3>

        <p>

            End the current authenticated session.

        </p>

        <br>

        <a href="logout.php" class="btn btn-secondary">

            Logout

        </a>

    </div>

</div>


<section class="section1">

<div class="dashboard-card">
<br>
<h2>Authentication Summary</h2>

<div class="stats-grid">

<div class="stat-box">

<h2>

<?= ucfirst($user['role']) ?>

</h2>

<p>Current Role</p>

</div>

<div class="stat-box">

<h2>

<?= ($_SESSION['login_method'] ?? '') === 'secure' ? 'Secure' : 'Vulnerable' ?>

</h2>

<p>Authentication Method</p>

</div>

<div class="stat-box">

<h2>

<?= date('H:i:s') ?>

</h2>

<p>Current Server Time</p>

</div>

<div class="stat-box">

<h2>

<?= SessionManager::isLoggedIn() ? 'Active' : 'Inactive' ?>

</h2>

<p>Session Status</p>

</div>

</div>

</div>

</section>


<section class="section1" >

<div class="info-box red-glass">

    <h4>CyberAuth Educational Project</h4>

    <p>
        This project demonstrates two authentication systems:
    </p>

    <ul>
        <li>
            A secure implementation using password hashing and prepared statements.
        </li>

        <li>
            An intentionally vulnerable implementation for demonstrating SQL Injection attacks.
        </li>

        <li>
            Every login attempt and attack is logged for administrator review.
        </li>

        <li>
            Compare both implementations to understand secure coding practices.
        </li>
    </ul>

</div>

</section>

<?php require_once 'includes/footer.php'; ?>