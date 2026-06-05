<?php
/**
 * views/bills/my_bills.php
 * Danh sách hóa đơn phòng dành cho sinh viên.
 * Biến nhận từ BillController::myBills():
 *   $bills  — mảng hóa đơn (có thể rỗng)
 *   $roomId — ID phòng hiện tại (hoặc null)
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn của tôi — Quản lý KTX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

<?php include __DIR__ . '/../partials/navbar.php'; ?>

<main class="main-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">Hóa đơn của tôi</h1>
            <p class="page-subtitle">
                <?php if ($roomId): ?>
                    Danh sách hóa đơn phòng <?= htmlspecialchars($bills[0]['room_number'] ?? $roomId) ?>.
                <?php else: ?>
                    Bạn chưa có phòng đang ở.
                <?php endif; ?>
            </p>
        </div>
        <a href="/" class="btn btn-outline">← Về trang chủ</a>
    </div>

    <?php if (empty($bills)): ?>
        <div class="card">
            <p class="text-muted" style="padding: 2rem; text-align: center;">
                <?= $roomId ? 'Chưa có hóa đơn nào cho phòng của bạn.' : 'Bạn chưa được phân phòng. Vui lòng liên hệ quản lý.' ?>
            </p>
        </div>
    <?php else: ?>

    <!-- Tóm tắt -->
    <?php
        $totalUnpaid  = count(array_filter($bills, fn($b) => $b['status'] === 'Chưa thanh toán'));
        $totalAmount  = array_sum(array_column(
            array_filter($bills, fn($b) => $b['status'] === 'Chưa thanh toán'),
            'total_amount'
        ));
    ?>
    <?php if ($totalUnpaid > 0): ?>
    <div class="alert alert-warning" style="margin-bottom: 1.5rem;">
        Bạn còn <strong><?= $totalUnpaid ?> hóa đơn chưa thanh toán !</strong>,
        tổng cộng <strong><?= number_format($totalAmount, 0, ',', '.') ?> đ</strong>.
        Vui lòng đến văn phòng ban quản lý để thanh toán.
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tháng</th>
                        <th>Điện (chỉ số)</th>
                        <th>Nước (chỉ số)</th>
                        <th>Phí phòng</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Ngày thanh toán</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bills as $bill): ?>
                    <tr>
                        <td>
                            <strong>Tháng <?= htmlspecialchars($bill['billing_month']) ?>/<?= htmlspecialchars($bill['billing_year']) ?></strong>
                        </td>
                        <td>
                            <?= htmlspecialchars($bill['old_electric_index']) ?>
                            → <?= htmlspecialchars($bill['new_electric_index']) ?>
                            <small class="text-muted">(<?= $bill['new_electric_index'] - $bill['old_electric_index'] ?> kWh)</small>
                        </td>
                        <td>
                            <?= htmlspecialchars($bill['old_water_index']) ?>
                            → <?= htmlspecialchars($bill['new_water_index']) ?>
                            <small class="text-muted">(<?= $bill['new_water_index'] - $bill['old_water_index'] ?> m³)</small>
                        </td>
                        <td><?= number_format($bill['room_fee'], 0, ',', '.') ?> đ</td>
                        <td><strong><?= number_format($bill['total_amount'], 0, ',', '.') ?> đ</strong></td>
                        <td>
                            <span class="badge <?= $bill['status'] === 'Đã thanh toán' ? 'badge-success' : 'badge-warning' ?>">
                                <?= htmlspecialchars($bill['status']) ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($bill['created_date']) ?></td>
                        <td><?= htmlspecialchars($bill['payment_date'] ?? '—') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php endif; ?>
</main>

</body>
</html>
