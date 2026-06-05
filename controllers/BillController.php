<?php

require_once 'models/UtilityBill.php';
require_once 'models/Room.php';

class BillController extends BaseController {
    private $db;
    private $bill;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->bill = new UtilityBill($this->db);
    }

    public function index() {
        $p = $this->paginate();
        $search      = $p['search'];
        $page        = $p['page'];
        $total_pages = ceil($this->bill->count($search) / $p['limit']);
        $bills       = $this->bill->read($search, $p['limit'], $p['offset'])->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/bills/index.php';
    }

    public function create() {
        $roomModel = new Room($this->db);
        $stmtR = $roomModel->read();
        $rooms = $stmtR->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->bill->room_id = $_POST['room_id'];
            $this->bill->billing_month = $_POST['billing_month'];
            $this->bill->billing_year = $_POST['billing_year'];
            $this->bill->old_electric_index = $_POST['old_electric_index'];
            $this->bill->new_electric_index = $_POST['new_electric_index'];
            $this->bill->old_water_index = $_POST['old_water_index'];
            $this->bill->new_water_index = $_POST['new_water_index'];
            $this->bill->room_fee = !empty($_POST['room_fee']) ? $_POST['room_fee'] : 0;
            $this->bill->status = $_POST['status'];

            if ($this->bill->new_electric_index < $this->bill->old_electric_index || $this->bill->new_water_index < $this->bill->old_water_index) {
                $error = "Chỉ số mới phải lớn hơn hoặc bằng chỉ số cũ!";
            } else {
                if ($this->bill->create()) {
                    header("Location: /bill?msg=success");
                    exit();
                } else {
                    $error = "Có lỗi xảy ra! Có thể hóa đơn của phòng trong tháng này đã tồn tại.";
                }
            }
        }
        require_once 'views/bills/create.php';
    }

    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: /bill");
            exit();
        }

        $this->bill->bill_id = $_GET['id'];

        $roomModel = new Room($this->db);
        $stmtR = $roomModel->read();
        $rooms = $stmtR->fetchAll(PDO::FETCH_ASSOC);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->bill->room_id = $_POST['room_id'];
            $this->bill->billing_month = $_POST['billing_month'];
            $this->bill->billing_year = $_POST['billing_year'];
            $this->bill->old_electric_index = $_POST['old_electric_index'];
            $this->bill->new_electric_index = $_POST['new_electric_index'];
            $this->bill->old_water_index = $_POST['old_water_index'];
            $this->bill->new_water_index = $_POST['new_water_index'];
            $this->bill->room_fee = !empty($_POST['room_fee']) ? $_POST['room_fee'] : 0;
            $this->bill->status = $_POST['status'];
            $this->bill->payment_date = $_POST['payment_date'];

            if ($this->bill->new_electric_index < $this->bill->old_electric_index || $this->bill->new_water_index < $this->bill->old_water_index) {
                $error = "Chỉ số mới phải lớn hơn hoặc bằng chỉ số cũ!";
            } else {
                if ($this->bill->update()) {
                    header("Location: /bill?msg=updated");
                    exit();
                } else {
                    $error = "Có lỗi xảy ra khi cập nhật.";
                }
            }
        } else {
            $this->bill->readOne();
        }

        require_once 'views/bills/edit.php';
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->bill->bill_id = $_GET['id'];
            if ($this->bill->delete()) {
                header("Location: /bill?msg=deleted");
            } else {
                header("Location: /bill?msg=error");
            }
        }
        exit();
    }
    
    public function export() {
        if (!isset($_GET['id'])) {
            die("Không tìm thấy hóa đơn!");
        }
        $this->bill->bill_id = $_GET['id'];
        if (!$this->bill->readOne()) {
            die("Hóa đơn không tồn tại!");
        }
        
        require_once 'views/bills/export.php';
    }

    // ----------------------------------------------------------
    // GET /bill/my-bills  →  Sinh viên xem hóa đơn phòng mình
    // ----------------------------------------------------------
    public function myBills(): void {
        $studentId = Auth::studentId();

        // Lấy room_id từ hợp đồng đang ở
        $stmtC = $this->db->prepare("
            SELECT room_id FROM Contract
            WHERE student_id = ? AND status = 'Đang ở'
            LIMIT 1
        ");
        $stmtC->execute([$studentId]);
        $contract = $stmtC->fetch(PDO::FETCH_ASSOC);

        $bills   = [];
        $roomId  = null;
        if ($contract) {
            $roomId = $contract['room_id'];
            $stmtB  = $this->db->prepare("
                SELECT b.*, r.room_number
                FROM UtilityBill b
                JOIN Room r ON b.room_id = r.room_id
                WHERE b.room_id = ?
                ORDER BY b.billing_year DESC, b.billing_month DESC
            ");
            $stmtB->execute([$roomId]);
            $bills = $stmtB->fetchAll(PDO::FETCH_ASSOC);
        }

        require_once 'views/bills/my_bills.php';
    }
}
?>
