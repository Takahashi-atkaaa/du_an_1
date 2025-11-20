# Hướng dẫn sử dụng Chức năng Quản lý Khách theo Tour

## Tổng quan
Hệ thống quản lý khách theo tour bao gồm các tính năng:
- ✅ Danh sách khách theo tour/lịch khởi hành
- ✅ Check-in khách (CMND, Passport, thông tin liên hệ)
- ✅ Phân phòng khách sạn
- ✅ In danh sách đoàn

## Cài đặt

### Bước 1: Chạy migration database
Truy cập: `http://localhost/du_an_1/project_quan_ly_tour_du_lich/run_migration_checkin.php`

Migration sẽ tạo 2 bảng:
- `tour_checkin`: Lưu thông tin check-in khách
- `hotel_room_assignment`: Lưu thông tin phân phòng khách sạn

### Bước 2: Kiểm tra routes
Đảm bảo các routes sau đã được thêm vào `index.php`:
- `admin/danhSachKhachTheoTour`
- `admin/checkInKhach`
- `admin/updateCheckIn`
- `admin/phanPhongKhachSan`

## Hướng dẫn sử dụng

### 1. Xem danh sách khách theo tour

**Cách 1: Từ trang Quản lý Tour**
1. Vào `Quản lý Tour` (index.php?act=admin/quanLyTour)
2. Click vào link "Danh sách khách" của tour cần xem
3. Chọn lịch khởi hành từ danh sách

**Cách 2: Truy cập trực tiếp**
```
index.php?act=admin/danhSachKhachTheoTour&tour_id={TOUR_ID}
index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id={LICH_KHOI_HANH_ID}
```

**Thông tin hiển thị:**
- Thông tin tour (tên, mã, ngày khởi hành, giá)
- Thống kê: Tổng booking, đã check-in, chưa check-in, đã phân phòng
- Bảng danh sách booking với các cột:
  - STT
  - Mã Booking
  - Tên Khách
  - Email
  - Số điện thoại
  - Số người
  - Trạng thái check-in
  - Thao tác (Check-in, Phân phòng)

### 2. Check-in khách

**Thao tác:**
1. Từ danh sách khách, click nút "Check-in" tại hàng booking
2. Điền thông tin form:
   - Họ và tên (bắt buộc)
   - Số CMND/CCCD
   - Số Passport
   - Số điện thoại
   - Email
   - Ghi chú
3. Click "✅ Check-in"

**Cập nhật check-in:**
- Sau khi đã check-in, click "Xem chi tiết" để cập nhật thông tin
- Có thể thay đổi trạng thái: Đã check-in → Đã check-out

**Database:**
- Bảng: `tour_checkin`
- Trường quan trọng: `booking_id`, `ho_ten`, `so_cmnd`, `so_passport`, `trang_thai`, `check_in_time`

### 3. Phân phòng khách sạn

**Thao tác:**
1. Từ danh sách khách, click nút "Phân phòng" tại hàng booking
2. Điền thông tin phòng:
   - Tên khách sạn (bắt buộc) - có gợi ý từ danh sách đã dùng
   - Số phòng (bắt buộc)
   - Loại phòng: Standard/Superior/Deluxe/Suite
   - Số giường: 1-4
   - Ngày nhận phòng
   - Ngày trả phòng
   - Giá phòng (VNĐ)
   - Trạng thái: Đã đặt phòng/Đã nhận phòng/Đã trả phòng
   - Ghi chú
3. Click "➕ Thêm phòng"

**Quản lý phòng:**
- Xem danh sách phòng đã phân cho booking
- Xóa phân phòng (nút 🗑️ Xóa)
- Mỗi booking có thể có nhiều phòng

**Database:**
- Bảng: `hotel_room_assignment`
- Trường quan trọng: `booking_id`, `ten_khach_san`, `so_phong`, `loai_phong`, `trang_thai`

### 4. In danh sách đoàn

**Thao tác:**
1. Từ trang danh sách khách theo tour
2. Click nút "📄 In Danh Sách Đoàn"
3. Hộp thoại in sẽ hiển thị với:
   - Thông tin tour
   - Thống kê
   - Bảng danh sách khách (ẩn cột "Thao tác")
   - Phần ký tên: Người lập danh sách, Trưởng đoàn

**In hoặc xuất PDF:**
- Chọn máy in hoặc "Save as PDF"
- Định dạng: A4
- Hướng: Portrait (dọc)

## Cấu trúc Code

