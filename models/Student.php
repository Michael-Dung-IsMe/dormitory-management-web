<?php

class Student extends BaseModel {
    private $table_name = "Student";

    protected $tableClause   = 'Student';
    protected $selectClause  = '*';
    protected $joinClause    = '';
    protected $searchColumns = ['full_name', 'student_code'];
    protected $orderBy       = 'student_id DESC';

    public $student_id;
    public $full_name;
    public $student_code;
    public $dob;
    public $phone;
    public $email;
    public $department;
    public $status;

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE student_id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->student_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->full_name = $row['full_name'];
            $this->student_code = $row['student_code'];
            $this->dob = $row['dob'];
            $this->phone = $row['phone'];
            $this->email = $row['email'];
            $this->department = $row['department'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET full_name=:full_name, student_code=:student_code, dob=:dob, phone=:phone, email=:email, department=:department, status=:status";

        $stmt = $this->conn->prepare($query);

        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->student_code = htmlspecialchars(strip_tags($this->student_code));
        $this->dob = htmlspecialchars(strip_tags($this->dob));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->department = htmlspecialchars(strip_tags($this->department));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":student_code", $this->student_code);
        $stmt->bindParam(":dob", $this->dob);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":department", $this->department);
        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET full_name=:full_name, student_code=:student_code, dob=:dob, phone=:phone, email=:email, department=:department, status=:status 
                  WHERE student_id = :student_id";

        $stmt = $this->conn->prepare($query);

        $this->student_id = htmlspecialchars(strip_tags($this->student_id));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->student_code = htmlspecialchars(strip_tags($this->student_code));
        $this->dob = htmlspecialchars(strip_tags($this->dob));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->department = htmlspecialchars(strip_tags($this->department));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(':student_id', $this->student_id);
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':student_code', $this->student_code);
        $stmt->bindParam(':dob', $this->dob);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':department', $this->department);
        $stmt->bindParam(':status', $this->status);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE student_id = ?";
        $stmt = $this->conn->prepare($query);
        $this->student_id = htmlspecialchars(strip_tags($this->student_id));
        $stmt->bindParam(1, $this->student_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
