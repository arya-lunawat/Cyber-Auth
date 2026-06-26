<?php

require_once __DIR__ . '/../Config/Database.php';

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE username = ?"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function findById($id)
{
    $stmt = $this->db->prepare(
        "SELECT * FROM users WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

    public function updateLastLogin($id)
{
    $stmt = $this->db->prepare(
        "UPDATE users
            SET last_login = NOW()
            WHERE id = ?"
    );

    $stmt->bind_param("i", $id);

    return $stmt->execute();
}

    public function incrementAttempts($username)
{
    $stmt = $this->db->prepare(
        "UPDATE users
            SET login_attempts = login_attempts + 1
            WHERE username = ?"
    );

    $stmt->bind_param("s", $username);

    return $stmt->execute();
}

    public function resetAttempts($username)
{
    $stmt = $this->db->prepare(
        "UPDATE users
            SET login_attempts = 0,
                locked_until = NULL
            WHERE username = ?"
    );

    $stmt->bind_param("s", $username);

    return $stmt->execute();
}

    public function lockAccount($username, $minutes = 15)
{
    $stmt = $this->db->prepare(
        "UPDATE users
            SET locked_until = DATE_ADD(NOW(), INTERVAL ? MINUTE)
            WHERE username = ?"
    );

    $stmt->bind_param(
        "is",
        $minutes,
        $username
    );

    return $stmt->execute();
}

    public function isLocked($username)
{
    $stmt = $this->db->prepare(
        "SELECT locked_until
            FROM users
            WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    if (!$result || !$result['locked_until']) {
        return false;
    }

    return strtotime($result['locked_until']) > time();
}

    public function getLoginAttempts($username)
{
    $stmt = $this->db->prepare(
        "SELECT login_attempts
            FROM users
            WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result()->fetch_assoc();

    return $result['login_attempts'] ?? 0;
}

    public function create(
        $username,
        $email,
        $password,
        $securePassword
    ) {
        $stmt = $this->db->prepare(
            "INSERT INTO users
            (username,email,password,secure_password)
            VALUES (?,?,?,?)"
        );

        $stmt->bind_param(
            "ssss",
            $username,
            $email,
            $password,
            $securePassword
        );

        return $stmt->execute();
    }
}