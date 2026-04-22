<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->username = $_POST['username'];
            $this->user->password = $_POST['password'];

            if ($this->user->login()) {
                $_SESSION['user_id'] = $this->user->id;
                $_SESSION['user_name'] = $this->user->name;
                $_SESSION['user_role'] = $this->user->role;
                header("Location: index.php");
                exit;
            } else {
                $_SESSION['message'] = "Invalid username or password.";
                $_SESSION['msg_type'] = "error";
                header("Location: index.php?action=login");
                exit;
            }
        } else {
            require __DIR__ . '/../views/auth/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }
}
?>
