<?php
$pageTitle = 'Cập nhật Hóa đơn Điện Nước';
include 'views/layout/header.php';
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="card-title mb-0">Cập nhật hóa đơn</h3>
        <a href="/bill" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="/bill/edit/<?php echo htmlspecialchars($this->bill->bill_id); ?>" method="POST" class="custom-form">
        <div class="form-row">
            <div class="form-group">
                <label>Phòng <span class="text-danger">*</span></label>
                <select name="room_id" class="form-control" required>
                    <?php foreach($rooms as $r): ?>
                        <option value="<?php echo $r['room_id']; ?>" <?php echo ($r['room_id'] == $this->bill->room_id) ? 'selected' : ''; ?>>
                            Phòng <?php echo htmlspecialchars($r['room_number']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Kỳ Thu (Tháng / Năm) <span class="text-danger">*</span></label>
                <div class="d-flex gap-10">
                    <select name="billing_month" class="form-control" required>
                        <?php for($i=1; $i<=12; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo ($this->bill->billing_month == $i) ? 'selected' : ''; ?>>Tháng <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    <input type="number" name="billing_year" class="form-control" required value="<?php echo htmlspecialchars($this->bill->billing_year); ?>">
                </div>
            </div>
        </div>

        <h4 class="mb-4 mt-4" style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Chỉ số Điện</h4>
        <div class="form-row">
            <div class="form-group">
                <label>Chỉ số Cũ <span class="text-danger">*</span></label>
                <input type="number" name="old_electric_index" class="form-control" required value="<?php echo htmlspecialchars($this->bill->old_electric_index); ?>">
            </div>
            <div class="form-group">
                <label>Chỉ số Mới <span class="text-danger">*</span></label>
                <input type="number" name="new_electric_index" class="form-control" required value="<?php echo htmlspecialchars($this->bill->new_electric_index); ?>">
            </div>
        </div>

        <h4 class="mb-4 mt-4" style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Chỉ số Nước</h4>
        <div class="form-row">
            <div class="form-group">
                <label>Chỉ số Cũ <span class="text-danger">*</span></label>
                <input type="number" name="old_water_index" class="form-control" required value="<?php echo htmlspecialchars($this->bill->old_water_index); ?>">
            </div>
            <div class="form-group">
                <label>Chỉ số Mới <span class="text-danger">*</span></label>
                <input type="number" name="new_water_index" class="form-control" required value="<?php echo htmlspecialchars($this->bill->new_water_index); ?>">
            </div>
        </div>

        <h4 class="mb-4 mt-4" style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Phí Khác & Trạng Thái</h4>
        <div class="form-row">
            <div class="form-group">
                <label>Phí Thuê Phòng / Khác (VNĐ)</label>
                <input type="number" name="room_fee" class="form-control" value="<?php echo htmlspecialchars($this->bill->room_fee); ?>">
            </div>
            <div class="form-group">
                <label>Trạng Thái Thanh Toán</label>
                <select name="status" class="form-control">
                    <option value="Chưa thanh toán" <?php echo ($this->bill->status == 'Chưa thanh toán') ? 'selected' : ''; ?>>Chưa thanh toán</option>
                    <option value="Đã thanh toán" <?php echo ($this->bill->status == 'Đã thanh toán') ? 'selected' : ''; ?>>Đã thanh toán</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Ngày Thanh Toán</label>
            <input type="date" name="payment_date" class="form-control" value="<?php echo htmlspecialchars($this->bill->payment_date); ?>">
        </div>

        <div class="form-actions text-right mt-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Cập nhật Hóa Đơn</button>
        </div>
    </form>
</div>

<?php include 'views/layout/footer.php'; ?>
