<?php
$pageTitle = 'Thêm Sinh viên mới';
include 'views/layout/header.php';
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="card-title mb-0">Thông tin Sinh viên</h3>
        <a href="/student" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="/student/create" method="POST" class="custom-form">
        <div class="form-row">
            <div class="form-group">
                <label>Mã Sinh Viên (MSSV) <span class="text-danger">*</span></label>
                <input type="text" name="student_code" class="form-control" required placeholder="Ví dụ: 20210001">
            </div>
            <div class="form-group">
                <label>Họ và Tên <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" required placeholder="Nhập họ và tên...">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Ngày Sinh</label>
                <input type="date" name="dob" class="form-control">
            </div>
            <div class="form-group">
                <label>Số Điện Thoại</label>
                <input type="text" name="phone" class="form-control" placeholder="09xxxxxxxxx">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="email@example.com">
            </div>
            <div class="form-group">
                <label>Khoa / Viện</label>
                <input type="text" name="department" class="form-control" placeholder="Công nghệ thông tin...">
            </div>
        </div>

        <div class="form-group mb-4">
            <label>Trạng Thái</label>
            <select name="status" class="form-control">
                <option value="Đang ở">Đang ở</option>
                <option value="Đã chuyển đi">Đã chuyển đi</option>
            </select>
        </div>

        <div class="form-actions text-right">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Lưu Sinh Viên</button>
        </div>
    </form>
</div>

<?php include 'views/layout/footer.php'; ?>
