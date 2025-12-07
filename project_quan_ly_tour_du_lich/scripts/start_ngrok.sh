#!/bin/bash

# Script khởi động Ngrok để test dự án trên thiết bị khác
# Sử dụng: ./scripts/start_ngrok.sh

echo "🚀 Đang khởi động Ngrok..."
echo "📝 Đảm bảo XAMPP Apache đang chạy trên port 80"
echo ""

# Kiểm tra ngrok đã cài đặt chưa
if ! command -v ngrok &> /dev/null; then
    echo "❌ Ngrok chưa được cài đặt!"
    echo "📥 Cài đặt ngrok:"
    echo "   macOS: brew install ngrok"
    echo "   Hoặc download từ: https://ngrok.com/download"
    exit 1
fi

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

echo "✅ Đang khởi động Ngrok tunnel..."
echo "🌐 URL công khai sẽ hiển thị bên dưới:"
echo ""

# Khởi động ngrok
ngrok http 80

