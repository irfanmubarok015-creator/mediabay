<?php

class AuthController extends Controller {

    public function loginForm(): void {
        if (isset($_SESSION['user_id'])) {
            $this->redirect($_SESSION['role'] === 'admin' ? '/admin' : '/dashboard');
        }
        // Gunakan layout 'auth' — tanpa navbar/footer
        $this->view('auth/login', ['flash' => $this->getFlash()], 'auth');
    }

    public function login(): void {
        $email    = trim($this->input('email', ''));
        $password = $this->input('password', '');

        if (!$email || !$password) {
            $this->flash('error', 'Email dan password harus diisi');
            $this->redirect('/auth/login');
            return;
        }

        $user = (new UserModel())->authenticate($email, $password);

        if (!$user) {
            $this->flash('error', 'Email atau password salah. Periksa kembali kredensial Anda.');
            $this->redirect('/auth/login');
            return;
        }

        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['email']   = $user['email'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['avatar']  = $user['avatar'] ?? '';

        // Redirect sesuai role
        $this->redirect($user['role'] === 'admin' ? '/admin' : '/dashboard');
    }

    public function registerForm(): void {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/register', ['flash' => $this->getFlash()], 'auth');
    }

    public function register(): void {
        $name     = $this->sanitize($this->input('name', ''));
        $email    = trim($this->input('email', ''));
        $phone    = $this->sanitize($this->input('phone', ''));
        $password = $this->input('password', '');
        $confirm  = $this->input('password_confirm', '');

        // Validasi
        if (!$name || !$email || !$phone || !$password) {
            $this->flash('error', 'Semua field wajib diisi');
            $this->redirect('/auth/register');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash('error', 'Format email tidak valid');
            $this->redirect('/auth/register');
            return;
        }

        if (strlen($password) < 8) {
            $this->flash('error', 'Password minimal 8 karakter');
            $this->redirect('/auth/register');
            return;
        }

        if ($password !== $confirm) {
            $this->flash('error', 'Konfirmasi password tidak cocok');
            $this->redirect('/auth/register');
            return;
        }

        $userModel = new UserModel();

        if ($userModel->findByEmail($email)) {
            $this->flash('error', 'Email sudah terdaftar, silakan login');
            $this->redirect('/auth/register');
            return;
        }

        $userModel->create([
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]),
            'role'     => 'user',
        ]);

        $this->flash('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        $this->redirect('/auth/login');
    }

    public function logout(): void {
        session_unset();
        session_destroy();
        $this->redirect('/');
    }
}
