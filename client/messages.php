<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('client')) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];

// جلب القضايا التي لها رسائل
$stmt = $pdo->prepare("SELECT DISTINCT c.id, c.title, c.status, 
                       u.name as lawyer_name,
                       (SELECT COUNT(*) FROM messages WHERE case_id = c.id) as message_count,
                       (SELECT created_at FROM messages WHERE case_id = c.id ORDER BY created_at DESC LIMIT 1) as last_message_time
                       FROM cases c 
                       JOIN users u ON c.lawyer_id = u.id 
                       WHERE c.client_id = ? 
                       ORDER BY last_message_time DESC");
$stmt->execute([$user_id]);
$cases_with_messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرسائل - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>مرحباً <?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
            <ul>
                <li><a href="dashboard.php">الرئيسية</a></li>
                <li><a href="new_case.php">قضية جديدة</a></li>
                <li><a href="consultations.php">الاستشارات</a></li>
                <li><a href="messages.php" class="active">الرسائل</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>الرسائل والمحادثات</h1>
                <p>تابع محادثاتك مع المحامين حول قضاياك</p>
            </div>
            
            <div class="card">
                <h2>محادثات القضايا</h2>
                
                <?php if (count($cases_with_messages) > 0): ?>
                    <div class="messages-list">
                        <?php foreach ($cases_with_messages as $case): ?>
                            <div class="message-thread">
                                <div class="thread-header">
                                    <h4><?php echo htmlspecialchars($case['title']); ?></h4>
                                    <span class="status-<?php echo $case['status']; ?>">
                                        <?php 
                                        $status_names = [
                                            'new' => 'جديدة',
                                            'in_progress' => 'قيد المعالجة',
                                            'closed' => 'مغلقة',
                                            'archived' => 'مؤرشفة'
                                        ];
                                        echo $status_names[$case['status']];
                                        ?>
                                    </span>
                                </div>
                                
                                <div class="thread-info">
                                    <p><strong>المحامي:</strong> <?php echo htmlspecialchars($case['lawyer_name']); ?></p>
                                    <p><strong>عدد الرسائل:</strong> <?php echo $case['message_count']; ?></p>
                                    <?php if ($case['last_message_time']): ?>
                                        <p><strong>آخر رسالة:</strong> <?php echo date('Y-m-d H:i', strtotime($case['last_message_time'])); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="thread-actions">
                                    <a href="../chat.php?case_id=<?php echo $case['id']; ?>" class="btn-primary">فتح المحادثة</a>
                                    <a href="case_details.php?id=<?php echo $case['id']; ?>" class="btn-secondary">تفاصيل القضية</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <p>لا توجد محادثات بعد.</p>
                        <p>عندما تقوم برفع قضية جديدة، ستتمكن من التواصل مع المحامي هنا.</p>
                        <a href="new_case.php" class="btn-primary">رفع قضية جديدة</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
