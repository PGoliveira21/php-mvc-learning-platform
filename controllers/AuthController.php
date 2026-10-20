<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin() {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function showRegister() {
        require __DIR__ . '/../views/auth/register.php';
    }

    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByUsername($username);

        if ($user && $this->userModel->verifyPassword($user['password_hash'], $password)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: /?page=dashboard");
            exit;
        }

        $error = "Usuário ou senha inválidos.";
        require __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (strlen($username) < 3 || strlen($password) < 4) {
            $error = "Usuário (mín. 3) e senha (mín. 4) muito curtos.";
            require __DIR__ . '/../views/auth/register.php';
            return;
        }

        if ($this->userModel->findByUsername($username)) {
            $error = "Usuário já existe.";
            require __DIR__ . '/../views/auth/register.php';
            return;
        }

        $this->userModel->register($username, $password);
        header("Location: /?page=login&msg=cadastrado");
        exit;
    }

    public function logout() {
        session_destroy();
        header("Location: /?page=login");
        exit;
    }
}