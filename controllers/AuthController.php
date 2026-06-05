<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../config/database.php';

/**
 * AuthController - Xử lý đăng nhập / đăng xuất.
 */
class AuthController extends BaseController {

    // -------------------------------------------------------
    // GET /auth/login  →  Hiển thị form đăng nhập
    // -------------------------------------------------------
    public function loginForm(): void {
        // Nếu đã đăng nhập rồi thì redirect thẳng về dashboard
        if (Auth::check()) {
            header('Location: /');
            exit;
        }
        include __DIR__ . '/../views/auth/login.php';
    }

    // -------------------------------------------------------
    // POST /auth/login  →  Xử lý đăng nhập
    // -------------------------------------------------------
    public function login(): void {
        if (Auth::check()) {
            header('Location: /');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate đầu vào cơ bản
        if (empty($username) || empty($password)) {
            $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
            include __DIR__ . '/../views/auth/login.php';
            return;
        }

        // Truy vấn tài khoản
        $db   = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM Account WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $account = $stmt->fetch();

        // Kiểm tra mật khẩu
        if (!$account || !password_verify($password, $account['password'])) {
            $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
            include __DIR__ . '/../views/auth/login.php';
            return;
        }

        // Đăng nhập thành công
        Auth::login($account);
        header('Location: /');
        exit;
    }

    // -------------------------------------------------------
    // GET /auth/logout  →  Đăng xuất
    // -------------------------------------------------------
    public function logout(): void {
        Auth::logout();
        header('Location: /auth/login');
        exit;
    }
}
?>
