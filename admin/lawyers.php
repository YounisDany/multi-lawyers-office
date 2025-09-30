<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('admin')) {
    redirect('../login.php');
}

$success = '';
$error = '';

// معالجة حذف أو إيقاف المحامي
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_lawyer'])) {
        $lawyer_id = $_POST['lawyer_id'];
        
        // التحقق من عدم وجود قضايا نشطة للمحامي
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM cases WHERE lawyer_id = ? AND status IN ('new', 'in_progress')");
        $stmt->execute([$lawyer_id]);
        $active_cases = $stmt->fetchColumn();
        
        if ($active_cases > 0) {
            $error = 'لا يمكن حذف المحامي لوجود قضايا نشطة';
        } else {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'lawyer'");
            if ($stmt->execute([$lawyer_id])) {
                $success = 'تم حذف المحامي بنجاح';
            } else {
                $error = 'حدث خطأ أثناء حذف المحامي';
            }
        }
    }
}

// جلب جميع المحامين مع إحصائياتهم
$stmt = $pdo->prepare("SELECT u.*, 
                       (SELECT COUNT(*) FROM cases WHERE lawyer_id = u.id) as total_cases,
                       (SELECT COUNT(*) FROM cases WHERE lawyer_id = u.id AND status IN ('new', 'in_progress')) as active_cases,
                       (SELECT COUNT(*) FROM consultations WHERE lawyer_id = u.id) as total_consultations
                       FROM users u 
                       WHERE u.role = 'lawyer' 
                       ORDER BY u.created_at DESC");
$stmt->execute();
$lawyers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المحامين - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>لوحة الإدارة</h3>
            <ul>
                <li><a href="dashboard.php">الرئيسية</a></li>
                <li><a href="lawyers.php" class="active">إدارة المحامين</a></li>
                <li><a href="clients.php">إدارة العملاء</a></li>
                <li><a href="cases.php">مراقبة القضايا</a></li>
                <li><a href="reports.php">التقارير</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>إدارة المحامين</h1>
                <p>عرض وإدارة جميع المحامين المسجلين في المنصة</p>
            </div>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="card">
                <h2>قائمة المحامين (<?php echo count($lawyers); ?>)</h2>
                
                <?php if (count($lawyers) > 0): ?>
                    <div class="lawyers-grid">
                        <?php foreach ($lawyers as $lawyer): ?>
                            <div class="lawyer-card">
                                <div class="lawyer-header">
                                    <h4><?php echo htmlspecialchars($lawyer['name']); ?></h4>
                                    <span class="lawyer-status">نشط</span>
                                </div>
                                
                                <div class="lawyer-info">
                                    <p><strong>البريد:</strong> <?php echo htmlspecialchars($lawyer['email']); ?></p>
                                    <p><strong>تاريخ التسجيل:</strong> <?php echo date('Y-m-d', strtotime($lawyer['created_at'])); ?></p>
                                </div>
                                
                                <div class="lawyer-stats">
                                    <div class="stat-item">
                                        <span class="stat-number"><?php echo $lawyer['total_cases']; ?></span>
                                        <span class="stat-label">إجمالي القضايا</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-number"><?php echo $lawyer['active_cases']; ?></span>
                                        <span class="stat-label">القضايا النشطة</span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-number"><?php echo $lawyer['total_consultations']; ?></span>
                                        <span class="stat-label">الاستشارات</span>
                                    </div>
                                </div>
                                
                                <div class="lawyer-actions">
                                    <a href="lawyer_details.php?id=<?php echo $lawyer['id']; ?>" class="btn-small">عرض التفاصيل</a>
                                    
                                    <?php if ($lawyer['active_cases'] == 0): ?>
                                        <form method="POST" style="display: inline;" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المحامي؟')">
                                            <input type="hidden" name="lawyer_id" value="<?php echo $lawyer['id']; ?>">
                                            <button type="submit" name="delete_lawyer" class="btn-danger">حذف</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="btn-disabled" title="لا يمكن الحذف لوجود قضايا نشطة">حذف</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>لا يوجد محامين مسجلين في المنصة بعد.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
