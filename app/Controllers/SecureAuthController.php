<?php

require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Services/Logger.php';
require_once __DIR__ . '/../Services/SessionManager.php';

class SecureAuthController
{
    private User $userModel;

    private int $maxAttempts = 5;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login($username, $password)
    {
        $user = $this->userModel->findByUsername(
            $username
        );

        if (!$user) {

            Logger::login(
                $username,
                false,
                'secure'
            );

            return [
                'success' => false,
                'message' => 'Invalid username or password'
            ];
        }

        if (
            !empty($user['locked_until']) &&
            strtotime($user['locked_until']) > time()
        ) {

            return [
                'success' => false,
                'message' =>
                    'Account temporarily locked'
            ];
        }

        if (
            password_verify(
                $password,
                $user['secure_password']
            )
        ) {

            $this->userModel->resetAttempts(
                $username
            );

            $this->userModel->updateLastLogin(
                $user['id']
            );

            SessionManager::login(
                $user,
                'secure'
            );

            Logger::login(
                $username,
                true,
                'secure'
            );

            return [
                'success' => true
            ];
        }

        $this->userModel->incrementAttempts(
            $username
        );

        $updatedUser =
            $this->userModel->findByUsername(
                $username
            );

        if (
            $updatedUser['login_attempts']
            >=
            $this->maxAttempts
        ) {

            $this->userModel->lockAccount(
                $username
            );

            Logger::attack(
                'ACCOUNT_LOCKED',
                $username,
                'Too many failed logins'
            );

            return [
                'success' => false,
                'message' =>
                    'Account locked for 15 minutes'
            ];
        }

        Logger::login(
            $username,
            false,
            'secure'
        );

        return [
            'success' => false,
            'message' =>
                'Invalid username or password'
        ];
    }
}