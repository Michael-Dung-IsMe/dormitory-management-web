<?php
// seed.php - Chạy file này trên trình duyệt hoặc CLI để tạo dữ liệu ảo
require_once __DIR__ . '/../config/database.php';

echo "<h2>Bắt đầu tạo dữ liệu ảo...</h2>";

try {
    $db = Database::getConnection();
    
    $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $db->exec("TRUNCATE TABLE Notice;");
    $db->exec("TRUNCATE TABLE UtilityBill;");
    $db->exec("TRUNCATE TABLE Contract;");
    $db->exec("TRUNCATE TABLE Room;");
    $db->exec("TRUNCATE TABLE Student;");
    $db->exec("SET FOREIGN_KEY_CHECKS = 1;");

    // Config số lượng
    $num_rooms = 15;
    $num_students = 50;

    // --- 1. Sinh Phòng ---
    echo "<p>Đang tạo $num_rooms Phòng...</p>";
    $stmtR = $db->prepare("INSERT INTO Room (room_number, floor_number, capacity, room_type, status) VALUES (?, ?, ?, ?, ?)");
    $room_types = ['4 người', '6 người', '8 người'];
    $capacities = [4, 6, 8];
    for ($i = 1; $i <= $num_rooms; $i++) {
        $floor = ceil($i / 5);
        $room_num = $floor * 100 + ($i % 5 == 0 ? 5 : $i % 5);
        $cap = $capacities[array_rand($capacities)];
        $type = $room_types[array_rand($room_types)];
        $status = (rand(1, 10) > 1) ? 'Hoạt động' : 'Đang sửa chữa';
        $stmtR->execute([$room_num, $floor, $cap, $type, $status]);
    }
    $roomIds = $db->query("SELECT room_id FROM Room WHERE status = 'Hoạt động'")->fetchAll(PDO::FETCH_COLUMN);

    // --- 2. Sinh Sinh viên ---
    echo "<p>Đang tạo $num_students Sinh viên...</p>";
    
    $lastNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương', 'Lý'];
    $middleNames = ['Văn', 'Thị', 'Đức', 'Hữu', 'Minh', 'Ngọc', 'Thanh', 'Hải', 'Xuân', 'Thu', 'Hồng', 'Gia'];
    $firstNames = ['Anh', 'Bình', 'Châu', 'Dũng', 'Hà', 'Huy', 'Khoa', 'Linh', 'Ninh', 'Phong', 'Quân', 'Sơn', 'Trang', 'Tùng', 'Vinh', 'Yến', 'Lan', 'Nam', 'Kiên'];
    
    $departments = [
        'CNTT' => 'CN',
        'ATTT' => 'AT',
        'KHMT' => 'KH',
        'Quản trị kinh doanh' => 'QK',
        'ĐTVT' => 'ĐT',
        'KTD-DT' => 'KD',
        'TTNT' => 'TT',
        'Marketing' => 'MK',
        'Kế toán' => 'KT',
        'Thương mại điện tử' => 'TM'
    ];
    $deptNames = array_keys($departments);
    
    function removeAccents($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'd', $str);
        return strtolower($str);
    }

    $stmtS = $db->prepare("INSERT INTO Student (full_name, student_code, dob, phone, email, department, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $generatedPhones = [];
    $generatedEmails = [];
    $generatedCodes = [];

    for ($i = 0; $i < $num_students; $i++) {
        $last = $lastNames[array_rand($lastNames)];
        $mid = $middleNames[array_rand($middleNames)];
        $first = $firstNames[array_rand($firstNames)];
        $fullName = "$last $mid $first";
        
        $dept = $deptNames[array_rand($deptNames)];
        $deptCode = $departments[$dept];
        
        $yearCode = ['21', '22', '23', '24', '25'][rand(0, 4)];
        
        do {
            $numCode = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $studentCode = "B{$yearCode}DC{$deptCode}{$numCode}";
        } while (in_array($studentCode, $generatedCodes));
        $generatedCodes[] = $studentCode;
        
        $dob_year = 2026 - (int)$yearCode - 3; 
        $dob = "$dob_year-" . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . "-" . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
        
        $prefixes = ['03', '08', '09'];
        do {
            $phone = $prefixes[array_rand($prefixes)] . str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (in_array($phone, $generatedPhones));
        $generatedPhones[] = $phone;
        
        $emailPrefix = removeAccents($first) . substr(removeAccents($last), 0, 1) . substr(removeAccents($mid), 0, 1);
        $email = $emailPrefix . "@stu.ptit.edu.vn";
        
        // Tránh trùng lặp email
        $counter = 1;
        while (in_array($email, $generatedEmails)) {
            $email = $emailPrefix . $counter . "@stu.ptit.edu.vn";
            $counter++;
        }
        $generatedEmails[] = $email;
        
        $status = (rand(1, 10) > 2) ? 'Đang ở' : 'Đã chuyển đi';
        
        $stmtS->execute([$fullName, $studentCode, $dob, $phone, $email, $dept, $status]);
    }
    
    $studentIds = $db->query("SELECT student_id FROM Student WHERE status = 'Đang ở'")->fetchAll(PDO::FETCH_COLUMN);

    
    // --- 3. Sinh Hợp đồng ---
    echo "<p>Đang tạo Hợp đồng...</p>";
    $stmtC = $db->prepare("INSERT INTO Contract (student_id, room_id, start_date, end_date, status, price, deposit) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Xáo trộn sinh viên
    shuffle($studentIds);
    $activeContracts = 0;
    
    $roomCapacities = [];
    foreach($db->query("SELECT room_id, capacity FROM Room WHERE status = 'Hoạt động'") as $row) {
        $roomCapacities[$row['room_id']] = $row['capacity'];
    }
    
    $roomOccupancy = array_fill_keys(array_keys($roomCapacities), 0);
    $activeRoomIds = array_keys($roomCapacities);
    
    foreach ($studentIds as $sId) {
        // Tìm phòng còn trống
        $availableRooms = array_filter($activeRoomIds, function($rId) use ($roomOccupancy, $roomCapacities) {
            return $roomOccupancy[$rId] < $roomCapacities[$rId];
        });
        
        if (empty($availableRooms)) {
            break; // Hết phòng trống
        }
        
        $rId = $availableRooms[array_rand($availableRooms)];
        $roomOccupancy[$rId]++;
        
        $startDate = '2023-09-0' . rand(1, 9);
        $endDate = '2024-09-0' . rand(1, 9);
        $price = rand(4, 8) * 100000; 
        $stmtC->execute([$sId, $rId, $startDate, $endDate, 'Đang ở', $price, $price]);
        $activeContracts++;
    }

    // --- 4. Sinh Hóa đơn ---
    echo "<p>Đang tạo Hóa đơn...</p>";
    $stmtB = $db->prepare("INSERT INTO UtilityBill (room_id, billing_month, billing_year, old_electric_index, new_electric_index, old_water_index, new_water_index, room_fee, total_amount, status, created_date, payment_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $year = date('Y');
    foreach ($activeRoomIds as $rId) {
        if ($roomOccupancy[$rId] > 0) { // Chỉ tạo bill cho phòng có người ở
            for ($month = 1; $month <= date('n'); $month++) {
                $oldE = rand(100, 500);
                $newE = $oldE + rand(50, 150);
                $oldW = rand(10, 50);
                $newW = $oldW + rand(5, 20);
                $roomFee = 0;
                $total = (($newE - $oldE) * 3500) + (($newW - $oldW) * 10000);
                
                $isPaid = ($month < date('n')) ? true : (rand(1, 10) > 5);
                $status = $isPaid ? 'Đã thanh toán' : 'Chưa thanh toán';
                
                $createdDate = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-05";
                $paymentDate = $isPaid ? "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-" . str_pad(rand(6, 20), 2, '0', STR_PAD_LEFT) : null;
                
                $stmtB->execute([$rId, $month, $year, $oldE, $newE, $oldW, $newW, $roomFee, $total, $status, $createdDate, $paymentDate]);
            }
        }
    }

    // --- 5. Sinh Thông báo ---
    echo "<p>Đang tạo Thông báo...</p>";
    $stmtN = $db->prepare("INSERT INTO Notice (target_type, room_id, student_id, description, date) VALUES (?, ?, ?, ?, ?)");
    
    $stmtN->execute(['Cả tòa', null, null, 'Lịch dọn vệ sinh tổng thể tòa nhà cuối tuần này.', date('Y-m-d')]);
    
    if (!empty($activeRoomIds)) {
        $stmtN->execute(['Phòng', $activeRoomIds[0], null, 'Phòng các bạn chú ý không để rác ngoài hành lang.', date('Y-m-d')]);
    }
    
    if (!empty($studentIds)) {
        $stmtN->execute(['Cá nhân', null, $studentIds[0], 'Mời bạn xuống văn phòng ban quản lý nhận thẻ sinh viên mới.', date('Y-m-d')]);
    }

    echo "<h3 style='color:green;'>Hoàn tất tạo dữ liệu! Đã tạo $num_rooms phòng, $num_students sinh viên, $activeContracts hợp đồng.</h3>";
    echo "<a href='../index.php' style='display:inline-block; padding: 10px 20px; background: #4361ee; color: white; text-decoration:none; border-radius: 5px;'>Quay lại Dashboard</a>";

} catch (PDOException $e) {
    echo "<h3 style='color:red;'>Có lỗi xảy ra: " . $e->getMessage() . "</h3>";
}
?>
