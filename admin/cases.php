<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('admin')) {
    redirect('../login.php');
}

// جلب جميع القضايا مع تفاصيل المحامي والعميل
$filter = $_GET['filter'] ?? 'all';
$where_clause = "";
$params = [];

if ($filter !== 'all') {
    $where_clause = "WHERE c.status = ?";
    $params[] = $filter;
}

$stmt = $pdo->prepare("SELECT c.*, 
                       lawyer.name as lawyer_name, 
                       client.name as client_name,
                       (SELECT COUNT(*) FROM messages WHERE case_id = c.id) as message_count
                       FROM cases c 
                       JOIN users lawyer ON c.lawyer_id = lawyer.id 
                       JOIN users client ON c.client_id = client.id 
                       $where_clause 
                       ORDER BY c.created_at DESC");
$stmt->execute($params);
$cases = $stmt->fetchAll();

// إحصائيات القضايا
$stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM cases GROUP BY status");
$stmt->execute();
$status_stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مراقبة القضايا - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>لوحة الإدارة</h3>
            <ul>
                <li><a href="dashboard.php">الرئيسية</a></li>
                <li><a href="lawyers.php">إدارة المحامين</a></li>
                <li><a href="clients.php">إدارة العملاء</a></li>
                <li><a href="cases.php" class="active">مراقبة القضايا</a></li>
                <li><a href="reports.php">التقارير</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>مراقبة القضايا</h1>
                <p>عرض ومراقبة جميع القضايا في المنصة</p>
            </div>
            
            <!-- إحصائيات القضايا -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $status_stats['new'] ?? 0; ?></div>
                    <div class="stat-label">قضايا جديدة</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $status_stats['in_progress'] ?? 0; ?></div>
                    <div class="stat-label">قيد المعالجة</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $status_stats['closed'] ?? 0; ?></div>
                    <div class="stat-label">مغلقة</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $status_stats['archived'] ?? 0; ?></div>
                    <div class="stat-label">مؤرشفة</div>
                </div>
            </div>
            
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
                    <table class="table">
                        <thead>
                            <tr>
                                <th>عنوان القضية</th>
                                <th>المحامي</th>
                                <th>العميل</th>
                                <th>الحالة</th>
                                <th>الرسائل</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cases as $case): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($case['title']); ?></td>
                                    <td><?php echo htmlspecialchars($case['lawyer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($case['client_name']); ?></td>
                                    <td>
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
                                    </td>
                                    <td><?php echo $case['message_count']; ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($case['created_at'])); ?></td>
                                    <td>
                                        <a href="case_details.php?id=<?php echo $case['id']; ?>" class="btn-small">عرض</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="empty-state">
                        <p>لا توجد قضايا تطابق الفلتر المحدد.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
