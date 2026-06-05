<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>403 - Không có quyền truy cập</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e1e2e 0%, #2a2d3e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e2e8f0;
        }
        .error-box {
            text-align: center;
            padding: 2rem;
        }
        .error-code {
            font-size: 7rem;
            font-weight: 700;
            line-height: 1;
            background: linear-gradient(135deg, #f43f5e, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .error-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 1rem 0 0.5rem;
        }
        .error-desc {
            font-size: 0.9rem;
            color: #94a3b8;
            margin-bottom: 2rem;
        }
        .btn-back {
            display: inline-block;
            padding: 0.65rem 1.5rem;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: opacity 0.2s;
        }
        .btn-back:hover { opacity: 0.85; }
    </style>
</head>
<body>
    <div class="error-box">
        <div class="error-code">403</div>
        <div class="error-title">Không có quyền truy cập</div>
        <div class="error-desc">Bạn không có quyền xem trang này.<br>Vui lòng liên hệ quản lý nếu cần hỗ trợ.</div>
        <a href="/" class="btn-back">← Quay về trang chủ</a>
    </div>
</body>
</html>
