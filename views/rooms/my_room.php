<?php
/**
 * views/rooms/my_room.php
 * Thông tin phòng dành riêng cho sinh viên.
 * Biến nhận từ RoomController::myRoom():
 *   $roomInfo  — thông tin phòng + hợp đồng (hoặc false nếu chưa có phòng)
 *   $roommates — danh sách bạn cùng phòng
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phòng của tôi — Quản lý KTX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../partials/navbar.php'; ?>

<main class="main-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">Phòng của tôi</h1>
            <p class="page-subtitle">Thông tin chi tiết về phòng bạn đang ở.</p>
        </div>
        <a href="/" class="btn btn-outline">← Về trang chủ</a>
    </div>

    <?php if (!$roomInfo): ?>
        <div class="card">
            <p class="text-muted" style="padding: 2rem; text-align: center;">
                Bạn hiện không có phòng đang ở. Vui lòng liên hệ quản lý để được hỗ trợ.
            </p>
        </div>
    <?php else: ?>

    <div class="content-grid">
        <!-- Thông tin phòng -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Thông tin phòng</h2>
                <span class="badge badge-success"><?= htmlspecialchars($roomInfo['status']) ?></span>
            </div>
            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Số phòng</span>
                    <span class="info-value" style="font-size: 1.4rem; font-weight: 700; color: var(--color-primary);">
                        Phòng <?= htmlspecialchars($roomInfo['room_number']) ?>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tầng</span>
                    <span class="info-value">Tầng <?= htmlspecialchars($roomInfo['floor_number']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Loại phòng</span>
                    <span class="info-value"><?= htmlspecialchars($roomInfo['room_type']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Sức chứa tối đa</span>
                    <span class="info-value"><?= htmlspecialchars($roomInfo['capacity']) ?> người</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ngày vào ở</span>
                    <span class="info-value"><?= htmlspecialchars($roomInfo['start_date']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ngày kết thúc hợp đồng</span>
                    <span class="info-value"><?= htmlspecialchars($roomInfo['end_date'] ?? 'Chưa xác định') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tiền phòng</span>
                    <span class="info-value"><?= number_format($roomInfo['price'] ?? 0, 0, ',', '.') ?> đ/tháng</span>
                </div>
            </div>
        </div>

        <!-- Bạn cùng phòng -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Bạn cùng phòng</h2>
                <span class="badge badge-info"><?= count($roommates) ?> người</span>
            </div>
            <?php if (empty($roommates)): ?>
                <p class="text-muted" style="padding: 1rem 0;">Bạn đang ở một mình trong phòng.</p>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Họ và tên</th>
                                <th>Mã SV</th>
                                <th>Khoa</th>
                                <th>Điện thoại</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roommates as $mate): ?>
                            <tr>
                                <td><?= htmlspecialchars($mate['full_name']) ?></td>
                                <td><code><?= htmlspecialchars($mate['student_code']) ?></code></td>
                                <td><?= htmlspecialchars($mate['department']) ?></td>
                                <td><?= htmlspecialchars($mate['phone'] ?? '—') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Link xem hóa đơn -->
    <div style="margin-top: 1rem;">
        <a href="/bill/my-bills" class="btn">
            💳 Xem hóa đơn của phòng
        </a>
    </div>

    <?php endif; ?>
</main>

</body>
</html>
