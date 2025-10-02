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
    <?php include '../includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="../assets/css/mobile-ionic.css">
    <title>لوحة تحكم الأدمن - منصة مكاتب المحاماة</title>
</head>
<body>
    <ion-app>
        <ion-content>
            <!-- Header -->
            <div class="app-header">
                <div class="header-content">
                    <ion-button href="../index.php" fill="clear" color="light" class="back-button">
                        <ion-icon slot="icon-only" name="arrow-forward"></ion-icon>
                    </ion-button>
                    <div>
                        <h1>لوحة تحكم الأدمن</h1>
                        <p>مراقبة وإدارة المنصة بشكل عام</p>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="content-section">
                <!-- الإحصائيات الرئيسية -->
                <h2 class="section-title">الإحصائيات العامة</h2>
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
                <h2 class="section-title">أحدث المحامين المسجلين</h2>
                <?php if (count($recent_lawyers) > 0): ?>
                    <div class="card">
                        <ion-list lines="none">
                            <?php foreach ($recent_lawyers as $lawyer): ?>
                                <ion-item detail href="lawyers.php?id=<?php echo $lawyer['id']; ?>">
                                    <ion-avatar slot="start">
                                        <ion-icon name="person-circle-outline"></ion-icon>
                                    </ion-avatar>
                                    <ion-label>
                                        <h3><?php echo htmlspecialchars($lawyer['name']); ?></h3>
                                        <p><?php echo htmlspecialchars($lawyer['email']); ?></p>
                                        <p class="ion-text-right"><?php echo date('Y-m-d', strtotime($lawyer['created_at'])); ?></p>
                                    </ion-label>
                                </ion-item>
                            <?php endforeach; ?>
                        </ion-list>
                        <ion-button expand="block" fill="clear" href="lawyers.php">
                            عرض جميع المحامين
                        </ion-button>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="people-outline"></ion-icon>
                        <h3>لا يوجد محامون مسجلون بعد.</h3>
                    </div>
                <?php endif; ?>
                
                <!-- العملاء الجدد -->
                <h2 class="section-title">أحدث العملاء المسجلين</h2>
                <?php if (count($recent_clients) > 0): ?>
                    <div class="card">
                        <ion-list lines="none">
                            <?php foreach ($recent_clients as $client): ?>
                                <ion-item detail href="clients.php?id=<?php echo $client['id']; ?>">
                                    <ion-avatar slot="start">
                                        <ion-icon name="person-circle-outline"></ion-icon>
                                    </ion-avatar>
                                    <ion-label>
                                        <h3><?php echo htmlspecialchars($client['name']); ?></h3>
                                        <p><?php echo htmlspecialchars($client['email']); ?></p>
                                        <p class="ion-text-right"><?php echo date('Y-m-d', strtotime($client['created_at'])); ?></p>
                                    </ion-label>
                                </ion-item>
                            <?php endforeach; ?>
                        </ion-list>
                        <ion-button expand="block" fill="clear" href="clients.php">
                            عرض جميع العملاء
                        </ion-button>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="people-outline"></ion-icon>
                        <h3>لا يوجد عملاء مسجلون بعد.</h3>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php include '../includes/bottom_nav.php'; ?>
        </ion-content>
    </ion-app>
</body>
</html>
