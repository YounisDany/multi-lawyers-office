<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];
$case_id = $_GET['case_id'] ?? 0;

// التحقق من صحة القضية وصلاحية الوصول
$stmt = $pdo->prepare("SELECT c.*, 
                       client.name as client_name, 
                       lawyer.name as lawyer_name 
                       FROM cases c 
                       JOIN users client ON c.client_id = client.id 
                       JOIN users lawyer ON c.lawyer_id = lawyer.id 
                       WHERE c.id = ? AND (c.client_id = ? OR c.lawyer_id = ?)");
$stmt->execute([$case_id, $user_id, $user_id]);
$case = $stmt->fetch();

if (!$case) {
    redirect($user_role . '/dashboard.php');
}

$success = '';
$error = '';

// معالجة إرسال رسالة جديدة
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'] ?? '';
    $attachment = null;
    
    // معالجة رفع الملف
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $upload_dir = 'uploads/';
        $file_name = time() . '_' . basename($_FILES['attachment']['name']);
        $upload_path = $upload_dir . $file_name;
        
        // التحقق من نوع الملف
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt'];
        $file_extension = strtolower(pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_types)) {
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $upload_path)) {
                $attachment = $file_name;
            } else {
                $error = 'فشل في رفع الملف';
            }
        } else {
            $error = 'نوع الملف غير مدعوم';
        }
    }
    
    // إدراج الرسالة في قاعدة البيانات
    if (!$error && (!empty($message) || $attachment)) {
        $stmt = $pdo->prepare("INSERT INTO messages (case_id, sender_id, message, attachment) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$case_id, $user_id, $message, $attachment])) {
            // إرسال إشعار بالبريد الإلكتروني للطرف الآخر
            require_once 'email_config.php';
            
            // تحديد المستقبل (العميل أو المحامي)
            $recipient_id = ($user_id == $case['client_id']) ? $case['lawyer_id'] : $case['client_id'];
            $recipient_stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
            $recipient_stmt->execute([$recipient_id]);
            $recipient = $recipient_stmt->fetch();
            
            if ($recipient) {
                notifyNewMessage($recipient['email'], $recipient['name'], $_SESSION['user_name'], $case['title']);
            }
            
            $success = 'تم إرسال الرسالة بنجاح';
            // إعادة تحميل الصفحة لعرض الرسالة الجديدة
            header("Location: chat.php?case_id=$case_id");
            exit();
        } else {
            $error = 'فشل في إرسال الرسالة';
        }
    } elseif (!$error) {
        $error = 'يرجى كتابة رسالة أو إرفاق ملف';
    }
}

// جلب جميع الرسائل
$stmt = $pdo->prepare("SELECT m.*, u.name as sender_name, u.role as sender_role 
                       FROM messages m 
                       JOIN users u ON m.sender_id = u.id 
                       WHERE m.case_id = ? ORDER BY m.created_at ASC");
$stmt->execute([$case_id]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <?php include 'includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="assets/css/mobile-ionic.css">
    <title>محادثة القضية - منصة مكاتب المحاماة</title>
    <style>
        ion-content {
            --background: #f8f9fa;
        }
        .chat-messages {
            padding: 10px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            height: calc(100vh - 180px); /* Adjust based on header/footer height */
        }
        .message-bubble {
            background: var(--ion-color-light);
            padding: 10px 15px;
            border-radius: 15px;
            margin-bottom: 10px;
            max-width: 80%;
            position: relative;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .message-bubble.sent .message-header {
            color: rgba(255,255,255,0.8);
        }
        .message-bubble.received .message-header {
            color: rgba(255,255,255,0.8);
        }
        .message-content {
            font-size: 0.9em;
            line-height: 1.4;
        }
        .message-attachment a {
            color: inherit;
            text-decoration: underline;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .message-input-area {
            display: flex;
            align-items: center;
            padding: 10px;
            background: white;
            border-top: 1px solid #eee;
            position: sticky;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        }
        .message-input-area ion-textarea {
            --padding-start: 10px;
            --padding-end: 10px;
            --padding-top: 10px;
            --padding-bottom: 10px;
            --background: #f0f2f5;
            border-radius: 20px;
            margin-right: 10px;
            flex-grow: 1;
        }
        .file-upload-button {
            margin-right: 10px;
        }
        .file-input-hidden {
            display: none;
        }
    </style>
</head>
<body>
    <ion-app>
        <ion-header>
            <ion-toolbar color="primary">
                <ion-buttons slot="start">
                    <ion-back-button default-href="<?php echo $user_role; ?>/dashboard.php"></ion-back-button>
                </ion-buttons>
                <ion-title><?php echo htmlspecialchars($case['title']); ?></ion-title>
                <ion-buttons slot="end">
                    <ion-button href="<?php echo $user_role; ?>/case_details.php?id=<?php echo $case_id; ?>">
                        <ion-icon slot="icon-only" name="information-circle-outline"></ion-icon>
                    </ion-button>
                </ion-buttons>
            </ion-toolbar>
        </ion-header>

        <ion-content class="ion-padding">
            <div class="chat-messages" id="chatMessages">
                <?php if (count($messages) > 0): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="message-bubble <?php echo $message['sender_id'] == $user_id ? 'sent' : 'received'; ?>">
                            <div class="message-header">
                                <strong><?php echo htmlspecialchars($message['sender_name']); ?></strong>
                                <span><?php echo date('H:i', strtotime($message['created_at'])); ?></span>
                            </div>
                            <?php if ($message['message']): ?>
                                <div class="message-content">
                                    <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($message['attachment']): ?>
                                <div class="message-attachment">
                                    <a href="uploads/<?php echo htmlspecialchars($message['attachment']); ?>" target="_blank">
                                        <ion-icon name="attach"></ion-icon> <?php echo htmlspecialchars($message['attachment']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="chatbubbles-outline"></ion-icon>
                        <h3>ابدأ المحادثة!</h3>
                        <p>لا توجد رسائل بعد. أرسل رسالتك الأولى.</p>
                    </div>
                <?php endif; ?>
            </div>
        </ion-content>

        <ion-footer>
            <ion-toolbar>
                <form method="POST" enctype="multipart/form-data" class="message-input-area">
                    <input type="file" id="attachment" name="attachment" class="file-input-hidden" 
                           accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt" onchange="this.form.submit()">
                    <ion-button onclick="document.getElementById('attachment').click()" fill="clear" class="file-upload-button">
                        <ion-icon slot="icon-only" name="attach-outline"></ion-icon>
                    </ion-button>
                    <ion-textarea name="message" placeholder="اكتب رسالتك هنا..." rows="1" auto-grow="true"></ion-textarea>
                    <ion-button type="submit" color="primary">
                        <ion-icon slot="icon-only" name="send"></ion-icon>
                    </ion-button>
                </form>
            </ion-toolbar>
            <?php include 'includes/bottom_nav.php'; ?>
        </ion-footer>
    </ion-app>
    
    <script>
        // تمرير تلقائي لأسفل عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.getElementById('chatMessages');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });
        
        // تحديث الرسائل كل 10 ثوانٍ (يمكن تعديلها أو استخدام WebSockets لتحسين الأداء)
        // setInterval(function() {
        //     location.reload();
        // }, 10000);
    </script>
</body>
</html>
