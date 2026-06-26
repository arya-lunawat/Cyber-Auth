<?php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Services/Logger.php';
require_once __DIR__ . '/../Services/SessionManager.php';
require_once __DIR__ . '/../Config/Database.php';

class VulnerableAuthController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function login($username, $password)
{
    // INTENTIONALLY VULNERABLE
    $query = "
        SELECT *
        FROM users
        WHERE username='$username'
        AND password='$password'
        LIMIT 1
    ";

    foreach ([
        "'",
        "--",
        "#",
        "/*",
        "*/",
        " OR ",
        " UNION ",
        " DROP ",
        " SELECT "
    ] as $pattern) {

        if (
            stripos($username, $pattern) !== false ||
            stripos($password, $pattern) !== false
        ) {

            Logger::attack(
                'SQL_INJECTION_ATTEMPT',
                $username,
                $query
            );

            break;
        }
    }

    $result = mysqli_query(
        $this->db,
        $query
    );

    if (!$result) {

        Logger::attack(
            'SQL_ERROR',
            $username,
            mysqli_error($this->db)
        );

        return [
            'success' => false,
            'message' => mysqli_error($this->db)
        ];
    }

    $user = mysqli_fetch_assoc($result);

    if ($user) {

        SessionManager::login(
            $user,
            'vulnerable'
        );

        Logger::login(
            $username,
            true,
            'vulnerable'
        );

        return [
            'success' => true
        ];
    }

    Logger::login(
        $username,
        false,
        'vulnerable'
    );

    return [
        'success' => false,
        'message' => 'Invalid username or password'
    ];
}
}