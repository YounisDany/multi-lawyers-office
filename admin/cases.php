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
    <?php include '../includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="../assets/css/mobile-ionic.css">
    <title>مراقبة القضايا - منصة مكاتب المحاماة</title>
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
                        <h1>مراقبة القضايا</h1>
                        <p>عرض ومراقبة جميع القضايا في المنصة</p>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="content-section">
                <!-- إحصائيات القضايا -->
                <h2 class="section-title">إحصائيات القضايا</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $status_stats['new'] ?? 0; ?></div>
                        <div class="stat-label">جديدة</div>
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
                <h2 class="section-title">تصفية القضايا</h2>
                <ion-segment value="<?php echo htmlspecialchars($filter); ?>" onIonChange="window.location.href='cases.php?filter=' + event.detail.value">
                    <ion-segment-button value="all">
                        <ion-label>الكل</ion-label>
                    </ion-segment-button>
                    <ion-segment-button value="new">
                        <ion-label>جديدة</ion-label>
                    </ion-segment-button>
                    <ion-segment-button value="in_progress">
                        <ion-label>قيد المعالجة</ion-label>
                    </ion-segment-button>
                    <ion-segment-button value="closed">
                        <ion-label>مغلقة</ion-label>
                    </ion-segment-button>
                    <ion-segment-button value="archived">
                        <ion-label>مؤرشفة</ion-label>
                    </ion-segment-button>
                </ion-segment>
                
                <!-- قائمة القضايا -->
                <h2 class="section-title">القضايا (<?php echo count($cases); ?>)</h2>
                
                <?php if (count($cases) > 0): ?>
                    <ion-list>
                        <?php foreach ($cases as $case): ?>
                            <ion-item-sliding>
                                <ion-item href="case_details.php?id=<?php echo $case['id']; ?>">
                                    <ion-icon name="briefcase-outline" slot="start"></ion-icon>
                                    <ion-label>
                                        <h3><?php echo htmlspecialchars($case['title']); ?></h3>
                                        <p>المحامي: <?php echo htmlspecialchars($case['lawyer_name']); ?></p>
                                        <p>العميل: <?php echo htmlspecialchars($case['client_name']); ?></p>
                                        <p class="ion-text-right">
                                            <span class="status-badge status-<?php echo $case['status']; ?>">
                                                <?php 
                                                $status_names = [
                                                    'new' => 'جديدة',
                                                    'in_progress' => 'قيد المعالجة',
                                                    'closed' => 'مغلقة',
                                                    'archived' => 'مؤرشفة'
                                                ];
                                                echo $status_names[$case['status']] ?? $case['status'];
                                                ?>
                                            </span>
                                        </p>
                                    </ion-label>
                                    <ion-note slot="end"><?php echo date('Y-m-d', strtotime($case['created_at'])); ?></ion-note>
                                </ion-item>
                                <ion-item-options side="end">
                                    <ion-item-option href="case_details.php?id=<?php echo $case['id']; ?>">
                                        <ion-icon slot="icon-only" name="eye"></ion-icon>
                                    </ion-item-option>
                                </ion-item-options>
                            </ion-item-sliding>
                        <?php endforeach; ?>
                    </ion-list>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="folder-open-outline"></ion-icon>
                        <h3>لا توجد قضايا</h3>
                        <p>لا توجد قضايا تطابق الفلتر المحدد.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php include '../includes/bottom_nav.php'; ?>
        </ion-content>
    </ion-app>
</body>
</html>
