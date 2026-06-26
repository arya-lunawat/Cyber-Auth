<?php

require_once '../../app/Controllers/AdminController.php';
require_once '../../app/Services/SessionManager.php';

SessionManager::start();

if (
    !isset($_SESSION['user_id']) ||
    $_SESSION['role'] !== 'admin'
) {
    die("Access denied");
}

$admin = new AdminController();

$attacks = $admin->getAllAttacks();

require_once '../includes/header.php';

?>

<section class="dashboard-header">

    <div>
<br>
        <h1>Attack Logs</h1>

        <p>

            Review detected SQL Injection attempts and database errors
            captured by CyberAuth.

        </p>

    </div>

</section>


<div class="quick-actions">

    <div class="action-card">

        <h3>Dashboard</h3>

        <p>

            Return to the Admin Dashboard.

        </p>

        <br>

        <a href="index.php" class="btn btn-primary">

            Dashboard

        </a>

    </div>

    <div class="action-card">

        <h3>Users</h3>

        <p>

            View registered users.

        </p>

        <br>

        <a href="users.php" class="btn btn-success">

            Users

        </a>

    </div>

    <div class="action-card">

        <h3>Login Logs</h3>

        <p>

            View all authentication attempts.

        </p>

        <br>

        <a href="logins.php" class="btn btn-warning">

            Login Logs

        </a>

    </div>

</div>


<div class="search-box">

<input
    type="text"
    id="attackSearch"
    placeholder="Search by username, payload, IP or attack type..."
    onkeyup="filterAttacks()"
>

</div>


<?php if(empty($attacks)): ?>

<div class="empty">

No attacks have been detected.

</div>

<?php else: ?>

<div class="table-wrapper">

<table id="attackTable">

<thead>

<tr>

<th>Date</th>

<th>Attack Type</th>

<th>Username</th>

<th>Payload</th>

<th>IP Address</th>

<th>User Agent</th>

</tr>

</thead>

<tbody>

<?php foreach($attacks as $attack): ?>

<tr>

<td>

<?= htmlspecialchars($attack['created_at']) ?>

</td>

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

<td style="max-width:350px;">

<?= htmlspecialchars($attack['user_agent']) ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php endif; ?>

<script>

function filterAttacks(){

    let input=document.getElementById("attackSearch");

    let filter=input.value.toUpperCase();

    let table=document.getElementById("attackTable");

    let tr=table.getElementsByTagName("tr");

    for(let i=1;i<tr.length;i++){

        let row=tr[i];

        let txt=row.textContent||row.innerText;

        if(txt.toUpperCase().indexOf(filter)>-1){

            row.style.display="";

        }

        else{

            row.style.display="none";

        }

    }

}

</script>

<?php require_once '../includes/footer.php'; ?>