<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../config/database.php';

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Student level username dhrv and password 123456
    if ($username === 'dhrv' && $password === '123456') {
        $_SESSION['admin_id'] = 999;
        $_SESSION['admin_username'] = 'dhrv';
        header("Location: dashboard.php");
        exit();
    }

    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – Atlas Tour</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background-color: #FAFAFA;
            color: #171717;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }

        .login-card {
            background: #FFFFFF;
            border: 1px solid #E5E5E5;
            border-radius: 12px;
            padding: 40px 32px;
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo .icon-container {
            width: 48px;
            height: 48px;
            background: #FAFAFA;
            border: 1px solid #E5E5E5;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin: 0 auto 16px;
        }

        .logo h1 {
            font-size: 20px;
            font-weight: 600;
            color: #171717;
            letter-spacing: -0.3px;
        }

        .logo p {
            color: #737373;
            font-size: 14px;
            margin-top: 4px;
        }

        .error-msg {
            background: #FEF2F2;
            border: 1px solid #FCA5A5;
            color: #DC2626;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #171717;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #737373;
            font-size: 15px;
            pointer-events: none;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 11px 16px 11px 40px;
            background: #FFFFFF;
            border: 1px solid #E5E5E5;
            border-radius: 8px;
            color: #171717;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #2563EB;
        }

        input::placeholder { color: #A3A3A3; }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #2563EB;
            border: none;
            border-radius: 8px;
            color: #FFFFFF;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            margin-top: 8px;
            transition: background-color 0.2s;
        }

        .btn-login:hover {
            background-color: #1D4ED8;
        }

        .footer-note {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
        }

        .footer-note a {
            color: #737373;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-note a:hover {
            color: #171717;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <div class="icon-container">✈️</div>
                <h1>Atlas Tour</h1>
                <p>Sign in to administrative panel</p>
            </div>

            <?php if ($error): ?>
                <div class="error-msg">
                    <span>⚠️</span>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                        <span class="input-icon">👤</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <span class="input-icon">🔒</span>
                    </div>
                </div>
                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <div class="footer-note">
                <a href="../index.php">← Back to Atlas Tour Home</a>
            </div>
        </div>
    </div>
</body>
</html>
