<?php

class UtilityBill extends BaseModel {
    private $table_name = "UtilityBill";

    protected $tableClause   = 'UtilityBill b';
    protected $selectClause  = 'b.*, r.room_number';
    protected $joinClause    = 'JOIN Room r ON b.room_id = r.room_id';
    protected $searchColumns = ['r.room_number', 'b.billing_month', 'b.billing_year'];
    protected $orderBy       = 'b.billing_year DESC, b.billing_month DESC, r.room_number ASC';

    public $bill_id;
    public $room_id;
    public $billing_month;
    public $billing_year;
    public $old_electric_index;
    public $new_electric_index;
    public $old_water_index;
    public $new_water_index;
    public $room_fee;
    public $total_amount;
    public $status;
    public $created_date;
    public $payment_date;
    public $room_number;

    private $electric_price = 3500;
    private $water_price    = 10000;

    public function calculateTotal() {
        $electric_usage = $this->new_electric_index - $this->old_electric_index;
        $water_usage = $this->new_water_index - $this->old_water_index;
        
        $electric_fee = $electric_usage * $this->electric_price;
        $water_fee = $water_usage * $this->water_price;
        
        $this->total_amount = $electric_fee + $water_fee + $this->room_fee;
    }

    public function readOne() {
        $query = "SELECT b.*, r.room_number FROM " . $this->table_name . " b JOIN Room r ON b.room_id = r.room_id WHERE b.bill_id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->bill_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->room_id = $row['room_id'];
            $this->billing_month = $row['billing_month'];
            $this->billing_year = $row['billing_year'];
            $this->old_electric_index = $row['old_electric_index'];
            $this->new_electric_index = $row['new_electric_index'];
            $this->old_water_index = $row['old_water_index'];
            $this->new_water_index = $row['new_water_index'];
            $this->room_fee = $row['room_fee'];
            $this->total_amount = $row['total_amount'];
            $this->status = $row['status'];
            $this->created_date = $row['created_date'];
            $this->payment_date = $row['payment_date'];
            $this->room_number = $row['room_number'];
            return true;
        }
        return false;
    }

    public function create() {
        $this->calculateTotal();
        $this->created_date = date('Y-m-d');

        $query = "INSERT INTO " . $this->table_name . " 
                  SET room_id=:room_id, billing_month=:billing_month, billing_year=:billing_year, 
                      old_electric_index=:old_electric_index, new_electric_index=:new_electric_index, 
                      old_water_index=:old_water_index, new_water_index=:new_water_index, 
                      room_fee=:room_fee, total_amount=:total_amount, status=:status, created_date=:created_date";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":room_id", $this->room_id);
        $stmt->bindParam(":billing_month", $this->billing_month);
        $stmt->bindParam(":billing_year", $this->billing_year);
        $stmt->bindParam(":old_electric_index", $this->old_electric_index);
        $stmt->bindParam(":new_electric_index", $this->new_electric_index);
        $stmt->bindParam(":old_water_index", $this->old_water_index);
        $stmt->bindParam(":new_water_index", $this->new_water_index);
        $stmt->bindParam(":room_fee", $this->room_fee);
        $stmt->bindParam(":total_amount", $this->total_amount);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":created_date", $this->created_date);

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
        $this->calculateTotal();

        $query = "UPDATE " . $this->table_name . " 
                  SET room_id=:room_id, billing_month=:billing_month, billing_year=:billing_year, 
                      old_electric_index=:old_electric_index, new_electric_index=:new_electric_index, 
                      old_water_index=:old_water_index, new_water_index=:new_water_index, 
                      room_fee=:room_fee, total_amount=:total_amount, status=:status, payment_date=:payment_date
                  WHERE bill_id = :bill_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":bill_id", $this->bill_id);
        $stmt->bindParam(":room_id", $this->room_id);
        $stmt->bindParam(":billing_month", $this->billing_month);
        $stmt->bindParam(":billing_year", $this->billing_year);
        $stmt->bindParam(":old_electric_index", $this->old_electric_index);
        $stmt->bindParam(":new_electric_index", $this->new_electric_index);
        $stmt->bindParam(":old_water_index", $this->old_water_index);
        $stmt->bindParam(":new_water_index", $this->new_water_index);
        $stmt->bindParam(":room_fee", $this->room_fee);
        $stmt->bindParam(":total_amount", $this->total_amount);
        $stmt->bindParam(":status", $this->status);
        
        if(empty($this->payment_date)) {
            $payment_date = null;
            $stmt->bindParam(":payment_date", $payment_date, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(":payment_date", $this->payment_date);
        }

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
        $query = "DELETE FROM " . $this->table_name . " WHERE bill_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->bill_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
