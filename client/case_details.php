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
    <?php include '../includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="../assets/css/mobile-ionic.css">
    <title>تفاصيل القضية - منصة مكاتب المحاماة</title>
    <style>
        .message-bubble {
            background: var(--ion-color-light);
            padding: 10px 15px;
            border-radius: 15px;
            margin-bottom: 10px;
            max-width: 80%;
            position: relative;
        }
        .message-bubble.sent {
            background: var(--ion-color-primary);
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 2px;
        }
        .message-bubble.received {
            background: var(--ion-color-tertiary);
            color: white;
            margin-right: auto;
            border-bottom-left-radius: 2px;
        }
        .message-header {
            font-size: 0.8em;
            opacity: 0.8;
            margin-bottom: 5px;
        }
        .message-content {
            font-size: 0.9em;
            line-height: 1.4;
        }
        .message-attachment a {
            color: inherit;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <ion-app>
        <ion-header>
            <ion-toolbar color="primary">
                <ion-buttons slot="start">
                    <ion-back-button default-href="dashboard.php"></ion-back-button>
                </ion-buttons>
                <ion-title>تفاصيل القضية</ion-title>
            </ion-toolbar>
        </ion-header>

        <ion-content class="ion-padding">
            <ion-card>
                <ion-card-header>
                    <ion-card-title><?php echo htmlspecialchars($case['title']); ?></ion-card-title>
                    <ion-card-subtitle>المحامي: <?php echo htmlspecialchars($case['lawyer_name']); ?></ion-card-subtitle>
                </ion-card-header>
                <ion-card-content>
                    <ion-list lines="none">
                        <ion-item>
                            <ion-label><strong>الحالة:</strong></ion-label>
                            <ion-badge slot="end" class="status-badge status-<?php echo $case['status']; ?>">
                                <?php 
                                $status_names = [
                                    'new' => 'جديدة',
                                    'in_progress' => 'قيد المعالجة',
                                    'closed' => 'مغلقة',
                                    'archived' => 'مؤرشفة'
                                ];
                                echo $status_names[$case['status']] ?? $case['status'];
                                ?>
                            </ion-badge>
                        </ion-item>
                        <ion-item>
                            <ion-label><strong>تاريخ الإنشاء:</strong></ion-label>
                            <ion-note slot="end"><?php echo date('Y-m-d H:i', strtotime($case['created_at'])); ?></ion-note>
                        </ion-item>
                    </ion-list>
                    <ion-item>
                        <ion-label position="stacked"><strong>تفاصيل القضية:</strong></ion-label>
                        <ion-text><p><?php echo nl2br(htmlspecialchars($case['details'])); ?></p></ion-text>
                    </ion-item>
                </ion-card-content>
            </ion-card>

            <ion-card>
                <ion-card-header>
                    <ion-card-title>المحادثات والتحديثات</ion-card-title>
                </ion-card-header>
                <ion-card-content>
                    <?php if (count($messages) > 0): ?>
                        <?php foreach ($messages as $message): ?>
                            <div class="message-bubble <?php echo $message['sender_id'] == $user_id ? 'sent' : 'received'; ?>">
                                <div class="message-header">
                                    <strong><?php echo htmlspecialchars($message['sender_name']); ?></strong>
                                    <span><?php echo date('Y-m-d H:i', strtotime($message['created_at'])); ?></span>
                                </div>
                                <?php if ($message['message']): ?>
                                    <div class="message-content">
                                        <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($message['attachment']): ?>
                                    <div class="message-attachment">
                                        <a href="../uploads/<?php echo htmlspecialchars($message['attachment']); ?>" target="_blank">
                                            <ion-icon name="attach"></ion-icon> <?php echo htmlspecialchars($message['attachment']); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <ion-text color="medium"><p class="ion-text-center">لا توجد رسائل بعد.</p></ion-text>
                    <?php endif; ?>
                    <ion-button expand="block" href="../chat.php?case_id=<?php echo $case['id']; ?>" class="ion-margin-top">
                        <ion-icon slot="start" name="send"></ion-icon>
                        إرسال رسالة
                    </ion-button>
                </ion-card-content>
            </ion-card>

            <?php include '../includes/bottom_nav.php'; ?>
        </ion-content>
    </ion-app>
</body>
</html>
