<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>منصة مكاتب المحاماة أونلاين</title>
    
    <!-- Ionic Framework -->
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />
    
    <!-- Ionicons -->
    <link href="https://unpkg.com/ionicons@5.5.2/dist/css/ionicons.min.css" rel="stylesheet">
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
        
        /* Custom Ionic Theme */
        :root {
            --ion-color-primary: #667eea;
            --ion-color-primary-rgb: 102, 126, 234;
            --ion-color-primary-contrast: #ffffff;
            --ion-color-primary-contrast-rgb: 255, 255, 255;
            --ion-color-primary-shade: #5a6fce;
            --ion-color-primary-tint: #758dec;
            
            --ion-color-secondary: #764ba2;
            --ion-color-secondary-rgb: 118, 75, 162;
            --ion-color-secondary-contrast: #ffffff;
            --ion-color-secondary-contrast-rgb: 255, 255, 255;
            --ion-color-secondary-shade: #68428f;
            --ion-color-secondary-tint: #845dab;
        }
        
        /* Hero Section with Gradient */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 60px 20px 40px;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 12px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .hero-subtitle {
            font-size: 1rem;
            opacity: 0.95;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        .hero-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        /* Feature Cards */
        .features-section {
            padding: 20px 16px;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        
        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        
        .feature-card:active {
            transform: scale(0.98);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .feature-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 6px;
        }
        
        .feature-description {
            font-size: 0.8rem;
            color: #666;
            line-height: 1.4;
        }
        
        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px 20px;
            margin: 20px 16px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .stat-item {
            text-align: center;
            color: white;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            display: block;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        /* Action Cards */
        .action-section {
            padding: 20px 16px;
        }
        
        .action-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .action-card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
        }
        
        .action-card-text {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 16px;
            line-height: 1.5;
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
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 999;
        }
        
        .fab-button:active {
            transform: scale(0.95);
        }
        
        /* Content Padding for Bottom Nav */
        .content-wrapper {
            padding-bottom: calc(80px + env(safe-area-inset-bottom));
        }
        
        /* Responsive */
        @media (min-width: 768px) {
            .features-grid {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
            
            .hero-title {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <ion-app>
        <ion-content>
            <div class="content-wrapper">
                <!-- Hero Section -->
                <div class="hero-section">
                    <div class="hero-content">
                        <h1 class="hero-title">منصة مكاتب المحاماة</h1>
                        <p class="hero-subtitle">منصة متكاملة تربط بين المحامين والعملاء لتقديم الخدمات القانونية بكفاءة وسهولة</p>
                        <div class="hero-buttons">
                            <ion-button href="register.php" color="light" size="default" shape="round">
                                <ion-icon slot="start" name="person-add"></ion-icon>
                                ابدأ الآن
                            </ion-button>
                            <ion-button href="login.php" fill="outline" color="light" size="default" shape="round">
                                <ion-icon slot="start" name="log-in"></ion-icon>
                                تسجيل الدخول
                            </ion-button>
                        </div>
                    </div>
                </div>
                
                <!-- Features Section -->
                <div class="features-section">
                    <h2 class="section-title">لماذا تختار منصتنا؟</h2>
                    <div class="features-grid">
                        <div class="feature-card">
                            <ion-icon name="scale" class="feature-icon" style="color: #667eea;"></ion-icon>
                            <h3 class="feature-title">خدمات متخصصة</h3>
                            <p class="feature-description">استشارات قانونية من محامين معتمدين</p>
                        </div>
                        
                        <div class="feature-card">
                            <ion-icon name="chatbubbles" class="feature-icon" style="color: #764ba2;"></ion-icon>
                            <h3 class="feature-title">تواصل مباشر</h3>
                            <p class="feature-description">نظام دردشة متطور وسريع</p>
                        </div>
                        
                        <div class="feature-card">
                            <ion-icon name="stats-chart" class="feature-icon" style="color: #667eea;"></ion-icon>
                            <h3 class="feature-title">متابعة شاملة</h3>
                            <p class="feature-description">تقارير مفصلة عن التقدم</p>
                        </div>
                        
                        <div class="feature-card">
                            <ion-icon name="shield-checkmark" class="feature-icon" style="color: #764ba2;"></ion-icon>
                            <h3 class="feature-title">أمان وخصوصية</h3>
                            <p class="feature-description">حماية كاملة لبياناتك</p>
                        </div>
                        
                        <div class="feature-card">
                            <ion-icon name="phone-portrait" class="feature-icon" style="color: #667eea;"></ion-icon>
                            <h3 class="feature-title">سهولة الاستخدام</h3>
                            <p class="feature-description">واجهة بسيطة وسهلة</p>
                        </div>
                        
                        <div class="feature-card">
                            <ion-icon name="time" class="feature-icon" style="color: #764ba2;"></ion-icon>
                            <h3 class="feature-title">متاح 24/7</h3>
                            <p class="feature-description">خدمة على مدار الساعة</p>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Section -->
                <div class="stats-section">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-number" data-count="500">500+</span>
                            <span class="stat-label">محامي معتمد</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="2000">2000+</span>
                            <span class="stat-label">عميل راضي</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="5000">5000+</span>
                            <span class="stat-label">قضية منجزة</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" data-count="10000">10K+</span>
                            <span class="stat-label">استشارة قانونية</span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Section -->
                <div class="action-section">
                    <ion-card class="action-card">
                        <h3 class="action-card-title">ابدأ رحلتك القانونية اليوم</h3>
                        <p class="action-card-text">انضم إلى آلاف العملاء والمحامين الذين يثقون في منصتنا</p>
                        <ion-button href="register.php" expand="block" shape="round" color="primary">
                            <ion-icon slot="start" name="rocket"></ion-icon>
                            إنشاء حساب جديد
                        </ion-button>
                    </ion-card>
                    
                    <ion-card class="action-card">
                        <h3 class="action-card-title">لديك حساب بالفعل؟</h3>
                        <p class="action-card-text">سجل دخولك للوصول إلى لوحة التحكم الخاصة بك</p>
                        <ion-button href="login.php" expand="block" shape="round" fill="outline" color="primary">
                            <ion-icon slot="start" name="log-in"></ion-icon>
                            تسجيل الدخول
                        </ion-button>
                    </ion-card>
                </div>
            </div>
            
            <!-- Floating Action Button -->
            <div class="fab-button" onclick="window.location.href='chat.php'">
                <ion-icon name="chatbubble-ellipses"></ion-icon>
            </div>
            
            <!-- Bottom Navigation -->
            <div class="bottom-nav">
                <a href="index.php" class="nav-item active">
                    <ion-icon name="home"></ion-icon>
                    <span>الرئيسية</span>
                </a>
                <a href="#services" class="nav-item">
                    <ion-icon name="briefcase"></ion-icon>
                    <span>الخدمات</span>
                </a>
                <a href="#lawyers" class="nav-item">
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
        // Counter Animation
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.stat-number[data-count]');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const target = parseInt(counter.getAttribute('data-count'));
                        let current = 0;
                        const increment = target / 50;
                        
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                counter.textContent = target >= 1000 ? (target/1000).toFixed(0) + 'K+' : target + '+';
                                clearInterval(timer);
                            } else {
                                counter.textContent = Math.floor(current).toLocaleString();
                            }
                        }, 30);
                        
                        observer.unobserve(counter);
                    }
                });
            });
            
            counters.forEach(counter => observer.observe(counter));
        });
        
        // Bottom Nav Active State
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                navItems.forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
