<?php

namespace App\Controllers;

use App\Models\UserModel;
use Exception;

class Auth extends BaseController
{
    public function login()
    {
        return view('template/header')
        .view('pages/login')
        .view('template/footer');
    }

    public function register()
    {
        return view('template/header')
        .view('pages/register')
        .view('template/footer');
    }

    public function ajaxlogin()
    {
        try {
            if ($this->request->isAJAX() || $this->request->getHeaderLine('Content-Type') === 'application/json') {
                $data = $this->request->getJSON(true);
                $email = $data['email'] ?? '';
                $password = $data['password'] ?? '';

                $userModel = new \App\Models\UserModel();
                $user = $userModel->where('email', $email)->first();

                if ($user && password_verify($password, $user['password'])) {
                    session()->set('user', [
                        'id' => $user['id'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'is_admin' => $user['is_admin'],
                        'logged_in' => true
                    ]);
                    return $this->response->setJSON([
                        'status' => 'success',
                        'message' => 'Đăng nhập thành công!',
                        'csrf_hash' => csrf_hash()
                    ]);
                } else {
                    throw new Exception('Email is invalid.');
                }
            }
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'data' => null,
                'message' => $th->getMessage(),
                'csrf_hash' => csrf_hash()
            ]);
        }
    }

    public function ajaxregister()
    {
        try {
            // Chỉ xử lý AJAX JSON
            if ($this->request->isAJAX() || $this->request->getHeaderLine('Content-Type') === 'application/json') {
                $data = $this->request->getJSON(true);

                // Validation
                $validation = \Config\Services::validation();
                $validation->setRules([
                    'name'             => 'required|min_length[3]|max_length[50]',
                    'email'            => 'required|valid_email|is_unique[users.email]',
                    'password'         => 'required|min_length[8]|max_length[18]',
                    'confirm_password' => 'required'
                ], [
                    'name' => [
                        'required'   => 'Vui lòng nhập tên.',
                        'min_length' => 'Tên phải có ít nhất 3 ký tự.',
                        'max_length' => 'Tên không được vượt quá 50 ký tự.'
                    ],
                    'email' => [
                        'required'    => 'Vui lòng nhập email.',
                        'valid_email' => 'Email không hợp lệ.',
                        'is_unique'   => 'Email đã tồn tại.'
                    ],
                    'password' => [
                        'required'   => 'Vui lòng nhập mật khẩu.',
                        'min_length' => 'Mật khẩu phải có ít nhất 8 ký tự.',
                        'max_length' => 'Mật khẩu không được vượt quá 18 ký tự.'
                    ],
                    'confirm_password' => [
                        'required' => 'Vui lòng xác nhận mật khẩu.'
                    ]
                ]);

                if (! $validation->run($data)) {
                    // Trả về lỗi validation
                    return $this->response->setStatusCode(422)
                        ->setJSON([
                            'status'    => 'error',
                            'message'   => 'Dữ liệu không hợp lệ.',
                            'errors'    => $validation->getErrors(),
                            'csrf_hash' => csrf_hash()
                        ]);
                }

                // Kiểm tra confirm password
                if ($data['password'] !== $data['confirm_password']) {
                    return $this->response->setStatusCode(422)
                        ->setJSON([
                            'status'    => 'error',
                            'message'   => 'Mật khẩu xác nhận không khớp.',
                            'errors'    => ['confirm_password' => 'Mật khẩu xác nhận không khớp.'],
                            'csrf_hash' => csrf_hash()
                        ]);
                }

                $userModel = new \App\Models\UserModel();
                $userModel->insert([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => password_hash($data['password'], PASSWORD_DEFAULT)
                ]);

                return $this->response->setJSON([
                    'status'    => 'success',
                    'message'   => 'Đăng ký thành công! Vui lòng đăng nhập.',
                    'csrf_hash' => csrf_hash()
                ]);
            }

            // Không phải AJAX/JSON
            return $this->response->setStatusCode(400)
                ->setJSON([
                    'status'    => 'error',
                    'message'   => 'Yêu cầu không hợp lệ.',
                    'csrf_hash' => csrf_hash()
                ]);
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'status'    => 'error',
                    'message'   => 'Lỗi máy chủ: ' . $th->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    public function ajaxlogout()
    {
        session()->remove('user');
        return redirect()->to(base_url('auth/login'))->with('success', 'Đăng xuất thành công!');
    }

    public function changeemail(){
        try {
            if ($this->request->isAJAX() || $this->request->getHeaderLine('Content-Type') === 'application/json') {
                $data = $this->request->getJSON(true);
                $email = $data['email'] ?? '';
                $userAuth = session('user');

                $userModel = new \App\Models\UserModel();
                $user = $userModel->where('id', $userAuth['id'])->first();
                if(!$user) {
                    throw new Exception("Not found user");
                }
                $userModel->update($userAuth['id'], ['email' => $email ]);
                session()->set('user', [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $email,
                    'is_admin' => $user['is_admin'],
                    'logged_in' => true
                ]);
                return $this->response->setJSON([
                    'status'    => 'success',
                    'message'   => "cập nhật emil thành công",
                    'csrf_hash' => csrf_hash()
                ]);
            }
        } catch (\Throwable $th) {
            return $this->response->setStatusCode(500)
                ->setJSON([
                    'status'    => 'error',
                    'message'   => 'Lỗi máy chủ: ' . $th->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }

    public function deleteUser(){
        try {
            if ($this->request->isAJAX() || $this->request->getHeaderLine('Content-Type') === 'application/json') {
                if(session()->has('user') && session('user')['is_admin'] == 1) {
                    $data = $this->request->getJSON(true);
                    $user_id = $data['user_id'] ?? '';

                    if($user_id == session('user')['id']){
                        throw new Exception("Bạn không được xoá chính bạn");
                    }

                    $userModel = new \App\Models\UserModel();
                    $userModel->delete($user_id);
                    
                    return $this->response->setJSON([
                        'status'    => 'success',
                        'message'   => "Xoá thành công.",
                        'csrf_hash' => csrf_hash()
                    ]);
                }
                throw new Exception("Bạn không có quyền xoá tài khoản.");
            }
            throw new Exception('Yêu cầu không hợp lệ.',);
        } catch (\Throwable $th) {
             return $this->response->setStatusCode(500)
                ->setJSON([
                    'status'    => 'error',
                    'message'   => 'Lỗi máy chủ: ' . $th->getMessage(),
                    'csrf_hash' => csrf_hash()
                ]);
        }
    }
}
