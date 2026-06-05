<?php
/**
 * views/dashboard/student.php
 * Dashboard dành riêng cho sinh viên.
 * Biến nhận từ DashboardController::studentDashboard():
 *   $studentInfo     — thông tin sinh viên (hoặc null nếu chưa liên kết)
 *   $currentContract — hợp đồng + thông tin phòng đang ở (hoặc null)
 *   $unpaidBills     — số hóa đơn chưa thanh toán của phòng
 *   $noticeCount     — số thông báo liên quan đến sinh viên
 */
$pageTitle = 'Tổng quan';
include 'views/layout/header.php';
?>

<!-- Stat Cards -->
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-door-open"></i>
        </div>
        <div class="stat-details">
            <h3><?= $currentContract ? 'Phòng ' . htmlspecialchars($currentContract['room_number']) : 'Chưa có' ?></h3>
            <p>Phòng đang ở</p>
        </div>
    </div>
    
    <div class="stat-card <?= $unpaidBills > 0 ? 'border-left-danger' : '' ?>">
        <div class="stat-icon red">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
        <div class="stat-details">
            <h3><?= htmlspecialchars($unpaidBills) ?></h3>
            <p>Hóa đơn chưa thanh toán</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-bell"></i>
        </div>
        <div class="stat-details">
            <h3><?= htmlspecialchars($noticeCount) ?></h3>
            <p>Thông báo liên quan</p>
        </div>
    </div>
</div>

<div class="student-dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; margin-bottom: 25px;">
    <!-- Hồ sơ cá nhân -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center mb-4">
            <h3 class="card-title mb-0" style="font-size: 18px; font-weight: 600; color: var(--dark-color);"><i class="fa-solid fa-user-tie" style="color: var(--primary-color); margin-right: 10px;"></i>Hồ sơ cá nhân</h3>
            <a href="/student/profile" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 12px;">Xem chi tiết</a>
        </div>
        <?php if ($studentInfo): ?>
            <div class="student-info-list" style="display: flex; flex-direction: column; gap: 15px;">
                <div class="info-item d-flex justify-content-between" style="border-bottom: 1px dashed #e9ecef; padding-bottom: 10px;">
                    <span class="info-label" style="color: var(--text-muted); font-size: 14px;">Mã sinh viên</span>
                    <span class="info-value" style="font-weight: 600; color: var(--dark-color); font-size: 14px;"><?= htmlspecialchars($studentInfo['student_code']) ?></span>
                </div>
                <div class="info-item d-flex justify-content-between" style="border-bottom: 1px dashed #e9ecef; padding-bottom: 10px;">
                    <span class="info-label" style="color: var(--text-muted); font-size: 14px;">Khoa / Viện</span>
                    <span class="info-value" style="font-weight: 600; color: var(--dark-color); font-size: 14px;"><?= htmlspecialchars($studentInfo['department']) ?></span>
                </div>
                <div class="info-item d-flex justify-content-between" style="border-bottom: 1px dashed #e9ecef; padding-bottom: 10px;">
                    <span class="info-label" style="color: var(--text-muted); font-size: 14px;">Email</span>
                    <span class="info-value" style="font-weight: 600; color: var(--dark-color); font-size: 14px;"><?= htmlspecialchars($studentInfo['email']) ?></span>
                </div>
                <div class="info-item d-flex justify-content-between" style="padding-bottom: 5px;">
                    <span class="info-label" style="color: var(--text-muted); font-size: 14px;">Số điện thoại</span>
                    <span class="info-value" style="font-weight: 600; color: var(--dark-color); font-size: 14px;"><?= htmlspecialchars($studentInfo['phone']) ?></span>
                </div>
            </div>
        <?php else: ?>
            <p class="text-muted" style="font-size: 14px;">Chưa liên kết hồ sơ sinh viên. Vui lòng liên hệ quản lý.</p>
        <?php endif; ?>
    </div>

    <!-- Thông tin phòng đang ở -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center mb-4">
            <h3 class="card-title mb-0" style="font-size: 18px; font-weight: 600; color: var(--dark-color);"><i class="fa-solid fa-hotel" style="color: var(--success-color); margin-right: 10px;"></i>Phòng đang ở</h3>
            <?php if ($currentContract): ?>
                <a href="/room/my-room" class="btn btn-secondary btn-sm" style="padding: 6px 12px; font-size: 12px;">Xem chi tiết</a>
            <?php endif; ?>
        </div>
        <?php if ($currentContract): ?>
            <div class="student-info-list" style="display: flex; flex-direction: column; gap: 15px;">
                <div class="info-item d-flex justify-content-between" style="border-bottom: 1px dashed #e9ecef; padding-bottom: 10px;">
                    <span class="info-label" style="color: var(--text-muted); font-size: 14px;">Số phòng</span>
                    <span class="info-value" style="font-weight: 600; color: var(--dark-color); font-size: 14px;">Phòng <?= htmlspecialchars($currentContract['room_number']) ?></span>
                </div>
                <div class="info-item d-flex justify-content-between" style="border-bottom: 1px dashed #e9ecef; padding-bottom: 10px;">
                    <span class="info-label" style="color: var(--text-muted); font-size: 14px;">Tầng</span>
                    <span class="info-value" style="font-weight: 600; color: var(--dark-color); font-size: 14px;">Tầng <?= htmlspecialchars($currentContract['floor_number']) ?></span>
                </div>
                <div class="info-item d-flex justify-content-between" style="border-bottom: 1px dashed #e9ecef; padding-bottom: 10px;">
                    <span class="info-label" style="color: var(--text-muted); font-size: 14px;">Loại phòng</span>
                    <span class="info-value" style="font-weight: 600; color: var(--dark-color); font-size: 14px;"><?= htmlspecialchars($currentContract['room_type']) ?></span>
                </div>
                <div class="info-item d-flex justify-content-between" style="padding-bottom: 5px;">
                    <span class="info-label" style="color: var(--text-muted); font-size: 14px;">Ngày bắt đầu</span>
                    <span class="info-value" style="font-weight: 600; color: var(--dark-color); font-size: 14px;"><?= htmlspecialchars($currentContract['start_date']) ?></span>
                </div>
            </div>
        <?php else: ?>
            <p class="text-muted" style="font-size: 14px;">Bạn hiện không có phòng đang ở.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Nút truy cập nhanh -->
