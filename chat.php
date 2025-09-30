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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>محادثة القضية - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .chat-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            min-height: 100vh;
        }
        
        .chat-header {
            background: #667eea;
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 0;
        }
        
        .chat-messages {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background: #f8f9fa;
            border: 1px solid #e1e5e9;
        }
        
        .chat-form {
            background: white;
            padding: 20px;
            border: 1px solid #e1e5e9;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }
        
        .message-input {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        
        .message-input textarea {
            flex: 1;
            resize: none;
            min-height: 60px;
        }
        
        .file-input {
            margin-bottom: 10px;
        }
        
        .send-button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            height: fit-content;
        }
        
        .send-button:hover {
            background: #5a67d8;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h2><?php echo htmlspecialchars($case['title']); ?></h2>
            <p>محادثة بين <?php echo htmlspecialchars($case['client_name']); ?> و <?php echo htmlspecialchars($case['lawyer_name']); ?></p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="chat-messages" id="chatMessages">
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
                                <a href="uploads/<?php echo htmlspecialchars($message['attachment']); ?>" target="_blank">
                                    📎 <?php echo htmlspecialchars($message['attachment']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: #6c757d;">لا توجد رسائل بعد. ابدأ المحادثة!</p>
            <?php endif; ?>
        </div>
        
        <div class="chat-form">
            <form method="POST" enctype="multipart/form-data">
                <div class="file-input">
                    <label for="attachment">إرفاق ملف (اختياري):</label>
                    <input type="file" id="attachment" name="attachment" 
                           accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.txt">
                    <small>الملفات المدعومة: JPG, PNG, PDF, DOC, TXT (حد أقصى 5MB)</small>
                </div>
                
                <div class="message-input">
                    <textarea name="message" placeholder="اكتب رسالتك هنا..." required></textarea>
                    <button type="submit" class="send-button">إرسال</button>
                </div>
            </form>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="<?php echo $user_role; ?>/dashboard.php" class="btn-secondary">العودة للرئيسية</a>
        </div>
    </div>
    
    <script>
        // تمرير تلقائي لأسفل عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        });
        
        // تحديث الرسائل كل 5 ثوانٍ
        setInterval(function() {
            location.reload();
        }, 5000);
    </script>
</body>
</html>
