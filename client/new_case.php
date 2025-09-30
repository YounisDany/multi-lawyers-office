<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('client')) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// جلب قائمة المحامين
$stmt = $pdo->prepare("SELECT id, name FROM users WHERE role = 'lawyer'");
$stmt->execute();
$lawyers = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lawyer_id = $_POST['lawyer_id'];
    $title = $_POST['title'];
    $details = $_POST['details'];
    
    if (!empty($lawyer_id) && !empty($title) && !empty($details)) {
        $stmt = $pdo->prepare("INSERT INTO cases (lawyer_id, client_id, title, details) VALUES (?, ?, ?, ?)");
        
        if ($stmt->execute([$lawyer_id, $user_id, $title, $details])) {
            // إرسال إشعار بالبريد الإلكتروني للمحامي
            require_once '../email_config.php';
            $lawyer_stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
            $lawyer_stmt->execute([$lawyer_id]);
            $lawyer = $lawyer_stmt->fetch();
            
            if ($lawyer) {
                notifyLawyerNewCase($lawyer['email'], $lawyer['name'], $title, $_SESSION['user_name']);
            }
            
            $success = 'تم إرسال القضية بنجاح. سيتم التواصل معك قريباً.';
        } else {
            $error = 'حدث خطأ أثناء إرسال القضية';
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
    <title>قضية جديدة - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>مرحباً <?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
            <ul>
                <li><a href="dashboard.php">الرئيسية</a></li>
                <li><a href="new_case.php" class="active">قضية جديدة</a></li>
                <li><a href="consultations.php">الاستشارات</a></li>
                <li><a href="messages.php">الرسائل</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>إضافة قضية جديدة</h1>
                <p>قم بملء التفاصيل أدناه لإرسال قضيتك إلى المحامي المختص</p>
            </div>
            
            <div class="card">
                <?php if ($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success-message"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" class="case-form">
                    <div class="form-group">
                        <label for="lawyer_id">اختر المحامي:</label>
                        <select id="lawyer_id" name="lawyer_id" required>
                            <option value="">اختر المحامي</option>
                            <?php foreach ($lawyers as $lawyer): ?>
                                <option value="<?php echo $lawyer['id']; ?>">
                                    <?php echo htmlspecialchars($lawyer['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="title">عنوان القضية:</label>
                        <input type="text" id="title" name="title" required 
                               placeholder="مثال: قضية عمالية - فصل تعسفي">
                    </div>
                    
                    <div class="form-group">
                        <label for="details">تفاصيل القضية:</label>
                        <textarea id="details" name="details" rows="8" required 
                                  placeholder="اكتب تفاصيل القضية بشكل واضح ومفصل..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">إرسال القضية</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
