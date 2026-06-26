<?php

require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Helpers/validators.php';

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register($username, $email, $password, $confirmPassword)
    {
        $errors = [];

        if ($error = validateUsername($username)) {
            $errors[] = $error;
        }

        if ($error = validateEmail($email)) {
            $errors[] = $error;
        }

        if ($error = validatePassword($password)) {
            $errors[] = $error;
        }

        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match";
        }

        if ($this->userModel->findByUsername($username)) {
            $errors[] = "Username already exists";
        }

        if ($this->userModel->findByEmail($email)) {
            $errors[] = "Email already exists";
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        $hashedPassword = password_hash(
            $password,
            PASSWORD_BCRYPT,
            ['cost' => 12]
        );

        $created = $this->userModel->create(
            $username,
            $email,
            $password,
            $hashedPassword
        );

        return [
            'success' => $created,
            'errors' => []
        ];
    }
}