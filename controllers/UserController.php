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
    }

    public function index() {
        $stmt = $this->user->readAll();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $content = __DIR__ . '/../views/users/index.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['name'])) {
                $this->user->name = $_POST['name'];
                if ($this->user->create()) {
                    $_SESSION['message'] = "User added successfully!";
                    $_SESSION['msg_type'] = "success";
                } else {
                    $_SESSION['message'] = "Failed to add user.";
                    $_SESSION['msg_type'] = "error";
                }
            } else {
                $_SESSION['message'] = "Name is required.";
                $_SESSION['msg_type'] = "error";
            }
            header("Location: index.php?action=users");
            exit;
        }
    }
}
?>
