<?php
$pageTitle = Auth::isManager() ? 'Quản lý Thông báo' : 'Thông báo';
include 'views/layout/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="card-title mb-0">Danh sách Thông báo</h3>
        <?php if (Auth::isManager()): ?>
        <a href="/notice/create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tạo Thông báo</a>
        <?php endif; ?>
    </div>

    <?php if (Auth::isManager()): ?>
    <div class="filter-section mb-4">
        <form action="" method="GET" class="d-flex gap-10" style="max-width: 500px;">
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm nội dung..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>
    <?php endif; ?>

    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'success'): ?>
            <div class="alert alert-success">Tạo thông báo thành công!</div>
        <?php elseif($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">Cập nhật thông báo thành công!</div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Xóa thông báo thành công!</div>
        <?php elseif($_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger">Có lỗi xảy ra, không thể thao tác!</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Đối Tượng</th>
                    <th>Nội Dung</th>
                    <?php if (Auth::isManager()): ?>
                    <th>Hành Động</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (count($notices) > 0): ?>
                    <?php foreach ($notices as $row): ?>
                        <tr>
                            <td style="white-space: nowrap;"><?php echo date('d/m/Y', strtotime($row['date'])); ?></td>
                            <td style="white-space: nowrap;">
                                <?php if ($row['target_type'] == 'Cả tòa'): ?>
                                    <span class="badge badge-success"><i class="fa-solid fa-bullhorn"></i> Cả tòa</span>
                                <?php elseif ($row['target_type'] == 'Phòng'): ?>
                                    <span class="badge badge-warning"><i class="fa-solid fa-door-closed"></i> Phòng <?php echo htmlspecialchars($row['room_number']); ?></span>
                                <?php else: ?>
                                    <span class="badge badge-light" style="background:#e9ecef;"><i class="fa-solid fa-user"></i> SV: <?php echo htmlspecialchars($row['student_name']); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="max-width: 400px; white-space: normal; line-height: 1.5; color: #495057;">
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </div>
                            </td>
                            <?php if (Auth::isManager()): ?>
                            <td class="action-links d-flex gap-10">
                                <a href="/notice/edit/<?php echo $row['notice_id']; ?>" class="btn-icon text-primary" title="Sửa"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="/notice/delete/<?php echo $row['notice_id']; ?>" class="btn-icon text-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa thông báo này?');"><i class="fa-solid fa-trash"></i></a>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Không có thông báo nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php include 'views/layout/pagination.php'; ?>
</div>

<?php include 'views/layout/footer.php'; ?>
