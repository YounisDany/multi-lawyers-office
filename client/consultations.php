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

// معالجة طلب استشارة جديدة
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lawyer_id = $_POST['lawyer_id'];
    $question = $_POST['question'];
    
    if (!empty($lawyer_id) && !empty($question)) {
        $stmt = $pdo->prepare("INSERT INTO consultations (lawyer_id, client_id, question) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$lawyer_id, $user_id, $question])) {
            // إرسال إشعار بالبريد الإلكتروني للمحامي
            require_once '../email_config.php';
            $lawyer_stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
            $lawyer_stmt->execute([$lawyer_id]);
            $lawyer = $lawyer_stmt->fetch();
            
            if ($lawyer) {
                notifyLawyerNewConsultation($lawyer['email'], $lawyer['name'], $_SESSION['user_name']);
            }
            
            $success = 'تم إرسال طلب الاستشارة بنجاح. سيتم الرد عليك قريباً.';
        } else {
            $error = 'حدث خطأ أثناء إرسال الاستشارة';
        }
    } else {
        $error = 'يرجى ملء جميع الحقول';
    }
}

// جلب الاستشارات السابقة
$stmt = $pdo->prepare("SELECT co.*, u.name as lawyer_name FROM consultations co 
                       JOIN users u ON co.lawyer_id = u.id 
                       WHERE co.client_id = ? ORDER BY co.created_at DESC");
$stmt->execute([$user_id]);
$consultations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الاستشارات - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>مرحباً <?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
            <ul>
                <li><a href="dashboard.php">الرئيسية</a></li>
                <li><a href="new_case.php">قضية جديدة</a></li>
                <li><a href="consultations.php" class="active">الاستشارات</a></li>
                <li><a href="messages.php">الرسائل</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>الاستشارات القانونية</h1>
                <p>اطلب استشارة قانونية أو تابع استشاراتك السابقة</p>
            </div>
            
            <div class="card">
                <h2>طلب استشارة جديدة</h2>
                
                <?php if ($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success-message"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" class="consultation-form">
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
                        <label for="question">سؤالك القانوني:</label>
                        <textarea id="question" name="question" rows="6" required 
                                  placeholder="اكتب سؤالك القانوني بشكل واضح ومفصل..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">إرسال الاستشارة</button>
                </form>
            </div>
            
            <div class="card">
                <h2>استشاراتك السابقة</h2>
                
                <?php if (count($consultations) > 0): ?>
                    <div class="consultations-list">
                        <?php foreach ($consultations as $consultation): ?>
                            <div class="consultation-item">
                                <div class="consultation-header">
                                    <h4>استشارة مع <?php echo htmlspecialchars($consultation['lawyer_name']); ?></h4>
                                    <span class="consultation-status status-<?php echo $consultation['status']; ?>">
                                        <?php echo $consultation['status'] == 'pending' ? 'في الانتظار' : 'تم الرد'; ?>
                                    </span>
                                </div>
                                
                                <div class="consultation-question">
                                    <strong>السؤال:</strong>
                                    <p><?php echo nl2br(htmlspecialchars($consultation['question'])); ?></p>
                                </div>
                                
                                <?php if ($consultation['answer']): ?>
                                    <div class="consultation-answer">
                                        <strong>الإجابة:</strong>
                                        <p><?php echo nl2br(htmlspecialchars($consultation['answer'])); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="consultation-date">
                                    تاريخ الإرسال: <?php echo date('Y-m-d H:i', strtotime($consultation['created_at'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>لا توجد استشارات سابقة.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
