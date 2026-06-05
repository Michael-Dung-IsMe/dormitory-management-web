<?php

/**
 * Auth - Lớp xác thực & phân quyền trung tâm.
 *
 * Cung cấp các helper tĩnh để:
 *  - Kiểm tra đăng nhập
 *  - Kiểm tra vai trò (role)
 *  - Lưu / xóa thông tin session
 */
class Auth {

    // Tên key lưu trong $_SESSION
    const SESSION_USER_ID   = 'auth_account_id';
    const SESSION_USERNAME  = 'auth_username';
    const SESSION_ROLE      = 'auth_role';
    const SESSION_STUDENT_ID = 'auth_student_id';

    /**
     * Trả về true nếu người dùng đã đăng nhập.
     */
    public static function check(): bool {
        return isset($_SESSION[self::SESSION_USER_ID]);
    }

    /**
     * Trả về role hiện tại ('manager' | 'student' | null).
     */
    public static function role(): ?string {
        return $_SESSION[self::SESSION_ROLE] ?? null;
    }

    /**
     * Trả về true nếu người dùng có vai trò là manager.
     */
    public static function isManager(): bool {
        return self::role() === 'manager';
    }

    /**
     * Trả về true nếu người dùng có vai trò là student.
     */
    public static function isStudent(): bool {
        return self::role() === 'student';
    }

    /**
     * Trả về student_id nếu là sinh viên, ngược lại null.
     */
    public static function studentId(): ?int {
        $id = $_SESSION[self::SESSION_STUDENT_ID] ?? null;
        return $id !== null ? (int)$id : null;
    }

    /**
     * Trả về account_id của người dùng hiện tại.
     */
    public static function id(): ?int {
        $id = $_SESSION[self::SESSION_USER_ID] ?? null;
        return $id !== null ? (int)$id : null;
    }

    /**
     * Trả về username của người dùng hiện tại.
     */
    public static function username(): ?string {
        return $_SESSION[self::SESSION_USERNAME] ?? null;
    }

    /**
     * Lưu thông tin đăng nhập vào session.
     *
     * @param array $account  Một hàng từ bảng Account (account_id, username, role, student_id)
     */
    public static function login(array $account): void {
        session_regenerate_id(true); // Chống session fixation
        $_SESSION[self::SESSION_USER_ID]    = $account['account_id'];
        $_SESSION[self::SESSION_USERNAME]   = $account['username'];
        $_SESSION[self::SESSION_ROLE]       = $account['role'];
        $_SESSION[self::SESSION_STUDENT_ID] = $account['student_id'] ?? null;
    }

    /**
     * Xóa toàn bộ session (đăng xuất).
     */
    public static function logout(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    /**
     * Kiểm tra người dùng đã đăng nhập chưa.
     * Nếu chưa → redirect về trang login.
     */
    public static function requireLogin(): void {
        if (!self::check()) {
            header('Location: /auth/login');
            exit;
        }
    }

    /**
     * Kiểm tra người dùng có đủ quyền không.
     * Nếu không đủ quyền → trả về 403.
     *
     * @param string|array $roles  Vai trò được phép, vd: 'manager' hoặc ['manager', 'student']
     */
    public static function requireRole(string|array $roles): void {
        self::requireLogin();

        $roles = (array)$roles;
        if (!in_array(self::role(), $roles, true)) {
            http_response_code(403);
            include __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }
}
?>
