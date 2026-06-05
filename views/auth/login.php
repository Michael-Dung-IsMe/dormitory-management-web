<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Quản lý KTX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
    <style>
        /* ---- Login-specific styles ---- */
        body.login-page {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #1e1e2e 0%, #2a2d3e 100%);
        }

        .login-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 24px 48px rgba(0,0,0,0.4);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .login-logo .icon {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        .login-logo h1 {
            font-size: 1.35rem;
            font-weight: 700;
            color: #e2e8f0;
            margin: 0;
        }

        .login-logo p {
            font-size: 0.82rem;
            color: #94a3b8;
            margin: 0.25rem 0 0;
        }

        .form-group {
            margin-bottom: 1.1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 500;
            color: #94a3b8;
            margin-bottom: 0.4rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.65rem 0.9rem;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 8px;
            color: #e2e8f0;
            font-size: 0.95rem;
            font-family: inherit;
            transition: border-color 0.2s;
            box-sizing: border-box;
        }

        .form-group input:focus {
            outline: none;
            border-color: #6366f1;
            background: rgba(99,102,241,0.08);
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            font-size: 0.95rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-login:hover  { opacity: 0.9; }
        .btn-login:active { transform: scale(0.98); }

        .alert-error {
            background: rgba(239,68,68,0.15);
            border: 1px solid rgba(239,68,68,0.35);
            color: #fca5a5;
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .login-hint {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 0.78rem;
            color: #64748b;
            text-align: center;
            line-height: 1.8;
        }
    </style>
</head>
<body class="login-page">

<div class="login-card">
    <div class="login-logo">
        <h1>Quản lý Ký túc xá</h1>
        <p>Đăng nhập để tiếp tục</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/auth/login" autocomplete="off">
        <div class="form-group">
            <label for="username">Tên đăng nhập</label>
            <input
                type="text"
                id="username"
                name="username"
                placeholder="Nhập tên đăng nhập"
                value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                required
                autofocus
            >
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Nhập mật khẩu"
                required
            >
        </div>

        <button type="submit" class="btn-login">Đăng nhập</button>
    </form>

    <div class="login-hint">
        <strong>Tài khoản demo</strong><br>
        Quản lý: <code>admin</code> / <code>Admin@123</code><br>
        Sinh viên: <code>student</code> / <code>Student@123</code>
    </div>
</div>

</body>
</html>
