<?php
// إدارة المحامين (للمدير) - يونس ضاعني
require_once __DIR__ . "/../config.php";

// التحقق من تسجيل الدخول ودور المستخدم
if (!isLoggedIn() || !hasRole("admin")) {
    redirect("../login.php");
}

$success = "";
$error = "";

// معالجة حذف المحامي
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] == "delete_lawyer") {
        $lawyer_id = sanitize_input($_POST["lawyer_id"]);
        
        // التحقق من عدم وجود قضايا نشطة للمحامي قبل الحذف
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM cases WHERE lawyer_id = ? AND status IN ("new", "in_progress")");
        $stmt->execute([$lawyer_id]);
        $active_cases = $stmt->fetchColumn();
        
        if ($active_cases > 0) {
            $error = "لا يمكن حذف المحامي لوجود قضايا نشطة مرتبطة به.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = "lawyer"");
            if ($stmt->execute([$lawyer_id])) {
                $success = "تم حذف المحامي بنجاح.";
                redirect("lawyers.php");
            } else {
                $error = "حدث خطأ أثناء حذف المحامي.";
            }
        }
    }
}

// جلب جميع المحامين
$stmt = $pdo->query("SELECT id, name, email, created_at FROM users WHERE role = "lawyer" ORDER BY created_at DESC");
$lawyers = $stmt->fetchAll();

// تضمين ملف الرأس
view_partial("header");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المحامين - <?php echo SITENAME; ?></title>
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
                <ion-buttons slot="start">
                    <ion-back-button default-href="dashboard.php"></ion-back-button>
                </ion-buttons>
                <ion-title>إدارة المحامين</ion-title>
            </ion-toolbar>
        </ion-header>
        
        <ion-content class="ion-padding">
            <div class="content-section">
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

                <?php if (!empty($lawyers)): ?>
                    <ion-list>
                        <?php foreach ($lawyers as $lawyer): ?>
                            <ion-item-sliding>
                                <ion-item>
                                    <ion-icon name="person-outline" slot="start"></ion-icon>
                                    <ion-label>
                                        <h3><?php echo htmlspecialchars($lawyer->name); ?></h3>
                                        <p><?php echo htmlspecialchars($lawyer->email); ?></p>
                                    </ion-label>
                                    <ion-note slot="end"><?php echo date("Y-m-d", strtotime($lawyer->created_at)); ?></ion-note>
                                </ion-item>
                                <ion-item-options side="end">
                                    <ion-item-option color="danger" onclick="deleteLawyer(<?php echo $lawyer->id; ?>)">
                                        <ion-icon slot="icon-only" name="trash"></ion-icon>
                                    </ion-item-option>
                                </ion-item-options>
                            </ion-item-sliding>
                        <?php endforeach; ?>
                    </ion-list>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="people-outline"></ion-icon>
                        <h3>لا يوجد محامون مسجلون حالياً.</h3>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script>
        function deleteLawyer(lawyerId) {
            if (confirm("هل أنت متأكد من حذف هذا المحامي؟")) {
                const form = document.createElement("form");
                form.method = "POST";
                form.action = "lawyers.php";

                const actionInput = document.createElement("input");
                actionInput.type = "hidden";
                actionInput.name = "action";
                actionInput.value = "delete_lawyer";
                form.appendChild(actionInput);

                const lawyerIdInput = document.createElement("input");
                lawyerIdInput.type = "hidden";
                lawyerIdInput.name = "lawyer_id";
                lawyerIdInput.value = lawyerId;
                form.appendChild(lawyerIdInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js"></script>
</body>
</html>
