<?php
$pageTitle = 'Quản lý Phòng';
include 'views/layout/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="card-title mb-0">Danh sách Phòng</h3>
        <a href="/room/create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm mới</a>
    </div>

    <div class="filter-section mb-4">
        <form action="" method="GET" class="d-flex gap-10" style="max-width: 400px;">
            
            <input type="number" name="search" class="form-control" placeholder="Tìm kiếm theo số phòng..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'success'): ?>
            <div class="alert alert-success">Thêm phòng thành công!</div>
        <?php elseif($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">Cập nhật thông tin thành công!</div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Xóa phòng thành công!</div>
        <?php elseif($_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger">Có lỗi xảy ra (Phòng có thể đang có người ở), không thể thao tác!</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th>Số Phòng</th>
                    <th>Tầng</th>
                    <th>Loại Phòng</th>
                    <th>Sức Chứa</th>
                    <th>Đang Ở</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($rooms) > 0): ?>
                    <?php foreach ($rooms as $row): ?>
                        <tr>
                            <td class="font-weight-600" style="font-size: 16px;">
                                <?php echo htmlspecialchars($row['room_number']); ?>
                            </td>
                            <td>Tầng <?php echo htmlspecialchars($row['floor_number']); ?></td>
                            <td>
                                <?php if ($row['room_type'] == 'Dịch vụ'): ?>
                                    <span class="badge" style="background: rgba(247, 37, 133, 0.1); color: var(--warning-color);">Dịch vụ</span>
                                <?php else: ?>
                                    <span class="badge badge-light">Thường</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['capacity']; ?> người</td>
                            <td>
                                <?php 
                                    $occ = $row['current_occupancy'];
                                    $cap = $row['capacity'];
                                    $isFull = $occ >= $cap;
                                ?>
                                <span class="badge <?php echo $isFull ? 'badge-warning' : 'badge-success'; ?>" style="font-size: 13px;">
                                    <?php echo $occ; ?> / <?php echo $cap; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'Hoạt động'): ?>
                                    <span class="badge badge-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge badge-light" style="background: #e9ecef;">Đang sửa chữa</span>
                                <?php endif; ?>
                            </td>
                            <td class="action-links d-flex gap-10">
                                <a href="/room/edit/<?php echo $row['room_id']; ?>" class="btn-icon text-primary" title="Sửa"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="/room/delete/<?php echo $row['room_id']; ?>" class="btn-icon text-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?');"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Không tìm thấy phòng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php include 'views/layout/pagination.php'; ?>
</div>

<?php include 'views/layout/footer.php'; ?>
