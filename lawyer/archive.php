<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('lawyer')) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// معالجة أرشفة القضية
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['archive_case'])) {
    $case_id = $_POST['case_id'];
    
    $stmt = $pdo->prepare("UPDATE cases SET status = 'archived' WHERE id = ? AND lawyer_id = ?");
    if ($stmt->execute([$case_id, $user_id])) {
        $success = 'تم أرشفة القضية بنجاح';
    } else {
        $error = 'حدث خطأ أثناء أرشفة القضية';
    }
}

// معالجة استعادة القضية من الأرشيف
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['restore_case'])) {
    $case_id = $_POST['case_id'];
    
    $stmt = $pdo->prepare("UPDATE cases SET status = 'closed' WHERE id = ? AND lawyer_id = ?");
    if ($stmt->execute([$case_id, $user_id])) {
        $success = 'تم استعادة القضية من الأرشيف';
    } else {
        $error = 'حدث خطأ أثناء استعادة القضية';
    }
}

// جلب القضايا المؤرشفة
$stmt = $pdo->prepare("SELECT c.*, u.name as client_name, u.email as client_email,
                       (SELECT COUNT(*) FROM messages WHERE case_id = c.id) as message_count
                       FROM cases c 
                       JOIN users u ON c.client_id = u.id 
                       WHERE c.lawyer_id = ? AND c.status = 'archived' 
                       ORDER BY c.updated_at DESC");
$stmt->execute([$user_id]);
$archived_cases = $stmt->fetchAll();

// جلب القضايا المغلقة (قابلة للأرشفة)
$stmt = $pdo->prepare("SELECT c.*, u.name as client_name, u.email as client_email,
                       (SELECT COUNT(*) FROM messages WHERE case_id = c.id) as message_count
                       FROM cases c 
                       JOIN users u ON c.client_id = u.id 
                       WHERE c.lawyer_id = ? AND c.status = 'closed' 
                       ORDER BY c.updated_at DESC");
$stmt->execute([$user_id]);
$closed_cases = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأرشيف - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>مرحباً <?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
            <ul>
                <li><a href="dashboard.php">الرئيسية</a></li>
                <li><a href="cases.php">إدارة القضايا</a></li>
                <li><a href="consultations.php">الاستشارات</a></li>
                <li><a href="clients.php">العملاء</a></li>
                <li><a href="reports.php">التقارير</a></li>
                <li><a href="archive.php" class="active">الأرشيف</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>أرشيف القضايا</h1>
                <p>إدارة القضايا المؤرشفة والمغلقة</p>
            </div>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- القضايا المغلقة (قابلة للأرشفة) -->
            <div class="card">
                <h2>القضايا المغلقة - قابلة للأرشفة (<?php echo count($closed_cases); ?>)</h2>
                
                <?php if (count($closed_cases) > 0): ?>
                    <div class="cases-grid">
                        <?php foreach ($closed_cases as $case): ?>
                            <div class="case-card">
                                <div class="case-header">
                                    <h4><?php echo htmlspecialchars($case['title']); ?></h4>
                                    <span class="status-closed">مغلقة</span>
                                </div>
                                
                                <div class="case-info">
                                    <p><strong>العميل:</strong> <?php echo htmlspecialchars($case['client_name']); ?></p>
                                    <p><strong>تاريخ الإنشاء:</strong> <?php echo date('Y-m-d', strtotime($case['created_at'])); ?></p>
                                    <p><strong>آخر تحديث:</strong> <?php echo date('Y-m-d', strtotime($case['updated_at'])); ?></p>
                                    <p><strong>عدد الرسائل:</strong> <?php echo $case['message_count']; ?></p>
                                </div>
                                
                                <div class="case-details">
                                    <?php echo nl2br(htmlspecialchars(substr($case['details'], 0, 150))); ?>
                                    <?php if (strlen($case['details']) > 150): ?>...<?php endif; ?>
                                </div>
                                
                                <div class="case-actions">
                                    <a href="../chat.php?case_id=<?php echo $case['id']; ?>" class="btn-small">عرض المحادثة</a>
                                    
                                    <form method="POST" style="display: inline;" 
                                          onsubmit="return confirm('هل أنت متأكد من أرشفة هذه القضية؟')">
                                        <input type="hidden" name="case_id" value="<?php echo $case['id']; ?>">
                                        <button type="submit" name="archive_case" class="btn-secondary">أرشفة</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>لا توجد قضايا مغلقة قابلة للأرشفة.</p>
                <?php endif; ?>
            </div>
            
            <!-- القضايا المؤرشفة -->
            <div class="card">
                <h2>القضايا المؤرشفة (<?php echo count($archived_cases); ?>)</h2>
                
                <?php if (count($archived_cases) > 0): ?>
                    <div class="cases-grid">
                        <?php foreach ($archived_cases as $case): ?>
                            <div class="case-card archived">
                                <div class="case-header">
                                    <h4><?php echo htmlspecialchars($case['title']); ?></h4>
                                    <span class="status-archived">مؤرشفة</span>
                                </div>
                                
                                <div class="case-info">
                                    <p><strong>العميل:</strong> <?php echo htmlspecialchars($case['client_name']); ?></p>
                                    <p><strong>تاريخ الإنشاء:</strong> <?php echo date('Y-m-d', strtotime($case['created_at'])); ?></p>
                                    <p><strong>تاريخ الأرشفة:</strong> <?php echo date('Y-m-d', strtotime($case['updated_at'])); ?></p>
                                    <p><strong>عدد الرسائل:</strong> <?php echo $case['message_count']; ?></p>
                                </div>
                                
                                <div class="case-details">
                                    <?php echo nl2br(htmlspecialchars(substr($case['details'], 0, 150))); ?>
                                    <?php if (strlen($case['details']) > 150): ?>...<?php endif; ?>
                                </div>
                                
                                <div class="case-actions">
                                    <a href="../chat.php?case_id=<?php echo $case['id']; ?>" class="btn-small">عرض المحادثة</a>
                                    
                                    <form method="POST" style="display: inline;" 
                                          onsubmit="return confirm('هل أنت متأكد من استعادة هذه القضية من الأرشيف؟')">
                                        <input type="hidden" name="case_id" value="<?php echo $case['id']; ?>">
                                        <button type="submit" name="restore_case" class="btn-primary">استعادة</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>لا توجد قضايا مؤرشفة.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
