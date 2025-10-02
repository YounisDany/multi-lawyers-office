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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lawyer_id = $_POST['lawyer_id'];
    $title = $_POST['title'];
    $details = $_POST['details'];
    
    if (!empty($lawyer_id) && !empty($title) && !empty($details)) {
        $stmt = $pdo->prepare("INSERT INTO cases (lawyer_id, client_id, title, details) VALUES (?, ?, ?, ?)");
        
        if ($stmt->execute([$lawyer_id, $user_id, $title, $details])) {
            // إرسال إشعار بالبريد الإلكتروني للمحامي
            require_once '../email_config.php';
            $lawyer_stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
            $lawyer_stmt->execute([$lawyer_id]);
            $lawyer = $lawyer_stmt->fetch();
            
            if ($lawyer) {
                notifyLawyerNewCase($lawyer['email'], $lawyer['name'], $title, $_SESSION['user_name']);
            }
            
            $success = 'تم إرسال القضية بنجاح. سيتم التواصل معك قريباً.';
        } else {
            $error = 'حدث خطأ أثناء إرسال القضية';
        }
    } else {
        $error = 'يرجى ملء جميع الحقول';
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <?php include '../includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="../assets/css/mobile-ionic.css">
    <title>قضية جديدة - منصة مكاتب المحاماة</title>
</head>
<body>
    <ion-app>
        <ion-header>
            <ion-toolbar color="primary">
                <ion-buttons slot="start">
                    <ion-back-button default-href="dashboard.php"></ion-back-button>
                </ion-buttons>
                <ion-title>إضافة قضية جديدة</ion-title>
            </ion-toolbar>
        </ion-header>

        <ion-content class="ion-padding">
            <div class="content-section">
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
                                <ion-input label="عنوان القضية:" label-placement="stacked" type="text" name="title" required 
                                           placeholder="مثال: قضية عمالية - فصل تعسفي"></ion-input>
                            </ion-item>
                            
                            <ion-item class="ion-margin-top">
                                <ion-textarea label="تفاصيل القضية:" label-placement="stacked" name="details" rows="8" required 
                                              placeholder="اكتب تفاصيل القضية بشكل واضح ومفصل..."></ion-textarea>
                            </ion-item>
                            
                            <ion-button expand="block" type="submit" class="ion-margin-top">
                                <ion-icon slot="start" name="send"></ion-icon>
                                إرسال القضية
                            </ion-button>
                        </form>
                    </ion-card-content>
                </ion-card>
            </div>
            
            <?php include '../includes/bottom_nav.php'; ?>
        </ion-content>
    </ion-app>
</body>
</html>
