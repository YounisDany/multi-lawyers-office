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
    <?php include 'includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="assets/css/mobile-ionic.css">
    <title>التسجيل - منصة مكاتب المحاماة</title>
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
                        <ion-icon name="person-add"></ion-icon>
                    </div>
                    <h1 class="login-title">إنشاء حساب جديد</h1>
                    <p class="login-subtitle">انضم إلى منصة مكاتب المحاماة</p>
                </div>
                
                <!-- Register Card -->
                <ion-card class="login-card">
                    <ion-card-content>
                        <?php if ($error): ?>
                            <ion-item color="danger" class="ion-margin-bottom">
                                <ion-icon slot="start" name="alert-circle"></ion-icon>
                                <ion-label><?php echo $error; ?></ion-label>
                            </ion-item>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <ion-item color="success" class="ion-margin-bottom">
                                <ion-icon slot="start" name="checkmark-circle"></ion-icon>
                                <ion-label><?php echo $success; ?></ion-label>
                            </ion-item>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">الاسم الكامل</ion-label>
                                <ion-input 
                                    type="text" 
                                    name="name" 
                                    placeholder="أدخل اسمك الكامل"
                                    required
                                ></ion-input>
                                <ion-icon name="person" slot="start"></ion-icon>
                            </ion-item>
                            
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
                            
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">تأكيد كلمة المرور</ion-label>
                                <ion-input 
                                    type="password" 
                                    name="confirm_password" 
                                    placeholder="••••••••"
                                    required
                                ></ion-input>
                                <ion-icon name="lock-closed" slot="start"></ion-icon>
                            </ion-item>
                            
                            <ion-item class="ion-margin-bottom">
                                <ion-select label="نوع الحساب:" label-placement="stacked" placeholder="اختر نوع الحساب" name="role" required>
                                    <ion-select-option value="client">عميل</ion-select-option>
                                    <ion-select-option value="lawyer">محامي</ion-select-option>
                                </ion-select>
                                <ion-icon name="briefcase" slot="start"></ion-icon>
                            </ion-item>
                            
                            <ion-button 
                                type="submit" 
                                expand="block" 
                                shape="round" 
                                size="large"
                                class="ion-margin-top"
                            >
                                <ion-icon slot="start" name="person-add"></ion-icon>
                                إنشاء الحساب
                            </ion-button>
                        </form>
                        
                        <div class="divider">
                            <span>أو</span>
                        </div>
                        
                        <div class="register-link">
                            <p class="register-text">
                                لديك حساب بالفعل؟ 
                                <a href="login.php">سجل الدخول</a>
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
