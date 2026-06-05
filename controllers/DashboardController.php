<?php

require_once 'config/database.php';

class DashboardController extends BaseController {

    public function index() {
        $db = Database::getConnection();

        if (Auth::isManager()) {
            $this->managerDashboard($db);
        } else {
            $this->studentDashboard($db);
        }
    }

    // ----------------------------------------------------------
    // Dashboard Quản lý: thống kê toàn hệ thống + biểu đồ
    // ----------------------------------------------------------
    private function managerDashboard($db): void {
        $stats = ['total_students' => 0, 'available_rooms' => 0, 'unpaid_bills' => 0];

        $stats['total_students'] = $db->query(
            "SELECT COUNT(*) FROM Student"
        )->fetchColumn();

        $stats['available_rooms'] = $db->query("
            SELECT COUNT(*) FROM Room
            WHERE status = 'Hoạt động'
            AND capacity > (
                SELECT COUNT(*) FROM Contract
                WHERE Contract.room_id = Room.room_id AND status = 'Đang ở'
            )
        ")->fetchColumn();

        $stats['unpaid_bills'] = $db->query(
            "SELECT COUNT(*) FROM UtilityBill WHERE status = 'Chưa thanh toán'"
        )->fetchColumn();

        $currentYear = date('Y');
        $q = $db->prepare("
            SELECT billing_month, SUM(total_amount) as revenue
            FROM UtilityBill
            WHERE status = 'Đã thanh toán' AND billing_year = ?
            GROUP BY billing_month ORDER BY billing_month
        ");
        $q->execute([$currentYear]);

        $monthlyRevenue = array_fill(1, 12, 0);
        foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $monthlyRevenue[(int)$row['billing_month']] = (float)$row['revenue'];
        }

        require_once 'views/dashboard/index.php';
    }

    // ----------------------------------------------------------
    // Dashboard Sinh viên: thông tin cá nhân + phòng + hóa đơn
    // ----------------------------------------------------------
    private function studentDashboard($db): void {
        $studentId = Auth::studentId();

        // Thông tin sinh viên
        $stmtSv = $db->prepare("SELECT * FROM Student WHERE student_id = ?");
        $stmtSv->execute([$studentId]);
        $studentInfo = $stmtSv->fetch(PDO::FETCH_ASSOC);

        // Hợp đồng & phòng đang ở
        $stmtRoom = $db->prepare("
            SELECT c.*, r.room_number, r.floor_number, r.room_type
            FROM Contract c
            JOIN Room r ON c.room_id = r.room_id
            WHERE c.student_id = ? AND c.status = 'Đang ở'
            LIMIT 1
        ");
        $stmtRoom->execute([$studentId]);
        $currentContract = $stmtRoom->fetch(PDO::FETCH_ASSOC);

        // Hóa đơn chưa thanh toán của phòng
        $unpaidBills = 0;
        if ($currentContract) {
            $stmtBill = $db->prepare("
                SELECT COUNT(*) FROM UtilityBill
                WHERE room_id = ? AND status = 'Chưa thanh toán'
            ");
            $stmtBill->execute([$currentContract['room_id']]);
            $unpaidBills = (int)$stmtBill->fetchColumn();
        }

        // Số thông báo liên quan đến sinh viên này
        $stmtNotice = $db->prepare("
            SELECT COUNT(*) FROM Notice
            WHERE target_type = 'Cả tòa'
               OR (target_type = 'Phòng'    AND room_id    = :room_id)
               OR (target_type = 'Cá nhân'  AND student_id = :student_id)
        ");
        $stmtNotice->execute([
            ':room_id'    => $currentContract['room_id'] ?? null,
            ':student_id' => $studentId,
        ]);
        $noticeCount = (int)$stmtNotice->fetchColumn();

        require_once 'views/dashboard/student.php';
    }
}
?>
