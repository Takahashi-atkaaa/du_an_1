# Hướng dẫn Test Dự án trên Thiết bị Khác

Có nhiều cách để test dự án trên thiết bị khác (giống ngrok). Dưới đây là các phương án:

## Phương án 1: Ngrok (Khuyên dùng - Nhanh nhất, ổn định nhất)

**✅ Ưu điểm:** Nhanh, ổn định, không cần password, tốc độ tốt hơn LocalTunnel

### Cài đặt:

```bash
# macOS
brew install ngrok

# Hoặc download từ: https://ngrok.com/download
```

### Sử dụng:

1. Đảm bảo XAMPP đang chạy và Apache đang listen trên port 80
2. Chạy lệnh:

```bash
ngrok http 80
```

Hoặc dùng script helper:

```bash
./scripts/start_ngrok.sh
```

3. Ngrok sẽ cung cấp URL công khai, ví dụ:

   - `https://abc123.ngrok.io` → truy cập từ bất kỳ đâu
   - `http://abc123.ngrok.io` → HTTP (miễn phí)

4. Truy cập dự án:
   ```
   https://abc123.ngrok.io/tunganh/du_an_1/project_quan_ly_tour_du_lich/
   ```

### Lưu ý:

- Miễn phí: URL thay đổi mỗi lần chạy, tốc độ tốt
- Trả phí: URL cố định, HTTPS, tốc độ cao hơn

---

## Phương án 2: LocalTunnel (Miễn phí, không cần đăng ký)

**⚠️ Lưu ý:**

- LocalTunnel yêu cầu password (IP công cộng) để truy cập
- **LocalTunnel miễn phí thường CHẬM** do giới hạn băng thông
- Nếu muốn nhanh hơn, dùng **Ngrok** (Phương án 1) hoặc **Serveo** (Phương án 3)

### Cách 1: Dùng npx (Không cần cài đặt)

```bash
npx --yes localtunnel --port 80
```

Hoặc dùng script helper:

```bash
./scripts/start_localtunnel.sh
```

### Cách 2: Cài đặt global (Có thể gặp lỗi permission)

```bash
# Nếu gặp lỗi permission, dùng:
sudo npm install -g localtunnel

# Sau đó chạy:
lt --port 80
1.55.69.238
https://tender-suns-refuse.loca.lt/tunganh/du_an_1/project_quan_ly_tour_du_lich/
```

Sẽ cho URL như: `https://random-name.loca.lt`

### Lấy Tunnel Password (IP công cộng):

Khi truy cập URL, LocalTunnel sẽ yêu cầu password. Password chính là **IP công cộng** của máy bạn:

1. **Trên máy local:** Mở trình duyệt và truy cập:

   ```
   https://loca.lt/mytunnelpassword
   ```

2. **Hoặc dùng lệnh:**

   ```bash
   curl https://loca.lt/mytunnelpassword
   # Hoặc
   curl https://api.ipify.org
   ```

3. Nhập IP công cộng đó vào ô "Tunnel Password" khi truy cập URL LocalTunnel.

**💡 Khuyến nghị:** Nếu không muốn phải nhập password mỗi lần, dùng **Serveo** (Phương án 3) hoặc **Ngrok** (Phương án 1) thay thế.

---

## Phương án 3: Serveo (Không cần cài đặt gì - Đơn giản nhất, nhanh hơn LocalTunnel)

**⚠️ Lưu ý:** Serveo có thể không hoạt động ổn định hoặc bị chặn ở một số mạng. Nếu gặp lỗi "Connection refused", dùng **Ngrok** (Phương án 1) thay thế.

**✅ Ưu điểm:** Không cần cài đặt, không cần password, nhanh hơn LocalTunnel miễn phí

### Sử dụng:

```bash
ssh -R 80:localhost:80 serveo.net
```

Hoặc dùng script helper:

```bash
./scripts/start_serveo.sh
```

Hoặc với port khác:

```bash
ssh -R 80:localhost:8080 serveo.net
```

**Lưu ý:**

- Lần đầu có thể hỏi xác nhận, gõ `yes` và Enter
- Nếu gặp lỗi "Connection refused", Serveo có thể đang down - dùng Ngrok thay thế

---

## Phương án 4: Truy cập từ mạng nội bộ (LAN)

### Cấu hình XAMPP:

1. Mở file `/Applications/XAMPP/xamppfiles/etc/httpd.conf`
2. Tìm dòng:
   ```apache
   Listen 80
   ```
