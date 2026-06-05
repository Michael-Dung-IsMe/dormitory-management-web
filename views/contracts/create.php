<?php
$pageTitle = 'Tạo Hợp đồng mới';
include 'views/layout/header.php';
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="card-title mb-0">Thông tin Hợp đồng</h3>
        <a href="/contract" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="/contract/create" method="POST" class="custom-form">
        <div class="form-row">
            <div class="form-group">
                <label>Sinh Viên <span class="text-danger">*</span></label>
                <select name="student_id" class="form-control" required>
                    <option value="">-- Chọn Sinh viên --</option>
                    <?php foreach($students as $st): ?>
                        <option value="<?php echo $st['student_id']; ?>">
                            <?php echo htmlspecialchars($st['student_code'] . ' - ' . $st['full_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Phòng <span class="text-danger">*</span></label>
                <select name="room_id" class="form-control" required>
                    <option value="">-- Chọn Phòng --</option>
                    <?php foreach($rooms as $r): ?>
                        <?php 
                            $isFull = $r['current_occupancy'] >= $r['capacity'];
                            $disabled = $isFull ? 'disabled' : '';
                            $statusText = $isFull ? '(Đã đầy)' : '('.$r['current_occupancy'].'/'.$r['capacity'].')';
                        ?>
                        <option value="<?php echo $r['room_id']; ?>" <?php echo $disabled; ?>>
                            Phòng <?php echo htmlspecialchars($r['room_number']); ?> <?php echo $statusText; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Ngày Bắt Đầu <span class="text-danger">*</span></label>
                <input type="date" name="start_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label>Ngày Kết Thúc (Dự kiến)</label>
                <input type="date" name="end_date" class="form-control">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Tiền Thuê (VNĐ / Tháng)</label>
                <input type="number" name="price" class="form-control" value="500000">
            </div>
            <div class="form-group">
                <label>Tiền Đặt Cọc (VNĐ)</label>
                <input type="number" name="deposit" class="form-control" value="500000">
            </div>
        </div>

        <div class="form-group mb-4">
            <label>Trạng Thái</label>
            <select name="status" class="form-control">
                <option value="Đang ở">Đang ở</option>
                <option value="Đã chuyển ra">Đã chuyển ra</option>
                <option value="Đã hủy">Đã hủy</option>
            </select>
        </div>

        <div class="form-actions text-right">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Tạo Hợp đồng</button>
        </div>
    </form>
</div>

<?php include 'views/layout/footer.php'; ?>
