# Changelog

## [Unreleased] — 2026-05-15

### Tính năng: Phân quyền người dùng (RBAC)

Triển khai hệ thống phân quyền 2 vai trò: **Quản lý (manager)** và **Sinh viên (student)**.

---

## Bước 1 — Database: Bảng Account & Dữ liệu mặc định

### Thêm mới
- **`database/db.sql`**
  - Thêm định nghĩa bảng `Account` vào cuối schema:
    - Các cột: `account_id`, `username`, `password` (bcrypt), `role` (ENUM: manager/student), `student_id` (FK → Student, NULL nếu là manager), `created_at`
    - Ràng buộc: `FOREIGN KEY (student_id) REFERENCES Student(student_id) ON DELETE CASCADE`

- **`database/seed_accounts.php`** *(file mới)*
  - Script tạo bảng `Account` (nếu chưa tồn tại) và insert 2 tài khoản mặc định:

    | Username  | Password      | Role    |
    |-----------|---------------|---------|
    | `admin`   | `Admin@123`   | manager |
    | `student` | `Student@123` | student |

  - Mật khẩu được hash bằng `password_hash(..., PASSWORD_BCRYPT, ['cost' => 12])`
  - Dùng `INSERT IGNORE` — chạy nhiều lần không bị lỗi trùng lặp

---

## Bước 2 — Core: Routing & Middleware

### Thêm mới
- **`core/Auth.php`** *(file mới)*
  - Class tĩnh xử lý toàn bộ logic xác thực và phân quyền
  - Các method chính:
    - `Auth::check()` — kiểm tra đã đăng nhập chưa
    - `Auth::role()` — lấy role hiện tại (`'manager'` | `'student'` | `null`)
    - `Auth::isManager()` / `Auth::isStudent()` — kiểm tra vai trò
    - `Auth::studentId()` — lấy `student_id` từ session
    - `Auth::username()` — lấy username từ session
    - `Auth::login(array $account)` — lưu thông tin vào session (có `session_regenerate_id`)
    - `Auth::logout()` — xóa toàn bộ session và cookie
    - `Auth::requireLogin()` — redirect `/auth/login` nếu chưa đăng nhập
    - `Auth::requireRole($roles)` — trả về 403 nếu không đủ quyền

- **`controllers/AuthController.php`** *(file mới)*
  - `loginForm()` — GET `/auth/login`, hiển thị form
  - `login()` — POST `/auth/login`, xác thực bằng `password_verify()`, lưu session
  - `logout()` — GET `/auth/logout`, gọi `Auth::logout()` rồi redirect

- **`views/auth/login.php`** *(file mới)*
  - Form đăng nhập với glassmorphism design
  - Hiển thị lỗi nếu sai thông tin
  - Có phần hint tài khoản demo

- **`views/errors/403.php`** *(file mới)*
  - Trang lỗi 403 Forbidden khi không đủ quyền truy cập

### Chỉnh sửa
- **`core/Router.php`**
  - Thêm tham số `array $roles = []` vào `get()` và `post()`
  - Thêm private method `checkAccess(array $roles)` với 3 trường hợp:
    - `$roles = []` → public, không cần đăng nhập
    - `$roles = ['*']` → phải đăng nhập, mọi role đều được
    - `$roles = ['manager']` / `['student']` / `['manager','student']` → kiểm tra role cụ thể

- **`core/BaseController.php`**
  - Thêm `requireLogin()` — wrapper gọi `Auth::requireLogin()`
  - Thêm `requireRole($roles)` — wrapper gọi `Auth::requireRole()`

