# Hướng dẫn Kết nối Database

## Bước 1: Tạo Database

1. Mở phpMyAdmin hoặc MySQL Command Line
2. Tạo database mới:
```sql
CREATE DATABASE quan_ly_tour_du_lich CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## Bước 2: Import Database

### Cách 1: Sử dụng phpMyAdmin
1. Chọn database `quan_ly_tour_du_lich`
2. Click vào tab "Import"
3. Chọn file `database.sql`
4. Click "Go" để import

### Cách 2: Sử dụng Command Line
```bash
mysql -u root -p quan_ly_tour_du_lich < database.sql
```

## Bước 3: Cấu hình file .env

1. Sao chép file `env.example` thành `.env`
```bash
cp env.example .env
```

2. Chỉnh sửa file `.env` với thông tin database của bạn:
```env
DB_HOST=localhost
DB_NAME=quan_ly_tour_du_lich
DB_USER=root
DB_PASS=your_password
```

## Bước 4: Test kết nối

Truy cập: `http://localhost/project_quan_ly_tour_du_lich/test_connection.php`

Nếu kết nối thành công, bạn sẽ thấy thông báo màu xanh và danh sách các bảng.

## Thông tin đăng nhập mặc định

Sau khi import database.sql, bạn có thể đăng nhập với:

- **Admin:**
  - Email: `admin@example.com`
  - Password: `password`

- **HDV:**
  - Email: `hdv@example.com`
  - Password: `password`

- **Khách hàng:**
  - Email: `khach@example.com`
  - Password: `password`

## Lưu ý

- Đảm bảo MySQL/MariaDB đã được khởi động
- Kiểm tra port MySQL (mặc định là 3306)
- Nếu dùng XAMPP, MySQL thường chạy trên port 3306
- Password mặc định của XAMPP MySQL thường là rỗng (empty)

## Xử lý lỗi

### Lỗi: "Access denied for user"
- Kiểm tra username và password trong file .env
- Đảm bảo user có quyền truy cập database

### Lỗi: "Unknown database"
- Đảm bảo database đã được tạo
- Kiểm tra tên database trong file .env

### Lỗi: "Connection refused"
- Kiểm tra MySQL đã khởi động chưa
- Kiểm tra port MySQL có đúng không

