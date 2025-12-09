# 📊 SO SÁNH TOURCONTROLLER VÀ ADMINCONTROLLER

## 🎯 MỤC ĐÍCH CHÍNH

| **TourController**                        | **AdminController**                                                         |
| ----------------------------------------- | --------------------------------------------------------------------------- |
| **Chuyên về quản lý Tour**                | **Quản lý toàn bộ hệ thống**                                                |
| Tập trung vào CRUD tour và lịch khởi hành | Quản lý nhiều module: tour, booking, HDV, nhân sự, nhà cung cấp, báo cáo... |

---

## 📏 QUY MÔ CODE

| **Tiêu chí**     | **TourController** | **AdminController**     |
| ---------------- | ------------------ | ----------------------- |
| **Số dòng code** | ~819 dòng          | ~1979 dòng              |
| **Số methods**   | 17 methods         | 50+ methods             |
| **Độ phức tạp**  | ⭐⭐ Trung bình    | ⭐⭐⭐⭐⭐ Rất phức tạp |

---

## 🏗️ CẤU TRÚC

### **TourController**

```php
class TourController {
    private $model;  // Chỉ có 1 model: Tour

    public function __construct() {
        $this->model = new Tour();
    }
}
```

- ✅ **Đơn giản**: Chỉ có 1 model trong constructor
- ✅ **Tập trung**: Tất cả methods đều liên quan đến Tour

### **AdminController**

```php
class AdminController {
    // KHÔNG có properties models
    // Tạo model trực tiếp trong từng method

    public function __construct() {
        requireRole('Admin');  // Kiểm tra quyền Admin
    }
}
```

- ⚠️ **Phức tạp hơn**: Tạo nhiều models khác nhau trong từng method
- ⚠️ **Rộng hơn**: Quản lý nhiều module khác nhau

---

## 📋 DANH SÁCH METHODS

### **TourController (17 methods)**

#### **CRUD Tour cơ bản:**

1. `index()` - Trang chủ (nhưng load view login - có vẻ lỗi)
2. `show()` - Xem chi tiết tour (cho khách hàng)
3. `create()` - Tạo tour mới
4. `update()` - Cập nhật tour
5. `delete()` - Xóa tour
6. `clone()` - Sao chép tour

#### **Lịch khởi hành:**

7. `taoLichKhoiHanh()` - Tạo lịch khởi hành
8. `chiTietLichKhoiHanh()` - Chi tiết lịch khởi hành
9. `phanBoNhanSuLichKhoiHanh()` - Phân bổ nhân sự
10. `updateTrangThaiNhanSuLichKhoiHanh()` - Cập nhật trạng thái nhân sự
11. `phanBoDichVuLichKhoiHanh()` - Phân bổ dịch vụ
12. `updateTrangThaiDichVuLichKhoiHanh()` - Cập nhật trạng thái dịch vụ
13. `deleteNhanSuLichKhoiHanh()` - Xóa phân bổ nhân sự
14. `deleteDichVuLichKhoiHanh()` - Xóa phân bổ dịch vụ

#### **Khác:**

15. `generateQr()` - Tạo mã QR cho tour
16. `bookOnline()` - Đặt tour online (chưa hoàn thiện)

### **AdminController (50+ methods)**

#### **Dashboard & Quản lý chung:**

- `dashboard()` - Trang chủ admin
- `quanLyTour()` - Quản lý tour (danh sách)
- `chiTietTour()` - Chi tiết tour (xem)
- `quanLyBooking()` - Quản lý booking
- `quanLyNguoiDung()` - Quản lý người dùng

#### **Yêu cầu đặc biệt:**

- `yeuCauDacBiet()` - Quản lý yêu cầu đặc biệt
- `capNhatYeuCauDacBiet()` - Cập nhật yêu cầu
- `taoYeuCauDacBiet()` - Tạo yêu cầu mới
- `quanLyYeuCauTour()` - Quản lý yêu cầu tour
- `chiTietYeuCauTour()` - Chi tiết yêu cầu tour

#### **Nhà cung cấp:**