3. Đảm bảo không có giới hạn IP, hoặc đổi thành:

   ```apache
   Listen 0.0.0.0:80
   ```

4. Tìm và sửa:

   ```apache
   <Directory "/Applications/XAMPP/xamppfiles/htdocs">
       Options Indexes FollowSymLinks
       AllowOverride All
       Require all granted  # Thay vì Require local
   </Directory>
   ```

5. Restart Apache trong XAMPP

### Lấy IP máy của bạn:

```bash
# macOS/Linux
ifconfig | grep "inet " | grep -v 127.0.0.1

# Hoặc
ipconfig getifaddr en0
```

### Truy cập:

- Từ thiết bị khác trong cùng mạng WiFi:
  - `http://192.168.1.XXX/tunganh/du_an_1/project_quan_ly_tour_du_lich/`
  - Thay `192.168.1.XXX` bằng IP máy của bạn

---

## Phương án 5: Cloudflare Tunnel (Miễn phí, ổn định)

### Cài đặt:

```bash
# macOS
brew install cloudflared

# Hoặc download từ: https://developers.cloudflare.com/cloudflare-one/connections/connect-apps/install-and-setup/installation/
```

### Sử dụng:

```bash
cloudflared tunnel --url http://localhost:80
```

---

## Cấu hình tự động BASE_URL

File `env.php` đã được cập nhật để tự động detect URL động. Nếu muốn override, tạo file `.env`:

```env
BASE_URL=http://your-ngrok-url.ngrok.io/tunganh/du_an_1/project_quan_ly_tour_du_lich/
```

---

## Khuyến nghị

- **Test nhanh trên mạng nội bộ**: Dùng Phương án 4 (LAN) - nhanh nhất
- **Test từ internet, demo cho khách, TỐC ĐỘ TỐT**: Dùng **Ngrok** (Phương án 1) - nhanh và ổn định nhất
- **Test từ internet, đơn giản, không cần cài đặt**: Dùng **Serveo** (Phương án 3) - nhanh hơn LocalTunnel
- **Test lâu dài, ổn định**: Dùng Cloudflare Tunnel (Phương án 5)
- **Tránh lỗi npm permission**: Dùng Serveo (Phương án 3 - không cần npm) hoặc Ngrok (cài qua brew)
- **Tránh phải nhập password**: Dùng Serveo hoặc Ngrok thay vì LocalTunnel
- **Tránh load chậm**: Dùng Ngrok hoặc Serveo thay vì LocalTunnel miễn phí

---

## Troubleshooting

### Lỗi "Connection refused":

- Đảm bảo XAMPP Apache đang chạy
- Kiểm tra firewall không chặn port 80

### Lỗi "403 Forbidden":

- Kiểm tra quyền truy cập trong `httpd.conf`
- Đảm bảo `Require all granted` thay vì `Require local`

### URL không hoạt động:

- Kiểm tra BASE_URL trong `env.php` hoặc `.env`
- Clear cache trình duyệt
- Kiểm tra đường dẫn có đúng không

### Trang web load chậm:

- **LocalTunnel miễn phí thường chậm** - đây là hạn chế của dịch vụ miễn phí
- **Giải pháp:** Chuyển sang **Ngrok** (nhanh hơn) hoặc **Serveo** (nhanh hơn LocalTunnel):

  ```bash
  # Ngrok (nhanh nhất)
  ngrok http 80

  # Hoặc Serveo (nhanh hơn LocalTunnel)
  ssh -R 80:localhost:80 serveo.net
  ```

- Kiểm tra kết nối mạng của bạn
- Kiểm tra XAMPP Apache có đang chạy ổn định không

### Lỗi npm permission (EACCES):

- **Giải pháp tốt nhất**: Dùng Serveo (không cần npm):
  ```bash
  ssh -R 80:localhost:80 serveo.net
  ```
- Hoặc dùng `npx` thay vì cài global:
  ```bash
  npx --yes localtunnel --port 80
  ```
- Hoặc dùng ngrok (cài qua brew, không cần npm)

### LocalTunnel yêu cầu password:

- **Giải pháp tốt nhất**: Dùng **Serveo** (không cần password):
  ```bash
  ssh -R 80:localhost:80 serveo.net
  ```
- Hoặc dùng **Ngrok** (không cần password):
  ```bash
  ngrok http 80
  ```
- Nếu vẫn muốn dùng LocalTunnel, lấy password (IP công cộng):
  ```bash
  curl https://api.ipify.org
  # Hoặc truy cập: https://loca.lt/mytunnelpassword
  ```
