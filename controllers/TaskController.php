<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';

class TaskController {
    private $db;
    private $task;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->task = new Task($this->db);
        $this->user = new User($this->db);
    }

    public function index() {
        $status_filter = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : null;
        $stmt = $this->task->readAll($status_filter);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Group tasks by status for a board view
        $board = [
            'To Do' => [],
            'In Progress' => [],
            'Done' => []
        ];
        
        foreach ($tasks as $t) {
            if (array_key_exists($t['status'], $board)) {
                $board[$t['status']][] = $t;
            }
        }

        $content = __DIR__ . '/../views/tasks/index.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function create() {
        $stmt_users = $this->user->readAll();
        $users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
        
        $task = ['id'=>'', 'name'=>'', 'detail'=>'', 'assigned_to'=>'', 'url'=>'', 'status'=>'To Do', 'assignment_date'=>''];
        $is_edit = false;

        $content = __DIR__ . '/../views/tasks/form.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: index.php");
            exit;
        }

        $this->task->id = $_GET['id'];
        $this->task->readOne();

        if (empty($this->task->name)) {
            header("Location: index.php");
            exit;
        }

        $task = [
            'id' => $this->task->id,
            'name' => $this->task->name,
            'detail' => $this->task->detail,
            'assigned_to' => $this->task->assigned_to,
            'url' => $this->task->url,
            'status' => $this->task->status,
            'assignment_date' => $this->task->assignment_date
        ];
        
        $stmt_users = $this->user->readAll();
        $users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
        $is_edit = true;

        $content = __DIR__ . '/../views/tasks/form.php';
        require __DIR__ . '/../views/layout.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['name'])) {
                $_SESSION['message'] = "Task name is required.";
                $_SESSION['msg_type'] = "error";
                header("Location: index.php?action=task_create");
                exit;
            }

            $this->task->name = $_POST['name'];
            $this->task->detail = $_POST['detail'];
            $this->task->assigned_to = $_POST['assigned_to'];
            $this->task->url = $_POST['url'];
            $this->task->status = $_POST['status'];
            $this->task->assignment_date = $_POST['assignment_date'];

            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $this->task->id = $_POST['id'];
                if ($this->task->update()) {
                    $_SESSION['message'] = "Task updated successfully!";
                    $_SESSION['msg_type'] = "success";
                } else {
                    $_SESSION['message'] = "Failed to update task.";
                    $_SESSION['msg_type'] = "error";
                }
            } else {
                if ($this->task->create()) {
                    $_SESSION['message'] = "Task created successfully!";
                    $_SESSION['msg_type'] = "success";
                } else {
                    $_SESSION['message'] = "Failed to create task.";
                    $_SESSION['msg_type'] = "error";
                }
            }
            header("Location: index.php");
            exit;
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $this->task->id = $_POST['id'];
            if ($this->task->delete()) {
                $_SESSION['message'] = "Task deleted successfully!";
                $_SESSION['msg_type'] = "success";
            } else {
                $_SESSION['message'] = "Failed to delete task.";
                $_SESSION['msg_type'] = "error";
            }
        }
        header("Location: index.php");
        exit;
    }
    
    public function update_status() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
            $this->task->id = $_POST['id'];
            $this->task->readOne();
            
            $this->task->status = $_POST['status'];
            if ($this->task->update()) {
                $_SESSION['message'] = "Task status updated!";
                $_SESSION['msg_type'] = "success";
            } else {
                 $_SESSION['message'] = "Failed to update status.";
                 $_SESSION['msg_type'] = "error";
            }
        }
        header("Location: index.php");
        exit;
    }
}
?>
