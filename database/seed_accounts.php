<?php
/**
 * seed_accounts.php
 * Tạo bảng Account (nếu chưa tồn tại) và insert 2 tài khoản mặc định.
 * Chạy 1 lần duy nhất: php database/seed_accounts.php
 *                   hoặc truy cập: http://localhost:8000/database/seed_accounts.php
 */
require_once __DIR__ . '/../config/database.php';

echo "<h2>Khởi tạo bảng Account & tài khoản mặc định</h2>";

try {
    $db = Database::getConnection();

    $db->exec("DROP TABLE IF EXISTS Account;");
    $db->exec("
        CREATE TABLE Account (
            account_id INT AUTO_INCREMENT PRIMARY KEY,
            username   VARCHAR(50)  NOT NULL UNIQUE,
            password   VARCHAR(255) NOT NULL,
            role       ENUM('manager', 'student') NOT NULL DEFAULT 'student',
            student_code VARCHAR(20) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (student_code) REFERENCES Student(student_code) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    echo "<p>✅ Bảng <strong>Account</strong> đã sẵn sàng.</p>";

    $sampleStudent = $db->query("SELECT student_code FROM Student LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $sampleStudentCode = $sampleStudent ? $sampleStudent['student_code'] : null;

    $defaultAccounts = [
        [
            'username'   => 'admin',
            'password'   => 'Admin@123',
            'role'       => 'manager',
            'student_code' => null,
        ],
        [
            'username'   => 'student',
            'password'   => 'Student@123',
            'role'       => 'student',
            'student_code' => $sampleStudentCode,
        ],
    ];

    $stmt = $db->prepare("
        INSERT IGNORE INTO Account (username, password, role, student_code)
        VALUES (:username, :password, :role, :student_code)
    ");

    foreach ($defaultAccounts as $acc) {
        $hash = password_hash($acc['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt->execute([
            ':username'   => $acc['username'],
            ':password'   => $hash,
            ':role'       => $acc['role'],
            ':student_code' => $acc['student_code'],
        ]);

        if ($stmt->rowCount() > 0) {
            echo "<p>✅ Đã tạo tài khoản: <strong>{$acc['username']}</strong> (role: {$acc['role']}, password: {$acc['password']})</p>";
        } else {
            echo "<p>⚠️ Tài khoản <strong>{$acc['username']}</strong> đã tồn tại, bỏ qua.</p>";
        }
    }

    $accounts = $db->query("SELECT account_id, username, role, student_code, created_at FROM Account")->fetchAll();

    echo "<h3>Danh sách tài khoản hiện có:</h3>";
    echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse;'>";
    echo "<tr style='background:#f0f0f0;'>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Student code</th>
            <th>Created At</th>
          </tr>";
    foreach ($accounts as $row) {
        echo "<tr>
                <td>{$row['account_id']}</td>
                <td>{$row['username']}</td>
                <td>{$row['role']}</td>
                <td>" . ($row['student_code'] ?? '<em>NULL</em>') . "</td>
                <td>{$row['created_at']}</td>
              </tr>";
    }
    echo "</table>";

    echo "<br><a href='../index.php' style='display:inline-block; padding:10px 20px; background:#4361ee; color:white; text-decoration:none; border-radius:5px;'>Quay lại Dashboard</a>";

} catch (PDOException $e) {
    echo "<h3 style='color:red;'>Có lỗi xảy ra: " . $e->getMessage() . "</h3>";
}
?>
