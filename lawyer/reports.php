<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('lawyer')) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];

// تحديد الفترة الزمنية للتقرير
$period = $_GET['period'] ?? 'month';
$date_filter = '';

switch ($period) {
    case 'week':
        $date_filter = "AND created_at >= DATE_SUB(NOW(), INTERVAL 1 WEEK)";
        break;
    case 'month':
        $date_filter = "AND created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
        break;
    case 'year':
        $date_filter = "AND created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)";
        break;
    default:
        $date_filter = "";
}

// إحصائيات القضايا
$stmt = $pdo->prepare("SELECT 
                       COUNT(*) as total_cases,
                       SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_cases,
                       SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_cases,
                       SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed_cases,
                       SUM(CASE WHEN status = 'archived' THEN 1 ELSE 0 END) as archived_cases
                       FROM cases WHERE lawyer_id = ? $date_filter");
$stmt->execute([$user_id]);
$cases_stats = $stmt->fetch();

// إحصائيات الاستشارات
$stmt = $pdo->prepare("SELECT 
                       COUNT(*) as total_consultations,
                       SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_consultations,
                       SUM(CASE WHEN status = 'answered' THEN 1 ELSE 0 END) as answered_consultations
                       FROM consultations WHERE lawyer_id = ? $date_filter");
$stmt->execute([$user_id]);
$consultations_stats = $stmt->fetch();

// إحصائيات الرسائل
$stmt = $pdo->prepare("SELECT COUNT(*) as total_messages 
                       FROM messages m 
                       JOIN cases c ON m.case_id = c.id 
                       WHERE c.lawyer_id = ? AND m.sender_id = ? $date_filter");
$stmt->execute([$user_id, $user_id]);
$messages_sent = $stmt->fetchColumn();

// العملاء الفريدون
$stmt = $pdo->prepare("SELECT COUNT(DISTINCT client_id) as unique_clients 
                       FROM cases WHERE lawyer_id = ? $date_filter");
$stmt->execute([$user_id]);
$unique_clients = $stmt->fetchColumn();

// أحدث القضايا
$stmt = $pdo->prepare("SELECT c.*, u.name as client_name 
                       FROM cases c 
                       JOIN users u ON c.client_id = u.id 
                       WHERE c.lawyer_id = ? $date_filter 
                       ORDER BY c.created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$recent_cases = $stmt->fetchAll();

// توزيع القضايا حسب الحالة (للرسم البياني)
$stmt = $pdo->prepare("SELECT status, COUNT(*) as count 
                       FROM cases WHERE lawyer_id = ? $date_filter 
                       GROUP BY status");
$stmt->execute([$user_id]);
$status_distribution = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>مرحباً <?php echo htmlspecialchars($_SESSION['user_name']); ?></h3>
            <ul>
                <li><a href="dashboard.php">الرئيسية</a></li>
                <li><a href="cases.php">إدارة القضايا</a></li>
                <li><a href="consultations.php">الاستشارات</a></li>
                <li><a href="clients.php">العملاء</a></li>
                <li><a href="reports.php" class="active">التقارير</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>تقارير الأداء</h1>
                <p>تحليل شامل لأدائك وإحصائياتك</p>
            </div>
            
            <!-- فلتر الفترة الزمنية -->
            <div class="card">
                <div class="filters">
                    <h3>الفترة الزمنية:</h3>
                    <div class="filter-buttons">
                        <a href="reports.php?period=week" class="filter-btn <?php echo $period === 'week' ? 'active' : ''; ?>">الأسبوع الماضي</a>
                        <a href="reports.php?period=month" class="filter-btn <?php echo $period === 'month' ? 'active' : ''; ?>">الشهر الماضي</a>
                        <a href="reports.php?period=year" class="filter-btn <?php echo $period === 'year' ? 'active' : ''; ?>">السنة الماضية</a>
                        <a href="reports.php?period=all" class="filter-btn <?php echo $period === 'all' ? 'active' : ''; ?>">جميع الفترات</a>
                    </div>
                </div>
            </div>
            
            <!-- الإحصائيات الرئيسية -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $cases_stats['total_cases']; ?></div>
                    <div class="stat-label">إجمالي القضايا</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $consultations_stats['total_consultations']; ?></div>
                    <div class="stat-label">إجمالي الاستشارات</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $messages_sent; ?></div>
                    <div class="stat-label">الرسائل المرسلة</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $unique_clients; ?></div>
                    <div class="stat-label">العملاء الفريدون</div>
                </div>
            </div>
            
            <!-- تفصيل القضايا -->
            <div class="card">
                <h2>تفصيل القضايا</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $cases_stats['new_cases']; ?></div>
                        <div class="stat-label">قضايا جديدة</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $cases_stats['in_progress_cases']; ?></div>
                        <div class="stat-label">قيد المعالجة</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $cases_stats['closed_cases']; ?></div>
                        <div class="stat-label">مغلقة</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $cases_stats['archived_cases']; ?></div>
                        <div class="stat-label">مؤرشفة</div>
                    </div>
                </div>
            </div>
            
            <!-- الرسم البياني -->
            <div class="card">
                <h2>توزيع القضايا حسب الحالة</h2>
                <div class="chart-container">
                    <canvas id="casesChart" width="400" height="200"></canvas>
                </div>
            </div>
            
            <!-- تفصيل الاستشارات -->
            <div class="card">
                <h2>تفصيل الاستشارات</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $consultations_stats['pending_consultations']; ?></div>
                        <div class="stat-label">في الانتظار</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $consultations_stats['answered_consultations']; ?></div>
                        <div class="stat-label">تم الرد عليها</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">
                            <?php 
                            $response_rate = $consultations_stats['total_consultations'] > 0 
                                ? round(($consultations_stats['answered_consultations'] / $consultations_stats['total_consultations']) * 100) 
                                : 0;
                            echo $response_rate . '%';
                            ?>
                        </div>
                        <div class="stat-label">معدل الاستجابة</div>
                    </div>
                </div>
            </div>
            
            <!-- أحدث القضايا -->
            <div class="card">
                <h2>أحدث القضايا</h2>
                <?php if (count($recent_cases) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>عنوان القضية</th>
                                <th>العميل</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_cases as $case): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($case['title']); ?></td>
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
                                    <td><?php echo date('Y-m-d', strtotime($case['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>لا توجد قضايا في الفترة المحددة.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // إعداد الرسم البياني
        const ctx = document.getElementById('casesChart').getContext('2d');
        const casesChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['جديدة', 'قيد المعالجة', 'مغلقة', 'مؤرشفة'],
                datasets: [{
                    data: [
                        <?php echo $status_distribution['new'] ?? 0; ?>,
                        <?php echo $status_distribution['in_progress'] ?? 0; ?>,
                        <?php echo $status_distribution['closed'] ?? 0; ?>,
                        <?php echo $status_distribution['archived'] ?? 0; ?>
                    ],
                    backgroundColor: [
                        '#007bff',
                        '#ffc107',
                        '#28a745',
                        '#6c757d'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>
