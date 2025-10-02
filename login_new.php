<?php
require_once 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            
            // إعادة التوجيه حسب دور المستخدم
            switch ($user['role']) {
                case 'admin':
                    redirect('admin/dashboard.php');
                    break;
                case 'lawyer':
                    redirect('lawyer/dashboard.php');
                    break;
                case 'client':
                    redirect('client/dashboard.php');
                    break;
            }
        } else {
            $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
        }
    } else {
        $error = 'يرجى ملء جميع الحقول';
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>تسجيل الدخول - منصة مكاتب المحاماة</title>
    
    <!-- Ionic Framework -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />
    
    <!-- Ionicons -->
    <script type="module">
        import { defineCustomElements } from 'https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js';
        defineCustomElements(window);
    </script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        :root {
            --ion-color-primary: #667eea;
            --ion-color-primary-rgb: 102, 126, 234;
            --ion-color-primary-contrast: #ffffff;
            --ion-color-primary-contrast-rgb: 255, 255, 255;
            --ion-color-primary-shade: #5a6fce;
            --ion-color-primary-tint: #758dec;
        }
        
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }
        
        .login-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            backdrop-filter: blur(10px);
        }
        
        .login-logo ion-icon {
            font-size: 48px;
            color: white;
        }
        
        .login-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .login-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
        }
        
        .login-card {
            background: white;
            border-radius: 24px;
            padding: 32px 24px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }
        
        ion-input {
            --background: #f4f5f8;
            --padding-start: 16px;
            --padding-end: 16px;
            border-radius: 12px;
            font-size: 1rem;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 20px;
            z-index: 10;
        }
        
        ion-input.has-icon {
            --padding-end: 48px;
        }
        
        .error-alert {
            background: #fee;
            border: 1px solid #fcc;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .error-alert ion-icon {
            color: #c33;
            font-size: 24px;
            flex-shrink: 0;
        }
        
        .error-text {
            color: #c33;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .login-button {
            margin-top: 24px;
        }
        
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 24px 0;
            color: #999;
            font-size: 0.85rem;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .divider span {
            padding: 0 12px;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .register-text {
            color: #666;
            font-size: 0.9rem;
        }
        
        .register-text a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }
        
        .back-button {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 100;
        }
        
        @media (max-width: 480px) {
            .login-card {
                padding: 28px 20px;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <ion-app>
        <ion-content>
            <!-- Back Button -->
            <ion-button href="index.php" fill="clear" class="back-button" color="light">
                <ion-icon slot="icon-only" name="arrow-forward"></ion-icon>
            </ion-button>
            
            <div class="login-wrapper">
                <!-- Header -->
                <div class="login-header">
                    <div class="login-logo">
                        <ion-icon name="scale"></ion-icon>
                    </div>
                    <h1 class="login-title">مرحباً بعودتك</h1>
                    <p class="login-subtitle">سجل دخولك للوصول إلى حسابك</p>
                </div>
                
                <!-- Login Card -->
                <div class="login-card">
                    <?php if ($error): ?>
                        <div class="error-alert">
                            <ion-icon name="alert-circle"></ion-icon>
                            <span class="error-text"><?php echo $error; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label class="form-label">البريد الإلكتروني</label>
                            <div class="input-wrapper">
                                <ion-input 
                                    type="email" 
                                    name="email" 
                                    placeholder="example@domain.com"
                                    required
                                    class="has-icon"
                                ></ion-input>
                                <ion-icon name="mail" class="input-icon"></ion-icon>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">كلمة المرور</label>
                            <div class="input-wrapper">
                                <ion-input 
                                    type="password" 
                                    name="password" 
                                    placeholder="••••••••"
                                    required
                                    class="has-icon"
                                ></ion-input>
                                <ion-icon name="lock-closed" class="input-icon"></ion-icon>
                            </div>
                        </div>
                        
                        <ion-button 
                            type="submit" 
                            expand="block" 
                            shape="round" 
                            size="large"
                            class="login-button"
                        >
                            <ion-icon slot="start" name="log-in"></ion-icon>
                            تسجيل الدخول
                        </ion-button>
                    </form>
                    
                    <div class="divider">
                        <span>أو</span>
                    </div>
                    
                    <div class="register-link">
                        <p class="register-text">
                            ليس لديك حساب؟ 
                            <a href="register.php">سجل الآن</a>
                        </p>
                    </div>
                </div>
            </div>
        </ion-content>
    </ion-app>
</body>
</html>