### Models
```
models/TourCheckin.php
- getAll()
- findById($id)
- getByBookingId($bookingId)
- getByLichKhoiHanhId($lichKhoiHanhId)
- insert($data)
- update($id, $data)
- checkout($id)
- delete($id)
- getStatsByLichKhoiHanh($lichKhoiHanhId)

models/HotelRoomAssignment.php
- getAll()
- findById($id)
- getByLichKhoiHanhId($lichKhoiHanhId)
- getByBookingId($bookingId)
- insert($data)
- update($id, $data)
- updateStatus($id, $status)
- delete($id)
- getHotelList()
- getStatsByLichKhoiHanh($lichKhoiHanhId)
```

### Controllers
```
controllers/AdminController.php
- danhSachKhachTheoTour()
- checkInKhach()
- updateCheckIn()
- phanPhongKhachSan()
```

### Views
```
views/admin/danh_sach_khach.php      - Danh sách khách với thống kê
views/admin/check_in.php             - Form check-in
views/admin/phan_phong.php           - Form và danh sách phân phòng
```

### Database Schema
```sql
-- Bảng tour_checkin
CREATE TABLE tour_checkin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lich_khoi_hanh_id INT NOT NULL,
    booking_id INT NOT NULL,
    ho_ten VARCHAR(255) NOT NULL,
    so_cmnd VARCHAR(20),
    so_passport VARCHAR(20),
    so_dien_thoai VARCHAR(20),
    email VARCHAR(255),
    check_in_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    check_out_time DATETIME,
    trang_thai ENUM('DaCheckIn','DaCheckOut') DEFAULT 'DaCheckIn',
    ghi_chu TEXT,
    FOREIGN KEY (booking_id) REFERENCES booking(booking_id)
);

-- Bảng hotel_room_assignment
CREATE TABLE hotel_room_assignment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lich_khoi_hanh_id INT NOT NULL,
    booking_id INT NOT NULL,
    checkin_id INT,
    ten_khach_san VARCHAR(255) NOT NULL,
    so_phong VARCHAR(50) NOT NULL,
    loai_phong VARCHAR(50) DEFAULT 'Standard',
    so_giuong INT DEFAULT 1,
    ngay_nhan_phong DATE,
    ngay_tra_phong DATE,
    gia_phong DECIMAL(15,2) DEFAULT 0,
    trang_thai ENUM('DaDatPhong','DaNhanPhong','DaTraPhong') DEFAULT 'DaDatPhong',
    ghi_chu TEXT,
    FOREIGN KEY (booking_id) REFERENCES booking(booking_id)
);
```

## Troubleshooting

### Lỗi "Table doesn't exist"
- Chạy lại migration: `run_migration_checkin.php`

### Không hiển thị danh sách khách
- Kiểm tra tour có booking không
- Kiểm tra lịch khởi hành đã được tạo chưa

### Lỗi khi check-in/phân phòng
- Kiểm tra foreign key: `booking_id` phải tồn tại trong bảng `booking`
- Kiểm tra `lich_khoi_hanh_id` đúng

### In không đẹp
- Sử dụng Chrome/Edge để in (hỗ trợ @media print tốt hơn)
- Kiểm tra orientation: Portrait
- Kiểm tra margins: mặc định

## API Endpoints (nếu cần tích hợp)

```php
// GET: Lấy danh sách khách theo lịch khởi hành
GET index.php?act=admin/danhSachKhachTheoTour&lich_khoi_hanh_id={ID}

// POST: Check-in khách
POST index.php?act=admin/checkInKhach
Body: {
    "lich_khoi_hanh_id": int,
    "booking_id": int,
    "ho_ten": string,
    "so_cmnd": string,
    "so_passport": string,
    "so_dien_thoai": string,
    "email": string,
    "ghi_chu": string
}

// POST: Phân phòng
POST index.php?act=admin/phanPhongKhachSan
Body: {
    "action": "add",
    "lich_khoi_hanh_id": int,
    "booking_id": int,
    "ten_khach_san": string,
    "so_phong": string,
    "loai_phong": string,
    "so_giuong": int,
    "ngay_nhan_phong": date,
    "ngay_tra_phong": date,
    "gia_phong": decimal,
    "trang_thai": string,
    "ghi_chu": string
}
```

## Tính năng mở rộng (có thể phát triển thêm)

- [ ] Export Excel danh sách khách
- [ ] Gửi email/SMS thông báo cho khách
- [ ] QR Code cho check-in nhanh
- [ ] Upload hình ảnh CMND/Passport
- [ ] Lịch sử thay đổi phòng
- [ ] Thống kê chi phí khách sạn theo tour
- [ ] Tích hợp với hệ thống khách sạn

---

**Ngày tạo:** <?php echo date('d/m/Y'); ?>  
**Phiên bản:** 1.0  
**Tác giả:** GitHub Copilot
