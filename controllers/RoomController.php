<?php

require_once 'models/Room.php';

class RoomController extends BaseController {
    private $db;
    private $room;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->room = new Room($this->db);
    }

    public function index() {
        $p = $this->paginate();
        $search      = $p['search'];
        $page        = $p['page'];
        $total_pages = ceil($this->room->count($search) / $p['limit']);
        $rooms       = $this->room->read($search, $p['limit'], $p['offset'])->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/rooms/index.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->room->room_number = $_POST['room_number'];
            $this->room->floor_number = $_POST['floor_number'];
            $this->room->capacity = $_POST['capacity'];
            $this->room->room_type = $_POST['room_type'];
            $this->room->status = $_POST['status'];

            if ($this->room->create()) {
                header("Location: /room?msg=success");
                exit();
            } else {
                $error = "Có lỗi xảy ra! Có thể số phòng này đã tồn tại.";
            }
        }
        require_once 'views/rooms/create.php';
    }

    public function edit() {
        if (!isset($_GET['id'])) {
            header("Location: /room");
            exit();
        }

        $this->room->room_id = $_GET['id'];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->room->room_number = $_POST['room_number'];
            $this->room->floor_number = $_POST['floor_number'];
            $this->room->capacity = $_POST['capacity'];
            $this->room->room_type = $_POST['room_type'];
            $this->room->status = $_POST['status'];

            if ($this->room->update()) {
                header("Location: /room?msg=updated");
                exit();
            } else {
                $error = "Có lỗi xảy ra khi cập nhật (Có thể trùng số phòng).";
            }
        } else {
            $this->room->readOne();
        }

        $room = $this->room;
        require_once 'views/rooms/edit.php';
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $this->room->room_id = $_GET['id'];
            if ($this->room->delete()) {
                header("Location: /room?msg=deleted");
            } else {
                header("Location: /room?msg=error");
            }
        }
        exit();
    }

    // ----------------------------------------------------------
    // GET /room/my-room  →  Sinh viên xem phòng đang ở
    // ----------------------------------------------------------
    public function myRoom(): void {
        $studentId = Auth::studentId();

        // Tìm hợp đồng đang ở để xác định phòng
        $stmt = $this->db->prepare("
            SELECT c.*, r.*
            FROM Contract c
            JOIN Room r ON c.room_id = r.room_id
            WHERE c.student_id = ? AND c.status = 'Đang ở'
            LIMIT 1
        ");
        $stmt->execute([$studentId]);
        $roomInfo = $stmt->fetch(PDO::FETCH_ASSOC);

        // Danh sách bạn cùng phòng (nếu đang ở)
        $roommates = [];
        if ($roomInfo) {
            $stmtMates = $this->db->prepare("
                SELECT s.full_name, s.student_code, s.department, s.phone
                FROM Contract c
                JOIN Student s ON c.student_id = s.student_id
                WHERE c.room_id = ? AND c.status = 'Đang ở' AND c.student_id != ?
            ");
            $stmtMates->execute([$roomInfo['room_id'], $studentId]);
            $roommates = $stmtMates->fetchAll(PDO::FETCH_ASSOC);
        }

        require_once 'views/rooms/my_room.php';
    }
}
?>
