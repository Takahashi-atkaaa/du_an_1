<?php
/**
 * File test kết nối database
 * Truy cập: http://localhost/project_quan_ly_tour_du_lich/test_connection.php
 */

// Load .env file if exists
if (file_exists(__DIR__ . '/.env')) {
    $envFile = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envFile as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Load commons
require_once __DIR__ . '/commons/env.php';
require_once __DIR__ . '/commons/function.php';

// Load config từ constants
$config = [
    'db_host' => DB_HOST,
    'db_name' => DB_NAME,
    'db_user' => DB_USERNAME,
    'db_pass' => DB_PASSWORD
];

echo "<h2>Test Kết nối Database</h2>";
echo "<hr>";

// Thông tin kết nối
echo "<h3>Thông tin kết nối:</h3>";
echo "Host: " . $config['db_host'] . "<br>";
echo "Database: " . $config['db_name'] . "<br>";
echo "User: " . $config['db_user'] . "<br>";
echo "Password: " . (empty($config['db_pass']) ? '(trống)' : '***') . "<br>";
echo "<hr>";

try {
    // Kết nối database
    $dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];
    
    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
    
    echo "<h3 style='color: green;'>✓ Kết nối thành công!</h3>";
    
    // Test query
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetch();
    echo "MySQL Version: " . $version['version'] . "<br>";
    
    // Kiểm tra các bảng
    echo "<h3>Danh sách bảng:</h3>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<p style='color: orange;'>⚠ Chưa có bảng nào. Vui lòng import file database.sql</p>";
    } else {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    }
    
    // Đếm số lượng records trong một số bảng
    if (in_array('nguoi_dung', $tables)) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM nguoi_dung");
        $count = $stmt->fetch();
        echo "Số người dùng: " . $count['count'] . "<br>";
    }
    
    if (in_array('tours', $tables)) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM tours");
        $count = $stmt->fetch();
        echo "Số tour: " . $count['count'] . "<br>";
    }
    
} catch (PDOException $e) {
    echo "<h3 style='color: red;'>✗ Lỗi kết nối:</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<hr>";
    echo "<h3>Hướng dẫn:</h3>";
    echo "<ol>";
    echo "<li>Kiểm tra MySQL đã được khởi động chưa</li>";
    echo "<li>Tạo database: <code>CREATE DATABASE quan_ly_tour_du_lich;</code></li>";
    echo "<li>Import file database.sql vào database</li>";
    echo "<li>Kiểm tra file .env có đúng thông tin kết nối không</li>";
    echo "</ol>";
}

?>

