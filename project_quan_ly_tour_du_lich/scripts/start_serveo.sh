#!/bin/bash

# Script khởi động Serveo để test dự án trên thiết bị khác
# Sử dụng: ./scripts/start_serveo.sh

echo "🚀 Đang khởi động Serveo..."
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

echo "✅ Đang khởi động Serveo tunnel..."
echo "🌐 URL công khai sẽ hiển thị bên dưới:"
echo ""

# Khởi động serveo
ssh -R 80:localhost:80 serveo.net

