<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('lawyer')) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// معالجة تحديث حالة القضية
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $case_id = $_POST['case_id'];
    $new_status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE cases SET status = ? WHERE id = ? AND lawyer_id = ?");
    if ($stmt->execute([$new_status, $case_id, $user_id])) {
        $success = 'تم تحديث حالة القضية بنجاح';
    } else {
        $error = 'حدث خطأ أثناء تحديث حالة القضية';
    }
}

// جلب جميع القضايا
$filter = $_GET['filter'] ?? 'all';
$where_clause = "WHERE c.lawyer_id = ?";
$params = [$user_id];

if ($filter !== 'all') {
    $where_clause .= " AND c.status = ?";
    $params[] = $filter;
}

$stmt = $pdo->prepare("SELECT c.*, u.name as client_name, u.email as client_email 
                       FROM cases c 
                       JOIN users u ON c.client_id = u.id 
                       $where_clause ORDER BY c.created_at DESC");
$stmt->execute($params);
$cases = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <?php include '../includes/ionic_header.php'; ?>
    <link rel="stylesheet" href="../assets/css/mobile-ionic.css">
    <title>إدارة القضايا - منصة مكاتب المحاماة</title>
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
                <?php if ($success): ?>
                    <ion-item color="success" class="ion-margin-bottom">
                        <ion-icon slot="start" name="checkmark-circle"></ion-icon>
                        <ion-label><?php echo $success; ?></ion-label>
                    </ion-item>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <ion-item color="danger" class="ion-margin-bottom">
                        <ion-icon slot="start" name="alert-circle"></ion-icon>
                        <ion-label><?php echo $error; ?></ion-label>
                    </ion-item>
                <?php endif; ?>
                
                <!-- فلاتر القضايا -->
                <ion-card>
                    <ion-card-header>
                        <ion-card-title>تصفية القضايا</ion-card-title>
                    </ion-card-header>
                    <ion-card-content>
                        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                            <ion-button href="cases.php?filter=all" size="small" fill="<?php echo $filter === 'all' ? 'solid' : 'outline'; ?>">
                                الكل
                            </ion-button>
                            <ion-button href="cases.php?filter=new" size="small" fill="<?php echo $filter === 'new' ? 'solid' : 'outline'; ?>" color="primary">
                                جديدة
                            </ion-button>
                            <ion-button href="cases.php?filter=in_progress" size="small" fill="<?php echo $filter === 'in_progress' ? 'solid' : 'outline'; ?>" color="warning">
                                قيد المعالجة
                            </ion-button>
                            <ion-button href="cases.php?filter=closed" size="small" fill="<?php echo $filter === 'closed' ? 'solid' : 'outline'; ?>" color="success">
                                مغلقة
                            </ion-button>
                            <ion-button href="cases.php?filter=archived" size="small" fill="<?php echo $filter === 'archived' ? 'solid' : 'outline'; ?>" color="medium">
                                مؤرشفة
                            </ion-button>
                        </div>
                    </ion-card-content>
                </ion-card>
                
                <!-- قائمة القضايا -->
                <h2 class="section-title">القضايا (<?php echo count($cases); ?>)</h2>
                
                <?php if (count($cases) > 0): ?>
                    <?php foreach ($cases as $case): ?>
                        <ion-card>
                            <ion-card-header>
                                <div style="display: flex; justify-content: space-between; align-items: start;">
                                    <ion-card-title><?php echo htmlspecialchars($case['title']); ?></ion-card-title>
                                    <span class="status-badge status-<?php echo $case['status']; ?>">
                                        <?php 
                                        $status_names = [
                                            'new' => 'جديدة',
                                            'in_progress' => 'قيد المعالجة',
                                            'closed' => 'مغلقة',
                                            'archived' => 'مؤرشفة'
                                        ];
                                        echo $status_names[$case['status']];
                                        ?>
                                    </span>
                                </div>
                            </ion-card-header>
                            <ion-card-content>
                                <ion-list lines="none">
                                    <ion-item>
                                        <ion-icon name="person" slot="start" color="primary"></ion-icon>
                                        <ion-label>
                                            <p>العميل</p>
                                            <h3><?php echo htmlspecialchars($case['client_name']); ?></h3>
                                        </ion-label>
                                    </ion-item>
                                    <ion-item>
                                        <ion-icon name="mail" slot="start" color="primary"></ion-icon>
                                        <ion-label>
                                            <p>البريد</p>
                                            <h3><?php echo htmlspecialchars($case['client_email']); ?></h3>
                                        </ion-label>
                                    </ion-item>
                                    <ion-item>
                                        <ion-icon name="calendar" slot="start" color="primary"></ion-icon>
                                        <ion-label>
                                            <p>تاريخ الإنشاء</p>
                                            <h3><?php echo date('Y-m-d H:i', strtotime($case['created_at'])); ?></h3>
                                        </ion-label>
                                    </ion-item>
                                </ion-list>
                                
                                <p style="margin-top: 12px; color: #666;">
                                    <?php echo htmlspecialchars(substr($case['details'], 0, 150)) . '...'; ?>
                                </p>
                                
                                <div style="display: flex; gap: 8px; margin-top: 16px; flex-wrap: wrap;">
                                    <ion-button href="case_details.php?id=<?php echo $case['id']; ?>" size="small" fill="solid">
                                        <ion-icon slot="start" name="document-text"></ion-icon>
                                        التفاصيل
                                    </ion-button>
                                    <ion-button href="../chat.php?case_id=<?php echo $case['id']; ?>" size="small" fill="outline">
                                        <ion-icon slot="start" name="chatbubbles"></ion-icon>
                                        محادثة
                                    </ion-button>
                                </div>
                                
                                <!-- تحديث الحالة -->
                                <form method="POST" style="margin-top: 12px;">
                                    <input type="hidden" name="case_id" value="<?php echo $case['id']; ?>">
                                    <input type="hidden" name="update_status" value="1">
                                    <ion-item>
                                        <ion-label>تحديث الحالة:</ion-label>
                                        <ion-select name="status" value="<?php echo $case['status']; ?>" onchange="this.form.submit()">
                                            <ion-select-option value="new">جديدة</ion-select-option>
                                            <ion-select-option value="in_progress">قيد المعالجة</ion-select-option>
                                            <ion-select-option value="closed">مغلقة</ion-select-option>
                                            <ion-select-option value="archived">مؤرشفة</ion-select-option>
                                        </ion-select>
                                    </ion-item>
                                </form>
                            </ion-card-content>
                        </ion-card>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="folder-open"></ion-icon>
                        <h3>لا توجد قضايا</h3>
                        <p>لا توجد قضايا تطابق الفلتر المحدد</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php include '../includes/bottom_nav.php'; ?>
        </ion-content>
    </ion-app>
</body>
</html>
