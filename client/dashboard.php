<?php
require_once '../config.php';

if (!isLoggedIn() || !hasRole('client')) {
    redirect('../login.php');
}

$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];

// جلب القضايا الخاصة بالعميل
$stmt = $pdo->prepare("SELECT c.*, u.name as lawyer_name FROM cases c 
                       JOIN users u ON c.lawyer_id = u.id 
                       WHERE c.client_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$user_id]);
$cases = $stmt->fetchAll();

// جلب الاستشارات
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم العميل - منصة مكاتب المحاماة</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <h3>مرحباً <?php echo htmlspecialchars($user_name); ?></h3>
            <ul>
                <li><a href="dashboard.php">الرئيسية</a></li>
                <li><a href="new_case.php">قضية جديدة</a></li>
                <li><a href="consultations.php">الاستشارات</a></li>
                <li><a href="messages.php">الرسائل</a></li>
                <li><a href="../logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        
        <div class="main-content">
            <div class="header">
                <h1>لوحة تحكم العميل</h1>
                <p>إدارة قضاياك واستشاراتك القانونية</p>
            </div>
            
            <div class="card">
                <h2>القضايا الحالية</h2>
                <?php if (count($cases) > 0): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>عنوان القضية</th>
                                <th>المحامي</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cases as $case): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($case['title']); ?></td>
                                    <td><?php echo htmlspecialchars($case['lawyer_name']); ?></td>
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
                <?php else: ?>
                    <p>لا توجد قضايا حالياً. <a href="new_case.php">أضف قضية جديدة</a></p>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <h2>الاستشارات الأخيرة</h2>
                <?php if (count($consultations) > 0): ?>
                    <div class="consultations-list">
                        <?php foreach (array_slice($consultations, 0, 3) as $consultation): ?>
                            <div class="consultation-item">
                                <h4><?php echo htmlspecialchars(substr($consultation['question'], 0, 100)) . '...'; ?></h4>
                                <p>المحامي: <?php echo htmlspecialchars($consultation['lawyer_name']); ?></p>
                                <p>الحالة: <?php echo $consultation['status'] == 'pending' ? 'في الانتظار' : 'تم الرد'; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="consultations.php" class="btn-primary">عرض جميع الاستشارات</a>
                <?php else: ?>
                    <p>لا توجد استشارات. <a href="consultations.php">اطلب استشارة جديدة</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
