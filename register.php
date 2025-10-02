<?php
// ملف التسجيل - يونس ضاعني
require_once 'config.php';

$error = '';
$success = '';

// معالجة التسجيل
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = sanitize_input($_POST['role']);
    
    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password) && !empty($role)) {
        if ($password === $confirm_password) {
            // التحقق من عدم وجود البريد الإلكتروني مسبقاً
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() == 0) {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - <?php echo SITENAME; ?></title>
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
                <ion-title>إنشاء حساب جديد</ion-title>
            </ion-toolbar>
        </ion-header>
        
        <ion-content class="ion-padding">
            <div class="auth-container">
                <div class="auth-header">
                    <h2>انضم إلينا</h2>
                    <p>أنشئ حسابك الجديد للبدء</p>
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
                        <ion-label position="stacked">الاسم الكامل</ion-label>
                        <ion-input type="text" name="name" placeholder="أدخل اسمك الكامل" required></ion-input>
                    </ion-item>
                    
                    <ion-item class="ion-margin-bottom">
                        <ion-label position="stacked">البريد الإلكتروني</ion-label>
                        <ion-input type="email" name="email" placeholder="أدخل بريدك الإلكتروني" required></ion-input>
                    </ion-item>
                    
                    <ion-item class="ion-margin-bottom">
                        <ion-label position="stacked">نوع الحساب</ion-label>
                        <ion-select name="role" placeholder="اختر نوع الحساب" required>
                            <ion-select-option value="client">عميل</ion-select-option>
                            <ion-select-option value="lawyer">محامي</ion-select-option>
                        </ion-select>
                    </ion-item>
                    
                    <ion-item class="ion-margin-bottom">
                        <ion-label position="stacked">كلمة المرور</ion-label>
                        <ion-input type="password" name="password" placeholder="أدخل كلمة المرور" required></ion-input>
                    </ion-item>
                    
                    <ion-item class="ion-margin-bottom">
                        <ion-label position="stacked">تأكيد كلمة المرور</ion-label>
                        <ion-input type="password" name="confirm_password" placeholder="أعد إدخال كلمة المرور" required></ion-input>
                    </ion-item>
                    
                    <ion-button type="submit" expand="block" shape="round" class="ion-margin-top">
                        <ion-icon slot="start" name="person-add"></ion-icon>
                        إنشاء الحساب
                    </ion-button>
                </form>
                
                <div class="auth-footer">
                    <p>لديك حساب بالفعل؟ <a href="login.php">تسجيل الدخول</a></p>
                </div>
            </div>
        </ion-content>
    </ion-app>
    
    <script src="public/assets/js/main.js"></script>
</body>
</html>
