<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('client')) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// جلب قائمة المحامين
$stmt = $pdo->prepare("SELECT id, name FROM users WHERE role = 'lawyer'");
$stmt->execute();
$lawyers = $stmt->fetchAll();

// معالجة طلب استشارة جديدة
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lawyer_id = $_POST['lawyer_id'];
    $question = $_POST['question'];
    
    if (!empty($lawyer_id) && !empty($question)) {
        $stmt = $pdo->prepare("INSERT INTO consultations (lawyer_id, client_id, question) VALUES (?, ?, ?)");
        
        if ($stmt->execute([$lawyer_id, $user_id, $question])) {
            // إرسال إشعار بالبريد الإلكتروني للمحامي
            require_once '../email_config.php';
            $lawyer_stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
            $lawyer_stmt->execute([$lawyer_id]);
            $lawyer = $lawyer_stmt->fetch();
            
            if ($lawyer) {
                notifyLawyerNewConsultation($lawyer['email'], $lawyer['name'], $_SESSION['user_name']);
            }
            
            $success = 'تم إرسال طلب الاستشارة بنجاح. سيتم الرد عليك قريباً.';
        } else {
            $error = 'حدث خطأ أثناء إرسال الاستشارة';
        }
    } else {
        $error = 'يرجى ملء جميع الحقول';
    }
}

// جلب الاستشارات السابقة
$stmt = $pdo->prepare("SELECT co.*, u.name as lawyer_name FROM consultations co 
                       JOIN users u ON co.lawyer_id = u.id 
                       WHERE co.client_id = ? ORDER BY co.created_at DESC");
$stmt->execute([$user_id]);
$consultations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <?php include '../includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="../assets/css/mobile-ionic.css">
    <title>الاستشارات - منصة مكاتب المحاماة</title>
</head>
<body>
    <ion-app>
        <ion-header>
            <ion-toolbar color="primary">
                <ion-buttons slot="start">
                    <ion-back-button default-href="dashboard.php"></ion-back-button>
                </ion-buttons>
                <ion-title>الاستشارات القانونية</ion-title>
            </ion-toolbar>
        </ion-header>

        <ion-content class="ion-padding">
            <div class="content-section">
                <h2 class="section-title">طلب استشارة جديدة</h2>
                
                <?php if ($error): ?>
                    <ion-item color="danger">
                        <ion-label><?php echo $error; ?></ion-label>
                    </ion-item>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <ion-item color="success">
                        <ion-label><?php echo $success; ?></ion-label>
                    </ion-item>
                <?php endif; ?>
                
                <ion-card>
                    <ion-card-content>
                        <form method="POST">
                            <ion-item>
                                <ion-select label="اختر المحامي:" label-placement="stacked" placeholder="اختر المحامي" name="lawyer_id" required>
                                    <?php foreach ($lawyers as $lawyer): ?>
                                        <ion-select-option value="<?php echo $lawyer['id']; ?>">
                                            <?php echo htmlspecialchars($lawyer['name']); ?>
                                        </ion-select-option>
                                    <?php endforeach; ?>
                                </ion-select>
                            </ion-item>
                            
                            <ion-item class="ion-margin-top">
                                <ion-textarea label="سؤالك القانوني:" label-placement="stacked" name="question" rows="6" required 
                                            placeholder="اكتب سؤالك القانوني بشكل واضح ومفصل..."></ion-textarea>
                            </ion-item>
                            
                            <ion-button expand="block" type="submit" class="ion-margin-top">
                                <ion-icon slot="start" name="send"></ion-icon>
                                إرسال الاستشارة
                            </ion-button>
                        </form>
                    </ion-card-content>
                </ion-card>
            
                <h2 class="section-title ion-margin-top">استشاراتك السابقة</h2>
                
                <?php if (count($consultations) > 0): ?>
                    <ion-list>
                        <?php foreach ($consultations as $consultation): ?>
                            <ion-item-sliding>
                                <ion-item detail href="consultation_details.php?id=<?php echo $consultation['id']; ?>">
                                    <ion-icon name="chatbubble-ellipses-outline" slot="start"></ion-icon>
                                    <ion-label>
                                        <h3>استشارة مع <?php echo htmlspecialchars($consultation['lawyer_name']); ?></h3>
                                        <p><?php echo htmlspecialchars(substr($consultation['question'], 0, 70)); ?>...</p>
                                        <p class="ion-text-right">
                                            <span class="status-badge status-<?php echo $consultation['status']; ?>">
                                                <?php echo $consultation['status'] == 'pending' ? 'في الانتظار' : 'تم الرد'; ?>
                                            </span>
                                        </p>
                                    </ion-label>
                                    <ion-note slot="end"><?php echo date('Y-m-d', strtotime($consultation['created_at'])); ?></ion-note>
                                </ion-item>
                                <ion-item-options side="end">
                                    <ion-item-option href="consultation_details.php?id=<?php echo $consultation['id']; ?>">
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
            
            <?php include '../includes/bottom_nav.php'; ?>
        </ion-content>
    </ion-app>
</body>
</html>
