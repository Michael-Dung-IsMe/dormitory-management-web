<?php

class Room extends BaseModel {
    private $table_name = "Room";

    protected $tableClause   = 'Room r';
    protected $selectClause  = "r.*, (SELECT COUNT(*) FROM Contract c WHERE c.room_id = r.room_id AND c.status = '\u0110ang \u1edf') as current_occupancy";
    protected $joinClause    = '';
    protected $searchColumns = ['r.room_number'];
    protected $orderBy       = 'r.floor_number ASC, r.room_number ASC';

    public $room_id;
    public $room_number;
    public $floor_number;
    public $capacity;
    public $room_type;
    public $status;
    public $current_occupancy;

    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE room_id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->room_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->room_number = $row['room_number'];
            $this->floor_number = $row['floor_number'];
            $this->capacity = $row['capacity'];
            $this->room_type = $row['room_type'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET room_number=:room_number, floor_number=:floor_number, capacity=:capacity, room_type=:room_type, status=:status";

        $stmt = $this->conn->prepare($query);

        $this->room_number = htmlspecialchars(strip_tags($this->room_number));
        $this->floor_number = htmlspecialchars(strip_tags($this->floor_number));
        $this->capacity = htmlspecialchars(strip_tags($this->capacity));
        $this->room_type = htmlspecialchars(strip_tags($this->room_type));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(":room_number", $this->room_number);
        $stmt->bindParam(":floor_number", $this->floor_number);
        $stmt->bindParam(":capacity", $this->capacity);
        $stmt->bindParam(":room_type", $this->room_type);
        $stmt->bindParam(":status", $this->status);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET room_number=:room_number, floor_number=:floor_number, capacity=:capacity, room_type=:room_type, status=:status 
                  WHERE room_id = :room_id";

        $stmt = $this->conn->prepare($query);

        $this->room_id = htmlspecialchars(strip_tags($this->room_id));
        $this->room_number = htmlspecialchars(strip_tags($this->room_number));
        $this->floor_number = htmlspecialchars(strip_tags($this->floor_number));
        $this->capacity = htmlspecialchars(strip_tags($this->capacity));
        $this->room_type = htmlspecialchars(strip_tags($this->room_type));
        $this->status = htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(':room_id', $this->room_id);
        $stmt->bindParam(':room_number', $this->room_number);
        $stmt->bindParam(':floor_number', $this->floor_number);
        $stmt->bindParam(':capacity', $this->capacity);
        $stmt->bindParam(':room_type', $this->room_type);
        $stmt->bindParam(':status', $this->status);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE room_id = ?";
        $stmt = $this->conn->prepare($query);
        $this->room_id = htmlspecialchars(strip_tags($this->room_id));
        $stmt->bindParam(1, $this->room_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch(PDOException $e) {
            return false;
        }
        return false;
    }
}
?>
