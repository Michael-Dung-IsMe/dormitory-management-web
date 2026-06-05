<?php
$pageTitle = 'Cập nhật Phòng';
include 'views/layout/header.php';
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="card-title mb-0">Cập nhật thông tin</h3>
        <a href="/room" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="/room/edit/<?php echo htmlspecialchars($room->room_id); ?>" method="POST" class="custom-form">
        <div class="form-row">
            <div class="form-group">
                <label>Số Phòng <span class="text-danger">*</span></label>
                <input type="number" name="room_number" class="form-control" required value="<?php echo htmlspecialchars($room->room_number); ?>">
            </div>
            <div class="form-group">
                <label>Tầng <span class="text-danger">*</span></label>
                <input type="number" name="floor_number" class="form-control" required value="<?php echo htmlspecialchars($room->floor_number); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Sức chứa (người) <span class="text-danger">*</span></label>
                <input type="number" name="capacity" class="form-control" required value="<?php echo htmlspecialchars($room->capacity); ?>" min="1" max="10">
            </div>
            <div class="form-group">
                <label>Loại Phòng</label>
                <select name="room_type" class="form-control">
                    <option value="4 người" <?php echo ($room->room_type == '4 người') ? 'selected' : ''; ?>>4 người</option>
                    <option value="6 người" <?php echo ($room->room_type == '6 người') ? 'selected' : ''; ?>>6 người</option>
                    <option value="8 người" <?php echo ($room->room_type == '8 người') ? 'selected' : ''; ?>>8 người</option>
                </select>
            </div>
        </div>

        <div class="form-group mb-4">
            <label>Trạng Thái</label>
            <select name="status" class="form-control">
                <option value="Hoạt động" <?php echo ($room->status == 'Hoạt động') ? 'selected' : ''; ?>>Hoạt động</option>
                <option value="Đang sửa chữa" <?php echo ($room->status == 'Đang sửa chữa') ? 'selected' : ''; ?>>Đang sửa chữa</option>
            </select>
        </div>

        <div class="form-actions text-right">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Cập nhật Phòng</button>
        </div>
    </form>
</div>

<?php include 'views/layout/footer.php'; ?>