- **`config/routes.php`**
  - Thêm tham số `$roles` cho toàn bộ route theo quy tắc:

    | Route group | Manager | Student |
    |---|---|---|
    | `/auth/*` | public | public |
    | `/` (Dashboard) | `['*']` | `['*']` |
    | `/student` (CRUD) | `['manager']` | — |
    | `/student/profile` | — | `['student']` |
    | `/room` (CRUD) | `['manager']` | — |
    | `/room/my-room` | — | `['student']` |
    | `/contract` (CRUD) | `['manager']` | — |
    | `/bill` (CRUD) | `['manager']` | — |
    | `/bill/my-bills` | — | `['student']` |
    | `/notice` (index) | `['manager','student']` | `['manager','student']` |
    | `/notice` (CRUD) | `['manager']` | — |

- **`index.php`**
  - Thêm `require_once 'core/Auth.php'` trước khi khởi tạo Router

---

## Bước 3 — Controllers: Lọc dữ liệu theo Role

### Chỉnh sửa
- **`controllers/DashboardController.php`**
  - Extends `BaseController` (trước đây không extends)
  - `index()` phân nhánh theo role:
    - Manager → `managerDashboard()`: thống kê toàn hệ thống + biểu đồ doanh thu
    - Student → `studentDashboard()`: thông tin cá nhân + phòng + hóa đơn chưa trả + số thông báo

- **`controllers/StudentController.php`**
  - Thêm method `profile()`:
    - Lấy `student_id` từ `Auth::studentId()` (không từ URL — ngăn leo quyền)
    - Lấy thông tin sinh viên + hợp đồng đang ở
    - Render `views/students/profile.php`

- **`controllers/RoomController.php`**
  - Thêm method `myRoom()`:
    - Tự tìm phòng qua `Contract` của sinh viên đang đăng nhập
    - Lấy danh sách bạn cùng phòng (JOIN Contract → Student)
    - Render `views/rooms/my_room.php`

- **`controllers/BillController.php`**
  - Thêm method `myBills()`:
    - Lấy `room_id` từ `Contract` đang ở của sinh viên
    - Truy vấn tất cả hóa đơn của phòng đó (sắp xếp mới nhất trước)
    - Render `views/bills/my_bills.php`

- **`controllers/NoticeController.php`**
  - Cập nhật `index()` — phân nhánh trong cùng một route `/notice`:
    - Manager: dùng model `Notice::read()` có phân trang + tìm kiếm
    - Student: SQL tùy chỉnh lọc thông báo `target_type = 'Cả tòa'` OR `Phòng` của mình OR `Cá nhân` cho mình

### Thêm mới (Views cho sinh viên)
- **`views/dashboard/student.php`** *(file mới)*
  - Stat cards: phòng đang ở, số hóa đơn chưa trả, số thông báo
  - Card thông tin cá nhân + card phòng đang ở
  - Quick links: Thông báo, Hóa đơn, Phòng của tôi, Hồ sơ

- **`views/students/profile.php`** *(file mới)*
  - Hồ sơ cá nhân chỉ đọc (full_name, student_code, dob, phone, email, department, status)
  - Card hợp đồng + phòng đang ở (price, start_date, end_date)

- **`views/rooms/my_room.php`** *(file mới)*
  - Thông tin chi tiết phòng + hợp đồng
  - Bảng danh sách bạn cùng phòng (full_name, student_code, department, phone)
  - Link nhanh đến trang hóa đơn

- **`views/bills/my_bills.php`** *(file mới)*
  - Cảnh báo nếu còn hóa đơn chưa thanh toán (số lượng + tổng tiền)
  - Bảng hóa đơn: tháng, chỉ số điện/nước, phí phòng, tổng, trạng thái, ngày tạo, ngày trả

---

## Bước 4 — Views: Giao diện theo Role

### Chỉnh sửa
- **`views/layout/sidebar.php`**
  - Thêm `$isManager = Auth::isManager()`
  - Brand title: `KTX Admin` (manager) / `KTX Portal` (student)
  - Menu Manager: Tổng quan, Sinh viên, Phòng KTX, Hợp đồng, Hóa đơn, Thông báo
  - Menu Student: Tổng quan, Hồ sơ cá nhân, Phòng của tôi, Hóa đơn, Thông báo
  - Thêm `sidebar-section-label` phân nhóm menu
  - Thêm `sidebar-user` card ở cuối: avatar chữ cái + tên + role + nút đăng xuất

