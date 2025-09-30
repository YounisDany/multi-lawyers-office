<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('admin')) {
    redirect('../login.php');
}

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

// الإحصائيات العامة
$stmt = $pdo->prepare("SELECT 
                       (SELECT COUNT(*) FROM users WHERE role = 'lawyer' $date_filter) as total_lawyers,
                       (SELECT COUNT(*) FROM users WHERE role = 'client' $date_filter) as total_clients,
                       (SELECT COUNT(*) FROM cases WHERE 1=1 $date_filter) as total_cases,
                       (SELECT COUNT(*) FROM consultations WHERE 1=1 $date_filter) as total_consultations,
                       (SELECT COUNT(*) FROM messages WHERE 1=1 $date_filter) as total_messages");
$stmt->execute();
$general_stats = $stmt->fetch();

// إحصائيات القضايا حسب الحالة
$stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM cases WHERE 1=1 $date_filter GROUP BY status");
$stmt->execute();
$cases_by_status = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// إحصائيات الاستشارات حسب الحالة
$stmt = $pdo->prepare("SELECT status, COUNT(*) as count FROM consultations WHERE 1=1 $date_filter GROUP BY status");
$stmt->execute();
$consultations_by_status = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// أكثر المحامين نشاطاً
$stmt = $pdo->prepare("SELECT u.name, 
                       COUNT(DISTINCT c.id) as cases_count,
                       COUNT(DISTINCT co.id) as consultations_count,
                       (COUNT(DISTINCT c.id) + COUNT(DISTINCT co.id)) as total_activity
                       FROM users u 
                       LEFT JOIN cases c ON u.id = c.lawyer_id 
                       LEFT JOIN consultations co ON u.id = co.lawyer_id 
                       WHERE u.role = 'lawyer' 
                       GROUP BY u.id, u.name 
                       ORDER BY total_activity DESC 
                       LIMIT 5");
$stmt->execute();
$top_lawyers = $stmt->fetchAll();

// إحصائيات التسجيل الشهرية
$stmt = $pdo->prepare("SELECT 
                       DATE_FORMAT(created_at, '%Y-%m') as month,
                       SUM(CASE WHEN role = 'lawyer' THEN 1 ELSE 0 END) as lawyers,
                       SUM(CASE WHEN role = 'client' THEN 1 ELSE 0 END) as clients
                       FROM users 
                       WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                       GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                       ORDER BY month");
$stmt->execute();
$monthly_registrations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التقارير - لوحة الإدارة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>لوحة الإدارة</h3>
            <ul>
                <li><a href="dashboard.php">الرئيسية</a></li>
                <li><a href="lawyers.php">إدارة المحامين</a></li>
                <li><a href="clients.php">إدارة العملاء</a></li>
                <li><a href="cases.php">مراقبة القضايا</a></li>
                <li><a href="reports.php" class="active">التقارير</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>تقارير المنصة</h1>
                <p>تحليل شامل لأداء المنصة وإحصائياتها</p>
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
            
            <!-- الإحصائيات العامة -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $general_stats['total_lawyers']; ?></div>
                    <div class="stat-label">المحامين</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $general_stats['total_clients']; ?></div>
                    <div class="stat-label">العملاء</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $general_stats['total_cases']; ?></div>
                    <div class="stat-label">القضايا</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $general_stats['total_consultations']; ?></div>
                    <div class="stat-label">الاستشارات</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $general_stats['total_messages']; ?></div>
                    <div class="stat-label">الرسائل</div>
                </div>
            </div>
            
            <!-- الرسوم البيانية -->
            <div class="charts-grid">
                <div class="card">
                    <h2>توزيع القضايا حسب الحالة</h2>
                    <div class="chart-container">
                        <canvas id="casesChart" width="400" height="200"></canvas>
                    </div>
                </div>
                
                <div class="card">
                    <h2>توزيع الاستشارات حسب الحالة</h2>
                    <div class="chart-container">
                        <canvas id="consultationsChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- أكثر المحامين نشاطاً -->
            <div class="card">
                <h2>أكثر المحامين نشاطاً</h2>
                <?php if (count($top_lawyers) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>اسم المحامي</th>
                                <th>عدد القضايا</th>
                                <th>عدد الاستشارات</th>
                                <th>إجمالي النشاط</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($top_lawyers as $lawyer): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($lawyer['name']); ?></td>
                                    <td><?php echo $lawyer['cases_count']; ?></td>
                                    <td><?php echo $lawyer['consultations_count']; ?></td>
                                    <td><strong><?php echo $lawyer['total_activity']; ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>لا توجد بيانات نشاط للمحامين.</p>
                <?php endif; ?>
            </div>
            
            <!-- إحصائيات التسجيل الشهرية -->
            <div class="card">
                <h2>التسجيلات الشهرية (آخر 6 أشهر)</h2>
                <div class="chart-container">
                    <canvas id="registrationsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // رسم بياني للقضايا
        const casesCtx = document.getElementById('casesChart').getContext('2d');
        new Chart(casesCtx, {
            type: 'pie',
            data: {
                labels: ['جديدة', 'قيد المعالجة', 'مغلقة', 'مؤرشفة'],
                datasets: [{
                    data: [
                        <?php echo $cases_by_status['new'] ?? 0; ?>,
                        <?php echo $cases_by_status['in_progress'] ?? 0; ?>,
                        <?php echo $cases_by_status['closed'] ?? 0; ?>,
                        <?php echo $cases_by_status['archived'] ?? 0; ?>
                    ],
                    backgroundColor: ['#007bff', '#ffc107', '#28a745', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        // رسم بياني للاستشارات
        const consultationsCtx = document.getElementById('consultationsChart').getContext('2d');
        new Chart(consultationsCtx, {
            type: 'doughnut',
            data: {
                labels: ['في الانتظار', 'تم الرد'],
                datasets: [{
                    data: [
                        <?php echo $consultations_by_status['pending'] ?? 0; ?>,
                        <?php echo $consultations_by_status['answered'] ?? 0; ?>
                    ],
                    backgroundColor: ['#ffc107', '#28a745']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        // رسم بياني للتسجيلات الشهرية
        const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
        new Chart(registrationsCtx, {
            type: 'line',
            data: {
                labels: [<?php echo '"' . implode('", "', array_column($monthly_registrations, 'month')) . '"'; ?>],
                datasets: [{
                    label: 'المحامين',
                    data: [<?php echo implode(', ', array_column($monthly_registrations, 'lawyers')); ?>],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4
                }, {
                    label: 'العملاء',
                    data: [<?php echo implode(', ', array_column($monthly_registrations, 'clients')); ?>],
                    borderColor: '#764ba2',
                    backgroundColor: 'rgba(118, 75, 162, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
