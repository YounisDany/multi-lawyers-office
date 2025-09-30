<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('lawyer')) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// معالجة تحديث حالة القضية
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $case_id = $_POST['case_id'];
    $new_status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE cases SET status = ? WHERE id = ? AND lawyer_id = ?");
    if ($stmt->execute([$new_status, $case_id, $user_id])) {
        $success = 'تم تحديث حالة القضية بنجاح';
    } else {
        $error = 'حدث خطأ أثناء تحديث حالة القضية';
    }
}

// جلب جميع القضايا
$filter = $_GET['filter'] ?? 'all';
$where_clause = "WHERE c.lawyer_id = ?";
$params = [$user_id];

if ($filter !== 'all') {
    $where_clause .= " AND c.status = ?";
    $params[] = $filter;
}

$stmt = $pdo->prepare("SELECT c.*, u.name as client_name, u.email as client_email 
                       FROM cases c 
                       JOIN users u ON c.client_id = u.id 
                       $where_clause ORDER BY c.created_at DESC");
$stmt->execute($params);
$cases = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة القضايا - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>مرحباً <?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
            <ul>
                <li><a href="dashboard.php">الرئيسية</a></li>
                <li><a href="cases.php" class="active">إدارة القضايا</a></li>
                <li><a href="consultations.php">الاستشارات</a></li>
                <li><a href="clients.php">العملاء</a></li>
                <li><a href="reports.php">التقارير</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>إدارة القضايا</h1>
                <p>عرض وإدارة جميع القضايا المسندة إليك</p>
            </div>
            
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- فلاتر القضايا -->
            <div class="card">
                <div class="filters">
                    <h3>تصفية القضايا:</h3>
                    <div class="filter-buttons">
                        <a href="cases.php?filter=all" class="filter-btn <?php echo $filter === 'all' ? 'active' : ''; ?>">الكل</a>
                        <a href="cases.php?filter=new" class="filter-btn <?php echo $filter === 'new' ? 'active' : ''; ?>">جديدة</a>
                        <a href="cases.php?filter=in_progress" class="filter-btn <?php echo $filter === 'in_progress' ? 'active' : ''; ?>">قيد المعالجة</a>
                        <a href="cases.php?filter=closed" class="filter-btn <?php echo $filter === 'closed' ? 'active' : ''; ?>">مغلقة</a>
                        <a href="cases.php?filter=archived" class="filter-btn <?php echo $filter === 'archived' ? 'active' : ''; ?>">مؤرشفة</a>
                    </div>
                </div>
            </div>
            
            <!-- قائمة القضايا -->
            <div class="card">
                <h2>القضايا (<?php echo count($cases); ?>)</h2>
                
                <?php if (count($cases) > 0): ?>
                    <div class="cases-grid">
                        <?php foreach ($cases as $case): ?>
                            <div class="case-card">
                                <div class="case-header">
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
                                
                                <div class="case-info">
                                    <p><strong>العميل:</strong> <?php echo htmlspecialchars($case['client_name']); ?></p>
                                    <p><strong>البريد:</strong> <?php echo htmlspecialchars($case['client_email']); ?></p>
                                    <p><strong>تاريخ الإنشاء:</strong> <?php echo date('Y-m-d H:i', strtotime($case['created_at'])); ?></p>
                                </div>
                                
                                <div class="case-details">
                                    <p><?php echo htmlspecialchars(substr($case['details'], 0, 150)) . '...'; ?></p>
                                </div>
                                
                                <div class="case-actions">
                                    <a href="case_details.php?id=<?php echo $case['id']; ?>" class="btn-small">عرض التفاصيل</a>
                                    <a href="chat.php?case_id=<?php echo $case['id']; ?>" class="btn-small">محادثة</a>
                                    
                                    <!-- تحديث الحالة -->
                                    <form method="POST" class="status-form" style="display: inline;">
                                        <input type="hidden" name="case_id" value="<?php echo $case['id']; ?>">
                                        <select name="status" onchange="this.form.submit()">
                                            <option value="new" <?php echo $case['status'] === 'new' ? 'selected' : ''; ?>>جديدة</option>
                                            <option value="in_progress" <?php echo $case['status'] === 'in_progress' ? 'selected' : ''; ?>>قيد المعالجة</option>
                                            <option value="closed" <?php echo $case['status'] === 'closed' ? 'selected' : ''; ?>>مغلقة</option>
                                            <option value="archived" <?php echo $case['status'] === 'archived' ? 'selected' : ''; ?>>مؤرشفة</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>لا توجد قضايا تطابق الفلتر المحدد.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
