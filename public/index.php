<?php
session_start();

// Simple routing
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'index':
        require_once __DIR__ . '/../controllers/TaskController.php';
        $controller = new TaskController();
        $controller->index();
        break;

    case 'task_create':
        require_once __DIR__ . '/../controllers/TaskController.php';
        $controller = new TaskController();
        $controller->create();
        break;

    case 'task_edit':
        require_once __DIR__ . '/../controllers/TaskController.php';
        $controller = new TaskController();
        $controller->edit();
        break;

    case 'task_store':
        require_once __DIR__ . '/../controllers/TaskController.php';
        $controller = new TaskController();
        $controller->store();
        break;

    case 'task_delete':
        require_once __DIR__ . '/../controllers/TaskController.php';
        $controller = new TaskController();
        $controller->delete();
        break;
        
    case 'task_update_status':
        require_once __DIR__ . '/../controllers/TaskController.php';
        $controller = new TaskController();
        $controller->update_status();
        break;

    case 'users':
        require_once __DIR__ . '/../controllers/UserController.php';
        $controller = new UserController();
        $controller->index();
        break;

    case 'user_create':
        require_once __DIR__ . '/../controllers/UserController.php';
        $controller = new UserController();
        $controller->create();
        break;

    default:
        require_once __DIR__ . '/../controllers/TaskController.php';
        $controller = new TaskController();
        $controller->index();
        break;
}
?>
