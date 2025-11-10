<?php
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
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->model->findByEmail($email);
            
            if ($user && password_verify($password, $user['mat_khau'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['vai_tro'];
                $_SESSION['user_name'] = $user['ho_ten'];
                
                if ($user['vai_tro'] === 'KhachHang') {
                    $khachHang = $this->khachHangModel->findByNguoiDungId($user['id']);
                    if ($khachHang) {
                        $_SESSION['khach_hang_id'] = $khachHang['khach_hang_id'];
                    }
                }
                
                redirect('index.php?act=tour/index');
            } else {
                $error = "Email hoặc mật khẩu không đúng";
                require 'views/auth/login.php';
            }
        } else {
            require 'views/auth/login.php';
        }
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ho_ten' => $_POST['ho_ten'] ?? '',
                'email' => $_POST['email'] ?? '',
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
                redirect('index.php?act=tour/index');
            }
        } else {
            require 'views/auth/register.php';
        }
    }
    
    public function logout() {
        session_destroy();
        redirect('index.php?act=auth/login');
    }
    
    public function forgotPassword() {
        require 'views/auth/forgot_password.php';
    }
    
    public function profile() {
        requireLogin();
        $user = $this->model->findById($_SESSION['user_id']);
        require 'views/auth/profile.php';
    }
}
