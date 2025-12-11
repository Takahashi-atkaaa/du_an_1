# Module Quản Lý Lương Thưởng Hoa Hồng

Module này quản lý lương, thưởng và hoa hồng cho Hướng Dẫn Viên (HDV).

## Cấu Trúc Module

```
modules/luong_thuong_hoa_hong/
├── controllers/
│   └── LuongThuongController.php    # Controller xử lý logic (Admin)
├── models/
│   └── SalaryBonus.php              # Model xử lý database
├── views/
│   ├── admin/
│   │   └── quan_ly_luong_hdv.php    # View quản lý lương HDV (Admin)
│   └── hdv/
│       └── luong_thuong.php          # View xem lương thưởng (HDV)
├── database/
│   └── migrate.sql                   # File SQL migration database
└── README.md                         # File này
```

## Chức Năng

### 1. Quản Lý Lương HDV
- Xem danh sách lương theo tour
- Duyệt lương (Pending → Approved)
- Thanh toán lương (Approved → Paid)
- Thống kê lương theo trạng thái

### 2. Quản Lý Thưởng HDV
- Xem danh sách thưởng
- Phê duyệt/từ chối thưởng
- Thống kê thưởng chờ duyệt

### 3. Thống Kê
- Tổng lương đang chờ
- Tổng lương đã duyệt
- Tổng lương đã thanh toán
- Tổng thưởng chờ duyệt
- Thống kê theo từng HDV

## Database Schema

### Bảng: hdv_salary
Lưu trữ lương chi tiết theo từng tour của HDV:
- `id` - ID bản ghi lương
- `nhan_su_id` - ID nhân sự (FK → nhan_su)
- `tour_id` - ID tour (FK → tour)
- `lich_khoi_hanh_id` - ID lịch khởi hành (FK → lich_khoi_hanh)
- `base_salary` - Lương cơ bản
- `commission_percentage` - Tỉ lệ hoa hồng (%)
- `tour_revenue` - Doanh thu tour
- `commission_amount` - Tiền hoa hồng
- `bonus_amount` - Tiền thưởng
- `total_amount` - Tổng tiền
- `payment_status` - Trạng thái thanh toán (Pending, Approved, Paid)
- `payment_date` - Ngày thanh toán
- `notes` - Ghi chú

### Bảng: hdv_bonus
Lưu trữ các khoản thưởng riêng biệt:
- `id` - ID bản ghi thưởng
- `nhan_su_id` - ID nhân sự (FK → nhan_su)
- `bonus_type` - Loại thưởng
- `amount` - Số tiền thưởng
- `reason` - Lý do thưởng
- `award_date` - Ngày thưởng
- `approval_status` - Trạng thái phê duyệt (ChoPheDuyet, DuyetPhep, TuChoi)
- `approved_by` - Phê duyệt bởi (FK → nguoi_dung)
- `notes` - Ghi chú

### View: view_hdv_salary_summary
Thống kê nhanh lương theo HDV:
- Số tour đã dẫn
- Tổng lương cơ bản
- Tổng hoa hồng
- Tổng thưởng
- Tổng cộng
- Số lượng thanh toán

## Cài Đặt Database

Chạy file migration để tạo các bảng và view:

```sql
-- Chạy file: modules/luong_thuong_hoa_hong/database/migrate.sql
```

File này sẽ:
1. Thêm cột `commission_percentage` vào bảng `nhan_su` (nếu chưa có)
2. Tạo bảng `hdv_salary`
3. Tạo bảng `hdv_bonus`
4. Tạo view `view_hdv_salary_summary`

## Sử Dụng

### Trong Controller

```php
// Sử dụng trong AdminController
require_once __DIR__ . '/../modules/luong_thuong_hoa_hong/controllers/LuongThuongController.php';
$controller = new LuongThuongController();
$controller->quanLyLuongHDV();
```

### Trong Model

```php
// Sử dụng SalaryBonus model
require_once __DIR__ . '/../modules/luong_thuong_hoa_hong/models/SalaryBonus.php';
$salaryBonus = new SalaryBonus();

// Lấy thống kê lương của HDV
$summary = $salaryBonus->getSalarySummary($hdvId);

// Lấy danh sách lương theo tour
$salaryList = $salaryBonus->getSalaryByTour($hdvId);

// Lấy danh sách thưởng
$bonuses = $salaryBonus->getBonuses($hdvId);
```

## Routes

Module này được tích hợp vào hệ thống routing qua AdminController và HDVController:

### Admin Routes:
- `admin/quanLyLuongHDV` - Trang quản lý lương & thưởng HDV
- `admin/approveSalary` - API duyệt/thanh toán lương (AJAX)
- `admin/approveBonus` - API phê duyệt/từ chối thưởng (AJAX)

### HDV Routes:
- `hdv/luongThuong` - Trang xem lương, thưởng & hoa hồng của HDV

## API Methods

### LuongThuongController

1. **quanLyLuongHDV()** - Hiển thị trang quản lý lương & thưởng
2. **approveSalary()** - Duyệt/thanh toán lương (AJAX)
3. **approveBonus()** - Phê duyệt/từ chối thưởng (AJAX)

### SalaryBonus Model

1. **getSalaryByTour($nhanSuId)** - Lấy danh sách lương theo tour
2. **getBonuses($nhanSuId)** - Lấy danh sách thưởng
3. **getSalarySummary($nhanSuId)** - Lấy thống kê tổng hợp
4. **createSalaryRecord($data)** - Tạo bản ghi lương mới
5. **updateSalaryRecord($salaryId, $data)** - Cập nhật bản ghi lương
6. **addBonus($data)** - Thêm thưởng
7. **getSalaryDetail($salaryId)** - Lấy chi tiết bản ghi lương
8. **calculateCommission($tourRevenue, $commissionPercentage)** - Tính hoa hồng

## Lưu Ý

1. Module này yêu cầu quyền Admin để truy cập
2. Database phải được migrate trước khi sử dụng
3. Các bảng `nhan_su`, `tour`, `lich_khoi_hanh`, `nguoi_dung` phải tồn tại
4. Tỉ lệ hoa hồng mặc định là 5% (có thể thay đổi trong bảng `nhan_su`)

## Tác Giả

Module được tách ra từ hệ thống chính để dễ quản lý và bảo trì.

