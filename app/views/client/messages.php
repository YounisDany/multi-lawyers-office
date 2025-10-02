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
    <?php include '../includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="../assets/css/mobile-ionic.css">
    <title>الرسائل - منصة مكاتب المحاماة</title>
</head>
<body>
    <ion-app>
        <ion-header>
            <ion-toolbar color="primary">
                <ion-buttons slot="start">
                    <ion-back-button default-href="dashboard.php"></ion-back-button>
                </ion-buttons>
                <ion-title>الرسائل والمحادثات</ion-title>
            </ion-toolbar>
        </ion-header>

        <ion-content class="ion-padding">
            <div class="content-section">
                <h2 class="section-title">محادثات القضايا</h2>
                
                <?php if (count($cases_with_messages) > 0): ?>
                    <ion-list>
                        <?php foreach ($cases_with_messages as $case): ?>
                            <ion-item-sliding>
                                <ion-item detail href="../chat.php?case_id=<?php echo $case['id']; ?>">
                                    <ion-icon name="chatbubbles-outline" slot="start"></ion-icon>
                                    <ion-label>
                                        <h3><?php echo htmlspecialchars($case['title']); ?></h3>
                                        <p>المحامي: <?php echo htmlspecialchars($case['lawyer_name']); ?></p>
                                        <p>الرسائل: <?php echo $case['message_count']; ?></p>
                                        <?php if ($case['last_message_time']): ?>
                                            <p class="ion-text-right">آخر رسالة: <?php echo date('Y-m-d H:i', strtotime($case['last_message_time'])); ?></p>
                                        <?php endif; ?>
                                    </ion-label>
                                    <ion-note slot="end">
                                        <span class="status-badge status-<?php echo $case['status']; ?>">
                                            <?php 
                                            $status_names = [
                                                'new' => 'جديدة',
                                                'in_progress' => 'قيد المعالجة',
                                                'closed' => 'مغلقة',
                                                'archived' => 'مؤرشفة'
                                            ];
                                            echo $status_names[$case['status']] ?? $case['status'];
                                            ?>
                                        </span>
                                    </ion-note>
                                </ion-item>
                                <ion-item-options side="end">
                                    <ion-item-option href="../chat.php?case_id=<?php echo $case['id']; ?>" color="primary">
                                        <ion-icon slot="icon-only" name="chatbubbles"></ion-icon>
                                    </ion-item-option>
                                    <ion-item-option href="case_details.php?id=<?php echo $case['id']; ?>">
                                        <ion-icon slot="icon-only" name="eye"></ion-icon>
                                    </ion-item-option>
                                </ion-item-options>
                            </ion-item-sliding>
                        <?php endforeach; ?>
                    </ion-list>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                        <h3>لا توجد محادثات بعد.</h3>
                        <p>عندما تقوم برفع قضية جديدة، ستتمكن من التواصل مع المحامي هنا.</p>
                        <ion-button expand="block" href="new_case.php" class="ion-margin-top">
                            <ion-icon slot="start" name="add-circle"></ion-icon>
                            رفع قضية جديدة
                        </ion-button>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php include '../includes/bottom_nav.php'; ?>
        </ion-content>
    </ion-app>
</body>
</html>
