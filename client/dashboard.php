<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('client')) {
    redirect('../login.php');
}

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

// جلب القضايا الخاصة بالعميل
$stmt = $pdo->prepare("SELECT c.*, u.name as lawyer_name FROM cases c 
                       JOIN users u ON c.lawyer_id = u.id 
                       WHERE c.client_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$user_id]);
$cases = $stmt->fetchAll();

// جلب الاستشارات
$stmt = $pdo->prepare("SELECT co.*, u.name as lawyer_name FROM consultations co 
                       JOIN users u ON co.lawyer_id = u.id 
                       WHERE co.client_id = ? ORDER BY co.created_at DESC");
$stmt->execute([$user_id]);
$consultations = $stmt->fetchAll();

// إحصائيات سريعة
$total_cases = count($cases);
$active_cases = count(array_filter($cases, function($case) { return $case['status'] == 'in_progress'; }));
$pending_consultations = count(array_filter($consultations, function($c) { return $c['status'] == 'pending'; }));
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <?php include '../includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="../assets/css/mobile-ionic.css">
    <title>لوحة تحكم العميل - منصة مكاتب المحاماة</title>
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
                        <h1>مرحباً، <?php echo htmlspecialchars($user_name); ?></h1>
                        <p>إدارة قضاياك واستشاراتك القانونية</p>
                    </div>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="content-section">
                <h2 class="section-title">نظرة عامة</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <ion-icon name="folder" class="stat-icon" color="primary"></ion-icon>
                        <span class="stat-number"><?php echo $total_cases; ?></span>
                        <span class="stat-label">إجمالي القضايا</span>
                    </div>
                    <div class="stat-card">
                        <ion-icon name="pulse" class="stat-icon" color="warning"></ion-icon>
                        <span class="stat-number"><?php echo $active_cases; ?></span>
                        <span class="stat-label">قضايا نشطة</span>
                    </div>
                    <div class="stat-card">
                        <ion-icon name="chatbubbles" class="stat-icon" color="secondary"></ion-icon>
                        <span class="stat-number"><?php echo $pending_consultations; ?></span>
                        <span class="stat-label">استشارات</span>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <h2 class="section-title">إجراءات سريعة</h2>
                <div class="stats-grid">
                    <ion-card button href="new_case.php">
                        <ion-card-content class="ion-text-center">
                            <ion-icon name="add-circle" color="primary" style="font-size: 48px;"></ion-icon>
                            <ion-card-title>قضية جديدة</ion-card-title>
                        </ion-card-content>
                    </ion-card>
                    <ion-card button href="consultations.php">
                        <ion-card-content class="ion-text-center">
                            <ion-icon name="help-circle" color="secondary" style="font-size: 48px;"></ion-icon>
                            <ion-card-title>استشارة</ion-card-title>
                        </ion-card-content>
                    </ion-card>
                </div>
                
                <!-- Recent Cases -->
                <h2 class="section-title">القضايا الحالية</h2>
                <?php if (count($cases) > 0): ?>
                    <ion-list>
                        <?php foreach (array_slice($cases, 0, 3) as $case): ?>
                            <ion-item-sliding>
                                <ion-item href="case_details.php?id=<?php echo $case['id']; ?>">
                                    <ion-icon name="briefcase-outline" slot="start"></ion-icon>
                                    <ion-label>
                                        <h3><?php echo htmlspecialchars($case['title']); ?></h3>
                                        <p>المحامي: <?php echo htmlspecialchars($case['lawyer_name']); ?></p>
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
                                    <ion-item-option href="../chat.php?case_id=<?php echo $case['id']; ?>" color="primary">
                                        <ion-icon slot="icon-only" name="chatbubbles"></ion-icon>
                                    </ion-item-option>
                                </ion-item-options>
                            </ion-item-sliding>
                        <?php endforeach; ?>
                    </ion-list>
                    <?php if (count($cases) > 3): ?>
                        <ion-button expand="block" fill="clear" href="cases.php">
                            عرض جميع القضايا
                        </ion-button>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="folder-open-outline"></ion-icon>
                        <h3>لا توجد قضايا حالياً</h3>
                        <p>ابدأ بإضافة قضية جديدة</p>
                    </div>
                <?php endif; ?>
                
                <!-- Recent Consultations -->
                <h2 class="section-title">الاستشارات الأخيرة</h2>
                <?php if (count($consultations) > 0): ?>
                    <ion-list>
                        <?php foreach (array_slice($consultations, 0, 3) as $consultation): ?>
                            <ion-item-sliding>
                                <ion-item href="consultations.php?id=<?php echo $consultation['id']; ?>">
                                    <ion-icon name="chatbubble-ellipses-outline" slot="start"></ion-icon>
                                    <ion-label>
                                        <h3>استشارة مع <?php echo htmlspecialchars($consultation['lawyer_name']); ?></h3>
                                        <p><?php echo htmlspecialchars(substr($consultation['question'], 0, 50)); ?>...</p>
                                        <p class="ion-text-right">
                                            <span class="status-badge status-<?php echo $consultation['status']; ?>">
                                                <?php 
                                                $status_names = [
                                                    'pending' => 'معلقة',
                                                    'answered' => 'تم الرد'
                                                ];
                                                echo $status_names[$consultation['status']] ?? $consultation['status'];
                                                ?>
                                            </span>
                                        </p>
                                    </ion-label>
                                    <ion-note slot="end"><?php echo date('Y-m-d', strtotime($consultation['created_at'])); ?></ion-note>
                                </ion-item>
                                <ion-item-options side="end">
                                    <ion-item-option href="consultations.php?id=<?php echo $consultation['id']; ?>">
                                        <ion-icon slot="icon-only" name="eye"></ion-icon>
                                    </ion-item-option>
                                </ion-item-options>
                            </ion-item-sliding>
                        <?php endforeach; ?>
                    </ion-list>
                    <?php if (count($consultations) > 3): ?>
                        <ion-button expand="block" fill="clear" href="consultations.php">
                            عرض جميع الاستشارات
                        </ion-button>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                        <h3>لا توجد استشارات حالياً</h3>
                        <p>يمكنك طلب استشارة جديدة</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php include '../includes/bottom_nav.php'; ?>
        </ion-content>
    </ion-app>
</body>
</html>
