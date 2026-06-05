<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' — KTX' : 'Hệ thống Quản lý Ký túc xá' ?></title>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="/public/css/components.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="app-container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <header class="top-header">
                <div class="header-left">
                    <h2><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Tổng quan'; ?></h2>
                </div>
                <div class="header-right">
                    <div class="user-profile">
                        <div class="user-avatar-initials">
                            <?= strtoupper(substr(Auth::username() ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="user-profile-info">
                            <span class="user-profile-name"><?= htmlspecialchars(Auth::username() ?? '') ?></span>
                            <span class="user-profile-role"><?= Auth::isManager() ? 'Quản lý' : 'Sinh viên' ?></span>
                        </div>
                    </div>
                    <a href="/auth/logout" class="btn-icon text-danger" title="Đăng xuất" style="margin-left: 0.75rem;">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </div>
            </header>
            <div class="content-wrapper">
