<?php

session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
exit;

require_once '../../app/Controllers/AdminController.php';

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'admin'
) {
    die('Access denied');
}

$admin = new AdminController();

$users = $admin->getUserCount();
$logins = $admin->getLoginCount();
$success = $admin->getSuccessfulLogins();
$failed = $admin->getFailedLogins();
$attacks = $admin->getAttackCount();
$recentLogins = $admin->getRecentLogins();
$recentAttacks = $admin->getRecentAttacks();
?>

<!DOCTYPE html>
<html>
<head>
    <title>CyberAuth Admin</title>
</head>
<body>

<h1>CyberAuth Admin Dashboard</h1>

<hr>

<p><strong>Total Users:</strong> <?= $users ?></p>
<p><strong>Total Logins:</strong> <?= $logins ?></p>
<p><strong>Successful Logins:</strong> <?= $success ?></p>
<p><strong>Failed Logins:</strong> <?= $failed ?></p>
<p><strong>Detected Attacks:</strong> <?= $attacks ?></p>

<h2>Recent Login Activity</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Username</th>
    <th>Success</th>
    <th>Method</th>
    <th>IP</th>
    <th>Time</th>
</tr>

<?php foreach($recentLogins as $log): ?>
<tr>
    <td><?= htmlspecialchars($log['username']) ?></td>
    <td><?= $log['success'] ? 'Success' : 'Failed' ?></td>
    <td><?= $log['method'] ?></td>
    <td><?= $log['ip_address'] ?></td>
    <td><?= $log['created_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>

<h2>Recent Attack Activity</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Attack Type</th>
    <th>Username</th>
    <th>Payload</th>
    <th>IP</th>
    <th>Time</th>
</tr>

<?php foreach($recentAttacks as $attack): ?>
<tr>
    <td><?= htmlspecialchars($attack['attack_type']) ?></td>
    <td><?= htmlspecialchars($attack['username']) ?></td>
    <td><?= htmlspecialchars($attack['payload']) ?></td>
    <td><?= $attack['ip_address'] ?></td>
    <td><?= $attack['created_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>