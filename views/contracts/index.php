<?php
$pageTitle = 'Quản lý Hợp đồng';
include 'views/layout/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="card-title mb-0">Danh sách Hợp đồng</h3>
        <a href="/contract/create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tạo mới</a>
    </div>

    <div class="filter-section mb-4">
        <form action="" method="GET" class="d-flex gap-10" style="max-width: 500px;">
            
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo MSSV, Tên hoặc Số phòng..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'success'): ?>
            <div class="alert alert-success">Tạo hợp đồng thành công!</div>
        <?php elseif($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">Cập nhật hợp đồng thành công!</div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Xóa hợp đồng thành công!</div>
        <?php elseif($_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger">Có lỗi xảy ra, không thể thao tác!</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sinh Viên</th>
                    <th>Phòng</th>
                    <th>Thời Gian</th>
                    <th>Tiền Thuê/tháng</th>
                    <th>Tiền Cọc</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($contracts) > 0): ?>
                    <?php foreach ($contracts as $row): ?>
                        <tr>
                            <td>#<?php echo $row['contract_id']; ?></td>
                            <td>
                                <div class="font-weight-600"><?php echo htmlspecialchars($row['student_name']); ?></div>
                                <div class="text-muted" style="font-size: 12px;"><?php echo htmlspecialchars($row['student_code']); ?></div>
                            </td>
                            <td class="font-weight-600">Phòng <?php echo htmlspecialchars($row['room_number']); ?></td>
                            <td style="font-size: 13px;">
                                <div>Từ: <?php echo date('d/m/Y', strtotime($row['start_date'])); ?></div>
                                <div>Đến: <?php echo !empty($row['end_date']) ? date('d/m/Y', strtotime($row['end_date'])) : 'Không xác định'; ?></div>
                            </td>
                            <td class="font-weight-600 text-primary"><?php echo number_format($row['price'], 0, ',', '.'); ?>đ</td>
                            <td><?php echo number_format($row['deposit'], 0, ',', '.'); ?>đ</td>
                            <td>
                                <?php if ($row['status'] == 'Đang ở'): ?>
                                    <span class="badge badge-success">Đang ở</span>
                                <?php elseif ($row['status'] == 'Đã chuyển ra'): ?>
                                    <span class="badge badge-warning">Đã chuyển ra</span>
                                <?php else: ?>
                                    <span class="badge badge-light">Đã hủy</span>
                                <?php endif; ?>
                            </td>
                            <td class="action-links d-flex gap-10">
                                <a href="/contract/edit/<?php echo $row['contract_id']; ?>" class="btn-icon text-primary" title="Sửa"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="/contract/delete/<?php echo $row['contract_id']; ?>" class="btn-icon text-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa hợp đồng này?');"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Không tìm thấy hợp đồng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php include 'views/layout/pagination.php'; ?>
</div>

<?php include 'views/layout/footer.php'; ?>
