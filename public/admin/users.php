<?php

require_once '../../app/Services/SessionManager.php';
require_once '../../app/Controllers/AdminController.php';
require_once '../../app/Config/Database.php';

SessionManager::start();

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'admin'
) {
    die("Access denied");
}

$db = Database::connect();

$result = $db->query("
    SELECT
        id,
        username,
        email,
        role,
        created_at,
        last_login
    FROM users
    ORDER BY id ASC
");

$users = $result->fetch_all(MYSQLI_ASSOC);

require_once '../includes/header.php';

?>

<section class="dashboard-header">

    <div>
<br>
        <h1>Registered Users</h1>

        <p>

            View all registered users within CyberAuth.

        </p>

    </div>

</section>

<div class="quick-actions">

    <div class="action-card">

        <h3>Dashboard</h3>

        <p>

            Return to the administrator dashboard.

        </p>

        <br>

        <a href="index.php" class="btn btn-primary">

            Dashboard

        </a>

    </div>

    <div class="action-card">

        <h3>Login Logs</h3>

        <p>

            View authentication history.

        </p>

        <br>

        <a href="logins.php" class="btn btn-success">

            Login Logs

        </a>

    </div>

    <div class="action-card">

        <h3>Attack Logs</h3>

        <p>

            Review SQL Injection attempts.

        </p>

        <br>

        <a href="attacks.php" class="btn btn-danger">

            Attack Logs

        </a>

    </div>

</div>


<div class="table-wrapper">

<table>

<thead>

<tr>

<th>ID</th>

<th>Username</th>

<th>Email</th>

<th>Role</th>

<th>Created</th>

<th>Last Login</th>

</tr>

</thead>

<tbody>

<?php foreach($users as $user): ?>

<tr>

<td>

<?= $user['id'] ?>

</td>

<td>

<?= htmlspecialchars($user['username']) ?>

</td>

<td>

<?= htmlspecialchars($user['email']) ?>

</td>

<td>

<?php if($user['role'] === 'admin'): ?>

<span class="badge badge-danger">

ADMIN

</span>

<?php else: ?>

<span class="badge badge-info">

USER

</span>

<?php endif; ?>

</td>

<td>

<?= $user['created_at'] ?>

</td>

<td>

<?= $user['last_login'] ?: 'Never' ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php require_once '../includes/footer.php'; ?>