<?php

require_once '../../app/Services/SessionManager.php';
require_once '../../app/Controllers/AdminController.php';

SessionManager::start();

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'admin'
) {
    die("Access denied");
}

$admin = new AdminController();

$users = $admin->getUserCount();
$logins = $admin->getLoginCount();
$success = $admin->getSuccessfulLogins();
$failed = $admin->getFailedLogins();
$attacks = $admin->getAttackCount();

$recentLogins = $admin->getRecentLogins();
$recentAttacks = $admin->getRecentAttacks();

require_once '../includes/header.php';

?>

<section class="dashboard-header">

    <div>
<br>
        <h1>Admin Dashboard</h1>

        <p>

            Monitor authentication activity,
            SQL Injection attempts and system usage.

        </p>

    </div>

</section>


<div class="quick-actions">

    <div class="action-card">

        <h3>Users</h3>

        <p>Manage registered users.</p>

        <br>

        <a href="users.php" class="btn btn-primary">

            View Users

        </a>

    </div>

    <div class="action-card">

        <h3>Login Logs</h3>

        <p>Review all login activity.</p>

        <br>

        <a href="logins.php" class="btn btn-success">

            View Logs

        </a>

    </div>

    <div class="action-card">

        <h3>Attack Logs</h3>

        <p>Inspect SQL Injection attempts.</p>

        <br>

        <a href="attacks.php" class="btn btn-danger">

            View Attacks

        </a>

    </div>

</div>

<br>

<div class="stats-grid">

<div class="stat-box">

<h2><?= $users ?></h2>

<p>Total Users</p>

</div>

<div class="stat-box">

<h2><?= $logins ?></h2>

<p>Total Logins</p>

</div>

<div class="stat-box">

<h2><?= $success ?></h2>

<p>Successful Logins</p>

</div>

<div class="stat-box">

<h2><?= $failed ?></h2>

<p>Failed Logins</p>

</div>

<div class="stat-box">

<h2><?= $attacks ?></h2>

<p>Detected Attacks</p>

</div>

</div>


<h2 style="margin-top:50px;">

Recent Login Activity

</h2>

<div class="table-wrapper">

<table>

<thead>

<tr>

<th>User</th>
<th>Status</th>
<th>Method</th>
<th>IP Address</th>
<th>Time</th>

</tr>

</thead>

<tbody>

<?php foreach($recentLogins as $log): ?>

<tr>

<td><?= htmlspecialchars($log['username']) ?></td>

<td>

<?php if($log['success']): ?>

<span class="badge badge-success">

SUCCESS

</span>

<?php else: ?>

<span class="badge badge-danger">

FAILED

</span>

<?php endif; ?>

</td>

<td>

<?= strtoupper(htmlspecialchars($log['method'])) ?>

</td>

<td>

<?= htmlspecialchars($log['ip_address']) ?>

</td>

<td>

<?= htmlspecialchars($log['created_at']) ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>


<h2 style="margin-top:60px;">

Recent SQL Injection Activity

</h2>

<div class="table-wrapper">

<table>

<thead>

<tr>

<th>Attack</th>

<th>User</th>

<th>Payload / Error</th>

<th>IP</th>

<th>Time</th>

</tr>

</thead>

<tbody>

<?php foreach($recentAttacks as $attack): ?>

<tr>

<td>

<?php

switch($attack['attack_type']){

case 'SQL_INJECTION_ATTEMPT':

echo '<span class="badge badge-danger">SQL Injection</span>';

break;

case 'SQL_ERROR':

echo '<span class="badge badge-warning">SQL Error</span>';

break;

default:

echo '<span class="badge badge-info">'
.htmlspecialchars($attack['attack_type']).
'</span>';

}

?>

</td>

<td>

<?= htmlspecialchars($attack['username']) ?>

</td>

<td>

<div class="payload">

<?= htmlspecialchars($attack['payload']) ?>

</div>

</td>

<td>

<?= htmlspecialchars($attack['ip_address']) ?>

</td>

<td>

<?= htmlspecialchars($attack['created_at']) ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php require_once '../includes/footer.php'; ?>