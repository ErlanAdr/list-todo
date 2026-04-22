<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Department.php';

class TaskController {
    private $db;
    private $task;
    private $user;
    private $department;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->task = new Task($this->db);
        $this->user = new User($this->db);
        $this->department = new Department($this->db);
    }

    public function index() {
        // Get filter parameters from GET request
        $status_filter = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : null;
        $assignee_filter = isset($_GET['assigned_to']) && $_GET['assigned_to'] !== '' ? $_GET['assigned_to'] : null;
        $date_filter = isset($_GET['date']) && $_GET['date'] !== '' ? $_GET['date'] : null;
        
        $tasks = $this->task->readAll($status_filter, $assignee_filter, $date_filter);
        
        // Fetch regular users for the filter dropdown
        $stmt_users = $this->user->readRegularUsers();
        $users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
        
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
        $stmt_users = $this->user->readRegularUsers(); // Exclude super_admin
        $users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt_depts = $this->department->readAll();
        $departments = $stmt_depts->fetchAll(PDO::FETCH_ASSOC);
        
        $task = ['id'=>'', 'name'=>'', 'detail'=>'', 'assigned_to'=>'', 'department_id'=>'', 'status'=>'To Do', 'assignment_date'=>'', 'urls'=>[], 'images'=>[], 'comments'=>[]];
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
            'department_id' => $this->task->department_id,
            'status' => $this->task->status,
            'assignment_date' => $this->task->assignment_date,
            'urls' => $this->task->urls,
            'images' => $this->task->images,
            'comments' => $this->task->comments,
            'created_by' => $this->task->created_by,
            'creator_name' => $this->task->creator_name
        ];
        
        $stmt_users = $this->user->readRegularUsers(); // Exclude super_admin
        $users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt_depts = $this->department->readAll();
        $departments = $stmt_depts->fetchAll(PDO::FETCH_ASSOC);
        
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
            $this->task->department_id = $_POST['department_id'];
            $this->task->status = $_POST['status'];
            $this->task->assignment_date = $_POST['assignment_date'];

            // Handle URLs array
            $this->task->urls = isset($_POST['urls']) && is_array($_POST['urls']) ? $_POST['urls'] : [];

            // Handle Images Upload with Error Checking
            $uploaded_images = [];
            $upload_errors = [];
            
            if(isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $upload_dir = __DIR__ . '/../public/uploads/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                
                // Get Max Upload Size in Bytes (default fallback 2MB if ini_get fails to parse cleanly)
                $max_size = 2 * 1024 * 1024; 
                $ini_max = ini_get('upload_max_filesize');
                if (preg_match('/^(\d+)(K|M|G|T)?$/i', $ini_max, $match)) {
                    $val = (int)$match[1];
                    switch (strtoupper($match[2] ?? '')) {
                        case 'G': $val *= 1024;
                        case 'M': $val *= 1024;
                        case 'K': $val *= 1024;
                    }
                    $max_size = $val;
                }
                
                for($i = 0; $i < count($_FILES['images']['name']); $i++) {
                    $tmp_name = $_FILES['images']['tmp_name'][$i];
                    $name = basename($_FILES['images']['name'][$i]);
                    $error_code = $_FILES['images']['error'][$i];
                    $size = $_FILES['images']['size'][$i];
                    
                    if ($error_code !== UPLOAD_ERR_OK) {
                        $upload_errors[] = "File '$name' error: " . $this->getUploadErrorMessage($error_code);
                        continue;
                    }
                    
                    if ($size > $max_size) {
                         $upload_errors[] = "File '$name' error: Ukuran melebihi batas maksimal server.";
                         continue;
                    }
                    
                    // Generate unique name
                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $valid_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                    
                    if(in_array($ext, $valid_ext)) {
                        $new_name = uniqid('task_') . '.' . $ext;
                        if(move_uploaded_file($tmp_name, $upload_dir . $new_name)) {
                            $uploaded_images[] = 'uploads/' . $new_name;
                        } else {
                            $upload_errors[] = "File '$name' error: Gagal memindahkan file ke folder uploads.";
                        }
                    } else {
                        $upload_errors[] = "File '$name' error: Format tidak didukung.";
                    }
                }
            }
            $this->task->images = $uploaded_images;

            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $this->task->id = $_POST['id'];
                if ($this->task->update()) {
                    if (!empty($upload_errors)) {
                        $_SESSION['message'] = "Task diupdate, TAPI ada gambar yang gagal: " . implode(" | ", $upload_errors);
                        $_SESSION['msg_type'] = "error";
                    } else {
                        $_SESSION['message'] = "Task updated successfully!";
                        $_SESSION['msg_type'] = "success";
                    }
                } else {
                    $_SESSION['message'] = "Failed to update task.";
                    $_SESSION['msg_type'] = "error";
                }
            } else {
                // For creation, set created_by
                $this->task->created_by = $_SESSION['user_id'];
                
                if ($this->task->create()) {
                    if (!empty($upload_errors)) {
                        $_SESSION['message'] = "Task dibuat, TAPI ada gambar yang gagal: " . implode(" | ", $upload_errors);
                        $_SESSION['msg_type'] = "error";
                    } else {
                        $_SESSION['message'] = "Task created successfully!";
                        $_SESSION['msg_type'] = "success";
                    }
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
            $this->task->status = $_POST['status'];
            
            if ($this->task->updateStatusOnly()) {
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
    
    public function add_comment() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id']) && !empty($_POST['comment'])) {
            $this->task->id = $_POST['task_id'];
            if ($this->task->addComment($_SESSION['user_id'], $_POST['comment'])) {
                $_SESSION['message'] = "Feedback added!";
                $_SESSION['msg_type'] = "success";
            } else {
                 $_SESSION['message'] = "Failed to add feedback.";
                 $_SESSION['msg_type'] = "error";
            }
            header("Location: index.php?action=task_edit&id=" . $_POST['task_id'] . "#comments");
            exit;
        }
    }
    
    private function getUploadErrorMessage($code) {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE: return "Melebihi batas upload_max_filesize di php.ini";
            case UPLOAD_ERR_FORM_SIZE: return "Melebihi batas MAX_FILE_SIZE di form HTML";
            case UPLOAD_ERR_PARTIAL: return "File hanya terupload sebagian";
            case UPLOAD_ERR_NO_FILE: return "Tidak ada file yang diupload";
            case UPLOAD_ERR_NO_TMP_DIR: return "Folder temporary hilang";
            case UPLOAD_ERR_CANT_WRITE: return "Gagal menulis file ke disk";
            case UPLOAD_ERR_EXTENSION: return "Upload dihentikan oleh ekstensi PHP";
            default: return "Unknown upload error";
        }
    }
}
?>