- `nhaCungCap()` - Quản lý nhà cung cấp
- `addNhacungcap()` - Thêm nhà cung cấp
- `updateNhaCungCap()` - Cập nhật
- `deleteNhaCungCap()` - Xóa
- `chiTietDichVu()` - Chi tiết dịch vụ

#### **HDV & Nhân sự:**

- `quanLyHDV()` - Quản lý HDV
- `hdvSchedule()` - Lịch làm việc HDV
- `hdvProfile()` - Hồ sơ HDV
- `nhanSu()` - Quản lý nhân sự
- `nhanSuCreate()` - Tạo nhân sự
- `nhanSuUpdate()` - Cập nhật nhân sự
- `nhanSuDelete()` - Xóa nhân sự
- `hdvAdvanced()` - Quản lý HDV nâng cao
- ... và nhiều methods khác

#### **Check-in & Khách:**

- `checkInKhach()` - Check-in khách
- `updateCheckIn()` - Cập nhật check-in
- `danhSachKhachTheoTour()` - Danh sách khách
- `phanPhongKhachSan()` - Phân phòng khách sạn
- `themKhachLichKhoiHanh()` - Thêm khách
- `suaKhachLichKhoiHanh()` - Sửa khách
- `xoaKhachLichKhoiHanh()` - Xóa khách

#### **Nhật ký:**

- `quanLyNhatKyTour()` - Quản lý nhật ký
- `formNhatKyTour()` - Form nhật ký
- `chiTietNhatKyTour()` - Chi tiết nhật ký
- `saveNhatKyTour()` - Lưu nhật ký
- `deleteNhatKyTour()` - Xóa nhật ký

#### **Lịch sử:**

- `lichSuXoaBooking()` - Lịch sử xóa booking
- `lichSuXoaNhaCungCap()` - Lịch sử xóa nhà cung cấp

#### **API:**

- `hdvApiGetSchedule()` - API lấy lịch HDV
- `hdvApiCheck()` - API kiểm tra HDV
- `hdvApiAssign()` - API phân công HDV
- `hdvApiSuggest()` - API gợi ý HDV

---

## 🔍 ĐIỂM KHÁC BIỆT CHÍNH

### 1. **Cách sử dụng Models**

#### **TourController:**

```php
// Tạo model 1 lần trong constructor
private $model;

public function __construct() {
    $this->model = new Tour();
}

// Dùng lại trong tất cả methods
$this->model->getAll();
$this->model->findById($id);
```

#### **AdminController:**

```php
// Tạo model mới trong từng method
public function quanLyTour() {
    $tourModel = new Tour();  // Tạo mới mỗi lần
}

public function quanLyBooking() {
    $bookingModel = new Booking();  // Tạo mới mỗi lần
    require_once 'models/ThongBao.php';
    $thongBaoModel = new ThongBao();
}
```

**→ AdminController tạo model nhiều lần, TourController tái sử dụng 1 model**

---

### 2. **Phạm vi chức năng**

#### **TourController:**

- ✅ **Tập trung**: Chỉ xử lý Tour và Lịch khởi hành
- ✅ **Chuyên sâu**: Có nhiều tính năng chi tiết cho tour (clone, QR, phân bổ...)
- ✅ **Rõ ràng**: Mỗi method có mục đích rõ ràng

#### **AdminController:**

- ⚠️ **Rộng**: Quản lý nhiều module khác nhau
- ⚠️ **Phức tạp**: Nhiều logic phức tạp (filter, map, xử lý nhiều bảng)
- ⚠️ **Khó theo dõi**: Nhiều methods, nhiều trách nhiệm

---

### 3. **Cách xử lý dữ liệu**

#### **TourController:**

```php
// Xử lý đơn giản, trực tiếp
$tour = $this->model->findById($id);
$lichTrinhList = $this->model->getLichTrinhByTourId($id);
require 'views/admin/tao_tour.php';
```

#### **AdminController:**

```php
// Xử lý phức tạp với nhiều bước
$bookings = $bookingModel->getAllWithDetails();

// Tạo map để tối ưu
$yeuCauMap = [];
$khachHangMap = [];

// Xử lý logic phức tạp
foreach ($bookings as &$booking) {
    // Logic phức tạp...
}
```

