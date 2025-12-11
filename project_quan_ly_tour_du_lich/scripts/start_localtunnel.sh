#!/bin/bash

# Script khởi động LocalTunnel để test dự án trên thiết bị khác
# Sử dụng: ./scripts/start_localtunnel.sh

echo "🚀 Đang khởi động LocalTunnel..."
echo "📝 Đảm bảo XAMPP Apache đang chạy trên port 80"
echo ""

# Kiểm tra Apache đang chạy
if ! lsof -Pi :80 -sTCP:LISTEN -t >/dev/null ; then
    echo "⚠️  Cảnh báo: Port 80 không có service nào đang chạy"
    echo "   Vui lòng khởi động XAMPP Apache trước"
    echo ""
    read -p "Bạn có muốn tiếp tục không? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
fi

echo "✅ Đang khởi động LocalTunnel..."
echo "🌐 URL công khai sẽ hiển thị bên dưới:"
echo ""

# Dùng npx để không cần cài đặt global
npx --yes localtunnel --port 80

