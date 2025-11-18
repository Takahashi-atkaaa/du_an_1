# HƯỚNG DẪN HỆ THỐNG QUẢN LÝ HDV NÂNG CAO

## ✅ QUAN TRỌNG: SỬ DỤNG DATABASE HIỆN CÓ

Hệ thống này **KHÔNG tạo bảng mới** mà sử dụng lại các bảng đã có:
- `lich_khoi_hanh` - Lịch khởi hành tour (dùng cho lịch làm việc HDV)
- `phan_hoi_danh_gia` - Phản hồi đánh giá (dùng cho đánh giá HDV)
- `nhat_ky_tour` - Nhật ký tour (dùng cho ghi chú hoạt động)
- `nhan_su` - Thông tin nhân sự (có sẵn các trường: ngon_ngu, kinh_nghiem)

## TÍNH NĂNG CHÍNH

### 1. Phân loại HDV tự động
Hệ thống tự động phân loại HDV dựa trên:
- **HDV Quốc tế**: Ngôn ngữ chứa "Anh", "Nhật", "Hàn", "Trung"
- **HDV Chuyên tuyến**: Kinh nghiệm chứa từ "chuyên"
- **HDV Khách đoàn**: Kinh nghiệm chứa "đoàn"
- **HDV Nội địa**: Còn lại

Logic phân loại trong SQL:
```sql
CASE 
    WHEN ns.ngon_ngu LIKE '%Anh%' OR ns.ngon_ngu LIKE '%Nhật%' THEN 'QuocTe'
    WHEN ns.kinh_nghiem LIKE '%chuyên%' THEN 'ChuyenTuyen'
    WHEN ns.kinh_nghiem LIKE '%đoàn%' THEN 'KhachDoan'
    ELSE 'NoiDia'
END AS loai_hdv
```

### 2. Quản lý lịch làm việc
- Phân công HDV cho tour qua bảng `lich_khoi_hanh`
- Kiểm tra xung đột lịch tự động
- Hiển thị lịch trên FullCalendar
- Tìm HDV sẵn sàng theo khoảng thời gian

### 3. Theo dõi hiệu suất
- Tổng số tour đã dẫn (từ `lich_khoi_hanh`)
- Điểm đánh giá trung bình (từ `phan_hoi_danh_gia`)
- Tour gần nhất
- Tỷ lệ hoàn thành

## CÁC FILE QUAN TRỌNG

### Models
- `models/HDVManagement.php` - Xử lý logic quản lý HDV

### Controllers
- `controllers/AdminController.php` - Các phương thức:
  - `hdvAdvanced()` - Trang chính
  - `hdvAddSchedule()` - Phân công HDV
  - `hdvGetSchedule()` - Lấy lịch JSON
  - `hdvDetail()` - Chi tiết HDV
  - `hdvSendNotification()` - Gửi thông báo (tùy chọn)

### Views
- `views/admin/hdv_quan_ly_nang_cao.php` - Giao diện chính

### Routing (index.php)
```php
case 'admin/hdv_advanced':
    $controller->hdvAdvanced();
    break;
case 'admin/hdv_add_schedule':
    $controller->hdvAddSchedule();
    break;
case 'admin/hdv_get_schedule':
    $controller->hdvGetSchedule();
    break;
case 'admin/hdv_detail':
    $controller->hdvDetail();
    break;
```

## PHƯƠNG THỨC MODEL CHÍNH

### HDVManagement Model

#### 1. getAllHDV()
Lấy danh sách tất cả HDV với phân loại tự động
```php
$hdvMgmt->getAllHDV()
```
Trả về: Array với các trường:
- `nhan_su_id`, `ho_ten`, `so_dien_thoai`, `email`
- `ngon_ngu`, `kinh_nghiem`
- `loai_hdv` (tự động phân loại)
- `trang_thai_lam_viec` ('SanSang', 'DangBan', 'NghiPhep')

#### 2. getHDVSanSang($ngay_bat_dau, $ngay_ket_thuc)
Tìm HDV rảnh trong khoảng thời gian
```php
$hdvMgmt->getHDVSanSang('2024-06-01', '2024-06-05')
```

#### 3. phanCongHDV($data)
Phân công HDV cho tour
```php
$data = [
    'tour_id' => 1,
    'hdv_id' => 5,
    'ngay_khoi_hanh' => '2024-06-01',
    'ngay_ket_thuc' => '2024-06-05',
    'diem_tap_trung' => 'Bến xe Miền Đông',
    'trang_thai' => 'DaXacNhan'
];
$result = $hdvMgmt->phanCongHDV($data);
```

#### 4. getLichLamViec($hdv_id = null)
Lấy lịch làm việc (format FullCalendar JSON)
```php
$hdvMgmt->getLichLamViec(5) // Lịch của HDV id=5
$hdvMgmt->getLichLamViec()  // Lịch của tất cả HDV
```

#### 5. getBaoCaoHieuSuat($hdv_id = null)
Báo cáo hiệu suất HDV
```php
$hdvMgmt->getBaoCaoHieuSuat(5)
```

#### 6. getDanhGiaByHDV($hdv_id)
Lấy danh sách đánh giá của HDV
```php
$hdvMgmt->getDanhGiaByHDV(5)
```

## CÁCH SỬ DỤNG

