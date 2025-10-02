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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التسجيل - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <div class="register-container">
        <div class="register-form">
            <h2>إنشاء حساب جديد</h2>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="name">الاسم الكامل:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">البريد الإلكتروني:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="role">نوع الحساب:</label>
                    <select id="role" name="role" required>
                        <option value="">اختر نوع الحساب</option>
                        <option value="client">عميل</option>
                        <option value="lawyer">محامي</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="password">كلمة المرور:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">تأكيد كلمة المرور:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn-primary">إنشاء الحساب</button>
            </form>
            
            <div class="login-link">
                <p>لديك حساب بالفعل؟ <a href="login.php">سجل الدخول</a></p>
            </div>
        </div>
    </div>
</body>
</html>
