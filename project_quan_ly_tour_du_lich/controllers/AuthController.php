<?php
require_once __DIR__ . '/../commons/function.php';
require_once 'models/NguoiDung.php';
require_once 'models/KhachHang.php';

class AuthController {
    private $model;
    private $khachHangModel;
    
    public function __construct() {
        $this->model = new NguoiDung();
        $this->khachHangModel = new KhachHang();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';  // Có thể là ten_dang_nhap hoặc email
            $password = $_POST['password'] ?? '';

            // Tìm người dùng theo ten_dang_nhap hoặc email
            $user = $this->model->find(['ten_dang_nhap' => $username]);
            if (!$user) {
                $user = $this->model->findByEmail($username);
            }

            if ($user) {
                $stored = $user['mat_khau'] ?? '';
                $authenticated = false;

                // Nếu mật khẩu đã hash (bcrypt,...), dùng password_verify
                if (!empty($stored) && password_verify($password, $stored)) {
                    $authenticated = true;
                } elseif ($stored === $password) {
                    // Trường hợp dữ liệu mẫu cũ lưu mật khẩu plaintext
                    $authenticated = true;
                    // Cập nhật lại thành hash an toàn
                    $newHash = password_hash($password, PASSWORD_DEFAULT);
                    $this->model->updatePassword($user['id'], $newHash);
                }

                if ($authenticated) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['vai_tro'];
                    $_SESSION['user_name'] = $user['ho_ten'];

                    if ($user['vai_tro'] === 'KhachHang') {
                        $khachHang = $this->khachHangModel->findByNguoiDungId($user['id']);
                        if ($khachHang) {
                            $_SESSION['khach_hang_id'] = $khachHang['khach_hang_id'];
                        }
                    }

                    // Redirect theo vai trò
                    switch ($user['vai_tro']) {
                        case 'Admin':
                        header('Location: index.php?act=admin/dashboard');
                        exit();
                        case 'HDV':
                            header('Location: index.php?act=hdv/dashboard');
                            exit();
                        case 'KhachHang':
                            header('Location: index.php?act=khachHang/danhSachTour');
                            exit();
                        case 'NhaCungCap':
                            header('Location: index.php?act=nhaCungCap/dichVu');
                            exit();
                        default:
                            header('Location: index.php?act=tour/index');
                        exit();
                    }
                }
            }

            $error = "Tên đăng nhập/Email hoặc mật khẩu không đúng";
            require 'views/auth/login.php';
        } else {
            require 'views/auth/login.php';
        }
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';

            // Kiểm tra email đã tồn tại chưa
            $existing = $this->model->findByEmail($email);
            if ($existing) {
                $error = "Email đã được sử dụng. Vui lòng dùng email khác.";
                require 'views/auth/register.php';
                return;
            }

            // Nếu người dùng có gửi ten_dang_nhap riêng, kiểm tra trùng tên đăng nhập
            $ten_dang_nhap = $_POST['ten_dang_nhap'] ?? $email;
            $existingUserName = $this->model->find(['ten_dang_nhap' => $ten_dang_nhap]);
            if ($existingUserName) {
                $error = "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác.";
                require 'views/auth/register.php';
                return;
            }

            $data = [
                'so_dien_thoai' => $_POST['so_dien_thoai'] ?? '',
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'email' => $email,
                'ten_dang_nhap' => $ten_dang_nhap,
                'mat_khau' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
                'vai_tro' => 'KhachHang',
                'ngay_tao' => date('Y-m-d H:i:s')
            ];

            $userId = $this->model->insert($data);

            if ($userId) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['role'] = 'KhachHang';
                $khachHangId = $this->khachHangModel->insert([
                    'nguoi_dung_id' => $userId
                ]);
                $_SESSION['khach_hang_id'] = $khachHangId;
                header('Location: index.php?act=tour/index');
                exit();
            } else {
                $error = "Đăng ký thất bại. Vui lòng thử lại sau.";
                require 'views/auth/register.php';
                return;
            }
        } else {
            require 'views/auth/register.php';
        }
    }
    
    public function logout() {
        session_destroy();
        header('Location: index.php?act=auth/login');
        exit();
    }
    

    public function profile() {
        requireLogin();
        $user = $this->model->findById($_SESSION['user_id']);
        require 'views/auth/profile.php';
    }
}
