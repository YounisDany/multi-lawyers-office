<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('admin')) {
    redirect('../login.php');
}

$success = '';
$error = '';

// معالجة حذف أو إيقاف المحامي
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_lawyer'])) {
        $lawyer_id = $_POST['lawyer_id'];
        
        // التحقق من عدم وجود قضايا نشطة للمحامي
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM cases WHERE lawyer_id = ? AND status IN ('new', 'in_progress')");
        $stmt->execute([$lawyer_id]);
        $active_cases = $stmt->fetchColumn();
        
        if ($active_cases > 0) {
            $error = 'لا يمكن حذف المحامي لوجود قضايا نشطة';
        } else {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'lawyer'");
            if ($stmt->execute([$lawyer_id])) {
                $success = 'تم حذف المحامي بنجاح';
            } else {
                $error = 'حدث خطأ أثناء حذف المحامي';
            }
        }
    }
}

// جلب جميع المحامين مع إحصائياتهم
$stmt = $pdo->prepare("SELECT u.*, 
                       (SELECT COUNT(*) FROM cases WHERE lawyer_id = u.id) as total_cases,
                       (SELECT COUNT(*) FROM cases WHERE lawyer_id = u.id AND status IN ('new', 'in_progress')) as active_cases,
                       (SELECT COUNT(*) FROM consultations WHERE lawyer_id = u.id) as total_consultations
                       FROM users u 
                       WHERE u.role = 'lawyer' 
                       ORDER BY u.created_at DESC");
$stmt->execute();
$lawyers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <?php include '../includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="../assets/css/mobile-ionic.css">
    <title>إدارة المحامين - منصة مكاتب المحاماة</title>
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
                        <h1>إدارة المحامين</h1>
                        <p>عرض وإدارة جميع المحامين المسجلين في المنصة</p>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="content-section">
                <?php if ($success): ?>
                    <ion-item color="success">
                        <ion-label><?php echo $success; ?></ion-label>
                    </ion-item>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <ion-item color="danger">
                        <ion-label><?php echo $error; ?></ion-label>
                    </ion-item>
                <?php endif; ?>
                
                <h2 class="section-title">قائمة المحامين (<?php echo count($lawyers); ?>)</h2>
                
                <?php if (count($lawyers) > 0): ?>
                    <ion-list>
                        <?php foreach ($lawyers as $lawyer): ?>
                            <ion-item-sliding>
                                <ion-item detail href="lawyer_details.php?id=<?php echo $lawyer['id']; ?>">
                                    <ion-avatar slot="start">
                                        <ion-icon name="person-circle-outline"></ion-icon>
                                    </ion-avatar>
                                    <ion-label>
                                        <h3><?php echo htmlspecialchars($lawyer['name']); ?></h3>
                                        <p><?php echo htmlspecialchars($lawyer['email']); ?></p>
                                        <div class="ion-text-right">
                                            <span class="status-badge status-active">نشط</span>
                                        </div>
                                    </ion-label>
                                </ion-item>
                                <ion-item-options side="end">
                                    <ion-item-option href="lawyer_details.php?id=<?php echo $lawyer['id']; ?>">
                                        <ion-icon slot="icon-only" name="eye"></ion-icon>
                                    </ion-item-option>
                                    <?php if ($lawyer['active_cases'] == 0): ?>
                                        <ion-item-option color="danger" onclick="confirmDelete(<?php echo $lawyer['id']; ?>)">
                                            <ion-icon slot="icon-only" name="trash"></ion-icon>
                                        </ion-item-option>
                                    <?php endif; ?>
                                </ion-item-options>
                            </ion-item-sliding>
                        <?php endforeach; ?>
                    </ion-list>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="people-outline"></ion-icon>
                        <h3>لا يوجد محامون مسجلون في المنصة بعد.</h3>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php include '../includes/bottom_nav.php'; ?>
        </ion-content>
    </ion-app>

    <script>
        function confirmDelete(lawyerId) {
            if (confirm('هل أنت متأكد من حذف هذا المحامي؟')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';

                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'lawyer_id';
                inputId.value = lawyerId;
                form.appendChild(inputId);

                const inputDelete = document.createElement('input');
                inputDelete.type = 'hidden';
                inputDelete.name = 'delete_lawyer';
                inputDelete.value = '1';
                form.appendChild(inputDelete);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
