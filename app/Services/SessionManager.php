<?php

class SessionManager
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {

            session_set_cookie_params([
                'lifetime' => 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();
        }
    }

    public static function login($user, $method)
    {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_method'] = $method;
        $_SESSION['login_time'] = time();
    }

    public static function logout()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }

    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public static function userId()
    {
        return $_SESSION['user_id'] ?? null;
    }
}