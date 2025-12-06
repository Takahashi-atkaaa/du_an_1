# Tính Năng Lương, Hoa Hồng & Thưởng cho HDV - Hướng Dẫn Cài Đặt

## 📋 Tổng Quan
Tính năng này cho phép HDV xem chi tiết lương cơ bản, hoa hồng theo từng tour, và các khoản thưởng của họ.

## 📁 Các Tệp Tạo/Sửa Đổi

### 1. **models/SalaryBonus.php** (Tạo mới)
Model quản lý lương, hoa hồng, và thưởng cho HDV.

**Các phương thức chính:**
- `getSalaryByTour($nhanSuId)` - Lấy danh sách lương theo tour
- `getBonuses($nhanSuId)` - Lấy danh sách thưởng
- `getSalarySummary($nhanSuId)` - Lấy thống kê tổng hợp
- `createSalaryRecord($data)` - Tạo bản ghi lương mới
- `updateSalaryRecord($salaryId, $data)` - Cập nhật bản ghi lương
- `addBonus($data)` - Thêm thưởng mới
- `getSalaryDetail($salaryId)` - Lấy chi tiết bản ghi lương
- `calculateCommission($tourRevenue, $commissionPercentage)` - Tính toán hoa hồng

### 2. **controllers/HDVController.php** (Sửa đổi)
Thêm:
- Import: `require_once 'models/SalaryBonus.php';`
- Property: `private $salaryBonusModel;`
- Khởi tạo trong constructor: `$this->salaryBonusModel = new SalaryBonus();`
- Phương thức mới: `luongThuong()` - Hiển thị trang lương và thưởng

### 3. **views/hdv/luong_thuong.php** (Tạo mới)
Giao diện để hiển thị lương, thưởng, hoa hồng cho HDV.

**Tính năng:**
- 4 thẻ thống kê: Lương cơ bản, Hoa hồng, Thưởng, Tổng cộng
- Tab 1: Lương theo tour (bảng chi tiết)
- Tab 2: Danh sách thưởng
- Responsive design với Bootstrap 5
- Định dạng tiền tệ Việt Nam

### 4. **index.php** (Sửa đổi)
Thêm route:
```php
'hdv/luongThuong' => (new HDVController())->luongThuong(),
```

### 5. **storage/migrate_hdv_salary_system.sql** (Tạo mới)
Migration SQL để tạo cơ sở dữ liệu:

**Bảng tạo:**
- `hdv_salary` - Lưu trữ lương chi tiết theo tour
- `hdv_bonus` - Lưu trữ thưởng cho HDV

**Cộng lại bảng `nhan_su`:**
- `commission_percentage` - Tỉ lệ hoa hồng mặc định (%)

**View tạo:**
- `view_hdv_salary_summary` - Thống kê lương HDV

### 6. **views/hdv/dashboard.php** (Sửa đổi)
Thêm feature card mới:
- Biểu tượng ví tiền
- Liên kết đến trang lương thưởng
- Mô tả: "Xem lương, hoa hồng, thưởng của bạn"

## 🚀 Cách Cài Đặt

### Bước 1: Chạy Migration
Chạy file SQL để tạo cơ sở dữ liệu:
```bash
# Trong phpMyAdmin hoặc command line MySQL
source /path/to/storage/migrate_hdv_salary_system.sql;
```

Hoặc nhập nội dung file vào phpmyadmin > SQL tab

### Bước 2: Xác minh các tệp
- ✅ Kiểm tra `models/SalaryBonus.php` tồn tại
- ✅ Kiểm tra `views/hdv/luong_thuong.php` tồn tại
- ✅ Kiểm tra route trong `index.php`
- ✅ Kiểm tra dashboard menu item trong `views/hdv/dashboard.php`

### Bước 3: Đăng nhập HDV
Đăng nhập vào tài khoản HDV và kiểm tra:
1. Trang dashboard có button "Lương & Thưởng" mới
2. Click vào button để xem trang lương thưởng

## 📊 Cấu Trúc Dữ Liệu