---

### 4. **Views được sử dụng**

#### **TourController:**

- `views/auth/login.php` (có vẻ lỗi)
- `views/khach_hang/chi_tiet_tour.php` - Cho khách hàng
- `views/admin/tao_tour.php` - Tạo/sửa tour
- `views/admin/tao_lich_khoi_hanh_tour.php` - Tạo lịch khởi hành
- `views/admin/chi_tiet_lich_khoi_hanh.php` - Chi tiết lịch khởi hành

**→ Chủ yếu views về Tour**

#### **AdminController:**

- `views/admin/dashboard.php`
- `views/admin/quan_ly_tour.php`
- `views/admin/quan_ly_booking.php`
- `views/admin/quan_ly_nguoi_dung.php`
- `views/admin/quan_ly_yeu_cau_dac_biet.php`
- `views/admin/nha_cung_cap.php`
- `views/admin/quan_ly_hdv.php`
- `views/admin/check_in.php`
- ... và 20+ views khác

**→ Views cho nhiều module khác nhau**

---

### 5. **Kiểm tra quyền**

#### **TourController:**

```php
public function __construct() {
    $this->model = new Tour();
    // KHÔNG kiểm tra quyền trong constructor
}

public function create() {
    // requireRole('Admin');  // Bị comment
}
```

#### **AdminController:**

```php
public function __construct() {
    requireRole('Admin');  // ✅ Luôn kiểm tra quyền Admin
}
```

**→ AdminController bảo mật hơn**

---

### 6. **Xử lý lỗi**

#### **TourController:**

```php
try {
    // Xử lý với transaction
    $this->model->conn->beginTransaction();
    // ...
    $this->model->conn->commit();
} catch (Exception $e) {
    $this->model->conn->rollBack();
    $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
}
```

#### **AdminController:**

```php
// Thường không dùng transaction
// Xử lý lỗi đơn giản hơn
if (!$result) {
    $_SESSION['error'] = 'Không thể thực hiện.';
}
```

---

## 📊 BẢNG TÓM TẮT

| **Tiêu chí**       | **TourController** | **AdminController**    |
| ------------------ | ------------------ | ---------------------- |
| **Mục đích**       | Quản lý Tour       | Quản lý toàn hệ thống  |
| **Số methods**     | 17                 | 50+                    |
| **Models**         | 1 model (Tour)     | Nhiều models khác nhau |
| **Views**          | 5 views            | 30+ views              |
| **Độ phức tạp**    | ⭐⭐ Trung bình    | ⭐⭐⭐⭐⭐ Rất cao     |
| **Kiểm tra quyền** | ❌ Không có        | ✅ Có (requireRole)    |
| **Transaction**    | ✅ Có dùng         | ❌ Ít dùng             |
| **Tập trung**      | ✅ Chỉ về Tour     | ❌ Nhiều module        |

---

## 💡 KẾT LUẬN

### **TourController:**

- ✅ **Đơn giản, dễ hiểu** - Tập trung vào 1 chức năng
- ✅ **Dễ bảo trì** - Code rõ ràng, có cấu trúc
- ✅ **Chuyên sâu** - Nhiều tính năng chi tiết cho tour
- ❌ **Thiếu bảo mật** - Không kiểm tra quyền

### **AdminController:**

- ✅ **Đầy đủ chức năng** - Quản lý toàn bộ hệ thống
- ✅ **Bảo mật tốt** - Có kiểm tra quyền Admin
- ❌ **Phức tạp** - Nhiều methods, khó theo dõi
- ❌ **Khó bảo trì** - Code dài, nhiều trách nhiệm

---

## 🎓 GỢI Ý CẢI THIỆN

1. **TourController nên thêm:**

   - Kiểm tra quyền trong constructor
   - Tách logic phức tạp thành private methods

2. **AdminController nên:**
   - Tách thành nhiều controller nhỏ hơn (BookingController, HDVController...)
   - Tạo models trong constructor thay vì trong từng method
   - Sử dụng transaction cho các thao tác quan trọng



