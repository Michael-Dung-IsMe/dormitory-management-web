<?php
$pageTitle = 'Tạo Thông báo';
include 'views/layout/header.php';
?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header d-flex justify-content-between align-items-center mb-4">
        <h3 class="card-title mb-0">Nội dung Thông báo</h3>
        <a href="/notice" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Quay lại</a>
    </div>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="/notice/create" method="POST" class="custom-form">
        <div class="form-group mb-4">
            <label>Loại Đối Tượng <span class="text-danger">*</span></label>
            <select name="target_type" id="target_type" class="form-control" required onchange="toggleTargets()">
                <option value="Cả tòa">Cả tòa (Tất cả sinh viên)</option>
                <option value="Phòng">Từng Phòng cụ thể</option>
                <option value="Cá nhân">Cá nhân (Sinh viên cụ thể)</option>
            </select>
        </div>

        <div class="form-group mb-4" id="room_select_group" style="display: none;">
            <label>Chọn Phòng <span class="text-danger">*</span></label>
            <select name="room_id" id="room_id" class="form-control">
                <option value="">-- Chọn Phòng --</option>
                <?php foreach($rooms as $r): ?>
                    <option value="<?php echo $r['room_id']; ?>">Phòng <?php echo htmlspecialchars($r['room_number']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group mb-4" id="student_select_group" style="display: none;">
            <label>Chọn Sinh Viên <span class="text-danger">*</span></label>
            <select name="student_id" id="student_id" class="form-control">
                <option value="">-- Chọn Sinh Viên --</option>
                <?php foreach($students as $st): ?>
                    <option value="<?php echo $st['student_id']; ?>"><?php echo htmlspecialchars($st['student_code'] . ' - ' . $st['full_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group mb-4">
            <label>Nội dung Thông báo <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" rows="5" required placeholder="Nhập nội dung thông báo... (Tối đa 300 ký tự)" maxlength="300"></textarea>
        </div>

        <div class="form-actions text-right">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Gửi Thông báo</button>
        </div>
    </form>
</div>

<script>
function toggleTargets() {
    var type = document.getElementById('target_type').value;
    var roomGroup = document.getElementById('room_select_group');
    var studentGroup = document.getElementById('student_select_group');
    var roomSelect = document.getElementById('room_id');
    var studentSelect = document.getElementById('student_id');

    if (type === 'Cả tòa') {
        roomGroup.style.display = 'none';
        studentGroup.style.display = 'none';
        roomSelect.required = false;
        studentSelect.required = false;
    } else if (type === 'Phòng') {
        roomGroup.style.display = 'block';
        studentGroup.style.display = 'none';
        roomSelect.required = true;
        studentSelect.required = false;
    } else if (type === 'Cá nhân') {
        roomGroup.style.display = 'none';
        studentGroup.style.display = 'block';
        roomSelect.required = false;
        studentSelect.required = true;
    }
}
// Init state
document.addEventListener("DOMContentLoaded", toggleTargets);
</script>

<?php include 'views/layout/footer.php'; ?>
