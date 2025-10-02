<?php
require_once 'config.php';

// جلب قائمة المحامين
$stmt = $pdo->prepare("SELECT id, name, email, created_at FROM users WHERE role = 'lawyer' ORDER BY created_at DESC");
$stmt->execute();
$lawyers = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#667eea">
    <title>المحامون - منصة مكاتب المحاماة</title>
    
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
            padding: calc(20px + env(safe-area-inset-top)) 16px 20px;
            color: white;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .back-button {
            margin: 0;
        }
        
        .header-title {
            flex: 1;
        }
        
        .header-title h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 4px;
        }
        
        .header-title p {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        /* Search Bar */
        .search-container {
            padding: 16px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        
        .search-wrapper {
            position: relative;
        }
        
        .search-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 20px;
            z-index: 10;
        }
        
        ion-searchbar {
            --background: #f4f5f8;
            --border-radius: 12px;
            --box-shadow: none;
            --padding-start: 16px;
            --padding-end: 16px;
        }
        
        /* Content */
        .content-section {
            padding: 16px;
            padding-bottom: calc(80px + env(safe-area-inset-bottom));
        }
        
        .section-info {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        
        .section-info ion-icon {
            font-size: 32px;
            color: #667eea;
        }
        
        .section-info-text h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 4px;
        }
        
        .section-info-text p {
            font-size: 0.85rem;
            color: #666;
        }
        
        /* Lawyer Card */
        .lawyer-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s ease;
        }
        
        .lawyer-card:active {
            transform: scale(0.98);
        }
        
        .lawyer-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .lawyer-avatar ion-icon {
            font-size: 32px;
            color: white;
        }
        
        .lawyer-info {
            flex: 1;
        }
        
        .lawyer-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 4px;
        }
        
        .lawyer-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.85rem;
            color: #666;
        }
        
        .lawyer-meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .lawyer-meta-item ion-icon {
            font-size: 16px;
        }
        
        .lawyer-actions {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .rating {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 4px;
        }
        
        .rating ion-icon {
            font-size: 14px;
            color: #ffa500;
        }
        
        .rating span {
            font-size: 0.85rem;
            color: #666;
            margin-right: 4px;
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
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
        }
        
        .empty-state ion-icon {
            font-size: 80px;
            color: #ccc;
            margin-bottom: 20px;
        }
        
        .empty-state h3 {
            font-size: 1.2rem;
            color: #666;
            margin-bottom: 8px;
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
                    <ion-button href="index.php" fill="clear" color="light" class="back-button">
                        <ion-icon slot="icon-only" name="arrow-forward"></ion-icon>
                    </ion-button>
                    <div class="header-title">
                        <h1>المحامون المعتمدون</h1>
                        <p>اختر المحامي المناسب لقضيتك</p>
                    </div>
                </div>
            </div>
            
            <!-- Search Bar -->
            <div class="search-container">
                <ion-searchbar 
                    placeholder="ابحث عن محامي..."
                    animated
                    show-clear-button="focus"
                ></ion-searchbar>
            </div>
            
            <!-- Content -->
            <div class="content-section">
                <div class="section-info">
                    <ion-icon name="information-circle"></ion-icon>
                    <div class="section-info-text">
                        <h3><?php echo count($lawyers); ?> محامي معتمد</h3>
                        <p>جميع المحامين مرخصون ومعتمدون</p>
                    </div>
                </div>
                
                <?php if (count($lawyers) > 0): ?>
                    <?php foreach ($lawyers as $lawyer): ?>
                        <div class="lawyer-card">
                            <div class="lawyer-avatar">
                                <ion-icon name="person"></ion-icon>
                            </div>
                            <div class="lawyer-info">
                                <h3 class="lawyer-name"><?php echo htmlspecialchars($lawyer['name']); ?></h3>
                                <div class="lawyer-meta">
                                    <div class="lawyer-meta-item">
                                        <ion-icon name="mail"></ion-icon>
                                        <span><?php echo htmlspecialchars($lawyer['email']); ?></span>
                                    </div>
                                </div>
                                <div class="rating">
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star"></ion-icon>
                                    <ion-icon name="star-half"></ion-icon>
                                    <span>(4.5)</span>
                                </div>
                            </div>
                            <div class="lawyer-actions">
                                <ion-button size="small" shape="round" href="lawyer_profile.php?id=<?php echo $lawyer['id']; ?>">
                                    <ion-icon slot="start" name="eye"></ion-icon>
                                    عرض
                                </ion-button>
                                <ion-button size="small" fill="outline" shape="round" href="chat.php?lawyer_id=<?php echo $lawyer['id']; ?>">
                                    <ion-icon slot="start" name="chatbubble"></ion-icon>
                                    تواصل
                                </ion-button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="people"></ion-icon>
                        <h3>لا يوجد محامون متاحون</h3>
                        <p>يرجى المحاولة لاحقاً</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Bottom Navigation -->
            <div class="bottom-nav">
                <a href="index.php" class="nav-item">
                    <ion-icon name="home"></ion-icon>
                    <span>الرئيسية</span>
                </a>
                <a href="#services" class="nav-item">
                    <ion-icon name="briefcase"></ion-icon>
                    <span>الخدمات</span>
                </a>
                <a href="lawyers.php" class="nav-item active">
                    <ion-icon name="people"></ion-icon>
                    <span>المحامون</span>
                </a>
                <a href="login.php" class="nav-item">
                    <ion-icon name="person-circle"></ion-icon>
                    <span>حسابي</span>
                </a>
            </div>
        </ion-content>
    </ion-app>
    
    <script>
        // Search functionality
        const searchbar = document.querySelector('ion-searchbar');
        const lawyerCards = document.querySelectorAll('.lawyer-card');
        
        searchbar.addEventListener('ionInput', (event) => {
            const query = event.target.value.toLowerCase();
            
            lawyerCards.forEach(card => {
                const name = card.querySelector('.lawyer-name').textContent.toLowerCase();
                const email = card.querySelector('.lawyer-meta-item span').textContent.toLowerCase();
                
                if (name.includes(query) || email.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