<div class="card" style="margin-top: 1.5rem;">
    <h3 class="card-title" style="font-size: 18px; font-weight: 600; color: var(--dark-color); margin-bottom: 20px;"><i class="fa-solid fa-bolt" style="color: #f72585; margin-right: 10px;"></i>Truy cập nhanh</h3>
    <div class="quick-links-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <a href="/notice" class="quick-link-item" style="display: flex; align-items: center; gap: 15px; padding: 20px; border-radius: var(--border-radius); background: #f8f9fa; border: 1px solid #e9ecef; text-decoration: none; color: var(--dark-color); transition: var(--transition);">
            <div style="font-size: 24px; color: var(--primary-color);"><i class="fa-solid fa-bell"></i></div>
            <div style="display: flex; flex-direction: column;">
                <span style="font-weight: 600; font-size: 15px;">Thông báo</span>
                <span style="font-size: 12px; color: var(--text-muted);">Xem thông tin mới</span>
            </div>
        </a>
        <a href="/bill/my-bills" class="quick-link-item" style="display: flex; align-items: center; gap: 15px; padding: 20px; border-radius: var(--border-radius); background: #f8f9fa; border: 1px solid #e9ecef; text-decoration: none; color: var(--dark-color); transition: var(--transition);">
            <div style="font-size: 24px; color: var(--danger-color);"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            <div style="display: flex; flex-direction: column;">
                <span style="font-weight: 600; font-size: 15px;">Hóa đơn</span>
                <span style="font-size: 12px; color: var(--text-muted);">Thanh toán hóa đơn</span>
            </div>
        </a>
        <a href="/room/my-room" class="quick-link-item" style="display: flex; align-items: center; gap: 15px; padding: 20px; border-radius: var(--border-radius); background: #f8f9fa; border: 1px solid #e9ecef; text-decoration: none; color: var(--dark-color); transition: var(--transition);">
            <div style="font-size: 24px; color: var(--success-color);"><i class="fa-solid fa-door-open"></i></div>
            <div style="display: flex; flex-direction: column;">
                <span style="font-weight: 600; font-size: 15px;">Phòng ở</span>
                <span style="font-size: 12px; color: var(--text-muted);">Thành viên & cơ sở vật chất</span>
            </div>
        </a>
        <a href="/student/profile" class="quick-link-item" style="display: flex; align-items: center; gap: 15px; padding: 20px; border-radius: var(--border-radius); background: #f8f9fa; border: 1px solid #e9ecef; text-decoration: none; color: var(--dark-color); transition: var(--transition);">
            <div style="font-size: 24px; color: #8b5cf6;"><i class="fa-solid fa-user-tie"></i></div>
            <div style="display: flex; flex-direction: column;">
                <span style="font-weight: 600; font-size: 15px;">Hồ sơ</span>
                <span style="font-size: 12px; color: var(--text-muted);">Thông tin cá nhân</span>
            </div>
        </a>
    </div>
</div>

<style>
.quick-link-item {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
}
.quick-link-item:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow);
    border-color: var(--primary-color) !important;
    background: #fff !important;
}
.border-left-danger {
    border-left: 4px solid var(--danger-color) !important;
}
</style>

<?php include 'views/layout/footer.php'; ?>
