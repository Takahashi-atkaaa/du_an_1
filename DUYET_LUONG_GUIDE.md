# 💰 Hướng Dẫn Duyệt Lương & Thưởng cho HDV

## **Quy Trình Duyệt Lương**

### **1️⃣ Trạng Thái Lương (3 bước)**

```
Pending (Chưa duyệt)
    ↓
Approved (Đã duyệt)
    ↓
Paid (Đã thanh toán)
```

---

## **👨‍💼 Cho Admin - Duyệt Lương**

### **Bước 1: Truy Cập Admin Panel**
```
URL: http://localhost/du_an_1/project_quan_ly_tour_du_lich/
Đăng nhập với tài khoản Admin
```

### **Bước 2: Vào Quản Lý Lương**
Cách 1: **Menu chính** → Tìm "Quản Lý Lương HDV"
Cách 2: **URL trực tiếp:**
```
http://localhost/du_an_1/project_quan_ly_tour_du_lich/index.php?act=admin/quanLyLuongHDV
```

### **Bước 3: Duyệt Lương**

**Giao diện Admin gồm 3 tab:**

#### **Tab 1: Lương Tour**
- Hiển thị danh sách lương của HDV
- Cột trạng thái: "Chưa Duyệt", "Đã Duyệt", "Đã Thanh Toán"
- **Nút Hành Động:**
  - 🟢 **Duyệt** - Nhấn nút khi trạng thái là "Chưa Duyệt"
  - 🔵 **Thanh Toán** - Nhấn nút khi trạng thái là "Đã Duyệt"

#### **Tab 2: Thưởng**
- Danh sách thưởng chờ phê duyệt
- 🟢 **Phê Duyệt** - Chấp nhận thưởng
- ❌ **Từ Chối** - Từ chối thưởng

#### **Tab 3: Thống Kê**
- Tổng lương đang chờ duyệt
- Tổng lương đã duyệt
- Tổng lương đã thanh toán
- Tổng thưởng chờ duyệt
- Thống kê chi tiết theo từng HDV

---

## **📊 Database - Các Trường Liên Quan**

### **Bảng: hdv_salary**

```sql
-- Xem tất cả lương chưa duyệt
SELECT * FROM hdv_salary WHERE payment_status = 'Pending';

-- Cập nhật trạng thái (Duyệt)
UPDATE hdv_salary SET payment_status = 'Approved' WHERE id = 1;

-- Cập nhật trạng thái (Thanh toán)
UPDATE hdv_salary SET payment_status = 'Paid', payment_date = NOW() WHERE id = 1;
```

### **Bảng: hdv_bonus**

```sql
-- Xem thưởng chờ phê duyệt
SELECT * FROM hdv_bonus WHERE approval_status = 'ChoPheDuyet';

-- Phê duyệt thưởng
UPDATE hdv_bonus SET approval_status = 'DuyetPhep', approved_by = 1 WHERE id = 1;

-- Từ chối thưởng
UPDATE hdv_bonus SET approval_status = 'TuChoi' WHERE id = 1;
```

---

## **✅ Các Trạng Thái**

### **Payment Status (hdv_salary.payment_status)**
| Giá Trị | Ý Nghĩa | Hành Động Tiếp Theo |
|---------|---------|-------------------|
| `Pending` | Chưa duyệt | Nhấn "Duyệt" |
| `Approved` | Đã duyệt | Nhấn "Thanh Toán" |
| `Paid` | Đã thanh toán | Hoàn thành |

### **Approval Status (hdv_bonus.approval_status)**
| Giá Trị | Ý Nghĩa |
|---------|---------|
| `ChoPheDuyet` | Chờ phê duyệt |
| `DuyetPhep` | Đã phê duyệt |
| `TuChoi` | Từ chối |

---

## **🔄 Qui Trình Chi Tiết**

### **Người Phân Bổ (HDV Lead/Manager):**
1. Nhập lương vào hệ thống
2. Lương được lưu với trạng thái: **Pending**

### **Admin Duyệt:**
1. Truy cập: Admin Panel → Quản Lý Lương HDV
2. Xem danh sách lương "Chưa Duyệt"
3. Kiểm tra chi tiết (tour, doanh thu, hoa hồng, ...)
4. Nhấn nút **"Duyệt"** → Trạng thái: **Approved**

### **Admin Thanh Toán:**
1. Xem danh sách lương "Đã Duyệt"
2. Nhấn nút **"Thanh Toán"** → Trạng thái: **Paid**
3. Hệ thống ghi nhận: `payment_date = NOW()`

### **HDV Xem Lương:**
1. Đăng nhập HDV account
2. Dashboard → "Lương & Thưởng"
3. Xem các trạng thái lương:
   - 🟡 Chưa Duyệt
   - 🟢 Đã Duyệt
   - 🔵 Đã Thanh Toán

---

## **🚀 Script Nhanh (phpMyAdmin)**

### **Duyệt tất cả lương chưa duyệt:**
```sql
UPDATE hdv_salary 
SET payment_status = 'Approved' 
WHERE payment_status = 'Pending';
```

### **Thanh toán tất cả lương đã duyệt:**
```sql
UPDATE hdv_salary 
SET payment_status = 'Paid', payment_date = NOW() 
WHERE payment_status = 'Approved';
```

### **Phê duyệt tất cả thưởng chờ:**
```sql
UPDATE hdv_bonus 
SET approval_status = 'DuyetPhep', approved_by = 1 
WHERE approval_status = 'ChoPheDuyet';
```

---

## **❓ Câu Hỏi Thường Gặp**

### **Q: Làm sao biết lương nào chủ yếu là do hoa hồng?**
A: Xem cột "Hoa hồng %" ở bảng. Tính toán:
```
Hoa hồng = (Doanh thu × Tỉ lệ %) / 100
```

### **Q: Tại sao lương hiển thị 0?**
A: Có thể do:
- Chưa nhập dữ liệu base_salary
- Tour chưa có booking
- Doanh thu = 0

### **Q: Có thể thay đổi tỉ lệ hoa hồng được không?**
A: Có, sửa ở:
- `nhan_su.commission_percentage` (mặc định cho HDV)
- `hdv_salary.commission_percentage` (riêng từng tour)

---

## **📍 Các Điểm Quan Trọng**

✅ **Trạng thái 3 bước:** Pending → Approved → Paid  
✅ **Admin duyệt:** Quản Lý Lương HDV panel  
✅ **HDV xem:** Dashboard → Lương & Thưởng  
✅ **Database:** `hdv_salary`, `hdv_bonus`  
✅ **View thống kê:** `view_hdv_salary_summary`

---

**Liên Hệ:** Nếu gặp vấn đề, kiểm tra:
1. Dữ liệu có trong `hdv_salary` không?
2. `nhan_su_id` có tồn tại không?
3. Tỉ lệ `commission_percentage` có hợp lý không?
