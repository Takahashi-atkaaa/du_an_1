# CẬP NHẬT HỆ THỐNG QUẢN LÝ HDV - PHIÊN BẢN 2.0

## 🎯 THAY ĐỔI QUAN TRỌNG

### ✅ SỬ DỤNG DATABASE HIỆN CÓ
Hệ thống đã được **viết lại hoàn toàn** để sử dụng các bảng database sẵn có:

| Bảng cũ (KHÔNG dùng) | Bảng mới (SỬ DỤNG) | Mục đích |
|---------------------|-------------------|----------|
| ~~lich_lam_viec_hdv~~ | `lich_khoi_hanh` | Lịch làm việc HDV |
| ~~hieu_suat_hdv~~ | `lich_khoi_hanh` + JOIN | Thống kê hiệu suất |
| ~~danh_gia_hdv~~ | `phan_hoi_danh_gia` | Đánh giá HDV |
| ~~thong_bao_hdv~~ | `$_SESSION['flash']` | Thông báo tạm thời |
| ~~chung_chi_hdv~~ | `nhan_su.chung_chi` | Chứng chỉ (đã có) |

**⚠️ QUAN TRỌNG:** Tuyệt đối KHÔNG chạy file `database_hdv_management.sql`

---

## 📋 FILE ĐÃ THAY ĐỔI

### 1. models/HDVManagement.php
**Trạng thái:** ✅ Đã tạo mới hoàn toàn

**Nội dung:**
- Sử dụng ONLY `lich_khoi_hanh`, `phan_hoi_danh_gia`, `nhat_ky_tour`
- Phân loại HDV bằng SQL CASE/LIKE (không có cột mới)
- Kiểm tra xung đột lịch tự động

**Phương thức chính:**
```php
getAllHDV()              // Lấy danh sách HDV + phân loại tự động
getHDVSanSang($start, $end)  // Tìm HDV rảnh
phanCongHDV($data)       // Phân công HDV cho tour
getLichLamViec($hdv_id)  // Lấy lịch FullCalendar
getBaoCaoHieuSuat($hdv_id)  // Báo cáo hiệu suất
getDanhGiaByHDV($hdv_id)    // Đánh giá HDV
```

### 2. controllers/AdminController.php
**Trạng thái:** ✅ Đã cập nhật

**Thay đổi:**
- `hdvAddSchedule()`: Nhận field từ form mới (tour_id, hdv_id, ngay_khoi_hanh, ngay_ket_thuc, diem_tap_trung, trang_thai)
- Gọi `phanCongHDV()` thay vì `addLichLamViec()`

### 3. views/admin/hdv_quan_ly_nang_cao.php
**Trạng thái:** ✅ Đã cập nhật

**Thay đổi:**
- Modal form: Đổi từ "Thêm lịch" → "Phân công HDV cho Tour"
- Field mới: 
  - `tour_id` (dropdown từ bảng tour)
  - `hdv_id` (thay vì nhan_su_id)
  - `ngay_khoi_hanh` (thay vì ngay_bat_dau)
  - `diem_tap_trung` (thay vì ghi_chu)
  - `trang_thai` (DaXacNhan/ChoXacNhan/Huy)
- Xóa field: `loai_lich` (không còn dùng)

### 4. HUONG_DAN_HDV_MANAGEMENT.md
**Trạng thái:** ✅ Đã viết lại

**Nội dung:**
- Hướng dẫn sử dụng database hiện có
- Giải thích phân loại HDV tự động
- API documentation
- Troubleshooting

---

## 🔍 LOGIC PHÂN LOẠI HDV TỰ ĐỘNG

Không có cột `loai_hdv` trong database. Phân loại runtime bằng SQL:

```sql
CASE 
    WHEN ns.ngon_ngu LIKE '%Anh%' OR ns.ngon_ngu LIKE '%Nhật%' 
         OR ns.ngon_ngu LIKE '%Hàn%' OR ns.ngon_ngu LIKE '%Trung%' 
    THEN 'QuocTe'
    
    WHEN ns.kinh_nghiem LIKE '%chuyên%' 
    THEN 'ChuyenTuyen'
    
    WHEN ns.kinh_nghiem LIKE '%đoàn%' 
    THEN 'KhachDoan'
    
    ELSE 'NoiDia'
END AS loai_hdv
```

