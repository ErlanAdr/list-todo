<?php

class Task {
    private $conn;
    private $table_name = "tasks";

    public $id;
    public $name;
    public $detail;
    public $assigned_to;
    public $url;
    public $status;
    public $assignment_date;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read tasks with user info
    public function readAll($status_filter = null) {
        $query = "SELECT t.id, t.name, t.detail, t.assigned_to, t.url, t.status, t.assignment_date, t.created_at, u.name as assignee_name 
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
        return $stmt;
    }

    // Read single task
    public function readOne() {
        $query = "SELECT t.id, t.name, t.detail, t.assigned_to, t.url, t.status, t.assignment_date, t.created_at, u.name as assignee_name 
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
            $this->url = $row['url'];
            $this->status = $row['status'];
            $this->assignment_date = $row['assignment_date'];
        }
    }

    // Create task
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET name=:name, detail=:detail, assigned_to=:assigned_to, url=:url, status=:status, assignment_date=:assignment_date";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->detail = htmlspecialchars(strip_tags($this->detail));
        $this->assigned_to = !empty($this->assigned_to) ? htmlspecialchars(strip_tags($this->assigned_to)) : null;
        $this->url = htmlspecialchars(strip_tags($this->url));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->assignment_date = !empty($this->assignment_date) ? htmlspecialchars(strip_tags($this->assignment_date)) : null;

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":detail", $this->detail);
        $stmt->bindParam(":assigned_to", $this->assigned_to);
        $stmt->bindParam(":url", $this->url);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":assignment_date", $this->assignment_date);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update task
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET name=:name, detail=:detail, assigned_to=:assigned_to, url=:url, status=:status, assignment_date=:assignment_date 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->detail = htmlspecialchars(strip_tags($this->detail));
        $this->assigned_to = !empty($this->assigned_to) ? htmlspecialchars(strip_tags($this->assigned_to)) : null;
        $this->url = htmlspecialchars(strip_tags($this->url));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->assignment_date = !empty($this->assignment_date) ? htmlspecialchars(strip_tags($this->assignment_date)) : null;
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":detail", $this->detail);
        $stmt->bindParam(":assigned_to", $this->assigned_to);
        $stmt->bindParam(":url", $this->url);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":assignment_date", $this->assignment_date);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
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

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
