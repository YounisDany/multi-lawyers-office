<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('lawyer')) {
    redirect('../login.php');
}

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

// جلب إحصائيات المحامي
$stmt = $pdo->prepare("SELECT COUNT(*) as total_cases FROM cases WHERE lawyer_id = ?");
$stmt->execute([$user_id]);
$total_cases = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) as new_cases FROM cases WHERE lawyer_id = ? AND status = 'new'");
$stmt->execute([$user_id]);
$new_cases = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) as pending_consultations FROM consultations WHERE lawyer_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$pending_consultations = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) as total_clients FROM cases WHERE lawyer_id = ? GROUP BY client_id");
$stmt->execute([$user_id]);
$total_clients = $stmt->rowCount();

// جلب القضايا الحديثة
$stmt = $pdo->prepare("SELECT c.*, u.name as client_name FROM cases c 
                       JOIN users u ON c.client_id = u.id 
                       WHERE c.lawyer_id = ? ORDER BY c.created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$recent_cases = $stmt->fetchAll();

// جلب الاستشارات المعلقة
$stmt = $pdo->prepare("SELECT co.*, u.name as client_name FROM consultations co 
                       JOIN users u ON co.client_id = u.id 
                       WHERE co.lawyer_id = ? AND co.status = 'pending' ORDER BY co.created_at DESC LIMIT 3");
$stmt->execute([$user_id]);
$pending_consultations_list = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المحامي - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>مرحباً <?php echo htmlspecialchars($user_name); ?></h3>
            <ul>
                <li><a href="dashboard.php" class="active">الرئيسية</a></li>
                <li><a href="cases.php">إدارة القضايا</a></li>
                <li><a href="consultations.php">الاستشارات</a></li>
                <li><a href="clients.php">العملاء</a></li>
                <li><a href="reports.php">التقارير</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>لوحة تحكم المحامي</h1>
                <p>إدارة قضاياك وعملائك واستشاراتك</p>
            </div>
            
            <!-- إحصائيات سريعة -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_cases; ?></div>
                    <div class="stat-label">إجمالي القضايا</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $new_cases; ?></div>
                    <div class="stat-label">قضايا جديدة</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $pending_consultations; ?></div>
                    <div class="stat-label">استشارات معلقة</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_clients; ?></div>
                    <div class="stat-label">العملاء</div>
                </div>
            </div>
            
            <!-- القضايا الحديثة -->
            <div class="card">
                <h2>القضايا الحديثة</h2>
                <?php if (count($recent_cases) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>عنوان القضية</th>
                                <th>العميل</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_cases as $case): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($case['title']); ?></td>
                                    <td><?php echo htmlspecialchars($case['client_name']); ?></td>
                                    <td>
                                        <span class="status-<?php echo $case['status']; ?>">
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
                                    </td>
                                    <td><?php echo date('Y-m-d', strtotime($case['created_at'])); ?></td>
                                    <td>
                                        <a href="case_details.php?id=<?php echo $case['id']; ?>" class="btn-small">عرض</a>
                                        <a href="chat.php?case_id=<?php echo $case['id']; ?>" class="btn-small">محادثة</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <a href="cases.php" class="btn-primary">عرض جميع القضايا</a>
                    </div>
                <?php else: ?>
                    <p>لا توجد قضايا حالياً.</p>
                <?php endif; ?>
            </div>
            
            <!-- الاستشارات المعلقة -->
            <div class="card">
                <h2>الاستشارات المعلقة</h2>
                <?php if (count($pending_consultations_list) > 0): ?>
                    <div class="consultations-list">
                        <?php foreach ($pending_consultations_list as $consultation): ?>
                            <div class="consultation-item">
                                <div class="consultation-header">
                                    <h4>استشارة من <?php echo htmlspecialchars($consultation['client_name']); ?></h4>
                                    <span class="consultation-date"><?php echo date('Y-m-d H:i', strtotime($consultation['created_at'])); ?></span>
                                </div>
                                
                                <div class="consultation-question">
                                    <p><?php echo htmlspecialchars(substr($consultation['question'], 0, 150)) . '...'; ?></p>
                                </div>
                                
                                <div class="consultation-actions">
                                    <a href="consultation_details.php?id=<?php echo $consultation['id']; ?>" class="btn-small">عرض والرد</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="card-footer">
                        <a href="consultations.php" class="btn-primary">عرض جميع الاستشارات</a>
                    </div>
                <?php else: ?>
                    <p>لا توجد استشارات معلقة.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