**Cách đổi loại HDV:**
- Vào "Quản lý nhân sự" → Sửa nhân sự
- Thêm từ khóa vào `ngon_ngu`: "Tiếng Anh", "Tiếng Nhật", v.v.
- Thêm từ khóa vào `kinh_nghiem`: "chuyên tuyến Đà Lạt", "chuyên khách đoàn"

---

## 🗂️ CẤU TRÚC BẢNG `lich_khoi_hanh`

```sql
lich_khoi_hanh (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    tour_id         INT,              -- Link đến tour
    ngay_khoi_hanh  DATE,             -- Ngày bắt đầu
    ngay_ket_thuc   DATE,             -- Ngày kết thúc
    diem_tap_trung  VARCHAR(255),     -- Điểm tập trung
    hdv_id          INT,              -- Link đến nhan_su.id
    trang_thai      ENUM('DaXacNhan','ChoXacNhan','Huy','HoanThanh')
)
```

**Sử dụng cho:**
1. Lịch làm việc HDV
2. Kiểm tra xung đột lịch
3. Thống kê số tour đã dẫn
4. Hiển thị FullCalendar

---

## 🎨 MÀU SẮC CALENDAR

| Trạng thái | Màu | Mã màu |
|-----------|-----|--------|
| DaXacNhan | Xanh lá | #28a745 |
| ChoXacNhan | Vàng | #ffc107 |
| Huy | Đỏ | #dc3545 |
| HoanThanh | Xám | #6c757d |

---

## 🧪 CÁCH TEST

### 1. Thêm HDV mẫu
```sql
INSERT INTO nhan_su (nguoi_dung_id, vai_tro, ngon_ngu, kinh_nghiem, chung_chi)
VALUES 
(1, 'HDV', 'Tiếng Việt, Tiếng Anh', '5 năm kinh nghiệm dẫn tour quốc tế', 'Chứng chỉ HDV quốc tế'),
(2, 'HDV', 'Tiếng Việt', '3 năm chuyên tuyến Đà Lạt', 'Chứng chỉ HDV nội địa'),
(3, 'HDV', 'Tiếng Việt, Tiếng Hàn', '10 năm chuyên khách đoàn Hàn Quốc', 'Chứng chỉ HDV quốc tế');
```

### 2. Truy cập trang
```
http://localhost/project_quan_ly_tour_du_lich/index.php?act=admin/hdv_advanced
```

### 3. Phân công HDV
1. Click "Phân công HDV"
2. Chọn tour, HDV, ngày
3. Submit → Kiểm tra có insert vào `lich_khoi_hanh`

### 4. Xem lịch
- Tab "Lịch làm việc"
- Check FullCalendar hiển thị đúng

### 5. Xem báo cáo
- Tab "Báo cáo hiệu suất"
- Kiểm tra số liệu khớp với `lich_khoi_hanh`

---

## ⚠️ LƯU Ý QUAN TRỌNG

1. **KHÔNG chạy SQL mới**: File `database_hdv_management.sql` chỉ để tham khảo
2. **Phân loại tự động**: Không có cột `loai_hdv`, chỉ tính toán runtime
3. **Trạng thái**: `trang_thai` trong `lich_khoi_hanh`, `trang_thai_lam_viec` là tính toán
4. **Đánh giá**: Join `phan_hoi_danh_gia` → `lich_khoi_hanh` để lấy HDV

---

## 📞 HỖ TRỢ

### Lỗi: Không hiển thị HDV
**Nguyên nhân:** Không có HDV trong bảng `nhan_su`  
**Giải pháp:** INSERT HDV với `vai_tro = 'HDV'`

### Lỗi: Không phân công được
**Nguyên nhân:** Xung đột lịch  
**Giải pháp:** Chọn HDV khác hoặc thời gian khác

### Lỗi: Phân loại sai
**Nguyên nhân:** `ngon_ngu` hoặc `kinh_nghiem` không có từ khóa  
**Giải pháp:** Thêm "Tiếng Anh", "chuyên", "đoàn" vào field tương ứng

### Lỗi: Calendar trống
**Nguyên nhân:** Không có dữ liệu trong `lich_khoi_hanh`  
**Giải pháp:** Phân công HDV cho tour trước

---

**Phiên bản:** 2.0  
**Ngày cập nhật:** <?php echo date('Y-m-d'); ?>  
**Tác giả:** System  
**Ghi chú:** Hoàn toàn tương thích với database hiện có, không cần migration
