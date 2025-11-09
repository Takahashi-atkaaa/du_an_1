<?php
/**
 * Script tạo password hash
 * Chạy: php generate_password.php
 */

$password = 'password'; // Mật khẩu mặc định
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Password: $password\n";
echo "Hash: $hash\n";
echo "\n";

// Test verify
if (password_verify($password, $hash)) {
    echo "✓ Password hash hợp lệ!\n";
} else {
    echo "✗ Password hash không hợp lệ!\n";
}

// Tạo hash mới cho database.sql
echo "\n--- Hash để copy vào database.sql ---\n";
echo "'" . $hash . "'\n";

