<?php
// إدارة القضايا (للمدير) - يونس ضاعني
require_once __DIR__ . "/../config.php";

// التحقق من تسجيل الدخول ودور المستخدم
if (!isLoggedIn() || !hasRole("admin")) {
    redirect("../login.php");
}

$error = "";
$success = "";

// جلب جميع القضايا
$stmt = $pdo->query("SELECT c.*, cl.name as client_name, l.name as lawyer_name FROM cases c JOIN users cl ON c.client_id = cl.id JOIN users l ON c.lawyer_id = l.id ORDER BY c.created_at DESC");
$cases = $stmt->fetchAll();

// معالجة تحديث حالة القضية أو حذفها
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] == "update_status") {
        $case_id = sanitize_input($_POST["case_id"]);
        $new_status = sanitize_input($_POST["status"]);
        $stmt = $pdo->prepare("UPDATE cases SET status = ? WHERE id = ?");
        if ($stmt->execute([$new_status, $case_id])) {
            $success = "تم تحديث حالة القضية بنجاح.";
            redirect("cases.php");
        } else {
            $error = "حدث خطأ أثناء تحديث حالة القضية.";
        }
    } elseif (isset($_POST["action"]) && $_POST["action"] == "delete_case") {
        $case_id = sanitize_input($_POST["case_id"]);
        $stmt = $pdo->prepare("DELETE FROM cases WHERE id = ?");
        if ($stmt->execute([$case_id])) {
            $success = "تم حذف القضية بنجاح.";
            redirect("cases.php");
        } else {
            $error = "حدث خطأ أثناء حذف القضية.";
        }
    }
}

// تضمين ملف الرأس
view_partial("header");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة القضايا - <?php echo SITENAME; ?></title>
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
                <ion-title>إدارة القضايا</ion-title>
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

                <?php if (!empty($cases)): ?>
                    <ion-list>
                        <?php foreach ($cases as $case): ?>
                            <ion-item-sliding>
                                <ion-item>
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
                                <ion-item-options side="end">
                                    <ion-item-option color="primary" onclick="openCaseDetails(<?php echo $case->id; ?>)">
                                        <ion-icon slot="icon-only" name="eye"></ion-icon>
                                    </ion-item-option>
                                    <ion-item-option color="danger" onclick="deleteCase(<?php echo $case->id; ?>)">
                                        <ion-icon slot="icon-only" name="trash"></ion-icon>
                                    </ion-item-option>
                                </ion-item-options>
                            </ion-item-sliding>
                        <?php endforeach; ?>
                    </ion-list>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="folder-open-outline"></ion-icon>
                        <h3>لا توجد قضايا حالياً.</h3>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script>
        function openCaseDetails(caseId) {
            window.location.href = `<?php echo URLROOT; ?>/lawyer_case_details.php?id=${caseId}`;
        }

        function deleteCase(caseId) {
            if (confirm("هل أنت متأكد من حذف هذه القضية؟")) {
                const form = document.createElement("form");
                form.method = "POST";
                form.action = "cases.php";

                const actionInput = document.createElement("input");
                actionInput.type = "hidden";
                actionInput.name = "action";
                actionInput.value = "delete_case";
                form.appendChild(actionInput);

                const caseIdInput = document.createElement("input");
                caseIdInput.type = "hidden";
                caseIdInput.name = "case_id";
                caseIdInput.value = caseId;
                form.appendChild(caseIdInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js"></script>
</body>
</html>
