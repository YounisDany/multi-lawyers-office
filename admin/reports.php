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
    <?php include '../includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="../assets/css/mobile-ionic.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>التقارير - لوحة الإدارة</title>
</head>
<body>
    <ion-app>
        <ion-content>
            <!-- Header -->
            <div class="app-header">
                <div class="header-content">
                    <ion-button href="dashboard.php" fill="clear" color="light" class="back-button">
                        <ion-icon slot="icon-only" name="arrow-forward"></ion-icon>
                    </ion-button>
                    <div>
                        <h1>تقارير المنصة</h1>
                        <p>تحليل شامل لأداء المنصة وإحصائياتها</p>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="content-section">
                <!-- فلتر الفترة الزمنية -->
                <h2 class="section-title">الفترة الزمنية</h2>
                <ion-segment value="<?php echo htmlspecialchars($period); ?>" onIonChange="window.location.href='reports.php?period=' + event.detail.value">
                    <ion-segment-button value="week">
                        <ion-label>أسبوع</ion-label>
                    </ion-segment-button>
                    <ion-segment-button value="month">
                        <ion-label>شهر</ion-label>
                    </ion-segment-button>
                    <ion-segment-button value="year">
                        <ion-label>سنة</ion-label>
                    </ion-segment-button>
                    <ion-segment-button value="all">
                        <ion-label>الكل</ion-label>
                    </ion-segment-button>
                </ion-segment>

                <!-- الإحصائيات العامة -->
                <h2 class="section-title">نظرة عامة</h2>
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
                </div>

                <!-- الرسوم البيانية -->
                <div class="card">
                    <h2 class="card-title">توزيع القضايا حسب الحالة</h2>
                    <canvas id="casesChart" height="250"></canvas>
                </div>

                <div class="card">
                    <h2 class="card-title">توزيع الاستشارات حسب الحالة</h2>
                    <canvas id="consultationsChart" height="250"></canvas>
                </div>

                <!-- أكثر المحامين نشاطاً -->
                <h2 class="section-title">أكثر المحامين نشاطاً</h2>
                <div class="card">
                    <?php if (count($top_lawyers) > 0): ?>
                        <ion-list lines="full">
                            <?php foreach ($top_lawyers as $lawyer): ?>
                                <ion-item>
                                    <ion-label>
                                        <h3><?php echo htmlspecialchars($lawyer['name']); ?></h3>
                                        <p>القضايا: <?php echo $lawyer['cases_count']; ?> | الاستشارات: <?php echo $lawyer['consultations_count']; ?></p>
                                    </ion-label>
                                    <ion-badge slot="end" color="primary"><?php echo $lawyer['total_activity']; ?> نشاط</ion-badge>
                                </ion-item>
                            <?php endforeach; ?>
                        </ion-list>
                    <?php else: ?>
                        <p class="ion-padding">لا توجد بيانات نشاط للمحامين.</p>
                    <?php endif; ?>
                </div>

                <!-- إحصائيات التسجيل الشهرية -->
                <div class="card">
                    <h2 class="card-title">التسجيلات الشهرية (آخر 6 أشهر)</h2>
                    <canvas id="registrationsChart" height="250"></canvas>
                </div>
            </div>
            
            <?php include '../includes/bottom_nav.php'; ?>
        </ion-content>
    </ion-app>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Chart.js Global Config
            Chart.defaults.font.family = "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif";
            Chart.defaults.color = '#666';

            // رسم بياني للقضايا
            const casesCtx = document.getElementById('casesChart')?.getContext('2d');
            if (casesCtx) {
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
                            backgroundColor: ['#667eea', '#ffc107', '#28a745', '#6c757d']
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }

            // رسم بياني للاستشارات
            const consultationsCtx = document.getElementById('consultationsChart')?.getContext('2d');
            if(consultationsCtx) {
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
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }

            // رسم بياني للتسجيلات الشهرية
            const registrationsCtx = document.getElementById('registrationsChart')?.getContext('2d');
            if(registrationsCtx) {
                new Chart(registrationsCtx, {
                    type: 'line',
                    data: {
                        labels: [<?php echo '"' . implode('", "', array_column($monthly_registrations, 'month')) . '"'; ?>],
                        datasets: [{
                            label: 'المحامين',
                            data: [<?php echo implode(', ', array_column($monthly_registrations, 'lawyers')); ?>],
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            tension: 0.4,
                            fill: true
                        }, {
                            label: 'العملاء',
                            data: [<?php echo implode(', ', array_column($monthly_registrations, 'clients')); ?>],
                            borderColor: '#764ba2',
                            backgroundColor: 'rgba(118, 75, 162, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
        });
    </script>
</body>
</html>
