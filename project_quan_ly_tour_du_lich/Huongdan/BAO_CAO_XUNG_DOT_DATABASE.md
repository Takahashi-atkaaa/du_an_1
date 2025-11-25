# 🔍 BÁO CÁO KIỂM TRA XUNG ĐỘT DATABASE

## ❌ CÁC VẤN ĐỀ PHÁT HIỆN

### 1. **TRÙNG LẶP BẢNG `booking_history`** (NGHIÊM TRỌNG)

**Vị trí:**
- **Lần 1**: Dòng 194-206 trong database.sql
- **Lần 2**: Dòng 313-327 trong database.sql

**Chi tiết:**
```sql
-- Lần 1 (dòng 194-206)
CREATE TABLE IF NOT EXISTS booking_history (
  ...
  FOREIGN KEY (booking_id) REFERENCES booking(booking_id) ON DELETE CASCADE,
  FOREIGN KEY (nguoi_thay_doi_id) REFERENCES nguoi_dung(id) ON DELETE SET NULL,
  INDEX idx_booking_id (booking_id),
  INDEX idx_thoi_gian (thoi_gian)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Lần 2 (dòng 313-327)
CREATE TABLE booking_history (
  ...
  FOREIGN KEY (booking_id) REFERENCES booking(booking_id),
  FOREIGN KEY (nguoi_thay_doi_id) REFERENCES nguoi_dung(id)
);
```

**Hậu quả:**
- ✅ Không gây lỗi khi chạy (do `IF NOT EXISTS` ở lần 1)
- ⚠️ Lần 2 sẽ bị bỏ qua vì bảng đã tồn tại
- ⚠️ Gây nhầm lẫn khi đọc code
- ⚠️ Nếu xóa lần 1, lần 2 thiếu ON DELETE CASCADE và indexes

**Khuyến nghị:** XÓA lần 2 (dòng 313-327)

---

### 2. **XUNG ĐỘT GIÁ TRỊ ENUM `lich_khoi_hanh.trang_thai`** (NGHIÊM TRỌNG)

**Database định nghĩa:**
```sql
trang_thai ENUM('SapKhoiHanh','DangChay','HoanThanh')
```

**Code sử dụng (views/admin/hdv_quan_ly_nang_cao.php):**
```html
<option value="DaXacNhan">Đã xác nhận</option>
<option value="ChoXacNhan">Chờ xác nhận</option>
<option value="Huy">Hủy</option>
```

**Controller (AdminController.php):**
```php
'trang_thai' => $_POST['trang_thai'] ?? 'DaXacNhan'
```

**Hậu quả:**
- ❌ INSERT sẽ BỊ LỖI vì giá trị không khớp với ENUM
- ❌ Form submit sẽ thất bại khi phân công HDV

**Khuyến nghị:** Cập nhật database hoặc sửa code

---

### 3. **CÁC BẢNG KHÁC (OK)**

✅ **Bảng `nguoi_dung`**: 
- Có trường `avatar` (dòng 17) - OK, chưa sử dụng nhưng không gây xung đột

✅ **Các FOREIGN KEY**: Tất cả đều tham chiếu đúng

✅ **Các INDEX**: Không có trùng lặp

---

## 🛠️ GIẢI PHÁP ĐỀ XUẤT

### Giải pháp 1: CẬP NHẬT DATABASE (KHUYẾN NGHỊ)

**Lý do:** Giữ logic code hiện tại, mở rộng ENUM cho linh hoạt

```sql
-- XÓA dòng 313-327 (bảng booking_history trùng)

-- SỬA dòng 136: Thêm giá trị ENUM
ALTER TABLE lich_khoi_hanh MODIFY trang_thai 
  ENUM('SapKhoiHanh','DangChay','HoanThanh','DaXacNhan','ChoXacNhan','Huy');
```

### Giải pháp 2: CẬP NHẬT CODE

**Nếu không muốn sửa database:**

Sửa file: `views/admin/hdv_quan_ly_nang_cao.php` (dòng 488)
```html
<option value="SapKhoiHanh">Sắp khởi hành</option>
<option value="DangChay">Đang chạy</option>
<option value="HoanThanh">Hoàn thành</option>
```

Sửa file: `controllers/AdminController.php` (hdvAddSchedule)
```php
'trang_thai' => $_POST['trang_thai'] ?? 'SapKhoiHanh'
```

---

## 📊 TỔNG KẾT

| Vấn đề | Mức độ | Gây lỗi | Giải pháp |
|--------|--------|---------|-----------|
| Trùng bảng `booking_history` | ⚠️ Trung bình | Không | Xóa lần 2 |
| ENUM `trang_thai` không khớp | ❌ Nghiêm trọng | **CÓ** | Sửa DB hoặc Code |
| Trường `avatar` chưa dùng | ✅ OK | Không | Giữ nguyên |

---

## ✅ CHECKLIST HÀNH ĐỘNG

- [ ] Xóa định nghĩa thứ 2 của bảng `booking_history` (dòng 313-327)
- [ ] Chọn 1 trong 2 giải pháp cho ENUM `trang_thai`
- [ ] Test lại chức năng phân công HDV
- [ ] Kiểm tra INSERT vào `lich_khoi_hanh`

---

**Ngày kiểm tra:** 2025-11-15  
**Người kiểm tra:** AI Assistant  
**Trạng thái:** Cần sửa ngay
