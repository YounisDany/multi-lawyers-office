<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    
    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password) && !empty($role)) {
        if ($password === $confirm_password) {
            // التحقق من عدم وجود البريد الإلكتروني مسبقاً
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if (!$stmt->fetch()) {
                $hashed_password = hashPassword($password);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                
                if ($stmt->execute([$name, $email, $hashed_password, $role])) {
                    $success = 'تم إنشاء الحساب بنجاح. يمكنك الآن تسجيل الدخول.';
                } else {
                    $error = 'حدث خطأ أثناء إنشاء الحساب';
                }
            } else {
                $error = 'البريد الإلكتروني مستخدم بالفعل';
            }
        } else {
            $error = 'كلمات المرور غير متطابقة';
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
    <title>التسجيل - منصة مكاتب المحاماة</title>
    
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
            
            --ion-color-success: #10dc60;
            --ion-color-success-rgb: 16, 220, 96;
            --ion-color-success-contrast: #ffffff;
            --ion-color-success-contrast-rgb: 255, 255, 255;
            --ion-color-success-shade: #0ec254;
            --ion-color-success-tint: #28e070;
        }
        
        .register-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
            padding-top: 60px;
        }
        
        .register-header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }
        
        .register-logo {
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
        
        .register-logo ion-icon {
            font-size: 48px;
            color: white;
        }
        
        .register-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .register-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
        }
        
        .register-card {
            background: white;
            border-radius: 24px;
            padding: 32px 24px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
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
        
        ion-input, ion-select {
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
        
        .success-alert {
            background: #efe;
            border: 1px solid #cfc;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .success-alert ion-icon {
            color: #3c3;
            font-size: 24px;
            flex-shrink: 0;
        }
        
        .success-text {
            color: #3c3;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .register-button {
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
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-text {
            color: #666;
            font-size: 0.9rem;
        }
        
        .login-text a {
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
        
        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .role-option {
            background: #f4f5f8;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .role-option:hover {
            background: #e8e9f0;
        }
        
        .role-option.selected {
            background: #eef0ff;
            border-color: #667eea;
        }
        
        .role-option ion-icon {
            font-size: 32px;
            color: #667eea;
            margin-bottom: 8px;
        }
        
        .role-option span {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
        }
        
        @media (max-width: 480px) {
            .register-card {
                padding: 28px 20px;
            }
            
            .register-title {
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
            
            <div class="register-wrapper">
                <!-- Header -->
                <div class="register-header">
                    <div class="register-logo">
                        <ion-icon name="person-add"></ion-icon>
                    </div>
                    <h1 class="register-title">إنشاء حساب جديد</h1>
                    <p class="register-subtitle">انضم إلى منصتنا وابدأ رحلتك القانونية</p>
                </div>
                
                <!-- Register Card -->
                <div class="register-card">
                    <?php if ($error): ?>
                        <div class="error-alert">
                            <ion-icon name="alert-circle"></ion-icon>
                            <span class="error-text"><?php echo $error; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="success-alert">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            <span class="success-text"><?php echo $success; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="registerForm">
                        <div class="form-group">
                            <label class="form-label">الاسم الكامل</label>
                            <div class="input-wrapper">
                                <ion-input 
                                    type="text" 
                                    name="name" 
                                    placeholder="أدخل اسمك الكامل"
                                    required
                                    class="has-icon"
                                ></ion-input>
                                <ion-icon name="person" class="input-icon"></ion-icon>
                            </div>
                        </div>
                        
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
                            <label class="form-label">نوع الحساب</label>
                            <div class="role-selector">
                                <div class="role-option" data-role="client">
                                    <ion-icon name="person-circle"></ion-icon>
                                    <span>عميل</span>
                                </div>
                                <div class="role-option" data-role="lawyer">
                                    <ion-icon name="briefcase"></ion-icon>
                                    <span>محامي</span>
                                </div>
                            </div>
                            <input type="hidden" name="role" id="roleInput" required>
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
                        
                        <div class="form-group">
                            <label class="form-label">تأكيد كلمة المرور</label>
                            <div class="input-wrapper">
                                <ion-input 
                                    type="password" 
                                    name="confirm_password" 
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
                            class="register-button"
                        >
                            <ion-icon slot="start" name="rocket"></ion-icon>
                            إنشاء الحساب
                        </ion-button>
                    </form>
                    
                    <div class="divider">
                        <span>أو</span>
                    </div>
                    
                    <div class="login-link">
                        <p class="login-text">
                            لديك حساب بالفعل؟ 
                            <a href="login.php">سجل الدخول</a>
                        </p>
                    </div>
                </div>
            </div>
        </ion-content>
    </ion-app>
    
    <script>
        // Role Selection
        const roleOptions = document.querySelectorAll('.role-option');
        const roleInput = document.getElementById('roleInput');
        
        roleOptions.forEach(option => {
            option.addEventListener('click', function() {
                roleOptions.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
                roleInput.value = this.getAttribute('data-role');
            });
        });
    </script>
</body>
</html>
