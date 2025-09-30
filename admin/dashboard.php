<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('admin')) {
    redirect('../login.php');
}

// جلب الإحصائيات العامة
$stmt = $pdo->prepare("SELECT COUNT(*) as total_lawyers FROM users WHERE role = 'lawyer'");
$stmt->execute();
$total_lawyers = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) as total_clients FROM users WHERE role = 'client'");
$stmt->execute();
$total_clients = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) as total_cases FROM cases");
$stmt->execute();
$total_cases = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) as total_consultations FROM consultations");
$stmt->execute();
$total_consultations = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) as active_cases FROM cases WHERE status IN ('new', 'in_progress')");
$stmt->execute();
$active_cases = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) as pending_consultations FROM consultations WHERE status = 'pending'");
$stmt->execute();
$pending_consultations = $stmt->fetchColumn();

// جلب أحدث المحامين المسجلين
$stmt = $pdo->prepare("SELECT id, name, email, created_at FROM users WHERE role = 'lawyer' ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$recent_lawyers = $stmt->fetchAll();

// جلب أحدث العملاء المسجلين
$stmt = $pdo->prepare("SELECT id, name, email, created_at FROM users WHERE role = 'client' ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$recent_clients = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الأدمن - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>لوحة الإدارة</h3>
            <ul>
                <li><a href="dashboard.php" class="active">الرئيسية</a></li>
                <li><a href="lawyers.php">إدارة المحامين</a></li>
                <li><a href="clients.php">إدارة العملاء</a></li>
                <li><a href="cases.php">مراقبة القضايا</a></li>
                <li><a href="reports.php">التقارير</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>لوحة تحكم الأدمن</h1>
                <p>مراقبة وإدارة المنصة بشكل عام</p>
            </div>
            
            <!-- الإحصائيات الرئيسية -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_lawyers; ?></div>
                    <div class="stat-label">إجمالي المحامين</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_clients; ?></div>
                    <div class="stat-label">إجمالي العملاء</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_cases; ?></div>
                    <div class="stat-label">إجمالي القضايا</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_consultations; ?></div>
                    <div class="stat-label">إجمالي الاستشارات</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $active_cases; ?></div>
                    <div class="stat-label">القضايا النشطة</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $pending_consultations; ?></div>
                    <div class="stat-label">الاستشارات المعلقة</div>
                </div>
            </div>
            
            <!-- المحامين الجدد -->
            <div class="card">
                <h2>أحدث المحامين المسجلين</h2>
                <?php if (count($recent_lawyers) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>تاريخ التسجيل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_lawyers as $lawyer): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($lawyer['name']); ?></td>
                                    <td><?php echo htmlspecialchars($lawyer['email']); ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($lawyer['created_at'])); ?></td>
                                    <td>
                                        <a href="lawyer_details.php?id=<?php echo $lawyer['id']; ?>" class="btn-small">عرض</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <a href="lawyers.php" class="btn-primary">عرض جميع المحامين</a>
                    </div>
                <?php else: ?>
                    <p>لا يوجد محامين مسجلين بعد.</p>
                <?php endif; ?>
            </div>
            
            <!-- العملاء الجدد -->
            <div class="card">
                <h2>أحدث العملاء المسجلين</h2>
                <?php if (count($recent_clients) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>تاريخ التسجيل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_clients as $client): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($client['name']); ?></td>
                                    <td><?php echo htmlspecialchars($client['email']); ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($client['created_at'])); ?></td>
                                    <td>
                                        <a href="client_details.php?id=<?php echo $client['id']; ?>" class="btn-small">عرض</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <a href="clients.php" class="btn-primary">عرض جميع العملاء</a>
                    </div>
                <?php else: ?>
                    <p>لا يوجد عملاء مسجلين بعد.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
