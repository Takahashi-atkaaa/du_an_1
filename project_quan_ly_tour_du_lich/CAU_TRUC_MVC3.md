# Cấu trúc MVC3

Dự án đã được tái cấu trúc theo mô hình MVC3 với cấu trúc đơn giản hơn.

## Cấu trúc thư mục

```
project_quan_ly_tour_du_lich/
├── index.php              # File entry point chính
├── commons/               # Thư mục chứa các file chung
│   ├── env.php           # Khai báo biến môi trường, constants
│   └── function.php      # Các hàm hỗ trợ (connectDB, uploadFile, redirect, etc.)
├── controllers/          # Các Controller
│   ├── AuthController.php
│   ├── TourController.php
│   ├── BookingController.php
│   └── ...
├── models/              # Các Model
│   ├── Tour.php
│   ├── NguoiDung.php
│   ├── Booking.php
│   └── ...
├── views/               # Các View
│   ├── auth/
│   ├── admin/
│   ├── khach_hang/
│   └── ...
├── uploads/             # Thư mục upload file
├── public/              # Thư mục public (CSS, JS, images)
├── database.sql         # File SQL để tạo database
└── .env                 # File cấu hình môi trường
```

## Đặc điểm

### 1. File index.php
- Sử dụng `match()` để routing
- Require tất cả Controller và Model
- Route format: `index.php?act=controller/action`

### 2. Commons
- **env.php**: Khai báo constants (BASE_URL, DB_HOST, DB_NAME, etc.)
- **function.php**: Các hàm hỗ trợ:
  - `connectDB()`: Kết nối PDO
  - `uploadFile()`: Upload file
  - `deleteFile()`: Xóa file
  - `redirect()`: Chuyển hướng
  - `requireLogin()`: Yêu cầu đăng nhập
  - `requireRole()`: Yêu cầu role cụ thể

### 3. Controllers
- Không extend class Controller
- Sử dụng hàm từ `function.php`
- Sử dụng `require` để load view
- Sử dụng `redirect()` để chuyển hướng

### 4. Models
- Sử dụng `connectDB()` từ `function.php`
- Sử dụng PDO với prepared statements
- Các method cơ bản: `getAll()`, `findById()`, `find()`, `insert()`, `update()`, `delete()`

### 5. Views
- Sử dụng `require` để load
- Đường dẫn link: `index.php?act=controller/action`
- Sử dụng các biến từ Controller

## Cách sử dụng

### Routing
```
index.php?act=tour/index        # Danh sách tour
index.php?act=tour/show&id=1    # Chi tiết tour
index.php?act=auth/login        # Đăng nhập
index.php?act=admin/dashboard   # Dashboard admin
```

### Tạo Controller mới
1. Tạo file trong `controllers/`
2. Thêm require trong `index.php`
3. Thêm route trong `match()`

### Tạo Model mới
1. Tạo file trong `models/`
2. Sử dụng `connectDB()` để kết nối
3. Implement các method cần thiết

## So sánh với cấu trúc cũ

| Cấu trúc cũ | Cấu trúc mới (MVC3) |
|-------------|---------------------|
| `app/core/` | `commons/` |
| `app/controllers/` | `controllers/` |
| `app/models/` | `models/` |
| `app/views/` | `views/` |
| `public/index.php` | `index.php` (root) |
| Route class | `match()` trong index.php |
| Controller extends Controller | Controller độc lập |
| Model extends Model | Model độc lập |

## Ưu điểm

1. **Đơn giản hơn**: Ít lớp abstraction, dễ hiểu
2. **Dễ maintain**: Cấu trúc rõ ràng, dễ tìm file
3. **Nhẹ hơn**: Không cần các class base phức tạp
4. **Phù hợp cho dự án vừa và nhỏ**: Đủ cho hầu hết các dự án

## Lưu ý

- Đảm bảo file `.env` đã được tạo và cấu hình đúng
- Database phải được import từ `database.sql`
- Các thư mục `uploads/` phải có quyền ghi

