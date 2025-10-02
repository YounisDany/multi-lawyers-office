<?php
// لوحة تحكم الإدارة - يونس ضاعني
require_once __DIR__ . "/../config.php";

// التحقق من تسجيل الدخول ودور المستخدم
if (!isLoggedIn() || !hasRole("admin")) {
    redirect("../login.php");
}

$user_id = $_SESSION["user_id"];

// جلب إحصائيات عامة
$total_clients = $pdo->query("SELECT COUNT(*) FROM users WHERE role = \"client\"")->fetchColumn();
$total_lawyers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = \"lawyer\"")->fetchColumn();
$total_cases = $pdo->query("SELECT COUNT(*) FROM cases")->fetchColumn();
$total_consultations = $pdo->query("SELECT COUNT(*) FROM consultations")->fetchColumn();

// جلب آخر 5 قضايا
$latest_cases = $pdo->query("SELECT c.*, cl.name as client_name, l.name as lawyer_name FROM cases c JOIN users cl ON c.client_id = cl.id JOIN users l ON c.lawyer_id = l.id ORDER BY c.created_at DESC LIMIT 5")->fetchAll();

// جلب آخر 5 استشارات
$latest_consultations = $pdo->query("SELECT co.*, cl.name as client_name, l.name as lawyer_name FROM consultations co JOIN users cl ON co.client_id = cl.id JOIN users l ON co.lawyer_id = l.id ORDER BY co.created_at DESC LIMIT 5")->fetchAll();

// تضمين ملف الرأس
view_partial("header");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الإدارة - <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/mobile-ionic.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>
</head>
<body>
    <ion-app>
        <ion-header>
            <ion-toolbar color="primary">
                <ion-title>مرحباً، <?php echo htmlspecialchars($_SESSION["user_name"]); ?></ion-title>
                <ion-buttons slot="end">
                    <ion-button href="<?php echo URLROOT; ?>/logout.php">
                        <ion-icon name="log-out"></ion-icon>
                    </ion-button>
                </ion-buttons>
            </ion-toolbar>
        </ion-header>
        
        <ion-content class="ion-padding">
            <div class="content-section">
                <h2 class="section-title">نظرة عامة</h2>
                <div class="stats-grid">
                    <ion-card class="stat-card">
                        <ion-card-content>
                            <ion-icon name="people-outline" color="primary"></ion-icon>
                            <h3>العملاء</h3>
                            <p><?php echo $total_clients; ?></p>
                        </ion-card-content>
                    </ion-card>
                    <ion-card class="stat-card">
                        <ion-card-content>
                            <ion-icon name="briefcase-outline" color="secondary"></ion-icon>
                            <h3>المحامون</h3>
                            <p><?php echo $total_lawyers; ?></p>
                        </ion-card-content>
                    </ion-card>
                    <ion-card class="stat-card">
                        <ion-card-content>
                            <ion-icon name="folder-open-outline" color="tertiary"></ion-icon>
                            <h3>القضايا</h3>
                            <p><?php echo $total_cases; ?></p>
                        </ion-card-content>
                    </ion-card>
                    <ion-card class="stat-card">
                        <ion-card-content>
                            <ion-icon name="help-circle-outline" color="warning"></ion-icon>
                            <h3>الاستشارات</h3>
                            <p><?php echo $total_consultations; ?></p>
                        </ion-card-content>
                    </ion-card>
                </div>

                <h2 class="section-title ion-margin-top">آخر القضايا</h2>
                <?php if (!empty($latest_cases)): ?>
                    <ion-list>
                        <?php foreach ($latest_cases as $case): ?>
                            <ion-item href="<?php echo URLROOT; ?>/lawyer_case_details.php?id=<?php echo $case->id; ?>">
                                <ion-icon name="folder-open-outline" slot="start"></ion-icon>
                                <ion-label>
                                    <h3><?php echo htmlspecialchars($case->title); ?></h3>
                                    <p>العميل: <?php echo htmlspecialchars($case->client_name); ?> | المحامي: <?php echo htmlspecialchars($case->lawyer_name); ?></p>
                                    <p class="ion-text-right">
                                        <ion-badge color="<?php echo $case->status == 'new' ? 'primary' : ($case->status == 'in_progress' ? 'warning' : 'success'); ?>">
                                            <?php 
                                            $statusText = [
                                                'new' => 'جديدة',
                                                'in_progress' => 'قيد المعالجة',
                                                'closed' => 'مغلقة',
                                                'archived' => 'مؤرشفة'
                                            ];
                                            echo $statusText[$case->status] ?? $case->status;
                                            ?>
                                        </ion-badge>
                                    </p>
                                </ion-label>
                                <ion-note slot="end"><?php echo date('Y-m-d', strtotime($case->created_at)); ?></ion-note>
                            </ion-item>
                        <?php endforeach; ?>
                    </ion-list>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="folder-open-outline"></ion-icon>
                        <h3>لا توجد قضايا حالياً.</h3>
                    </div>
                <?php endif; ?>

                <h2 class="section-title ion-margin-top">آخر الاستشارات</h2>
                <?php if (!empty($latest_consultations)): ?>
                    <ion-list>
                        <?php foreach ($latest_consultations as $consultation): ?>
                            <ion-item href="<?php echo URLROOT; ?>/lawyer_consultation_details.php?id=<?php echo $consultation->id; ?>">
                                <ion-icon name="help-circle-outline" slot="start"></ion-icon>
                                <ion-label>
                                    <h3>استشارة من: <?php echo htmlspecialchars($consultation->client_name); ?></h3>
                                    <p>المحامي: <?php echo htmlspecialchars($consultation->lawyer_name); ?></p>
                                    <p class="ion-text-right">
                                        <ion-badge color="<?php echo $consultation->status == 'pending' ? 'warning' : 'success'; ?>">
                                            <?php echo $consultation->status == 'pending' ? 'معلقة' : 'تم الرد'; ?>
                                        </ion-badge>
                                    </p>
                                </ion-label>
                                <ion-note slot="end"><?php echo date('Y-m-d', strtotime($consultation->created_at)); ?></ion-note>
                            </ion-item>
                        <?php endforeach; ?>
                    </ion-list>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                        <h3>لا توجد استشارات جديدة.</h3>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js"></script>
</body>
</html>
