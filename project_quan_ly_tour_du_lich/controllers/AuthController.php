<?php
require_once 'models/NguoiDung.php';

class AuthController {
    private $model;

    public function __construct() {
        $this->model = new NguoiDung();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->model->findByEmail($email);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['vai_tro'];
                $_SESSION['user_name'] = $user['ten'];
                
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
                'ten' => $_POST['ten'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
                'vai_tro' => 'khach_hang',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $userId = $this->model->insert($data);
            
            if ($userId) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['role'] = 'khach_hang';
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
