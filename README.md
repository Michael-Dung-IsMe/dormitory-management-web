# Hệ Thống Quản Lý Ký Túc Xá (Dormitory Management System)

Dự án Hệ thống Quản lý Ký Túc Xá là một ứng dụng web được xây dựng bằng **PHP thuần (Vanilla PHP)**, áp dụng chặt chẽ kiến trúc **MVC (Model - View - Controller)** hướng đối tượng kết hợp với hệ thống **Routing** tùy chỉnh.

## Cấu trúc thư mục

```
dormitory-management-web/
│
├── index.php
├── README.md
│
├── config/
│   ├── database.php
│   ├── database.php.example
│   └── routes.php
│
├── core/
│   ├── Router.php
│   ├── BaseController.php
│   └── BaseModel.php
│
├── controllers/
│   ├── DashboardController.php
│   ├── StudentController.php
│   ├── RoomController.php
│   ├── ContractController.php
│   ├── BillController.php
│   └── NoticeController.php
│
├── models/
│   ├── Student.php
│   ├── Room.php
│   ├── Contract.php
│   ├── UtilityBill.php
│   └── Notice.php
│
├── views/
│   ├── layout/
│   │   ├── header.php
│   │   ├── sidebar.php
│   │   ├── footer.php
│   │   └── pagination.php
│   ├── dashboard/
│   │   └── index.php
│   ├── students/
│   │   ├── index.php
│   │   ├── create.php
│   │   └── edit.php
│   ├── rooms/
│   │   ├── index.php
│   │   ├── create.php
│   │   └── edit.php
│   ├── contracts/
│   │   ├── index.php
│   │   ├── create.php
│   │   └── edit.php
│   ├── bills/
│   │   ├── index.php
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── export.php
│   └── notices/
│       ├── index.php
│       ├── create.php
│       └── edit.php
│
├── database/
│   ├── db.sql
│   └── seed.php
│
└── public/
    ├── css/
    │   ├── style.css
    │   └── components.css
    ├── js/
    └── images/
```

## Kiến trúc & Thư mục dự án

Dự án được phân chia thành các thư mục độc lập, tuân thủ nguyên tắc thiết kế **Separation of Concerns**:

- **[`core/`](core/README.md)**: Chứa các thành phần lõi nền tảng, bao gồm `Router` (điều hướng URL, hỗ trợ tham số động `{id}`), `BaseController` (xử lý phân trang chung), và `BaseModel` (cung cấp `count()` và `read()` dùng chung cho mọi Model).
- **[`config/`](config/README.md)**: Chứa cấu hình kết nối Database (Singleton PDO, đảm bảo chỉ có 1 kết nối duy nhất mỗi request) và `routes.php` khai báo toàn bộ endpoint của hệ thống.
- **[`controllers/`](controllers/README.md)**: Tầng Controller tiếp nhận request từ Router, giao tiếp với Model để xử lý nghiệp vụ, sau đó load View tương ứng. Bao gồm 6 controller: Dashboard, Sinh viên, Phòng, Hợp đồng, Hóa đơn và Thông báo.
- **[`models/`](models/README.md)**: Tầng Model đại diện cho các bảng CSDL, xử lý toàn bộ truy vấn PDO. Kế thừa chức năng phân trang/đếm số lượng từ `BaseModel`, chỉ cần tự định nghĩa `readOne()`, `create()`, `update()`, `delete()`.
- **[`views/`](views/README.md)**: Tầng giao diện. Thư mục `layout/` chứa các partial tái sử dụng (header, sidebar, footer, pagination). Mỗi module có 3 view tiêu chuẩn: `index`, `create`, `edit`.
- **[`database/`](database/README.md)**: Schema CSDL gốc (`db.sql`) và script seed tự động tạo dữ liệu mẫu chuẩn PTIT (`seed.php`).
- **`public/`**: Static assets — CSS (`style.css`, `components.css`), JavaScript, hình ảnh.

## Chức năng chính

| #   | Module        | Chức năng                                                                                             |
| --- | ------------- | ----------------------------------------------------------------------------------------------------- |
| 1   | **Dashboard** | Thống kê tổng quan (tổng SV, phòng trống, hóa đơn chưa thu). Biểu đồ doanh thu theo tháng (Chart.js). |
| 2   | **Sinh viên** | CRUD sinh viên. Tìm kiếm theo tên hoặc mã sinh viên.                                                  |
| 3   | **Phòng KTX** | CRUD phòng. Tự động tính `current_occupancy`. Sắp xếp theo tầng.                                      |
| 4   | **Hợp đồng**  | Gán sinh viên vào phòng. Validate phòng chưa đầy & SV không trùng hợp đồng active.                    |
| 5   | **Hóa đơn**   | CRUD hóa đơn điện, nước, phòng. Tự động tính tổng tiền. Xuất/in hóa đơn trực tiếp.                    |
| 6   | **Thông báo** | Đăng thông báo 3 cấp: Toàn trường / Theo phòng / Theo sinh viên cụ thể.                               |

## Hướng dẫn cài đặt & Chạy ứng dụng

### Yêu cầu hệ thống

- PHP >= 7.4
- MySQL / MariaDB

### Các bước triển khai

**1. Clone repository:**

```bash
git clone <repo_url>
cd dormitory-management-web
```

**2. Khởi tạo Cơ sở dữ liệu:**

```bash
mysql -u root -p < database/db.sql
```

**3. Cấu hình môi trường:**

```bash
cp config/database.php.example config/database.php
```

Mở `config/database.php` và cập nhật `$host`, `$db_name`, `$username`, `$password` phù hợp môi trường local.

**4. Khởi tạo dữ liệu mẫu** _(tùy chọn)_:

```bash
php database/seed.php
```

Script tự động tạo dữ liệu liên kết theo thứ tự: Room → Student → Contract → UtilityBill → Notice, với mã sinh viên chuẩn định dạng PTIT.

**5. Khởi chạy ứng dụng:**

```bash
php -S localhost:8000
```
