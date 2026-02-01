<?php

class AuthController
{
    private $pdo;
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function signup()
    {
        // Registration logic would go here
    }
    public function login()
    {
        // Login logic would go here
    }
    public function logout()
    {
        // Logout logic would go here
    }
}