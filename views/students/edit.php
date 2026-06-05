<?php
$pageTitle = 'Cập nhật Sinh viên';
include 'views/layout/header.php';
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="card-title mb-0">Cập nhật thông tin</h3>
        <a href="/student" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="/student/edit/<?php echo htmlspecialchars($student->student_id); ?>" method="POST" class="custom-form">
        <div class="form-row">
            <div class="form-group">
                <label>Mã Sinh Viên (MSSV) <span class="text-danger">*</span></label>
                <input type="text" name="student_code" class="form-control" required value="<?php echo htmlspecialchars($student->student_code); ?>">
            </div>
            <div class="form-group">
                <label>Họ và Tên <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" required value="<?php echo htmlspecialchars($student->full_name); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Ngày Sinh</label>
                <input type="date" name="dob" class="form-control" value="<?php echo htmlspecialchars($student->dob); ?>">
            </div>
            <div class="form-group">
                <label>Số Điện Thoại</label>
                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($student->phone); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student->email); ?>">
            </div>
            <div class="form-group">
                <label>Khoa / Viện</label>
                <input type="text" name="department" class="form-control" value="<?php echo htmlspecialchars($student->department); ?>">
            </div>
        </div>

        <div class="form-group mb-4">
            <label>Trạng Thái</label>
            <select name="status" class="form-control">
                <option value="Đang ở" <?php echo ($student->status == 'Đang ở') ? 'selected' : ''; ?>>Đang ở</option>
                <option value="Đã chuyển đi" <?php echo ($student->status == 'Đã chuyển đi') ? 'selected' : ''; ?>>Đã chuyển đi</option>
            </select>
        </div>

        <div class="form-actions text-right">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Cập nhật Sinh Viên</button>
        </div>
    </form>
</div>

<?php include 'views/layout/footer.php'; ?>
