<?php

require_once 'models/Notice.php';
require_once 'models/Room.php';
require_once 'models/Student.php';

class NoticeController extends BaseController {
    private $db;
    private $notice;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->notice = new Notice($this->db);
    }

    public function index() {
        if (Auth::isManager()) {
            // Manager: xem tất cả thông báo (giữ nguyên logic cũ)
            $p           = $this->paginate();
            $search      = $p['search'];
            $page        = $p['page'];
            $total_pages = ceil($this->notice->count($search) / $p['limit']);
            $notices     = $this->notice->read($search, $p['limit'], $p['offset'])->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Student: chỉ thấy thông báo liên quan đến bản thân
            $studentId = Auth::studentId();
            $search    = '';
            $page      = 1;

            // Tìm phòng hiện tại của sinh viên
            $stmtC = $this->db->prepare("
                SELECT room_id FROM Contract
                WHERE student_id = ? AND status = 'Đang ở' LIMIT 1
            ");
            $stmtC->execute([$studentId]);
            $roomId = $stmtC->fetchColumn() ?: null;

            $stmtN = $this->db->prepare("
                SELECT n.*, r.room_number
                FROM Notice n
                LEFT JOIN Room r ON n.room_id = r.room_id
                WHERE n.target_type = 'Cả tòa'
                   OR (n.target_type = 'Phòng'   AND n.room_id    = :room_id)
                   OR (n.target_type = 'Cá nhân' AND n.student_id = :student_id)
                ORDER BY n.date DESC
            ");
            $stmtN->execute([':room_id' => $roomId, ':student_id' => $studentId]);
            $notices     = $stmtN->fetchAll(PDO::FETCH_ASSOC);
            $total_pages = 1;
        }

        require_once 'views/notices/index.php';
    }

    public function create() {
        $roomModel = new Room($this->db);
        $stmtR = $roomModel->read();
        $rooms = $stmtR->fetchAll(PDO::FETCH_ASSOC);

        $studentModel = new Student($this->db);
        $stmtS = $studentModel->read();
        $students = $stmtS->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->notice->target_type = $_POST['target_type'];
            $this->notice->room_id = !empty($_POST['room_id']) ? $_POST['room_id'] : null;
            $this->notice->student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : null;
            $this->notice->description = $_POST['description'];

            if ($this->notice->create()) {
                header("Location: /notice?msg=success");
                exit();
            } else {
                $error = "Có lỗi xảy ra khi tạo thông báo.";
            }
        }
        require_once 'views/notices/create.php';
    }

    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: /notice");
            exit();
        }

        $this->notice->notice_id = $_GET['id'];

        $roomModel = new Room($this->db);
        $stmtR = $roomModel->read();
        $rooms = $stmtR->fetchAll(PDO::FETCH_ASSOC);

        $studentModel = new Student($this->db);
        $stmtS = $studentModel->read();
        $students = $stmtS->fetchAll(PDO::FETCH_ASSOC);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->notice->target_type = $_POST['target_type'];
            $this->notice->room_id = !empty($_POST['room_id']) ? $_POST['room_id'] : null;
            $this->notice->student_id = !empty($_POST['student_id']) ? $_POST['student_id'] : null;
            $this->notice->description = $_POST['description'];
            $this->notice->date = $_POST['date']; // Giữ nguyên ngày tạo hoặc cập nhật mới

            if ($this->notice->update()) {
                header("Location: /notice?msg=updated");
                exit();
            } else {
                $error = "Có lỗi xảy ra khi cập nhật thông báo.";
            }
        } else {
            $this->notice->readOne();
        }

        require_once 'views/notices/edit.php';
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->notice->notice_id = $_GET['id'];
            if ($this->notice->delete()) {
                header("Location: /notice?msg=deleted");
            } else {
                header("Location: /notice?msg=error");
            }
        }
        exit();
    }
}
?>
