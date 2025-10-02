<?php
// لوحة تحكم العميل - يونس ضاعني
require_once 'config.php';

// التحقق من تسجيل الدخول ودور المستخدم
if (!isLoggedIn() || !hasRole('client')) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// جلب قضايا العميل
$stmt = $pdo->prepare("SELECT c.*, u.name as lawyer_name FROM cases c JOIN users u ON c.lawyer_id = u.id WHERE c.client_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$user_id]);
$cases = $stmt->fetchAll();

// جلب استشارات العميل
$stmt = $pdo->prepare("SELECT co.*, u.name as lawyer_name FROM consultations co JOIN users u ON co.lawyer_id = u.id WHERE co.client_id = ? ORDER BY co.created_at DESC");
$stmt->execute([$user_id]);
$consultations = $stmt->fetchAll();

// تضمين ملف الرأس
view_partial('header');
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم العميل - <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="public/assets/css/mobile-ionic.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>
</head>
<body>
    <ion-app>
        <ion-header>
            <ion-toolbar color="primary">
                <ion-title>مرحباً، <?php echo htmlspecialchars($_SESSION['user_name']); ?></ion-title>
                <ion-buttons slot="end">
                    <ion-button href="logout.php">
                        <ion-icon name="log-out"></ion-icon>
                    </ion-button>
                </ion-buttons>
            </ion-toolbar>
        </ion-header>
        
        <ion-content class="ion-padding">
            <div class="content-section">
                <ion-card>
                    <ion-card-header>
                        <ion-card-title>قضاياي</ion-card-title>
                    </ion-card-header>
                    <ion-card-content>
                        <?php if (!empty($cases)): ?>
                            <ion-list>
                                <?php foreach ($cases as $case): ?>
                                    <ion-item href="client_case_details.php?id=<?php echo $case->id; ?>">
                                        <ion-icon name="folder-open-outline" slot="start"></ion-icon>
                                        <ion-label>
                                            <h3><?php echo htmlspecialchars($case->title); ?></h3>
                                            <p>المحامي: <?php echo htmlspecialchars($case->lawyer_name); ?></p>
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
                                <p>يمكنك <a href="client_new_case.php">فتح قضية جديدة</a>.</p>
                            </div>
                        <?php endif; ?>
                        <ion-button expand="block" href="client_new_case.php" class="ion-margin-top">
                            <ion-icon slot="start" name="add-circle-outline"></ion-icon>
                            فتح قضية جديدة
                        </ion-button>
                    </ion-card-content>
                </ion-card>

                <ion-card class="ion-margin-top">
                    <ion-card-header>
                        <ion-card-title>استشاراتي</ion-card-title>
                    </ion-card-header>
                    <ion-card-content>
                        <?php if (!empty($consultations)): ?>
                            <ion-list>
                                <?php foreach ($consultations as $consultation): ?>
                                    <ion-item href="client_consultations.php?id=<?php echo $consultation->id; ?>">
                                        <ion-icon name="help-circle-outline" slot="start"></ion-icon>
                                        <ion-label>
                                            <h3>استشارة مع: <?php echo htmlspecialchars($consultation->lawyer_name); ?></h3>
                                            <p><?php echo substr(htmlspecialchars($consultation->question), 0, 70) . (strlen($consultation->question) > 70 ? '...' : ''); ?></p>
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
                                <h3>لا توجد استشارات سابقة.</h3>
                                <p>يمكنك <a href="client_consultations.php">طلب استشارة جديدة</a>.</p>
                            </div>
                        <?php endif; ?>
                        <ion-button expand="block" href="client_consultations.php" class="ion-margin-top">
                            <ion-icon slot="start" name="add-circle-outline"></ion-icon>
                            طلب استشارة جديدة
                        </ion-button>
                    </ion-card-content>
                </ion-card>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="public/assets/js/main.js"></script>
</body>
</html>
