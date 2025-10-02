<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('lawyer')) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// معالجة الرد على الاستشارة
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['answer_consultation'])) {
    $consultation_id = $_POST['consultation_id'];
    $answer = $_POST['answer'];
    
    if (!empty($answer)) {
        $stmt = $pdo->prepare("UPDATE consultations SET answer = ?, status = 'answered' WHERE id = ? AND lawyer_id = ?");
        if ($stmt->execute([$answer, $consultation_id, $user_id])) {
            // إرسال إشعار بالبريد الإلكتروني للعميل
            require_once '../email_config.php';
            $client_stmt = $pdo->prepare("SELECT u.name, u.email FROM consultations c 
                                         JOIN users u ON c.client_id = u.id 
                                         WHERE c.id = ?");
            $client_stmt->execute([$consultation_id]);
            $client = $client_stmt->fetch();
            
            if ($client) {
                notifyClientConsultationAnswer($client['email'], $client['name'], $_SESSION['user_name']);
            }
            
            $success = 'تم إرسال الرد بنجاح';
        } else {
            $error = 'حدث خطأ أثناء إرسال الرد';
        }
    } else {
        $error = 'يرجى كتابة الرد';
    }
}

// جلب الاستشارات
$filter = $_GET['filter'] ?? 'all';
$where_clause = "WHERE co.lawyer_id = ?";
$params = [$user_id];

if ($filter !== 'all') {
    $where_clause .= " AND co.status = ?";
    $params[] = $filter;
}

$stmt = $pdo->prepare("SELECT co.*, u.name as client_name, u.email as client_email 
                       FROM consultations co 
                       JOIN users u ON co.client_id = u.id 
                       $where_clause ORDER BY co.created_at DESC");
$stmt->execute($params);
$consultations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الاستشارات - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>مرحباً <?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
            <ul>
                <li><a href="dashboard.php">الرئيسية</a></li>
                <li><a href="cases.php">إدارة القضايا</a></li>
                <li><a href="consultations.php" class="active">الاستشارات</a></li>
                <li><a href="clients.php">العملاء</a></li>
                <li><a href="reports.php">التقارير</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>إدارة الاستشارات</h1>
                <p>الرد على الاستشارات القانونية من العملاء</p>
            </div>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- فلاتر الاستشارات -->
            <div class="card">
                <div class="filters">
                    <h3>تصفية الاستشارات:</h3>
                    <div class="filter-buttons">
                        <a href="consultations.php?filter=all" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">الكل</a>
                        <a href="consultations.php?filter=pending" class="filter-btn <?php echo $filter === 'pending' ? 'active' : ''; ?>">في الانتظار</a>
                        <a href="consultations.php?filter=answered" class="filter-btn <?php echo $filter === 'answered' ? 'active' : ''; ?>">تم الرد</a>
                    </div>
                </div>
            </div>
            
            <!-- قائمة الاستشارات -->
            <div class="card">
                <h2>الاستشارات (<?php echo count($consultations); ?>)</h2>
                
                <?php if (count($consultations) > 0): ?>
                    <div class="consultations-list">
                        <?php foreach ($consultations as $consultation): ?>
                            <div class="consultation-item">
                                <div class="consultation-header">
                                    <h4>استشارة من <?php echo htmlspecialchars($consultation['client_name']); ?></h4>
                                    <div class="consultation-meta">
                                        <span class="consultation-status status-<?php echo $consultation['status']; ?>">
                                            <?php echo $consultation['status'] == 'pending' ? 'في الانتظار' : 'تم الرد'; ?>
                                        </span>
                                        <span class="consultation-date"><?php echo date('Y-m-d H:i', strtotime($consultation['created_at'])); ?></span>
                                    </div>
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
                                <?php else: ?>
                                    <!-- نموذج الرد -->
                                    <div class="answer-form">
                                        <form method="POST">
                                            <input type="hidden" name="consultation_id" value="<?php echo $consultation['id']; ?>">
                                            <div class="form-group">
                                                <label for="answer_<?php echo $consultation['id']; ?>">الرد على الاستشارة:</label>
                                                <textarea id="answer_<?php echo $consultation['id']; ?>" name="answer" rows="4" required 
                                                          placeholder="اكتب ردك على الاستشارة هنا..."></textarea>
                                            </div>
                                            <button type="submit" name="answer_consultation" class="btn-primary">إرسال الرد</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="consultation-client-info">
                                    <small>بريد العميل: <?php echo htmlspecialchars($consultation['client_email']); ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>لا توجد استشارات تطابق الفلتر المحدد.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
