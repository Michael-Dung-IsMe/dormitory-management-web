<?php
/**
 * views/students/profile.php
 * Hồ sơ cá nhân dành cho sinh viên — chỉ xem, không sửa.
 * Biến nhận từ StudentController::profile():
 *   $student         — object Student (đã readOne())
 *   $currentContract — hợp đồng + phòng đang ở (hoặc false)
 *   $error           — thông báo lỗi nếu chưa liên kết (optional)
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân — Quản lý KTX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../partials/navbar.php'; ?>

<main class="main-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">Hồ sơ cá nhân</h1>
            <p class="page-subtitle">Thông tin sinh viên của bạn trong hệ thống.</p>
        </div>
        <a href="/" class="btn btn-outline">← Về trang chủ</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-warning"><?= htmlspecialchars($error) ?></div>
    <?php else: ?>

    <div class="content-grid">
        <!-- Thông tin cá nhân -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Thông tin cá nhân</h2>
            </div>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Họ và tên</span>
                    <span class="info-value"><?= htmlspecialchars($student->full_name) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Mã sinh viên</span>
                    <span class="info-value"><?= htmlspecialchars($student->student_code) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ngày sinh</span>
                    <span class="info-value"><?= htmlspecialchars($student->dob ?? '—') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Số điện thoại</span>
                    <span class="info-value"><?= htmlspecialchars($student->phone ?? '—') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <span class="info-value"><?= htmlspecialchars($student->email ?? '—') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Khoa / Viện</span>
                    <span class="info-value"><?= htmlspecialchars($student->department ?? '—') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Trạng thái</span>
                    <span class="info-value">
                        <span class="badge <?= $student->status === 'Đang ở' ? 'badge-success' : 'badge-secondary' ?>">
                            <?= htmlspecialchars($student->status) ?>
                        </span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Thông tin hợp đồng -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Hợp đồng & Phòng ở</h2>
            </div>
            <?php if ($currentContract): ?>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Số phòng</span>
                    <span class="info-value">Phòng <?= htmlspecialchars($currentContract['room_number']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tầng</span>
                    <span class="info-value">Tầng <?= htmlspecialchars($currentContract['floor_number']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Loại phòng</span>
                    <span class="info-value"><?= htmlspecialchars($currentContract['room_type']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ngày bắt đầu</span>
                    <span class="info-value"><?= htmlspecialchars($currentContract['start_date']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ngày kết thúc</span>
                    <span class="info-value"><?= htmlspecialchars($currentContract['end_date'] ?? 'Chưa xác định') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tiền phòng</span>
                    <span class="info-value"><?= number_format($currentContract['price'] ?? 0, 0, ',', '.') ?> đ/tháng</span>
                </div>
            </div>
            <?php else: ?>
                <p class="text-muted">Bạn hiện không có hợp đồng nào đang hoạt động.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php endif; ?>
</main>

</body>
</html>