- **`views/layout/header.php`**
  - `<title>` động theo `$pageTitle`
  - Xóa avatar hardcode `ui-avatars.com` → thay bằng `user-avatar-initials` (chữ cái đầu username)
  - Hiển thị `user-profile-name` + `user-profile-role` từ `Auth::username()` / `Auth::isManager()`
  - Thêm nút đăng xuất trong header

- **`views/students/index.php`**
  - Nút "Thêm mới": chỉ hiện với `Auth::isManager()`
  - Cột Hành Động: Manager thấy nút Sửa + Xóa; Student thấy dấu `—`

- **`views/bills/index.php`**
  - Nút "Lập hóa đơn": chỉ hiện với `Auth::isManager()`
  - Cột Hành Động: Manager thấy Print + Sửa + Xóa; Student chỉ thấy Print

- **`views/notices/index.php`**
  - `$pageTitle` động: "Quản lý Thông báo" (manager) / "Thông báo" (student)
  - Nút "Tạo Thông báo": chỉ hiện với `Auth::isManager()`
  - Thanh tìm kiếm: chỉ hiện với `Auth::isManager()`
  - Cột "Hành Động" trong `<thead>`: chỉ render với `Auth::isManager()`
  - Các nút Sửa + Xóa trong `<tbody>`: chỉ render với `Auth::isManager()`

- **`public/css/style.css`**
  - Thêm styles cho các component UI mới:
    - `.sidebar-section-label` — nhãn phân nhóm menu
    - `.sidebar-user` / `.sidebar-user-avatar` / `.sidebar-user-name` / `.sidebar-user-role` — user card cuối sidebar
    - `.sidebar-logout` — nút đăng xuất sidebar
    - `.user-avatar-initials` — avatar chữ cái trong header
    - `.user-profile-info` / `.user-profile-name` / `.user-profile-role` — thông tin user trong header
    - `.header-right` — fix flex alignment

---

## Tổng hợp File

| File | Trạng thái |
|---|---|
| `database/db.sql` | ✏️ Chỉnh sửa |
| `database/seed_accounts.php` | 🆕 Mới |
| `core/Auth.php` | 🆕 Mới |
| `core/Router.php` | ✏️ Chỉnh sửa |
| `core/BaseController.php` | ✏️ Chỉnh sửa |
| `index.php` | ✏️ Chỉnh sửa |
| `config/routes.php` | ✏️ Chỉnh sửa |
| `controllers/AuthController.php` | 🆕 Mới |
| `controllers/DashboardController.php` | ✏️ Chỉnh sửa |
| `controllers/StudentController.php` | ✏️ Chỉnh sửa |
| `controllers/RoomController.php` | ✏️ Chỉnh sửa |
| `controllers/BillController.php` | ✏️ Chỉnh sửa |
| `controllers/NoticeController.php` | ✏️ Chỉnh sửa |
| `views/auth/login.php` | 🆕 Mới |
| `views/errors/403.php` | 🆕 Mới |
| `views/dashboard/student.php` | 🆕 Mới |
| `views/students/profile.php` | 🆕 Mới |
| `views/rooms/my_room.php` | 🆕 Mới |
| `views/bills/my_bills.php` | 🆕 Mới |
| `views/layout/sidebar.php` | ✏️ Chỉnh sửa |
| `views/layout/header.php` | ✏️ Chỉnh sửa |
| `views/students/index.php` | ✏️ Chỉnh sửa |
| `views/bills/index.php` | ✏️ Chỉnh sửa |
| `views/notices/index.php` | ✏️ Chỉnh sửa |
| `public/css/style.css` | ✏️ Chỉnh sửa |
