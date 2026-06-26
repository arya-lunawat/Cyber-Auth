<?php

require_once __DIR__ . '/../Config/Database.php';

class AdminController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getUserCount()
    {
        $result = $this->db->query(
            "SELECT COUNT(*) as total FROM users"
        );

        return $result->fetch_assoc()['total'];
    }

    public function getLoginCount()
    {
        $result = $this->db->query(
            "SELECT COUNT(*) as total FROM login_logs"
        );

        return $result->fetch_assoc()['total'];
    }

    public function getSuccessfulLogins()
    {
        $result = $this->db->query(
            "SELECT COUNT(*) as total
             FROM login_logs
             WHERE success = 1"
        );

        return $result->fetch_assoc()['total'];
    }

    public function getFailedLogins()
    {
        $result = $this->db->query(
            "SELECT COUNT(*) as total
             FROM login_logs
             WHERE success = 0"
        );

        return $result->fetch_assoc()['total'];
    }

    public function getAttackCount()
    {
        $result = $this->db->query(
            "SELECT COUNT(*) as total
                FROM attack_logs"
        );

        return $result->fetch_assoc()['total'];
    }
    public function getRecentLogins()
{
    $result = $this->db->query(
        "SELECT *
            FROM login_logs
            ORDER BY id DESC
            LIMIT 10"
    );

    return $result->fetch_all(MYSQLI_ASSOC);
}

public function getRecentAttacks()
{
    $result = $this->db->query(
        "SELECT *
            FROM attack_logs
            ORDER BY id DESC
            LIMIT 10"
    );

    return $result->fetch_all(MYSQLI_ASSOC);
}
}