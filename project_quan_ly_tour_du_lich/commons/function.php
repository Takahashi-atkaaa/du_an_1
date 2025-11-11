<?php

// Kết nối CSDL qua PDO
function connectDB() {
    return getPDOConnection();
}

// Upload file
function uploadFile($file, $folderSave) {
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return null;
    }
    
    $file_upload = $file;
    $fileExtension = pathinfo($file_upload['name'], PATHINFO_EXTENSION);
    $fileName = rand(10000, 99999) . '_' . time() . '.' . $fileExtension;
    $pathStorage = $folderSave . $fileName;

    $tmp_file = $file_upload['tmp_name'];
    $pathSave = PATH_ROOT . $pathStorage; // Đường dẫn tuyệt đối của file

    // Tạo thư mục nếu chưa tồn tại
    $dir = dirname($pathSave);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    if (move_uploaded_file($tmp_file, $pathSave)) {
        return $pathStorage;
    }
    return null;
}

// Delete file
function deleteFile($file) {
    if (empty($file)) {
        return false;
    }
    $pathDelete = PATH_ROOT . $file;
    if (file_exists($pathDelete)) {
        return unlink($pathDelete); // Hàm unlink dùng để xóa file
    }
    return false;
}

// Redirect


// Flash message
function setFlashMessage($key, $message) {
    if (!isset($_SESSION)) {
        session_start();
    }
    $_SESSION['flash'][$key] = $message;
}

function getFlashMessage($key) {
    if (!isset($_SESSION)) {
        session_start();
    }
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    return null;
}

// Check login
function isLoggedIn() {
    if (!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION['user_id']);
}

// Require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php?act=auth/login');
        exit();
    }
}

// Require role
function requireRole($role) {
    requireLogin();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        header('Location: index.php?act=tour/index');
        exit();
    }
}

