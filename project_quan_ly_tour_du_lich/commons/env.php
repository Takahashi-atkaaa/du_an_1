<?php 

// Biến môi trường, dùng chung toàn hệ thống
// Khai báo dưới dạng HẰNG SỐ để không phải dùng $GLOBALS

// Load .env file if exists
if (file_exists(__DIR__ . '/../.env')) {
    $envFile = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envFile as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Base URL - Tự động detect từ request hoặc dùng từ .env
if (isset($_ENV['BASE_URL'])) {
    // Nếu có trong .env file, dùng giá trị đó
    $baseUrl = $_ENV['BASE_URL'];
} else {
    // Tự động detect từ request hiện tại
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptPath = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    
    // Xác định đường dẫn đến project
    $projectPath = '/tunganh/du_an_1/project_quan_ly_tour_du_lich';
    
    // Nếu script đang chạy từ thư mục project, dùng đường dẫn đó
    if (strpos($scriptPath, $projectPath) !== false) {
        $baseUrl = $protocol . $host . $projectPath . '/';
    } else {
        // Fallback về localhost
        $baseUrl = 'http://localhost' . $projectPath . '/';
    }
}
define('BASE_URL', $baseUrl);

define('DB_HOST'    , 'localhost');
define('DB_PORT'    , 3306);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME'    , 'quan_ly_tour_du_lich');  // Tên database

// Path Configuration
define('PATH_ROOT', __DIR__ . '/../');
define('PATH_UPLOADS', PATH_ROOT . 'uploads/');
define('PATH_VIEWS', PATH_ROOT . 'views/');

// Hàm tạo kết nối PDO
function getPDOConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USERNAME,
            DB_PASSWORD,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        // Thiết lập timezone
        $conn->exec("SET time_zone = '+07:00'");
        return $conn;
    } catch (PDOException $e) {
        die("Kết nối thất bại: " . $e->getMessage());
    }
}