### Bảng hdv_salary
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | INT | ID bản ghi lương |
| nhan_su_id | INT | ID nhân sự |
| tour_id | INT | ID tour |
| lich_khoi_hanh_id | INT | ID lịch khởi hành |
| base_salary | DECIMAL(15,2) | Lương cơ bản |
| commission_percentage | DECIMAL(5,2) | Tỉ lệ hoa hồng (%) |
| tour_revenue | DECIMAL(15,2) | Doanh thu tour |
| commission_amount | DECIMAL(15,2) | Tiền hoa hồng |
| bonus_amount | DECIMAL(15,2) | Tiền thưởng |
| total_amount | DECIMAL(15,2) | Tổng tiền |
| payment_status | ENUM | Trạng thái thanh toán |
| payment_date | DATETIME | Ngày thanh toán |
| notes | TEXT | Ghi chú |

### Bảng hdv_bonus
| Cột | Kiểu | Mô tả |
|-----|------|-------|
| id | INT | ID bản ghi thưởng |
| nhan_su_id | INT | ID nhân sự |
| bonus_type | VARCHAR(100) | Loại thưởng |
| amount | DECIMAL(15,2) | Số tiền thưởng |
| reason | TEXT | Lý do thưởng |
| award_date | DATE | Ngày thưởng |
| approval_status | ENUM | Trạng thái phê duyệt |
| approved_by | INT | Phê duyệt bởi |
| notes | TEXT | Ghi chú |

## 💡 Cách Sử Dụng

### Cho HDV:
1. Đăng nhập vào tài khoản HDV
2. Click vào card "Lương & Thưởng" ở dashboard
3. Xem thông tin tổng hợp ở phần thẻ thống kê trên cùng
4. Click tab "Lương Theo Tour" để xem chi tiết lương từng tour
5. Click tab "Danh Sách Thưởng" để xem các khoản thưởng

### Cho Admin (thêm dữ liệu):
```php
// Thêm lương mới
$salaryBonus = new SalaryBonus();
$data = [
    'nhan_su_id' => 100,
    'tour_id' => 6,
    'lich_khoi_hanh_id' => 10,
    'base_salary' => 5000000,
    'commission_percentage' => 5,
    'tour_revenue' => 263920000,
    'commission_amount' => (263920000 * 5 / 100),
    'bonus_amount' => 0,
    'total_amount' => 5000000 + (263920000 * 5 / 100),
    'payment_status' => 'Pending',
    'notes' => 'Lương tour Nhật Bản'
];
$salaryBonus->createSalaryRecord($data);

// Thêm thưởng
$bonusData = [
    'nhan_su_id' => 100,
    'bonus_type' => 'KhenThuong',
    'amount' => 1000000,
    'reason' => 'Dẫn tour xuất sắc',
    'award_date' => date('Y-m-d'),
    'approval_status' => 'DuyetPhep'
];
$salaryBonus->addBonus($bonusData);
```

## 🔧 Lưu Ý Quan Trọng

1. **Bảng hdv_salary cần được cập nhật** bằng script hoặc admin function
2. **commission_percentage** ở `nhan_su` table có giá trị mặc định là 5% (có thể thay đổi)
3. **tour_revenue** có thể được tính từ `booking.tong_tien` hoặc nhập thủ công
4. **Trạng thái thanh toán**: 
   - Pending: Chưa duyệt
   - Approved: Đã duyệt
   - Paid: Đã thanh toán

## 📝 Truy Vấn Hữu Ích

### Lấy tóm tắt lương tất cả HDV:
```sql
SELECT * FROM view_hdv_salary_summary;
```

### Lấy lương chưa thanh toán:
```sql
SELECT * FROM hdv_salary 
WHERE payment_status = 'Pending'
ORDER BY created_at DESC;
```

### Lấy thưởng chờ phê duyệt:
```sql
SELECT * FROM hdv_bonus 
WHERE approval_status = 'ChoPheDuyet'
ORDER BY award_date DESC;
```

## ✨ Tính Năng Mở Rộng (Tương Lai)

- [ ] Tạo admin panel để quản lý lương, thưởng
- [ ] Xuất báo cáo lương PDF
- [ ] Gửi thông báo khi lương được thanh toán
- [ ] Tích hợp thanh toán trực tuyến
- [ ] Lịch sử thanh toán
- [ ] So sánh lương giữa các tháng

---

**Phiên bản:** 1.0  
**Ngày tạo:** 2025-01-01  
**Tác giả:** Development Team
