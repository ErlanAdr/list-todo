<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        
        // Restrict all user routes to super_admin only
        if ($_SESSION['user_role'] !== 'super_admin') {
            $_SESSION['message'] = "Access denied. Super Admin only.";
            $_SESSION['msg_type'] = "error";
            header("Location: index.php");
            exit;
        }
    }

    public function index() {
        $stmt = $this->user->readAll();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $content = __DIR__ . '/../views/users/index.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['name'])) {
                $this->user->username = $_POST['username'];
                $this->user->password = $_POST['password'];
                $this->user->name = $_POST['name'];
                $this->user->role = isset($_POST['role']) ? $_POST['role'] : 'user';

                if ($this->user->create()) {
                    $_SESSION['message'] = "User added successfully!";
                    $_SESSION['msg_type'] = "success";
                } else {
                    $_SESSION['message'] = "Failed to add user. Username might already exist.";
                    $_SESSION['msg_type'] = "error";
                }
            } else {
                $_SESSION['message'] = "All fields are required.";
                $_SESSION['msg_type'] = "error";
            }
            header("Location: index.php?action=users");
            exit;
        }
    }
}
?>
