CREATE DATABASE QuanLyKTX;

USE QuanLyKTX;

-- Bảng Student (Sinh viên)
CREATE TABLE Student (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    student_code VARCHAR(20) NOT NULL UNIQUE,
    dob DATE,
    phone VARCHAR(15) UNIQUE,
    email VARCHAR(100) UNIQUE,
    department VARCHAR(100), -- Lưu trực tiếp tên khoa/viện dưới dạng chuỗi
    status ENUM('Đang ở', 'Đã chuyển đi') DEFAULT 'Đang ở'
);

-- Bảng Room (Phòng)
CREATE TABLE Room (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_number INT NOT NULL UNIQUE,
    floor_number INT NOT NULL,
    capacity INT NOT NULL,
    room_type ENUM('4 người', '6 người', '8 người') DEFAULT '8 người',
    status ENUM('Hoạt động', 'Đang sửa chữa') DEFAULT 'Hoạt động'
);

-- Bảng Contract (Hợp đồng)
CREATE TABLE Contract (
    contract_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    room_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    status ENUM('Đang ở', 'Đã chuyển ra', 'Đã hủy') DEFAULT 'Đang ở',
    price DECIMAL(10,2),
    deposit DECIMAL(10,2),
    FOREIGN KEY (student_id) REFERENCES Student(student_id),
    FOREIGN KEY (room_id) REFERENCES Room(room_id)
);

-- Bảng UtilityBill (Hóa đơn Hàng tháng)
CREATE TABLE UtilityBill (
    bill_id            INT PRIMARY KEY AUTO_INCREMENT,
    room_id            INT NOT NULL,
    billing_month      TINYINT NOT NULL CHECK (billing_month BETWEEN 1 AND 12),
    billing_year       INT NOT NULL,
    old_electric_index INT NOT NULL,
    new_electric_index INT NOT NULL,
    old_water_index    INT NOT NULL,
    new_water_index    INT NOT NULL,
    room_fee           DECIMAL(10, 2) NOT NULL DEFAULT 0, -- Phí thuê phòng (nếu đóng cùng lúc)
    total_amount       DECIMAL(12, 2) NOT NULL,           -- Tổng tiền cần thanh toán
    status             ENUM('Chưa thanh toán', 'Đã thanh toán') NOT NULL DEFAULT 'Chưa thanh toán',
    created_date       DATE NOT NULL,
    payment_date       DATE DEFAULT NULL, -- Ngày thực tế đóng tiền

    CONSTRAINT fk_bill_room FOREIGN KEY (room_id) REFERENCES Room(room_id),
    
    -- Chỉ số mới phải luôn lớn hơn hoặc bằng chỉ số cũ
    CONSTRAINT chk_index_electric CHECK (new_electric_index >= old_electric_index),
    CONSTRAINT chk_index_water CHECK (new_water_index >= old_water_index),
    
    -- Mỗi phòng chỉ có 1 hóa đơn cho 1 tháng trong 1 năm cụ thể
    CONSTRAINT uq_room_billing UNIQUE (room_id, billing_month, billing_year)
);


-- Bảng Notice (Thông báo)
CREATE TABLE Notice (
    notice_id INT AUTO_INCREMENT PRIMARY KEY,
    target_type ENUM('Cả tòa', 'Phòng', 'Cá nhân') NOT NULL,
    room_id INT NULL,
    student_id INT NULL,
    description VARCHAR(300),
    date DATE NOT NULL,
    FOREIGN KEY (room_id) REFERENCES Room(room_id),
    FOREIGN KEY (student_id) REFERENCES Student(student_id),
    CHECK (
        (target_type = 'Cả tòa' AND room_id IS NULL AND student_id IS NULL) OR
        (target_type = 'Phòng' AND room_id IS NOT NULL AND student_id IS NULL) OR
        (target_type = 'Cá nhân' AND student_id IS NOT NULL AND room_id IS NULL)
    )
);


-- Bảng Account (Tài khoản đăng nhập & phân quyền)
-- role = 'manager': quản lý, student_id = NULL
-- role = 'student': sinh viên, student_id liên kết tới bảng Student
CREATE TABLE Account (
    account_id INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('manager', 'student') NOT NULL DEFAULT 'student',
    student_code VARCHAR(20) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_code) REFERENCES Student(student_code) ON DELETE CASCADE
);
