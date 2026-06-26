<?php

require_once __DIR__ . '/../Services/SessionManager.php';

class AuthMiddleware
{
    public static function handle()
    {
        SessionManager::start();

        if (!SessionManager::isLoggedIn()) {

            header("Location: /");
            exit;
        }
    }
}