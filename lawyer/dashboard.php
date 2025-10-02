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

$stmt = $pdo->prepare("SELECT COUNT(DISTINCT client_id) as total_clients FROM cases WHERE lawyer_id = ?");
$stmt->execute([$user_id]);
$total_clients = $stmt->fetchColumn();

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
    <?php include '../includes/ionic_header.php'; ?>
    <title>لوحة تحكم المحامي - منصة مكاتب المحاماة</title>
    
    <style>
        .app-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: calc(20px + env(safe-area-inset-top)) 16px 20px;
            color: white;
        }
        
        .header-content h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        
        .header-content p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .content-section {
            padding: 16px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 0.85rem;
            color: #666;
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 20px 0 12px;
        }
        
        .case-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        
        .case-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
        }
        
        .case-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 4px;
        }
        
        .case-client {
            font-size: 0.85rem;
            color: #666;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .case-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-new {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .status-in_progress {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .status-closed {
            background: #e8f5e9;
            color: #388e3c;
        }
        
        .case-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #f0f0f0;
        }
        
        .case-date {
            font-size: 0.8rem;
            color: #999;
        }
        
        .case-actions {
            display: flex;
            gap: 8px;
        }
        
        .consultation-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        
        .consultation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .consultation-header h4 {
            font-size: 1rem;
            font-weight: 700;
            color: #1a1a1a;
        }
        
        .consultation-date {
            font-size: 0.75rem;
            color: #999;
        }
        
        .consultation-question {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.5;
            margin-bottom: 12px;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 16px;
        }
        
        .empty-state ion-icon {
            font-size: 60px;
            color: #ccc;
            margin-bottom: 16px;
        }
        
        .empty-state p {
            font-size: 0.95rem;
            color: #999;
        }
    </style>
</head>
<body>
    <ion-app>
        <ion-content>
            <!-- Header -->
            <div class="app-header">
                <div class="header-content">
                    <h1>مرحباً <?php echo htmlspecialchars($user_name); ?></h1>
                    <p>إدارة قضاياك وعملائك واستشاراتك</p>
                </div>
            </div>
            
            <!-- Content -->
            <div class="content-section">
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
                <h2 class="section-title">القضايا الحديثة</h2>
                <?php if (count($recent_cases) > 0): ?>
                    <?php foreach ($recent_cases as $case): ?>
                        <div class="case-card">
                            <div class="case-header">
                                <div>
                                    <h3 class="case-title"><?php echo htmlspecialchars($case['title']); ?></h3>
                                    <div class="case-client">
                                        <ion-icon name="person"></ion-icon>
                                        <?php echo htmlspecialchars($case['client_name']); ?>
                                    </div>
                                </div>
                                <span class="case-status status-<?php echo $case['status']; ?>">
                                    <?php 
                                    $status_names = [
                                        'new' => 'جديدة',
                                        'in_progress' => 'قيد المعالجة',
                                        'closed' => 'مغلقة',
                                        'archived' => 'مؤرشفة'
                                    ];
                                    echo $status_names[$case['status']] ?? $case['status'];
                                    ?>
                                </span>
                            </div>
                            
                            <div class="case-meta">
                                <span class="case-date">
                                    <ion-icon name="calendar"></ion-icon>
                                    <?php echo date('Y-m-d', strtotime($case['created_at'])); ?>
                                </span>
                                <div class="case-actions">
                                    <ion-button size="small" fill="outline" shape="round" href="cases.php?id=<?php echo $case['id']; ?>">
                                        <ion-icon slot="start" name="eye"></ion-icon>
                                        عرض
                                    </ion-button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <ion-button expand="block" shape="round" href="cases.php">
                        عرض جميع القضايا
                    </ion-button>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="briefcase"></ion-icon>
                        <p>لا توجد قضايا حالياً</p>
                    </div>
                <?php endif; ?>
                
                <!-- الاستشارات المعلقة -->
                <h2 class="section-title">الاستشارات المعلقة</h2>
                <?php if (count($pending_consultations_list) > 0): ?>
                    <?php foreach ($pending_consultations_list as $consultation): ?>
                        <div class="consultation-card">
                            <div class="consultation-header">
                                <h4>استشارة من <?php echo htmlspecialchars($consultation['client_name']); ?></h4>
                                <span class="consultation-date"><?php echo date('Y-m-d H:i', strtotime($consultation['created_at'])); ?></span>
                            </div>
                            
                            <div class="consultation-question">
                                <p><?php echo htmlspecialchars(substr($consultation['question'], 0, 150)) . '...'; ?></p>
                            </div>
                            
                            <ion-button size="small" fill="outline" shape="round" href="consultations.php?id=<?php echo $consultation['id']; ?>">
                                <ion-icon slot="start" name="chatbubble-ellipses"></ion-icon>
                                عرض والرد
                            </ion-button>
                        </div>
                    <?php endforeach; ?>
                    
                    <ion-button expand="block" shape="round" href="consultations.php">
                        عرض جميع الاستشارات
                    </ion-button>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="chatbubble-ellipses"></ion-icon>
                        <p>لا توجد استشارات معلقة</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php include '../includes/bottom_nav.php'; ?>
        </ion-content>
    </ion-app>
</body>
</html>