### Bước 1: Truy cập trang quản lý
```
http://localhost/project_quan_ly_tour_du_lich/index.php?act=admin/hdv_advanced
```

### Bước 2: Phân công HDV cho tour
1. Click "Phân công HDV"
2. Chọn tour từ dropdown
3. Chọn HDV phù hợp
4. Nhập ngày khởi hành, kết thúc
5. Nhập điểm tập trung (tùy chọn)
6. Chọn trạng thái
7. Click "Phân công"

### Bước 3: Xem lịch làm việc
- Tab "Lịch làm việc" hiển thị FullCalendar
- Lọc theo HDV, loại HDV, trạng thái
- Màu sắc:
  - Xanh lá: Đã xác nhận
  - Vàng: Chờ xác nhận
  - Đỏ: Hủy
  - Xám: Hoàn thành

### Bước 4: Xem báo cáo hiệu suất
- Tab "Báo cáo hiệu suất"
- Thống kê: Tour hoàn thành, điểm TB, tour gần nhất
- Click "Chi tiết" để xem thông tin đầy đủ

## CẤU TRÚC DATABASE LIÊN QUAN

### Bảng lich_khoi_hanh
```sql
CREATE TABLE lich_khoi_hanh (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tour_id INT,
    ngay_khoi_hanh DATE,
    ngay_ket_thuc DATE,
    diem_tap_trung VARCHAR(255),
    hdv_id INT,               -- Link đến nhan_su.id
    trang_thai ENUM('DaXacNhan','ChoXacNhan','Huy','HoanThanh'),
    FOREIGN KEY (tour_id) REFERENCES tour(id),
    FOREIGN KEY (hdv_id) REFERENCES nhan_su(id)
);
```

### Bảng phan_hoi_danh_gia
```sql
CREATE TABLE phan_hoi_danh_gia (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tour_id INT,
    nguoi_dung_id INT,
    loai ENUM('TourGuide','Service','Accommodation'),
    diem INT,                 -- 1-5 sao
    noi_dung TEXT,
    ngay_danh_gia DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tour_id) REFERENCES tour(id)
);
```

### Bảng nhan_su
```sql
CREATE TABLE nhan_su (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nguoi_dung_id INT,
    vai_tro ENUM('HDV','DieuHanh','TaiXe','Khac'),
    ngon_ngu TEXT,            -- Dùng để phân loại HDV Quốc tế
    kinh_nghiem TEXT,         -- Dùng để phân loại Chuyên tuyến/Đoàn
    chung_chi TEXT,
    FOREIGN KEY (nguoi_dung_id) REFERENCES nguoi_dung(id)
);
```

## LƯU Ý QUAN TRỌNG

### 1. Không tạo bảng mới
- Tuyệt đối KHÔNG chạy file `database_hdv_management.sql` (nếu có)
- Chỉ sử dụng các bảng đã tồn tại

### 2. Phân loại HDV
- Phân loại tự động dựa vào TEXT fields (`ngon_ngu`, `kinh_nghiem`)
- Không có cột `loai_hdv` trong database (chỉ tính toán runtime)
- Để đổi loại HDV, sửa nội dung `ngon_ngu` hoặc `kinh_nghiem`

### 3. Trạng thái lịch
- `trang_thai` trong `lich_khoi_hanh`: 'DaXacNhan', 'ChoXacNhan', 'Huy', 'HoanThanh'
- `trang_thai_lam_viec` (tính toán): 'SanSang', 'DangBan', 'NghiPhep'

### 4. Đánh giá HDV
- Lấy từ `phan_hoi_danh_gia` với `loai = 'TourGuide'`
- Join qua `lich_khoi_hanh` để lấy `hdv_id`

## TROUBLESHOOTING

### Lỗi: Không hiển thị HDV
- Kiểm tra bảng `nhan_su` có `vai_tro = 'HDV'`
- Kiểm tra join với `nguoi_dung`

### Lỗi: Không phân công được
- Kiểm tra xung đột lịch (HDV đã có tour trong thời gian đó)
- Kiểm tra `tour_id` và `hdv_id` có tồn tại

### Lỗi: Calendar không hiển thị
- Kiểm tra file JavaScript `fullcalendar` đã load
- Kiểm tra route `admin/hdv_get_schedule` trả về JSON đúng format

### Lỗi: Phân loại không đúng
- Kiểm tra nội dung `ngon_ngu` và `kinh_nghiem` trong bảng `nhan_su`
- Thêm từ khóa phù hợp (ví dụ: "Tiếng Anh", "chuyên tuyến Đà Lạt")

## TÍNH NĂNG MỞ RỘNG (TÙY CHỌN)

### 1. Thông báo
- Hiện tại sử dụng `$_SESSION['flash']` cho thông báo tạm thời
- Có thể tạo bảng `thong_bao` nếu cần lưu trữ lâu dài

### 2. Export báo cáo
- Thêm chức năng xuất Excel/PDF cho báo cáo hiệu suất

### 3. Dashboard HDV
- Tạo trang riêng cho HDV xem lịch của mình
- Route: `index.php?act=hdv/lich_lam_viec`

---

**Phiên bản:** 2.0  
**Cập nhật:** Sử dụng database hiện có, không tạo bảng mới  
**Tác giả:** System
