<?php

class Notice extends BaseModel {
    private $table_name = "Notice";

    protected $tableClause   = 'Notice n';
    protected $selectClause  = 'n.*, r.room_number, s.full_name as student_name';
    protected $joinClause    = 'LEFT JOIN Room r ON n.room_id = r.room_id LEFT JOIN Student s ON n.student_id = s.student_id';
    protected $searchColumns = ['n.description', 'n.target_type', 'r.room_number', 's.full_name'];
    protected $orderBy       = 'n.date DESC, n.notice_id DESC';

    public $notice_id;
    public $target_type;
    public $room_id;
    public $student_id;
    public $description;
    public $date;
    public $room_number;
    public $student_name;

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE notice_id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->notice_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->target_type = $row['target_type'];
            $this->room_id = $row['room_id'];
            $this->student_id = $row['student_id'];
            $this->description = $row['description'];
            $this->date = $row['date'];
            return true;
        }
        return false;
    }

    public function create() {
        $this->date = date('Y-m-d');

        if ($this->target_type == 'Cả tòa') {
            $this->room_id = null;
            $this->student_id = null;
        } elseif ($this->target_type == 'Phòng') {
            $this->student_id = null;
        } elseif ($this->target_type == 'Cá nhân') {
            $this->room_id = null;
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  SET target_type=:target_type, room_id=:room_id, student_id=:student_id, description=:description, date=:date";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":target_type", $this->target_type);
        
        if(empty($this->room_id)) {
            $r_id = null;
            $stmt->bindParam(":room_id", $r_id, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(":room_id", $this->room_id);
        }

        if(empty($this->student_id)) {
            $s_id = null;
            $stmt->bindParam(":student_id", $s_id, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(":student_id", $this->student_id);
        }

        $this->description = htmlspecialchars(strip_tags($this->description));
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":date", $this->date);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        if ($this->target_type == 'Cả tòa') {
            $this->room_id = null;
            $this->student_id = null;
        } elseif ($this->target_type == 'Phòng') {
            $this->student_id = null;
        } elseif ($this->target_type == 'Cá nhân') {
            $this->room_id = null;
        }

        $query = "UPDATE " . $this->table_name . " 
                  SET target_type=:target_type, room_id=:room_id, student_id=:student_id, description=:description, date=:date
                  WHERE notice_id = :notice_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":notice_id", $this->notice_id);
        $stmt->bindParam(":target_type", $this->target_type);
        
        if(empty($this->room_id)) {
            $r_id = null;
            $stmt->bindParam(":room_id", $r_id, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(":room_id", $this->room_id);
        }

        if(empty($this->student_id)) {
            $s_id = null;
            $stmt->bindParam(":student_id", $s_id, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(":student_id", $this->student_id);
        }

        $this->description = htmlspecialchars(strip_tags($this->description));
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":date", $this->date);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE notice_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->notice_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
