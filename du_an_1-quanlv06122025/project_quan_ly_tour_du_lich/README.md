# Hệ thống Quản lý Tour Du lịch

Hệ thống quản lý tour du lịch được xây dựng bằng PHP thuần với kiến trúc MVC.

## Tính năng

- **Quản lý Tour**: Thêm, sửa, xóa tour
- **Quản lý Booking**: Đặt tour, xem hóa đơn
- **Quản lý Người dùng**: Phân quyền Admin, HDV, Khách hàng, Nhà cung cấp
- **Báo cáo Tài chính**: Thống kê doanh thu
- **Đánh giá Tour**: Khách hàng đánh giá tour

## Yêu cầu hệ thống

- PHP >= 7.4
- MySQL >= 5.7
- Apache/Nginx với mod_rewrite
- Composer (tùy chọn)

## Cài đặt

1. Clone repository:
```bash
git clone <repository-url>
cd project_quan_ly_tour_du_lich
```

2. Cấu hình database:
- Tạo database MySQL
- Sao chép file `.env.example` thành `.env`
- Điền thông tin database vào file `.env`

3. Import database:
```sql
-- Tạo các bảng cần thiết
-- (Cần tạo script SQL để import)
```

4. Import database:
- Import file `database.sql` vào MySQL

5. Khởi chạy:
- Truy cập `http://localhost/project_quan_ly_tour_du_lich/` hoặc URL đã cấu hình
- Hoặc sử dụng: `http://localhost/project_quan_ly_tour_du_lich/index.php?act=tour/index`

## Cấu trúc thư mục

```
project_quan_ly_tour_du_lich/
├── index.php           # Entry point chính
├── commons/            # File chung (env.php, function.php)
├── controllers/        # Controllers
├── models/             # Models
├── views/              # Views
├── public/             # Public files (CSS, JS, images)
├── uploads/            # Upload files
├── storage/            # Logs, backups, cache
└── database.sql        # File SQL để tạo database
```

## Sử dụng

### Đăng nhập
- Admin: Quản lý toàn bộ hệ thống
- HDV: Quản lý lịch làm việc và tour
- Khách hàng: Xem và đặt tour
- Nhà cung cấp: Quản lý dịch vụ và hợp đồng

## License

MIT


