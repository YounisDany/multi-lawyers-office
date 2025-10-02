<?php
require_once '../includes/session.php';
require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (empty($email) || empty($password)) {
        $error = 'يرجى ملء جميع الحقول';
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("SELECT id, name, email, password FROM clients WHERE email = ?");
            $stmt->execute([$email]);
            $client = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($client && sha1($password) === $client['password']) {
                $_SESSION['user_id'] = $client['id'];
                $_SESSION['user_type'] = 'client';
                $_SESSION['user_name'] = $client['name'];
                $_SESSION['user_email'] = $client['email'];
                
                header('Location: ../client/dashboard.php');
                exit();
            } else {
                $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
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
    <title>تسجيل دخول العميل - منصة مكاتب محاماة أونلاين</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="header fade-in">
            <h1>تسجيل دخول العميل</h1>
            <p>أدخل بياناتك للوصول إلى حسابك</p>
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
                    <label for="email">البريد الإلكتروني:</label>
                    <input type="email" id="email" name="email" class="form-control" required 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-group" style="text-align: center;">
                    <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <p>ليس لديك حساب؟ <a href="client_register.php" style="color: #667eea;">إنشاء حساب جديد</a></p>
                    <p><a href="../index.php" style="color: #666;">العودة إلى الصفحة الرئيسية</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="../js/main.js"></script>
</body>
</html>
