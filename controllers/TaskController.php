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
        $tasks = $this->task->readAll($status_filter);
        
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
        
        $task = ['id'=>'', 'name'=>'', 'detail'=>'', 'assigned_to'=>'', 'status'=>'To Do', 'assignment_date'=>'', 'urls'=>[], 'images'=>[]];
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
            'status' => $this->task->status,
            'assignment_date' => $this->task->assignment_date,
            'urls' => $this->task->urls,
            'images' => $this->task->images
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
            $this->task->status = $_POST['status'];
            $this->task->assignment_date = $_POST['assignment_date'];

            // Handle URLs array
            $this->task->urls = isset($_POST['urls']) && is_array($_POST['urls']) ? $_POST['urls'] : [];

            // Handle Images Upload
            $uploaded_images = [];
            if(isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $upload_dir = __DIR__ . '/../public/uploads/';
                
                for($i = 0; $i < count($_FILES['images']['name']); $i++) {
                    $tmp_name = $_FILES['images']['tmp_name'][$i];
                    $name = basename($_FILES['images']['name'][$i]);
                    
                    // Generate unique name
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $valid_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    
                    if(in_array($ext, $valid_ext)) {
                        $new_name = uniqid('task_') . '.' . $ext;
                        if(move_uploaded_file($tmp_name, $upload_dir . $new_name)) {
                            $uploaded_images[] = 'uploads/' . $new_name;
                        }
                    }
                }
            }
            $this->task->images = $uploaded_images;

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
