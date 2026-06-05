<?php

require_once 'models/Contract.php';
require_once 'models/Student.php';
require_once 'models/Room.php';

class ContractController extends BaseController {
    private $db;
    private $contract;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->contract = new Contract($this->db);
    }

    public function index() {
        $p = $this->paginate();
        $search      = $p['search'];
        $page        = $p['page'];
        $total_pages = ceil($this->contract->count($search) / $p['limit']);
        $contracts   = $this->contract->read($search, $p['limit'], $p['offset'])->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/contracts/index.php';
    }

    public function create() {
        // Fetch students and rooms for dropdowns
        $studentModel = new Student($this->db);
        $stmtS = $studentModel->read();
        $students = $stmtS->fetchAll(PDO::FETCH_ASSOC);

        $roomModel = new Room($this->db);
        $stmtR = $roomModel->read();
        $rooms = $stmtR->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->contract->student_id = $_POST['student_id'];
            $this->contract->room_id = $_POST['room_id'];
            $this->contract->start_date = $_POST['start_date'];
            $this->contract->end_date = $_POST['end_date'];
            $this->contract->status = $_POST['status'];
            $this->contract->price = !empty($_POST['price']) ? $_POST['price'] : 0;
            $this->contract->deposit = !empty($_POST['deposit']) ? $_POST['deposit'] : 0;

            $result = $this->contract->create();
            if ($result == "success") {
                header("Location: /contract?msg=success");
                exit();
            } elseif ($result == "full") {
                $error = "Phòng đã đầy, vui lòng chọn phòng khác!";
            } elseif ($result == "student_active") {
                $error = "Sinh viên này đang có hợp đồng đang ở!";
            } else {
                $error = "Có lỗi xảy ra khi tạo hợp đồng.";
            }
        }
        require_once 'views/contracts/create.php';
    }

    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: /contract");
            exit();
        }

        $this->contract->contract_id = $_GET['id'];

        $studentModel = new Student($this->db);
        $stmtS = $studentModel->read();
        $students = $stmtS->fetchAll(PDO::FETCH_ASSOC);

        $roomModel = new Room($this->db);
        $stmtR = $roomModel->read();
        $rooms = $stmtR->fetchAll(PDO::FETCH_ASSOC);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->contract->student_id = $_POST['student_id'];
            $this->contract->room_id = $_POST['room_id'];
            $this->contract->start_date = $_POST['start_date'];
            $this->contract->end_date = $_POST['end_date'];
            $this->contract->status = $_POST['status'];
            $this->contract->price = !empty($_POST['price']) ? $_POST['price'] : 0;
            $this->contract->deposit = !empty($_POST['deposit']) ? $_POST['deposit'] : 0;

            if ($this->contract->update()) {
                header("Location: /contract?msg=updated");
                exit();
            } else {
                $error = "Có lỗi xảy ra khi cập nhật.";
            }
        } else {
            $this->contract->readOne();
        }

        $contract = $this->contract;
        require_once 'views/contracts/edit.php';
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->contract->contract_id = $_GET['id'];
            if ($this->contract->delete()) {
                header("Location: /contract?msg=deleted");
            } else {
                header("Location: /contract?msg=error");
            }
        }
        exit();
    }
}
?>
