<?php

class Contract extends BaseModel {
    private $table_name = "Contract";

    protected $tableClause   = 'Contract c';
    protected $selectClause  = 'c.*, s.full_name as student_name, s.student_code, r.room_number';
    protected $joinClause    = 'JOIN Student s ON c.student_id = s.student_id JOIN Room r ON c.room_id = r.room_id';
    protected $searchColumns = ['s.full_name', 's.student_code', 'r.room_number'];
    protected $orderBy       = 'c.contract_id DESC';

    public $contract_id;
    public $student_id;
    public $room_id;
    public $start_date;
    public $end_date;
    public $status;
    public $price;
    public $deposit;
    public $student_name;
    public $student_code;
    public $room_number;

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE contract_id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->contract_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->student_id = $row['student_id'];
            $this->room_id = $row['room_id'];
            $this->start_date = $row['start_date'];
            $this->end_date = $row['end_date'];
            $this->status = $row['status'];
            $this->price = $row['price'];
            $this->deposit = $row['deposit'];
            return true;
        }
        return false;
    }

    public function create() {
        if (!$this->checkRoomCapacity($this->room_id)) {
            return "full"; 
        }
        
        if ($this->checkStudentHasActiveContract($this->student_id)) {
            return "student_active"; 
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  SET student_id=:student_id, room_id=:room_id, start_date=:start_date, end_date=:end_date, 
                      status=:status, price=:price, deposit=:deposit";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":student_id", $this->student_id);
        $stmt->bindParam(":room_id", $this->room_id);
        $stmt->bindParam(":start_date", $this->start_date);
        
        if(empty($this->end_date)) {
            $end_date = null;
            $stmt->bindParam(":end_date", $end_date, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(":end_date", $this->end_date);
        }

        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":deposit", $this->deposit);

        if ($stmt->execute()) {
            return "success";
        }
        return "error";
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET student_id=:student_id, room_id=:room_id, start_date=:start_date, end_date=:end_date, 
                      status=:status, price=:price, deposit=:deposit
                  WHERE contract_id = :contract_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":contract_id", $this->contract_id);
        $stmt->bindParam(":student_id", $this->student_id);
        $stmt->bindParam(":room_id", $this->room_id);
        $stmt->bindParam(":start_date", $this->start_date);
        
        if(empty($this->end_date)) {
            $end_date = null;
            $stmt->bindParam(":end_date", $end_date, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(":end_date", $this->end_date);
        }

        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":deposit", $this->deposit);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE contract_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->contract_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }

    // Helper functions
    private function checkRoomCapacity($room_id) {
        $query = "SELECT capacity, 
                 (SELECT COUNT(*) FROM Contract WHERE room_id = ? AND status = 'Đang ở') as current_occupancy 
                 FROM Room WHERE room_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $room_id);
        $stmt->bindParam(2, $room_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && $row['current_occupancy'] < $row['capacity']) {
            return true;
        }
        return false;
    }

    private function checkStudentHasActiveContract($student_id) {
        $query = "SELECT COUNT(*) as count FROM Contract WHERE student_id = ? AND status = 'Đang ở'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $student_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && $row['count'] > 0) {
            return true;
        }
        return false;
    }
}
?>
