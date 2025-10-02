<?php
require_once '../includes/session.php';
require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'يرجى ملء جميع الحقول المطلوبة';
    } elseif ($password !== $confirm_password) {
        $error = 'كلمة المرور وتأكيد كلمة المرور غير متطابقتين';
    } elseif (strlen($password) < 6) {
        $error = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    } else {
        try {
            $pdo = getDBConnection();
            
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM lawyers WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'البريد الإلكتروني مستخدم بالفعل';
            } else {
                // Insert new lawyer
                $stmt = $pdo->prepare("INSERT INTO lawyers (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, sha1($password), $phone, $address]);
                
                $success = 'تم إنشاء الحساب بنجاح. يمكنك الآن تسجيل الدخول';
                
                // Clear form data
                $_POST = array();
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ في النظام. يرجى المحاولة لاحقاً';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل محامي جديد - منصة مكاتب محاماة أونلاين</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="header fade-in">
            <h1>تسجيل محامي جديد</h1>
            <p>أنشئ حسابك للانضمام إلى منصة المحاماة</p>
        </div>

        <div class="card fade-in">
            <form method="POST" action="">
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="name">الاسم الكامل: *</label>
                    <input type="text" id="name" name="name" class="form-control" required 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="email">البريد الإلكتروني: *</label>
                    <input type="email" id="email" name="email" class="form-control" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="phone">رقم الهاتف:</label>
                    <input type="tel" id="phone" name="phone" class="form-control" 
                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="address">العنوان:</label>
                    <textarea id="address" name="address" class="form-control" rows="3"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور: *</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <small style="color: #666;">يجب أن تكون 6 أحرف على الأقل</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">تأكيد كلمة المرور: *</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <div class="form-group" style="text-align: center;">
                    <button type="submit" class="btn btn-primary">إنشاء الحساب</button>
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <p>لديك حساب بالفعل؟ <a href="lawyer_login.php" style="color: #667eea;">تسجيل الدخول</a></p>
                    <p><a href="../index.php" style="color: #666;">العودة إلى الصفحة الرئيسية</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="../js/main.js"></script>
</body>
</html>
