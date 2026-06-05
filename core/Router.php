<?php

class Router {
    private $routes = [];

    /**
     * Đăng ký route GET.
     *
     * @param string       $route   Pattern URL, vd: '/student/{id}'
     * @param string       $action  'ControllerClass@method'
     * @param array        $roles   Các role được phép, vd: ['manager']. Rỗng = yêu cầu đăng nhập nhưng không giới hạn role.
     *                              Dùng ['*'] hoặc bỏ qua để cho phép tất cả (kể cả khách).
     */
    public function get(string $route, string $action, array $roles = []): void {
        $this->addRoute('GET', $route, $action, $roles);
    }

    /**
     * Đăng ký route POST.
     */
    public function post(string $route, string $action, array $roles = []): void {
        $this->addRoute('POST', $route, $action, $roles);
    }

    private function addRoute(string $method, string $route, string $action, array $roles): void {
        $routePattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $route);
        $routePattern = '#^' . $routePattern . '$#';
        $this->routes[] = [
            'method'  => $method,
            'pattern' => $routePattern,
            'action'  => $action,
            'roles'   => $roles,
        ];
    }

    /**
     * Khớp URI với routes đã đăng ký, kiểm tra quyền, rồi gọi Controller.
     */
    public function dispatch(string $uri, string $method): void {
        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (!preg_match($route['pattern'], $uri, $matches)) {
                continue;
            }

            // --- Kiểm tra quyền truy cập ---
            $this->checkAccess($route['roles']);

            // --- Gọi Controller ---
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            foreach ($params as $key => $value) {
                $_GET[$key] = $value;
            }

            [$controllerClass, $methodName] = explode('@', $route['action']);

            require_once "controllers/{$controllerClass}.php";
            $controllerInstance = new $controllerClass();

            if (method_exists($controllerInstance, $methodName)) {
                $controllerInstance->$methodName();
            }

            return;
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    /**
     * Kiểm tra quyền truy cập dựa trên $roles của route.
     *
     * Logic:
     *  - $roles rỗng ([])      → public, không cần đăng nhập
     *  - $roles = ['*']        → chỉ cần đăng nhập, không giới hạn role
     *  - $roles = ['manager']  → phải đăng nhập VÀ phải là manager
     */
    private function checkAccess(array $roles): void {
        // Route public — bỏ qua kiểm tra
        if (empty($roles)) {
            return;
        }

        // Phải đăng nhập
        if (!Auth::check()) {
            header('Location: /auth/login');
            exit;
        }

        // Cho phép mọi role đã đăng nhập
        if (in_array('*', $roles, true)) {
            return;
        }

        // Kiểm tra role cụ thể
        if (!in_array(Auth::role(), $roles, true)) {
            http_response_code(403);
            include __DIR__ . '/../views/errors/403.php';
            exit;
        }
    }
}
?>
