<?php

require_once '../app/Services/SessionManager.php';

SessionManager::start();

SessionManager::logout();

header("Location: /");

exit;