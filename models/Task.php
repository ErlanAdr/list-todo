<?php

class Task {
    private $conn;
    private $table_name = "tasks";

    public $id;
    public $name;
    public $detail;
    public $assigned_to;
    public $status;
    public $assignment_date;
    public $created_at;
    
    // Arrays for advanced features
    public $urls = [];
    public $images = [];

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read tasks with user info
    public function readAll($status_filter = null) {
        $query = "SELECT t.id, t.name, t.detail, t.assigned_to, t.status, t.assignment_date, t.created_at, u.name as assignee_name 
                  FROM " . $this->table_name . " t 
                  LEFT JOIN users u ON t.assigned_to = u.id ";
                  
        if ($status_filter) {
            $query .= " WHERE t.status = :status ";
        }
                  
        $query .= " ORDER BY t.created_at DESC";

        $stmt = $this->conn->prepare($query);
        
        if ($status_filter) {
            $stmt->bindParam(":status", $status_filter);
        }

        $stmt->execute();
        
        // Fetch tasks and append urls/images
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($tasks as &$t) {
            $t['urls'] = $this->getUrls($t['id']);
            $t['images'] = $this->getImages($t['id']);
        }
        return $tasks;
    }

    // Read single task
    public function readOne() {
        $query = "SELECT t.id, t.name, t.detail, t.assigned_to, t.status, t.assignment_date, t.created_at, u.name as assignee_name 
                  FROM " . $this->table_name . " t 
                  LEFT JOIN users u ON t.assigned_to = u.id 
                  WHERE t.id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->name = $row['name'];
            $this->detail = $row['detail'];
            $this->assigned_to = $row['assigned_to'];
            $this->status = $row['status'];
            $this->assignment_date = $row['assignment_date'];
            
            $this->urls = $this->getUrls($this->id);
            $this->images = $this->getImages($this->id);
        }
    }

    // Create task
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET name=:name, detail=:detail, assigned_to=:assigned_to, status=:status, assignment_date=:assignment_date";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->detail = htmlspecialchars(strip_tags($this->detail));
        $this->assigned_to = !empty($this->assigned_to) ? htmlspecialchars(strip_tags($this->assigned_to)) : null;
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->assignment_date = !empty($this->assignment_date) ? htmlspecialchars(strip_tags($this->assignment_date)) : null;

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":detail", $this->detail);
        $stmt->bindParam(":assigned_to", $this->assigned_to);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":assignment_date", $this->assignment_date);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            $this->saveUrls();
            $this->saveImages();
            return true;
        }
        return false;
    }

    // Update task
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name=:name, detail=:detail, assigned_to=:assigned_to, status=:status, assignment_date=:assignment_date 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->detail = htmlspecialchars(strip_tags($this->detail));
        $this->assigned_to = !empty($this->assigned_to) ? htmlspecialchars(strip_tags($this->assigned_to)) : null;
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->assignment_date = !empty($this->assignment_date) ? htmlspecialchars(strip_tags($this->assignment_date)) : null;
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":detail", $this->detail);
        $stmt->bindParam(":assigned_to", $this->assigned_to);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":assignment_date", $this->assignment_date);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            // Delete old urls and re-insert
            $this->conn->exec("DELETE FROM task_urls WHERE task_id = " . $this->id);
            $this->saveUrls();
            
            // We append new images, don't delete old ones automatically unless requested, 
            // but for simplicity in this update we just append new uploaded ones.
            $this->saveImages();
            return true;
        }
        return false;
    }

    // Delete task
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        // Delete associated physical image files
        $images = $this->getImages($this->id);
        foreach($images as $img) {
            $file = __DIR__ . '/../public/' . $img['file_path'];
            if(file_exists($file)) {
                unlink($file);
            }
        }

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Helpers for URLs
    private function getUrls($task_id) {
        $stmt = $this->conn->prepare("SELECT id, url FROM task_urls WHERE task_id = ?");
        $stmt->execute([$task_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function saveUrls() {
        if(!empty($this->urls) && is_array($this->urls)) {
            $stmt = $this->conn->prepare("INSERT INTO task_urls (task_id, url) VALUES (?, ?)");
            foreach($this->urls as $url) {
                if(!empty(trim($url))) {
                    $stmt->execute([$this->id, htmlspecialchars(strip_tags($url))]);
                }
            }
        }
    }
    
    // Helpers for Images
    private function getImages($task_id) {
        $stmt = $this->conn->prepare("SELECT id, file_path FROM task_images WHERE task_id = ?");
        $stmt->execute([$task_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function saveImages() {
        if(!empty($this->images) && is_array($this->images)) {
            $stmt = $this->conn->prepare("INSERT INTO task_images (task_id, file_path) VALUES (?, ?)");
            foreach($this->images as $path) {
                if(!empty(trim($path))) {
                    $stmt->execute([$this->id, htmlspecialchars(strip_tags($path))]);
                }
            }
        }
    }
}
?>
