<?php
// تفاصيل الاستشارة للمحامي - يونس ضاعني
require_once 'config.php';

// التحقق من تسجيل الدخول ودور المستخدم
if (!isLoggedIn() || !hasRole('lawyer')) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// جلب تفاصيل الاستشارة
$consultation_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT co.*, u.name as client_name FROM consultations co 
                       JOIN users u ON co.client_id = u.id 
                       WHERE co.id = ? AND co.lawyer_id = ?");
$stmt->execute([$consultation_id, $user_id]);
$consultation = $stmt->fetch();

// معالجة الرد على الاستشارة
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answer = sanitize_input($_POST['answer']);

    if (empty($answer)) {
        $error = 'يرجى كتابة الرد على الاستشارة.';
    } else {
        $stmt = $pdo->prepare("UPDATE consultations SET answer = ?, status = 'answered' WHERE id = ? AND lawyer_id = ?");
        if ($stmt->execute([$answer, $consultation_id, $user_id])) {
            $success = 'تم إرسال الرد بنجاح.';
            // يمكنك إضافة منطق لإرسال إشعار للعميل هنا
            redirect('lawyer_consultation_details.php?id=' . $consultation_id);
        } else {
            $error = 'حدث خطأ أثناء إرسال الرد.';
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
    <title>تفاصيل الاستشارة - <?php echo SITENAME; ?></title>
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
                    <ion-back-button default-href="lawyer_dashboard.php"></ion-back-button>
                </ion-buttons>
                <ion-title>تفاصيل الاستشارة</ion-title>
            </ion-toolbar>
        </ion-header>
        
        <ion-content class="ion-padding">
            <div class="content-section">
                <?php if ($consultation): ?>
                    <ion-card>
                        <ion-card-header>
                            <ion-card-title>استشارة من: <?php echo htmlspecialchars($consultation->client_name); ?></ion-card-title>
                            <ion-card-subtitle>تاريخ: <?php echo date('Y-m-d H:i', strtotime($consultation->created_at)); ?></ion-card-subtitle>
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

                            <h3>سؤال العميل:</h3>
                            <p><?php echo nl2br(htmlspecialchars($consultation->question)); ?></p>
                            
                            <?php if ($consultation->status == 'answered'): ?>
                                <h3 class="ion-margin-top">ردك على الاستشارة:</h3>
                                <p><?php echo nl2br(htmlspecialchars($consultation->answer)); ?></p>
                            <?php else: ?>
                                <form action="lawyer_consultation_details.php?id=<?php echo $consultation->id; ?>" method="POST" class="ion-margin-top">
                                    <ion-item>
                                        <ion-label position="stacked">الرد على الاستشارة</ion-label>
                                        <ion-textarea name="answer" placeholder="اكتب ردك هنا..." rows="5" required></ion-textarea>
                                    </ion-item>
                                    <ion-button type="submit" expand="block" shape="round" class="ion-margin-top">
                                        <ion-icon slot="start" name="send"></ion-icon>
                                        إرسال الرد
                                    </ion-button>
                                </form>
                            <?php endif; ?>
                        </ion-card-content>
                    </ion-card>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                        <h3>الاستشارة غير موجودة أو ليس لديك صلاحية لعرضها.</h3>
                        <ion-button href="lawyer_dashboard.php" fill="outline" class="ion-margin-top">
                            العودة إلى لوحة التحكم
                        </ion-button>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="public/assets/js/main.js"></script>
</body>
</html>
