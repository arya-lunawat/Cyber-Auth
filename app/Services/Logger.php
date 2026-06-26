<?php

require_once __DIR__ . '/../Config/Database.php';

class Logger
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function logLogin(
        $username,
        $success,
        $method
    ) {
        $stmt = $this->db->prepare(
            "INSERT INTO login_logs
            (
                username,
                success,
                method,
                ip_address,
                user_agent
            )
            VALUES (?,?,?,?,?)"
        );

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

        $agent = $_SERVER['HTTP_USER_AGENT']
            ?? 'Unknown';

        $stmt->bind_param(
            "sisss",
            $username,
            $success,
            $method,
            $ip,
            $agent
        );

        return $stmt->execute();
    }

    public function logAttack(
        $type,
        $username,
        $payload
    ) {
        $stmt = $this->db->prepare(
            "INSERT INTO attack_logs
            (
                attack_type,
                username,
                payload,
                ip_address,
                user_agent
            )
            VALUES (?,?,?,?,?)"
        );

        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

        $agent = $_SERVER['HTTP_USER_AGENT']
            ?? 'Unknown';

        $stmt->bind_param(
            "sssss",
            $type,
            $username,
            $payload,
            $ip,
            $agent
        );

        return $stmt->execute();
    }

    public static function attack(
    $type,
    $username,
    $payload
)
{
    $db = Database::connect();

    $stmt = $db->prepare(
        "INSERT INTO attack_logs
        (
            attack_type,
            username,
            payload,
            ip_address,
            user_agent
        )
        VALUES
        (
            ?, ?, ?, ?, ?
        )"
    );

    $ip =
        $_SERVER['REMOTE_ADDR']
        ?? 'Unknown';

    $agent =
        $_SERVER['HTTP_USER_AGENT']
        ?? 'Unknown';

    $stmt->bind_param(
        "sssss",
        $type,
        $username,
        $payload,
        $ip,
        $agent
    );

    $stmt->execute();
}

    public static function login(
    $username,
    $success,
    $method
)
{
    $db = Database::connect();

    $stmt = $db->prepare(
        "INSERT INTO login_logs
        (
            username,
            success,
            method,
            ip_address,
            user_agent
        )
        VALUES
        (
            ?, ?, ?, ?, ?
        )"
    );

    $ip =
        $_SERVER['REMOTE_ADDR']
        ?? 'Unknown';

    $agent =
        $_SERVER['HTTP_USER_AGENT']
        ?? 'Unknown';

    $stmt->bind_param(
        "sisss",
        $username,
        $success,
        $method,
        $ip,
        $agent
    );

    $stmt->execute();
}
}