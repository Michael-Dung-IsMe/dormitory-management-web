<?php

class BaseController {

    /**
     * Đọc các tham số phân trang từ request.
     * Trả về mảng: search, page, limit, offset
     */
    protected function paginate(int $limit = 25): array {
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $search = trim($_GET['search'] ?? '');
        return [
            'search' => $search,
            'page'   => $page,
            'limit'  => $limit,
            'offset' => ($page - 1) * $limit,
        ];
    }

    /**
     * Yêu cầu đăng nhập. Redirect về /auth/login nếu chưa đăng nhập.
     */
    protected function requireLogin(): void {
        Auth::requireLogin();
    }

    /**
     * Yêu cầu role cụ thể. Trả về 403 nếu không đủ quyền.
     *
     * @param string|array $roles  Vd: 'manager' hoặc ['manager', 'student']
     */
    protected function requireRole(string|array $roles): void {
        Auth::requireRole($roles);
    }
}
?>
