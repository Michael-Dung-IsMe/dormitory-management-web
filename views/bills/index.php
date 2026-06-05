<?php
$pageTitle = 'Quản lý Hóa đơn Điện Nước';
include 'views/layout/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="card-title mb-0">Danh sách Hóa đơn</h3>
        <?php if (Auth::isManager()): ?>
        <a href="/bill/create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Lập hóa đơn</a>
        <?php endif; ?>
    </div>

    <div class="filter-section mb-4">
        <form action="" method="GET" class="d-flex gap-10" style="max-width: 500px;">
            
            <input type="text" name="search" class="form-control" placeholder="Tìm theo số phòng, tháng, năm..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'success'): ?>
            <div class="alert alert-success">Lập hóa đơn thành công!</div>
        <?php elseif($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">Cập nhật hóa đơn thành công!</div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Xóa hóa đơn thành công!</div>
        <?php elseif($_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger">Có lỗi xảy ra, không thể thao tác!</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th>Kỳ Thu</th>
                    <th>Phòng</th>
                    <th>Điện (KWh)</th>
                    <th>Nước (Khối)</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Ngày Lập</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($bills) > 0): ?>
                    <?php foreach ($bills as $row): ?>
                        <tr>
                            <td class="font-weight-600">Tháng <?php echo $row['billing_month'] . '/' . $row['billing_year']; ?></td>
                            <td class="font-weight-600">Phòng <?php echo htmlspecialchars($row['room_number']); ?></td>
                            <td>
                                <?php 
                                    $elec_usage = $row['new_electric_index'] - $row['old_electric_index'];
                                    echo $elec_usage;
                                ?>
                                <div class="text-muted" style="font-size: 11px;">(Cũ: <?php echo $row['old_electric_index']; ?> - Mới: <?php echo $row['new_electric_index']; ?>)</div>
                            </td>
                            <td>
                                <?php 
                                    $water_usage = $row['new_water_index'] - $row['old_water_index'];
                                    echo $water_usage;
                                ?>
                                <div class="text-muted" style="font-size: 11px;">(Cũ: <?php echo $row['old_water_index']; ?> - Mới: <?php echo $row['new_water_index']; ?>)</div>
                            </td>
                            <td class="font-weight-600 text-primary"><?php echo number_format($row['total_amount'], 0, ',', '.'); ?>đ</td>
                            <td>
                                <?php if ($row['status'] == 'Đã thanh toán'): ?>
                                    <span class="badge badge-success">Đã thanh toán</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Chưa thanh toán</span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size: 13px;"><?php echo date('d/m/Y', strtotime($row['created_date'])); ?></td>
                            <td class="action-links d-flex gap-10">
                                <a href="/bill/export/<?php echo $row['bill_id']; ?>" target="_blank" class="btn-icon" style="background: rgba(76, 201, 240, 0.1); color: var(--success-color);" title="Xuất PDF"><i class="fa-solid fa-print"></i></a>
                                <?php if (Auth::isManager()): ?>
                                <a href="/bill/edit/<?php echo $row['bill_id']; ?>" class="btn-icon text-primary" title="Sửa"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="/bill/delete/<?php echo $row['bill_id']; ?>" class="btn-icon text-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa hóa đơn này?');"><i class="fa-solid fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Không tìm thấy hóa đơn nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php include 'views/layout/pagination.php'; ?>
</div>

<?php include 'views/layout/footer.php'; ?>
