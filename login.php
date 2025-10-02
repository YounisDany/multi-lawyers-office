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
    <?php include 'includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="assets/css/mobile-ionic.css">
    <title>تسجيل الدخول - منصة مكاتب المحاماة</title>
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
                <ion-card class="login-card">
                    <ion-card-content>
                        <?php if ($error): ?>
                            <ion-item color="danger" class="ion-margin-bottom">
                                <ion-icon slot="start" name="alert-circle"></ion-icon>
                                <ion-label><?php echo $error; ?></ion-label>
                            </ion-item>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">البريد الإلكتروني</ion-label>
                                <ion-input 
                                    type="email" 
                                    name="email" 
                                    placeholder="example@domain.com"
                                    required
                                ></ion-input>
                                <ion-icon name="mail" slot="start"></ion-icon>
                            </ion-item>
                            
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">كلمة المرور</ion-label>
                                <ion-input 
                                    type="password" 
                                    name="password" 
                                    placeholder="••••••••"
                                    required
                                ></ion-input>
                                <ion-icon name="lock-closed" slot="start"></ion-icon>
                            </ion-item>
                            
                            <ion-button 
                                type="submit" 
                                expand="block" 
                                shape="round" 
                                size="large"
                                class="ion-margin-top"
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
                    </ion-card-content>
                </ion-card>
            </div>
            <?php include 'includes/bottom_nav.php'; ?>
        </ion-content>
    </ion-app>
</body>
</html>
