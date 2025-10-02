<?php
// استشارات العميل - يونس ضاعني
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

// معالجة طلب استشارة جديدة
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lawyer_id = sanitize_input($_POST['lawyer_id']);
    $question = sanitize_input($_POST['question']);

    if (empty($lawyer_id) || empty($question)) {
        $error = 'يرجى ملء جميع الحقول.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO consultations (client_id, lawyer_id, question, status) VALUES (?, ?, ?, 'pending')");
        if ($stmt->execute([$user_id, $lawyer_id, $question])) {
            $success = 'تم إرسال الاستشارة بنجاح. سيتم الرد عليها قريباً.';
            // يمكنك إضافة منطق لإرسال إشعار للمحامي هنا
        } else {
            $error = 'حدث خطأ أثناء إرسال الاستشارة.';
        }
    }
}

// جلب استشارات العميل السابقة
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
    <title>استشاراتي - <?php echo SITENAME; ?></title>
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
                <ion-title>استشاراتي</ion-title>
            </ion-toolbar>
        </ion-header>
        
        <ion-content class="ion-padding">
            <div class="content-section">
                <ion-card>
                    <ion-card-header>
                        <ion-card-title>طلب استشارة جديدة</ion-card-title>
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
                        
                        <form action="client_consultations.php" method="POST">
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
                            
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">سؤالك</ion-label>
                                <ion-textarea name="question" placeholder="اكتب سؤالك هنا..." rows="5" required></ion-textarea>
                            </ion-item>
                            
                            <ion-button type="submit" expand="block" shape="round" class="ion-margin-top">
                                <ion-icon slot="start" name="send"></ion-icon>
                                إرسال الاستشارة
                            </ion-button>
                        </form>
                    </ion-card-content>
                </ion-card>
                
                <h2 class="section-title ion-margin-top">استشاراتي السابقة</h2>
                <?php if (!empty($consultations)): ?>
                    <ion-list>
                        <?php foreach ($consultations as $consultation): ?>
                            <ion-item-sliding>
                                <ion-item>
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
                                <ion-item-options side="end">
                                    <ion-item-option color="primary" onclick="alert('تفاصيل الاستشارة: <?php echo htmlspecialchars(addslashes($consultation->question)); ?>\n\nالرد: <?php echo htmlspecialchars(addslashes($consultation->answer ?? 'لا يوجد رد بعد.')); ?>')">
                                        <ion-icon slot="icon-only" name="eye"></ion-icon>
                                    </ion-item-option>
                                </ion-item-options>
                            </ion-item-sliding>
                        <?php endforeach; ?>
                    </ion-list>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                        <h3>لا توجد استشارات سابقة.</h3>
                        <p>يمكنك طلب استشارة جديدة الآن.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="public/assets/js/main.js"></script>
</body>
</html>
