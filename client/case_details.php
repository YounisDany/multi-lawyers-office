<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('client')) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$case_id = $_GET['id'] ?? 0;

// جلب تفاصيل القضية
$stmt = $pdo->prepare("SELECT c.*, u.name as lawyer_name FROM cases c 
                       JOIN users u ON c.lawyer_id = u.id 
                       WHERE c.id = ? AND c.client_id = ?");
$stmt->execute([$case_id, $user_id]);
$case = $stmt->fetch();

if (!$case) {
    redirect('dashboard.php');
}

// جلب الرسائل المتعلقة بالقضية
$stmt = $pdo->prepare("SELECT m.*, u.name as sender_name FROM messages m 
                       JOIN users u ON m.sender_id = u.id 
                       WHERE m.case_id = ? ORDER BY m.created_at ASC");
$stmt->execute([$case_id]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل القضية - منصة مكاتب المحاماة</title>
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
                <li><a href="messages.php">الرسائل</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>تفاصيل القضية</h1>
                <p><?php echo htmlspecialchars($case['title']); ?></p>
            </div>
            
            <div class="card">
                <div class="case-info">
                    <div class="info-row">
                        <strong>المحامي المسؤول:</strong>
                        <span><?php echo htmlspecialchars($case['lawyer_name']); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <strong>حالة القضية:</strong>
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
                    
                    <div class="info-row">
                        <strong>تاريخ الإنشاء:</strong>
                        <span><?php echo date('Y-m-d H:i', strtotime($case['created_at'])); ?></span>
                    </div>
                </div>
                
                <div class="case-details">
                    <h3>تفاصيل القضية:</h3>
                    <p><?php echo nl2br(htmlspecialchars($case['details'])); ?></p>
                </div>
            </div>
            
            <div class="card">
                <h2>المحادثات والتحديثات</h2>
                
                <div class="messages-container">
                    <?php if (count($messages) > 0): ?>
                        <?php foreach ($messages as $message): ?>
                            <div class="message <?php echo $message['sender_id'] == $user_id ? 'message-sent' : 'message-received'; ?>">
                                <div class="message-header">
                                    <strong><?php echo htmlspecialchars($message['sender_name']); ?></strong>
                                    <span class="message-time"><?php echo date('Y-m-d H:i', strtotime($message['created_at'])); ?></span>
                                </div>
                                
                                <?php if ($message['message']): ?>
                                    <div class="message-content">
                                        <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($message['attachment']): ?>
                                    <div class="message-attachment">
                                        <a href="../uploads/<?php echo htmlspecialchars($message['attachment']); ?>" target="_blank">
                                            📎 <?php echo htmlspecialchars($message['attachment']); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>لا توجد رسائل بعد.</p>
                    <?php endif; ?>
                </div>
                
                <div class="action-buttons">
                    <a href="chat.php?case_id=<?php echo $case['id']; ?>" class="btn-primary">إرسال رسالة</a>
                    <a href="dashboard.php" class="btn-secondary">العودة للرئيسية</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
