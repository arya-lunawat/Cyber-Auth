<?php

require_once __DIR__ . '/../../app/Services/SessionManager.php';

SessionManager::start();

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

$currentPage = basename($_SERVER['PHP_SELF']);
$currentPath = $_SERVER['PHP_SELF'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>CyberAuth</title>

<link rel="stylesheet" href="/assets/css/style.css">

</head>

<body>

<header>

<div class="container navbar">

<a href="/index.php" class="logo">

CyberAuth

</a>

<nav>

<a
href="/index.php"
class="<?= $currentPage == 'index.php' && strpos($currentPath,'admin') === false ? 'active' : '' ?>"
>

Home

</a>

<?php if(!$isLoggedIn): ?>

<a
href="/register.php"
class="<?= $currentPage == 'register.php' ? 'active' : '' ?>"
>

Register

</a>

<a
href="/secure/login.php"
class="<?= strpos($currentPath,'secure/login.php') !== false ? 'active' : '' ?>"
>

Secure Login

</a>

<a
href="/vulnerable/login.php"
class="<?= strpos($currentPath,'vulnerable/login.php') !== false ? 'active' : '' ?>"
>

Vulnerable Login

</a>

<?php endif; ?>

<?php if($isLoggedIn): ?>

<a
href="/dashboard.php"
class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>"
>

Dashboard

</a>

<?php endif; ?>

<?php if($isAdmin): ?>

<a
href="/admin/index.php"
class="<?= strpos($currentPath,'/admin/') !== false ? 'active' : '' ?>"
>

Admin

</a>

<?php endif; ?>

<?php if($isLoggedIn): ?>

<a
href="/logout.php"
class="logout-btn"
>

Logout

</a>

<?php endif; ?>

</nav>

</div>

</header>

<main class="container fade-in">