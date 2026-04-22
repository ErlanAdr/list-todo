<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Department.php';

class DepartmentController {
    private $db;
    private $department;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->department = new Department($this->db);
        
        // Restrict to super_admin
        if ($_SESSION['user_role'] !== 'super_admin') {
            $_SESSION['message'] = "Access denied. Super Admin only.";
            $_SESSION['msg_type'] = "error";
            header("Location: index.php");
            exit;
        }
    }

    public function index() {
        $stmt = $this->department->readAll();
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $content = __DIR__ . '/../views/departments/index.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['name'])) {
                $this->department->name = $_POST['name'];
                if ($this->department->create()) {
                    $_SESSION['message'] = "Department added successfully!";
                    $_SESSION['msg_type'] = "success";
                } else {
                    $_SESSION['message'] = "Failed to add department.";
                    $_SESSION['msg_type'] = "error";
                }
            } else {
                $_SESSION['message'] = "Department name is required.";
                $_SESSION['msg_type'] = "error";
            }
            header("Location: index.php?action=departments");
            exit;
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $this->department->id = $_POST['id'];
            if ($this->department->delete()) {
                $_SESSION['message'] = "Department deleted successfully!";
                $_SESSION['msg_type'] = "success";
            } else {
                $_SESSION['message'] = "Failed to delete department. It might be linked to existing tasks.";
                $_SESSION['msg_type'] = "error";
            }
            header("Location: index.php?action=departments");
            exit;
        }
    }
}
?>
