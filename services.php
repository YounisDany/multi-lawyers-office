<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#667eea">
    <title>الخدمات القانونية - منصة مكاتب المحاماة</title>
    
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
            padding: calc(20px + env(safe-area-inset-top)) 16px 30px;
            color: white;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }
        
        .back-button {
            margin: 0;
        }
        
        .header-title h1 {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .header-description {
            font-size: 0.95rem;
            opacity: 0.9;
            line-height: 1.5;
        }
        
        /* Content */
        .content-section {
            padding: 0 16px calc(80px + env(safe-area-inset-bottom));
            margin-top: -20px;
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 24px 0 16px;
        }
        
        /* Service Card */
        .service-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .service-card:active {
            transform: scale(0.98);
        }
        
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }
        
        .service-card.civil::before {
            background: #667eea;
        }
        
        .service-card.criminal::before {
            background: #f57c00;
        }
        
        .service-card.commercial::before {
            background: #4caf50;
        }
        
        .service-card.family::before {
            background: #e91e63;
        }
        
        .service-card.labor::before {
            background: #9c27b0;
        }
        
        .service-card.real-estate::before {
            background: #00bcd4;
        }
        
        .service-header {
            display: flex;
            align-items: start;
            gap: 16px;
            margin-bottom: 12px;
        }
        
        .service-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .service-icon ion-icon {
            font-size: 28px;
            color: white;
        }
        
        .service-card.civil .service-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .service-card.criminal .service-icon {
            background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%);
        }
        
        .service-card.commercial .service-icon {
            background: linear-gradient(135deg, #4caf50 0%, #8bc34a 100%);
        }
        
        .service-card.family .service-icon {
            background: linear-gradient(135deg, #e91e63 0%, #f06292 100%);
        }
        
        .service-card.labor .service-icon {
            background: linear-gradient(135deg, #9c27b0 0%, #ba68c8 100%);
        }
        
        .service-card.real-estate .service-icon {
            background: linear-gradient(135deg, #00bcd4 0%, #4dd0e1 100%);
        }
        
        .service-info {
            flex: 1;
        }
        
        .service-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 6px;
        }
        
        .service-subtitle {
            font-size: 0.85rem;
            color: #666;
        }
        
        .service-description {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 16px;
        }
        
        .service-features {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }
        
        .feature-tag {
            background: #f4f5f8;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: #666;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .feature-tag ion-icon {
            font-size: 14px;
        }
        
        .service-action {
            display: flex;
            gap: 8px;
        }
        
        /* Stats Section */
        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #667eea;
            display: block;
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #666;
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
                        <h1>الخدمات القانونية</h1>
                    </div>
                </div>
                <p class="header-description">نقدم مجموعة شاملة من الخدمات القانونية المتخصصة لتلبية جميع احتياجاتك</p>
            </div>
            
            <!-- Content -->
            <div class="content-section">
                <!-- Stats -->
                <div class="stats-card">
                    <div class="stat-item">
                        <span class="stat-number">6</span>
                        <span class="stat-label">تخصصات</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">محامي</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">دعم</span>
                    </div>
                </div>
                
                <h2 class="section-title">التخصصات القانونية</h2>
                
                <!-- Civil Law -->
                <div class="service-card civil">
                    <div class="service-header">
                        <div class="service-icon">
                            <ion-icon name="document-text"></ion-icon>
                        </div>
                        <div class="service-info">
                            <h3 class="service-title">القانون المدني</h3>
                            <p class="service-subtitle">حقوق وعقود مدنية</p>
                        </div>
                    </div>
                    <p class="service-description">
                        نقدم خدمات قانونية شاملة في المسائل المدنية بما في ذلك العقود والتعويضات والمنازعات المدنية
                    </p>
                    <div class="service-features">
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            صياغة العقود
                        </span>
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            التقاضي المدني
                        </span>
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            التعويضات
                        </span>
                    </div>
                    <div class="service-action">
                        <ion-button size="small" shape="round" expand="block">
                            <ion-icon slot="start" name="call"></ion-icon>
                            استشارة فورية
                        </ion-button>
                    </div>
                </div>
                
                <!-- Criminal Law -->
                <div class="service-card criminal">
                    <div class="service-header">
                        <div class="service-icon">
                            <ion-icon name="shield-checkmark"></ion-icon>
                        </div>
                        <div class="service-info">
                            <h3 class="service-title">القانون الجنائي</h3>
                            <p class="service-subtitle">دفاع جنائي متخصص</p>
                        </div>
                    </div>
                    <p class="service-description">
                        فريق متخصص في الدفاع الجنائي وحماية حقوقك في جميع مراحل التحقيق والمحاكمة
                    </p>
                    <div class="service-features">
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            الدفاع الجنائي
                        </span>
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            الاستئناف
                        </span>
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            التحقيقات
                        </span>
                    </div>
                    <div class="service-action">
                        <ion-button size="small" shape="round" expand="block">
                            <ion-icon slot="start" name="call"></ion-icon>
                            استشارة فورية
                        </ion-button>
                    </div>
                </div>
                
                <!-- Commercial Law -->
                <div class="service-card commercial">
                    <div class="service-header">
                        <div class="service-icon">
                            <ion-icon name="briefcase"></ion-icon>
                        </div>
                        <div class="service-info">
                            <h3 class="service-title">القانون التجاري</h3>
                            <p class="service-subtitle">حلول قانونية للأعمال</p>
                        </div>
                    </div>
                    <p class="service-description">
                        استشارات قانونية متخصصة للشركات والمؤسسات التجارية في جميع المعاملات التجارية
                    </p>
                    <div class="service-features">
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            تأسيس الشركات
                        </span>
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            العقود التجارية
                        </span>
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            الإفلاس
                        </span>
                    </div>
                    <div class="service-action">
                        <ion-button size="small" shape="round" expand="block">
                            <ion-icon slot="start" name="call"></ion-icon>
                            استشارة فورية
                        </ion-button>
                    </div>
                </div>
                
                <!-- Family Law -->
                <div class="service-card family">
                    <div class="service-header">
                        <div class="service-icon">
                            <ion-icon name="people"></ion-icon>
                        </div>
                        <div class="service-info">
                            <h3 class="service-title">قانون الأسرة</h3>
                            <p class="service-subtitle">قضايا أسرية حساسة</p>
                        </div>
                    </div>
                    <p class="service-description">
                        معالجة القضايا الأسرية بحساسية واحترافية بما في ذلك الطلاق والحضانة والنفقة
                    </p>
                    <div class="service-features">
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            الطلاق
                        </span>
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            الحضانة
                        </span>
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            النفقة
                        </span>
                    </div>
                    <div class="service-action">
                        <ion-button size="small" shape="round" expand="block">
                            <ion-icon slot="start" name="call"></ion-icon>
                            استشارة فورية
                        </ion-button>
                    </div>
                </div>
                
                <!-- Labor Law -->
                <div class="service-card labor">
                    <div class="service-header">
                        <div class="service-icon">
                            <ion-icon name="business"></ion-icon>
                        </div>
                        <div class="service-info">
                            <h3 class="service-title">قانون العمل</h3>
                            <p class="service-subtitle">حقوق العمال وأصحاب العمل</p>
                        </div>
                    </div>
                    <p class="service-description">
                        حماية حقوق العمال وأصحاب العمل في جميع المنازعات العمالية والتأمينات الاجتماعية
                    </p>
                    <div class="service-features">
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            عقود العمل
                        </span>
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            الفصل التعسفي
                        </span>
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            التأمينات
                        </span>
                    </div>
                    <div class="service-action">
                        <ion-button size="small" shape="round" expand="block">
                            <ion-icon slot="start" name="call"></ion-icon>
                            استشارة فورية
                        </ion-button>
                    </div>
                </div>
                
                <!-- Real Estate Law -->
                <div class="service-card real-estate">
                    <div class="service-header">
                        <div class="service-icon">
                            <ion-icon name="home"></ion-icon>
                        </div>
                        <div class="service-info">
                            <h3 class="service-title">قانون العقارات</h3>
                            <p class="service-subtitle">معاملات عقارية آمنة</p>
                        </div>
                    </div>
                    <p class="service-description">
                        خدمات قانونية شاملة في المعاملات العقارية والملكية والإيجارات والنزاعات العقارية
                    </p>
                    <div class="service-features">
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            البيع والشراء
                        </span>
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            الإيجارات
                        </span>
                        <span class="feature-tag">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            الملكية
                        </span>
                    </div>
                    <div class="service-action">
                        <ion-button size="small" shape="round" expand="block">
                            <ion-icon slot="start" name="call"></ion-icon>
                            استشارة فورية
                        </ion-button>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Navigation -->
            <div class="bottom-nav">
                <a href="index.php" class="nav-item">
                    <ion-icon name="home"></ion-icon>
                    <span>الرئيسية</span>
                </a>
                <a href="services.php" class="nav-item active">
                    <ion-icon name="briefcase"></ion-icon>
                    <span>الخدمات</span>
                </a>
                <a href="lawyers.php" class="nav-item">
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
</body>
</html>
