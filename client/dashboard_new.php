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

// إحصائيات سريعة
$total_cases = count($cases);
$active_cases = count(array_filter($cases, function($case) { return $case['status'] == 'in_progress'; }));
$pending_consultations = count(array_filter($consultations, function($c) { return $c['status'] == 'pending'; }));
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>لوحة تحكم العميل - منصة مكاتب المحاماة</title>
    
    <!-- Ionic Framework -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />
    
    <!-- Ionicons -->
    <script type="module">
        import { defineCustomElements } from 'https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js';
        defineCustomElements(window);
    </script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f4f5f8;
        }
        
        :root {
            --ion-color-primary: #667eea;
            --ion-color-primary-rgb: 102, 126, 234;
            --ion-color-primary-contrast: #ffffff;
            --ion-color-primary-contrast-rgb: 255, 255, 255;
            --ion-color-primary-shade: #5a6fce;
            --ion-color-primary-tint: #758dec;
        }
        
        /* Header */
        .app-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 16px calc(20px + env(safe-area-inset-top));
            padding-top: calc(20px + env(safe-area-inset-top));
            color: white;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .user-info h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        
        .user-info p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .header-actions {
            display: flex;
            gap: 8px;
        }
        
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: -40px;
            padding: 0 16px 20px;
            position: relative;
            z-index: 10;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 16px 12px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .stat-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
            display: block;
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 0.75rem;
            color: #666;
        }
        
        /* Content Section */
        .content-section {
            padding: 0 16px 100px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            margin-top: 24px;
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a1a1a;
        }
        
        .section-link {
            font-size: 0.85rem;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        /* Case Card */
        .case-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            position: relative;
            overflow: hidden;
        }
        
        .case-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .case-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
        }
        
        .case-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 4px;
        }
        
        .case-status {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
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
            align-items: center;
            gap: 16px;
            margin-bottom: 12px;
            font-size: 0.85rem;
            color: #666;
        }
        
        .case-meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .case-meta-item ion-icon {
            font-size: 16px;
        }
        
        .case-actions {
            display: flex;
            gap: 8px;
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }
        
        .action-button {
            background: white;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            text-decoration: none;
            color: #1a1a1a;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
        }
        
        .action-button:active {
            transform: scale(0.98);
        }
        
        .action-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }
        
        .action-icon ion-icon {
            font-size: 24px;
            color: white;
        }
        
        .action-label {
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 16px;
        }
        
        .empty-state ion-icon {
            font-size: 64px;
            color: #ccc;
            margin-bottom: 16px;
        }
        
        .empty-state h3 {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 8px;
        }
        
        .empty-state p {
            font-size: 0.9rem;
            color: #999;
        }
        
        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-around;
            padding: 8px 0 calc(8px + env(safe-area-inset-bottom));
            z-index: 1000;
        }
        
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 8px 16px;
            text-decoration: none;
            color: #666;
            transition: all 0.3s ease;
        }
        
        .nav-item.active {
            color: #667eea;
        }
        
        .nav-item ion-icon {
            font-size: 24px;
            margin-bottom: 4px;
        }
        
        .nav-item span {
            font-size: 0.7rem;
            font-weight: 500;
        }
        
        /* Floating Action Button */
        .fab-button {
            position: fixed;
            bottom: calc(70px + env(safe-area-inset-bottom));
            left: 20px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            text-decoration: none;
            transition: all 0.3s ease;
            z-index: 999;
        }
        
        .fab-button:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body>
    <ion-app>
        <ion-content>
            <!-- Header -->
            <div class="app-header">
                <div class="header-top">
                    <div class="user-info">
                        <h2>مرحباً، <?php echo htmlspecialchars($user_name); ?></h2>
                        <p>إدارة قضاياك واستشاراتك القانونية</p>
                    </div>
                    <div class="header-actions">
                        <ion-button fill="clear" color="light" href="messages.php">
                            <ion-icon slot="icon-only" name="notifications"></ion-icon>
                        </ion-button>
                    </div>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <ion-icon name="folder" class="stat-icon" style="color: #667eea;"></ion-icon>
                    <span class="stat-number"><?php echo $total_cases; ?></span>
                    <span class="stat-label">إجمالي القضايا</span>
                </div>
                <div class="stat-card">
                    <ion-icon name="pulse" class="stat-icon" style="color: #f57c00;"></ion-icon>
                    <span class="stat-number"><?php echo $active_cases; ?></span>
                    <span class="stat-label">قضايا نشطة</span>
                </div>
                <div class="stat-card">
                    <ion-icon name="chatbubbles" class="stat-icon" style="color: #764ba2;"></ion-icon>
                    <span class="stat-number"><?php echo $pending_consultations; ?></span>
                    <span class="stat-label">استشارات</span>
                </div>
            </div>
            
            <!-- Content -->
            <div class="content-section">
                <!-- Quick Actions -->
                <div class="section-header">
                    <h3 class="section-title">إجراءات سريعة</h3>
                </div>
                <div class="quick-actions">
                    <a href="new_case.php" class="action-button">
                        <div class="action-icon">
                            <ion-icon name="add-circle"></ion-icon>
                        </div>
                        <div class="action-label">قضية جديدة</div>
                    </a>
                    <a href="consultations.php" class="action-button">
                        <div class="action-icon">
                            <ion-icon name="help-circle"></ion-icon>
                        </div>
                        <div class="action-label">استشارة</div>
                    </a>
                </div>
                
                <!-- Recent Cases -->
                <div class="section-header">
                    <h3 class="section-title">القضايا الحالية</h3>
                    <?php if (count($cases) > 3): ?>
                        <a href="#" class="section-link">عرض الكل</a>
                    <?php endif; ?>
                </div>
                
                <?php if (count($cases) > 0): ?>
                    <?php foreach (array_slice($cases, 0, 3) as $case): ?>
                        <div class="case-card">
                            <div class="case-header">
                                <div>
                                    <h4 class="case-title"><?php echo htmlspecialchars($case['title']); ?></h4>
                                </div>
                                <span class="case-status status-<?php echo $case['status']; ?>">
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
                            <div class="case-meta">
                                <div class="case-meta-item">
                                    <ion-icon name="person"></ion-icon>
                                    <span><?php echo htmlspecialchars($case['lawyer_name']); ?></span>
                                </div>
                                <div class="case-meta-item">
                                    <ion-icon name="calendar"></ion-icon>
                                    <span><?php echo date('Y-m-d', strtotime($case['created_at'])); ?></span>
                                </div>
                            </div>
                            <div class="case-actions">
                                <ion-button href="case_details.php?id=<?php echo $case['id']; ?>" size="small" fill="outline" shape="round">
                                    <ion-icon slot="start" name="eye"></ion-icon>
                                    عرض
                                </ion-button>
                                <ion-button href="../chat.php?case_id=<?php echo $case['id']; ?>" size="small" shape="round">
                                    <ion-icon slot="start" name="chatbubble"></ion-icon>
                                    محادثة
                                </ion-button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="folder-open"></ion-icon>
                        <h3>لا توجد قضايا حالياً</h3>
                        <p>ابدأ بإضافة قضية جديدة</p>
                    </div>
                <?php endif; ?>
                
                <!-- Recent Consultations -->
                <?php if (count($consultations) > 0): ?>
                    <div class="section-header">
                        <h3 class="section-title">الاستشارات الأخيرة</h3>
                        <a href="consultations.php" class="section-link">عرض الكل</a>
                    </div>
                    
                    <?php foreach (array_slice($consultations, 0, 2) as $consultation): ?>
                        <div class="case-card">
                            <div class="case-header">
                                <div>
                                    <h4 class="case-title"><?php echo htmlspecialchars(substr($consultation['question'], 0, 60)) . '...'; ?></h4>
                                </div>
                                <span class="case-status <?php echo $consultation['status'] == 'pending' ? 'status-new' : 'status-closed'; ?>">
                                    <?php echo $consultation['status'] == 'pending' ? 'في الانتظار' : 'تم الرد'; ?>
                                </span>
                            </div>
                            <div class="case-meta">
                                <div class="case-meta-item">
                                    <ion-icon name="person"></ion-icon>
                                    <span><?php echo htmlspecialchars($consultation['lawyer_name']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Floating Action Button -->
            <a href="../chat.php" class="fab-button">
                <ion-icon name="chatbubble-ellipses"></ion-icon>
            </a>
            
            <!-- Bottom Navigation -->
            <div class="bottom-nav">
                <a href="dashboard.php" class="nav-item active">
                    <ion-icon name="home"></ion-icon>
                    <span>الرئيسية</span>
                </a>
                <a href="new_case.php" class="nav-item">
                    <ion-icon name="add-circle"></ion-icon>
                    <span>قضية جديدة</span>
                </a>
                <a href="consultations.php" class="nav-item">
                    <ion-icon name="chatbubbles"></ion-icon>
                    <span>الاستشارات</span>
                </a>
                <a href="messages.php" class="nav-item">
                    <ion-icon name="mail"></ion-icon>
                    <span>الرسائل</span>
                </a>
                <a href="../logout.php" class="nav-item">
                    <ion-icon name="log-out"></ion-icon>
                    <span>خروج</span>
                </a>
            </div>
        </ion-content>
    </ion-app>
</body>
</html>
