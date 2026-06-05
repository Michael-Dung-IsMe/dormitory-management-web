<?php

require_once 'models/Student.php';

class StudentController extends BaseController {
    private $db;
    private $student;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->student = new Student($this->db);
    }

    public function index() {
        $p = $this->paginate();
        $search      = $p['search'];
        $page        = $p['page'];
        $total_pages = ceil($this->student->count($search) / $p['limit']);
        $students    = $this->student->read($search, $p['limit'], $p['offset'])->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/students/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->student->full_name = $_POST['full_name'];
            $this->student->student_code = $_POST['student_code'];
            $this->student->dob = $_POST['dob'];
            $this->student->phone = $_POST['phone'];
            $this->student->email = $_POST['email'];
            $this->student->department = $_POST['department'];
            $this->student->status = $_POST['status'];

            if ($this->student->create()) {
                header("Location: /student?msg=success");
                exit();
            } else {
                $error = "Có lỗi xảy ra khi thêm sinh viên.";
            }
        }
        require_once 'views/students/create.php';
    }

    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: /student");
            exit();
        }

        $this->student->student_id = $_GET['id'];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->student->full_name = $_POST['full_name'];
            $this->student->student_code = $_POST['student_code'];
            $this->student->dob = $_POST['dob'];
            $this->student->phone = $_POST['phone'];
            $this->student->email = $_POST['email'];
            $this->student->department = $_POST['department'];
            $this->student->status = $_POST['status'];

            if ($this->student->update()) {
                header("Location: /student?msg=updated");
                exit();
            } else {
                $error = "Có lỗi xảy ra khi cập nhật.";
            }
        } else {
            $this->student->readOne();
        }

        $student = $this->student;
        require_once 'views/students/edit.php';
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->student->student_id = $_GET['id'];
            // Thêm kiểm tra ở đây nếu cần (VD: sinh viên đang có hợp đồng thì không cho xóa)
            if ($this->student->delete()) {
                header("Location: /student?msg=deleted");
            } else {
                header("Location: /student?msg=error");
            }
        }
        exit();
    }

    // ----------------------------------------------------------
    // GET /student/profile  →  Sinh viên xem hồ sơ bản thân
    // ----------------------------------------------------------
    public function profile(): void {
        $studentId = Auth::studentId();

        if (!$studentId) {
            // Tài khoản student chưa liên kết với sinh viên nào
            $error = 'Tài khoản của bạn chưa được liên kết với hồ sơ sinh viên. Vui lòng liên hệ quản lý.';
            require_once 'views/students/profile.php';
            return;
        }

        $this->student->student_id = $studentId;
        $this->student->readOne();
        $student = $this->student;

        // Lấy thêm hợp đồng đang ở
        $stmt = $this->db->prepare("
            SELECT c.*, r.room_number, r.floor_number, r.room_type
            FROM Contract c
            JOIN Room r ON c.room_id = r.room_id
            WHERE c.student_id = ? AND c.status = 'Đang ở'
            LIMIT 1
        ");
        $stmt->execute([$studentId]);
        $currentContract = $stmt->fetch(PDO::FETCH_ASSOC);

        require_once 'views/students/profile.php';
    }
}
?>
