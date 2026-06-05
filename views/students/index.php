<?php
$pageTitle = 'Quản lý Sinh viên';
include 'views/layout/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="card-title mb-0">Danh sách Sinh viên</h3>
        <?php if (Auth::isManager()): ?>
        <a href="/student/create" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Thêm mới</a>
        <?php endif; ?>
    </div>

    <div class="filter-section mb-4">
        <form action="" method="GET" class="d-flex gap-10" style="max-width: 500px;">
            
            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên hoặc MSSV..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-search"></i></button>
        </form>
    </div>

    <?php if(isset($_GET['msg'])): ?>
        <?php if($_GET['msg'] == 'success'): ?>
            <div class="alert alert-success">Thêm sinh viên thành công!</div>
        <?php elseif($_GET['msg'] == 'updated'): ?>
            <div class="alert alert-success">Cập nhật thông tin thành công!</div>
        <?php elseif($_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success">Xóa sinh viên thành công!</div>
        <?php elseif($_GET['msg'] == 'error'): ?>
            <div class="alert alert-danger">Có lỗi xảy ra, vui lòng thử lại!</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>MSSV</th>
                    <th>Họ Tên</th>
                    <th>Ngày Sinh</th>
                    <th>SĐT</th>
                    <th>Khoa/Viện</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($students) > 0): ?>
                    <?php foreach ($students as $row): ?>
                        <tr>
                            <td><?php echo $row['student_id']; ?></td>
                            <td><span class="badge badge-light"><?php echo htmlspecialchars($row['student_code']); ?></span></td>
                            <td class="font-weight-600"><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo (!empty($row['dob'])) ? date('d/m/Y', strtotime($row['dob'])) : ''; ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['department']); ?></td>
                            <td>
                                <?php if ($row['status'] == 'Đang ở'): ?>
                                    <span class="badge badge-success">Đang ở</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Đã chuyển đi</span>
                                <?php endif; ?>
                            </td>
                            <td class="action-links d-flex gap-10">
                                <?php if (Auth::isManager()): ?>
                                <a href="/student/edit/<?php echo $row['student_id']; ?>" class="btn-icon text-primary" title="Sửa"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="/student/delete/<?php echo $row['student_id']; ?>" class="btn-icon text-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?');"><i class="fa-solid fa-trash"></i></a>
                                <?php else: ?>
                                <span class="text-muted" style="font-size:12px;">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Không tìm thấy sinh viên nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php include 'views/layout/pagination.php'; ?>
</div>

<?php include 'views/layout/footer.php'; ?>
