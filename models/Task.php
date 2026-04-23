<?php

class Task {
    private $conn;
    private $table_name = "tasks";

    public $id;
    public $name;
    public $detail;
    public $assigned_to;
    public $department_id;
    public $created_by;
    public $status;
    public $review_status;
    public $assignment_date;
    public $created_at;
    
    // Arrays for advanced features
    public $urls = [];
    public $images = [];
    public $comments = [];
    public $comment_count = 0;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Read tasks with user info and filters
    public function readAll($status_filter = null, $assignee_filter = null, $date_filter = null) {
        $query = "SELECT t.id, t.name, t.detail, t.assigned_to, t.status, t.review_status, t.assignment_date, t.created_at, 
                  u.name as assignee_name, d.name as department_name, creator.name as creator_name,
                  (SELECT COUNT(*) FROM task_comments tc WHERE tc.task_id = t.id) as comment_count
                  FROM " . $this->table_name . " t 
                  LEFT JOIN users u ON t.assigned_to = u.id 
                  LEFT JOIN users creator ON t.created_by = creator.id
                  LEFT JOIN departments d ON t.department_id = d.id
                  WHERE 1=1 
                  AND NOT (t.status = 'Done' AND t.created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)) ";
        
        $params = [];
        
        if (!empty($status_filter)) {
            $query .= " AND t.status = :status ";
            $params[':status'] = $status_filter;
        }
        
        if (!empty($assignee_filter)) {
            $query .= " AND t.assigned_to = :assigned_to ";
            $params[':assigned_to'] = $assignee_filter;
        }
        
        if (!empty($date_filter)) {
            $query .= " AND t.assignment_date = :date ";
            $params[':date'] = $date_filter;
        }
                  
        $query .= " ORDER BY t.created_at DESC";

        $stmt = $this->conn->prepare($query);
        
        foreach($params as $key => &$val) {
            $stmt->bindParam($key, $val);
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
        $query = "SELECT t.id, t.name, t.detail, t.assigned_to, t.department_id, t.created_by, t.status, t.review_status, t.assignment_date, t.created_at, 
                  u.name as assignee_name, d.name as department_name, creator.name as creator_name 
                  FROM " . $this->table_name . " t 
                  LEFT JOIN users u ON t.assigned_to = u.id 
                  LEFT JOIN users creator ON t.created_by = creator.id
                  LEFT JOIN departments d ON t.department_id = d.id
                  WHERE t.id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->name = $row['name'];
            $this->detail = $row['detail'];
            $this->assigned_to = $row['assigned_to'];
            $this->department_id = $row['department_id'];
            $this->created_by = $row['created_by'];
            $this->status = $row['status'];
            $this->review_status = $row['review_status'];
            $this->assignment_date = $row['assignment_date'];
            
            // Add custom properties not originally in class definition but useful for view
            $this->creator_name = $row['creator_name'];
            $this->department_name = $row['department_name'];
            $this->assignee_name = $row['assignee_name'];
            
            $this->urls = $this->getUrls($this->id);
            $this->images = $this->getImages($this->id);
            $this->comments = $this->getComments($this->id);
        }
    }

    // Create task
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET name=:name, detail=:detail, assigned_to=:assigned_to, department_id=:department_id, created_by=:created_by, status=:status, review_status=:review_status, assignment_date=:assignment_date";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->detail = htmlspecialchars(strip_tags($this->detail));
        $this->assigned_to = !empty($this->assigned_to) ? htmlspecialchars(strip_tags($this->assigned_to)) : null;
        $this->department_id = !empty($this->department_id) ? htmlspecialchars(strip_tags($this->department_id)) : null;
        $this->created_by = htmlspecialchars(strip_tags($this->created_by));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->review_status = !empty($this->review_status) ? htmlspecialchars(strip_tags($this->review_status)) : 'None';
        $this->assignment_date = !empty($this->assignment_date) ? htmlspecialchars(strip_tags($this->assignment_date)) : null;

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":detail", $this->detail);
        $stmt->bindParam(":assigned_to", $this->assigned_to);
        $stmt->bindParam(":department_id", $this->department_id);
        $stmt->bindParam(":created_by", $this->created_by);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":review_status", $this->review_status);
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
                  SET name=:name, detail=:detail, assigned_to=:assigned_to, department_id=:department_id, status=:status, review_status=:review_status, assignment_date=:assignment_date 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->detail = htmlspecialchars(strip_tags($this->detail));
        $this->assigned_to = !empty($this->assigned_to) ? htmlspecialchars(strip_tags($this->assigned_to)) : null;
        $this->department_id = !empty($this->department_id) ? htmlspecialchars(strip_tags($this->department_id)) : null;
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->review_status = !empty($this->review_status) ? htmlspecialchars(strip_tags($this->review_status)) : 'None';
        $this->assignment_date = !empty($this->assignment_date) ? htmlspecialchars(strip_tags($this->assignment_date)) : null;
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":detail", $this->detail);
        $stmt->bindParam(":assigned_to", $this->assigned_to);
        $stmt->bindParam(":department_id", $this->department_id);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":review_status", $this->review_status);
        $stmt->bindParam(":assignment_date", $this->assignment_date);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            $this->conn->exec("DELETE FROM task_urls WHERE task_id = " . $this->id);
            $this->saveUrls();
            $this->saveImages(); // Add images will just append. We should probably clear them if we had a full management system, but we'll leave it as additive for now.
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
    
    // Update status only
    public function updateStatusOnly() {
        $query = "UPDATE " . $this->table_name . " SET status=:status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update review status only
    public function updateReviewStatusOnly() {
        $query = "UPDATE " . $this->table_name . " SET review_status=:review_status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->review_status = htmlspecialchars(strip_tags($this->review_status));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(":review_status", $this->review_status);
        $stmt->bindParam(":id", $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Add Comment
    public function addComment($user_id, $comment_text) {
        $stmt = $this->conn->prepare("INSERT INTO task_comments (task_id, user_id, comment) VALUES (?, ?, ?)");
        if($stmt->execute([$this->id, $user_id, htmlspecialchars(strip_tags($comment_text))])) {
            return true;
        }
        return false;
    }
    
    // Get Comments
    private function getComments($task_id) {
        $stmt = $this->conn->prepare("
            SELECT tc.id, tc.comment, tc.created_at, u.name as user_name 
            FROM task_comments tc 
            JOIN users u ON tc.user_id = u.id 
            WHERE tc.task_id = ? 
            ORDER BY tc.created_at ASC
        ");
        $stmt->execute([$task_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Read Reports (Done tasks older than 7 days)
    public function readReports() {
        $query = "SELECT t.id, t.name, t.detail, t.assigned_to, t.status, t.review_status, t.assignment_date, t.created_at, DATE(t.created_at) as report_date,
                  u.name as assignee_name, d.name as department_name, creator.name as creator_name,
                  (SELECT COUNT(*) FROM task_comments tc WHERE tc.task_id = t.id) as comment_count
                  FROM " . $this->table_name . " t 
                  LEFT JOIN users u ON t.assigned_to = u.id 
                  LEFT JOIN users creator ON t.created_by = creator.id
                  LEFT JOIN departments d ON t.department_id = d.id
                  WHERE t.status = 'Done' AND t.created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
                  ORDER BY t.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        // Fetch tasks and append urls/images
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($tasks as &$t) {
            $t['urls'] = $this->getUrls($t['id']);
            $t['images'] = $this->getImages($t['id']);
        }
        return $tasks;
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
