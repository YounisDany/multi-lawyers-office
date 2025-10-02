<?php
// ملف تسجيل الدخول - يونس ضاعني
require_once 'config.php';

// إذا كان المستخدم مسجل دخول بالفعل، توجيهه إلى لوحة التحكم المناسبة
if (isLoggedIn()) {
    if (hasRole('client')) {
        header("Location: client_dashboard.php");
        exit();
    } elseif (hasRole('lawyer')) {
        header("Location: lawyer_dashboard.php");
        exit();
    } elseif (hasRole('admin')) {
        header("Location: admin/dashboard.php");
        exit();
    }
}

$error = '';
$success = '';

// معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && verifyPassword($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_role'] = $user->role;
            
            // توجيه المستخدم حسب دوره
            if ($user->role == 'client') {
                header("Location: client_dashboard.php");
                exit();
            } elseif ($user->role == 'lawyer') {
                header("Location: lawyer_dashboard.php");
                exit();
            } elseif ($user->role == 'admin') {
                header("Location: admin/dashboard.php");
                exit();
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="public/assets/css/mobile-ionic.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>
</head>
<body>
    <ion-app>
        <ion-header>
            <ion-toolbar color="primary">
                <ion-buttons slot="start">
                    <ion-back-button default-href="index.php"></ion-back-button>
                </ion-buttons>
                <ion-title>تسجيل الدخول</ion-title>
            </ion-toolbar>
        </ion-header>
        
        <ion-content class="ion-padding">
            <div class="auth-container">
                <div class="auth-header">
                    <h2>مرحباً بعودتك</h2>
                    <p>سجل دخولك للوصول إلى حسابك</p>
                </div>
                
                <?php if ($error): ?>
                    <ion-item color="danger" class="ion-margin-bottom">
                        <ion-icon name="alert-circle" slot="start"></ion-icon>
                        <ion-label><?php echo $error; ?></ion-label>
                    </ion-item>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <ion-item color="success" class="ion-margin-bottom">
                        <ion-icon name="checkmark-circle" slot="start"></ion-icon>
                        <ion-label><?php echo $success; ?></ion-label>
                    </ion-item>
                <?php endif; ?>
                
                <form method="POST" class="auth-form">
                    <ion-item class="ion-margin-bottom">
                        <ion-label position="stacked">البريد الإلكتروني</ion-label>
                        <ion-input type="email" name="email" placeholder="أدخل بريدك الإلكتروني" required></ion-input>
                    </ion-item>
                    
                    <ion-item class="ion-margin-bottom">
                        <ion-label position="stacked">كلمة المرور</ion-label>
                        <ion-input type="password" name="password" placeholder="أدخل كلمة المرور" required></ion-input>
                    </ion-item>
                    
                    <ion-button type="submit" expand="block" shape="round" class="ion-margin-top">
                        <ion-icon slot="start" name="log-in"></ion-icon>
                        تسجيل الدخول
                    </ion-button>
                </form>
                
                <div class="auth-footer">
                    <p>ليس لديك حساب؟ <a href="register.php">إنشاء حساب جديد</a></p>
                </div>
            </div>
        </ion-content>
    </ion-app>
    
    <script src="public/assets/js/main.js"></script>
</body>
</html>
