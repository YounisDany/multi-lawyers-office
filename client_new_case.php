<?php
// فتح قضية جديدة للعميل - يونس ضاعني
require_once 'config.php';

// التحقق من تسجيل الدخول ودور المستخدم
if (!isLoggedIn() || !hasRole('client')) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// جلب قائمة المحامين لعرضها في النموذج
$stmt = $pdo->query("SELECT id, name FROM users WHERE role = 'lawyer'");
$lawyers = $stmt->fetchAll();

// معالجة طلب فتح قضية جديدة
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize_input($_POST['title']);
    $details = sanitize_input($_POST['details']);
    $lawyer_id = sanitize_input($_POST['lawyer_id']);

    if (empty($title) || empty($details) || empty($lawyer_id)) {
        $error = 'يرجى ملء جميع الحقول.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO cases (client_id, lawyer_id, title, details, status) VALUES (?, ?, ?, ?, 'new')");
        if ($stmt->execute([$user_id, $lawyer_id, $title, $details])) {
            $success = 'تم فتح القضية بنجاح. سيتم مراجعتها من قبل المحامي.';
            // يمكنك إضافة منطق لإرسال إشعار للمحامي هنا
        } else {
            $error = 'حدث خطأ أثناء فتح القضية.';
        }
    }
}

// تضمين ملف الرأس
view_partial('header');
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فتح قضية جديدة - <?php echo SITENAME; ?></title>
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
                <ion-buttons slot="start">
                    <ion-back-button default-href="client_dashboard.php"></ion-back-button>
                </ion-buttons>
                <ion-title>فتح قضية جديدة</ion-title>
            </ion-toolbar>
        </ion-header>
        
        <ion-content class="ion-padding">
            <div class="content-section">
                <ion-card>
                    <ion-card-header>
                        <ion-card-title>نموذج فتح قضية</ion-card-title>
                    </ion-card-header>
                    <ion-card-content>
                        <?php if ($error): ?>
                            <ion-item color="danger" class="ion-margin-bottom">
                                <ion-icon name="alert-circle" slot="start"></ion-icon>
                                <ion-label><?php echo $error; ?></ion-label>
                            </ion-item>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <ion-item color="success" class="ion-margin-bottom">
                                <ion-icon name="checkmark-circle" slot="start"></ion-icon>
                                <ion-label><?php echo $success; ?></ion-label>
                            </ion-item>
                        <?php endif; ?>
                        
                        <form action="client_new_case.php" method="POST">
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">عنوان القضية</ion-label>
                                <ion-input type="text" name="title" placeholder="مثال: قضية تعويض عن حادث" required></ion-input>
                            </ion-item>
                            
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">تفاصيل القضية</ion-label>
                                <ion-textarea name="details" placeholder="اذكر تفاصيل القضية هنا..." rows="5" required></ion-textarea>
                            </ion-item>
                            
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">اختر المحامي</ion-label>
                                <ion-select name="lawyer_id" placeholder="اختر محامي" required>
                                    <?php foreach ($lawyers as $lawyer): ?>
                                        <ion-select-option value="<?php echo $lawyer->id; ?>">
                                            <?php echo htmlspecialchars($lawyer->name); ?>
                                        </ion-select-option>
                                    <?php endforeach; ?>
                                </ion-select>
                            </ion-item>
                            
                            <ion-button type="submit" expand="block" shape="round" class="ion-margin-top">
                                <ion-icon slot="start" name="add-circle-outline"></ion-icon>
                                فتح القضية
                            </ion-button>
                        </form>
                    </ion-card-content>
                </ion-card>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="public/assets/js/main.js"></script>
</body>
</html>
