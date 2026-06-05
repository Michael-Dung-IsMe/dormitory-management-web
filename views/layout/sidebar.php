<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', trim($uri, '/'));
$controller = !empty($parts[0]) ? $parts[0] : 'dashboard';
$isManager  = Auth::isManager();
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="fa-solid fa-building-user"></i>
        <span><?= $isManager ? 'KTX Admin' : 'KTX Portal' ?></span>
    </div>
    <ul class="sidebar-nav">

        <li>
            <a href="/" class="<?php echo ($controller == 'dashboard' || $uri == '/') ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Tổng quan</span>
            </a>
        </li>

        <?php if ($isManager): ?>
        <li class="sidebar-section-label">Quản lý</li>
        <li>
            <a href="/student" class="<?php echo ($controller == 'student') ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-graduate"></i>
                <span>Sinh viên</span>
            </a>
        </li>
        <li>
            <a href="/room" class="<?php echo ($controller == 'room') ? 'active' : ''; ?>">
                <i class="fa-solid fa-door-open"></i>
                <span>Phòng KTX</span>
            </a>
        </li>
        <li>
            <a href="/contract" class="<?php echo ($controller == 'contract') ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-signature"></i>
                <span>Hợp đồng</span>
            </a>
        </li>
        <li>
            <a href="/bill" class="<?php echo ($controller == 'bill') ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Hóa đơn</span>
            </a>
        </li>
        <li>
            <a href="/notice" class="<?php echo ($controller == 'notice') ? 'active' : ''; ?>">
                <i class="fa-solid fa-bell"></i>
                <span>Thông báo</span>
            </a>
        </li>

        <?php else: ?>
        <li class="sidebar-section-label">Của tôi</li>
        <li>
            <a href="/student/profile" class="<?php echo ($uri == '/student/profile') ? 'active' : ''; ?>">
                <i class="fa-solid fa-id-card"></i>
                <span>Hồ sơ cá nhân</span>
            </a>
        </li>
        <li>
            <a href="/room/my-room" class="<?php echo ($uri == '/room/my-room') ? 'active' : ''; ?>">
                <i class="fa-solid fa-door-open"></i>
                <span>Phòng của tôi</span>
            </a>
        </li>
        <li>
            <a href="/bill/my-bills" class="<?php echo ($uri == '/bill/my-bills') ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Hóa đơn</span>
            </a>
        </li>
        <li>
            <a href="/notice" class="<?php echo ($controller == 'notice') ? 'active' : ''; ?>">
                <i class="fa-solid fa-bell"></i>
                <span>Thông báo</span>
            </a>
        </li>
        <?php endif; ?>

    </ul>

    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            <?= strtoupper(substr(Auth::username() ?? 'U', 0, 1)) ?>
        </div>
        <div class="sidebar-user-info">
            <span class="sidebar-user-name"><?= htmlspecialchars(Auth::username() ?? '') ?></span>
            <span class="sidebar-user-role"><?= $isManager ? 'Quản lý' : 'Sinh viên' ?></span>
        </div>
        <a href="/auth/logout" class="sidebar-logout" title="Đăng xuất">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
    </div>
</aside>
