<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة مكاتب المحاماة أونلاين</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* تصميم الصفحة الرئيسية */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,0 1000,100 0,80"/></svg>');
            background-size: cover;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .hero p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .hero-btn {
            padding: 15px 30px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }
        
        .hero-btn:hover {
            background: white;
            color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .hero-btn.primary {
            background: white;
            color: #667eea;
        }
        
        .hero-btn.primary:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-3px) scale(1.05);
        }
        
        .features {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .features h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 60px;
            color: #333;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }
        
        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
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
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #667eea;
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: #333;
        }
        
        .feature-card p {
            color: #6c757d;
            line-height: 1.6;
        }
        
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            text-align: center;
        }
        
        .stat-item {
            padding: 20px;
        }
        
        .stat-item .number {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }
        
        .stat-item .label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .cta-section {
            padding: 80px 0;
            background: white;
            text-align: center;
        }
        
        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
        }
        
        .cta-section p {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 40px;
        }
        
        .footer {
            background: #333;
            color: white;
            padding: 40px 0;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <!-- القسم الرئيسي -->
    <section class="hero">
        <div class="hero-content">
            <h1>منصة مكاتب المحاماة أونلاين</h1>
            <p>منصة متكاملة تربط بين المحامين والعملاء لتقديم الخدمات القانونية بكفاءة وسهولة</p>
            <div class="hero-buttons">
                <a href="register.php" class="hero-btn primary">ابدأ الآن</a>
                <a href="login.php" class="hero-btn">تسجيل الدخول</a>
            </div>
        </div>
    </section>
    
    <!-- الميزات -->
    <section class="features">
        <div class="container">
            <h2>لماذا تختار منصتنا؟</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">⚖️</div>
                    <h3>خدمات قانونية متخصصة</h3>
                    <p>احصل على استشارات قانونية من محامين معتمدين ومتخصصين في مختلف المجالات القانونية</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">💬</div>
                    <h3>تواصل مباشر</h3>
                    <p>نظام دردشة متطور يتيح التواصل المباشر مع المحامي وإرسال الملفات والمستندات</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>متابعة شاملة</h3>
                    <p>تابع حالة قضاياك واستشاراتك بشكل مستمر مع تقارير مفصلة عن التقدم</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>أمان وخصوصية</h3>
                    <p>حماية كاملة لبياناتك ومعلوماتك الشخصية مع أعلى معايير الأمان والخصوصية</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>سهولة الاستخدام</h3>
                    <p>واجهة مستخدم بسيطة وسهلة تعمل على جميع الأجهزة والمنصات</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">⏰</div>
                    <h3>متاح 24/7</h3>
                    <p>خدمة متاحة على مدار الساعة مع إشعارات فورية لجميع التحديثات</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- الإحصائيات -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="number" data-count="500">0</span>
                    <span class="label">محامي معتمد</span>
                </div>
                <div class="stat-item">
                    <span class="number" data-count="2000">0</span>
                    <span class="label">عميل راضي</span>
                </div>
                <div class="stat-item">
                    <span class="number" data-count="5000">0</span>
                    <span class="label">قضية منجزة</span>
                </div>
                <div class="stat-item">
                    <span class="number" data-count="10000">0</span>
                    <span class="label">استشارة قانونية</span>
                </div>
            </div>
        </div>
    </section>
    
    <!-- دعوة للعمل -->
    <section class="cta-section">
        <div class="container">
            <h2>ابدأ رحلتك القانونية اليوم</h2>
            <p>انضم إلى آلاف العملاء والمحامين الذين يثقون في منصتنا</p>
            <div class="hero-buttons">
                <a href="register.php" class="hero-btn primary">إنشاء حساب جديد</a>
                <a href="login.php" class="hero-btn">لديك حساب؟ سجل دخولك</a>
            </div>
        </div>
    </section>
    
    <!-- التذييل -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 منصة مكاتب المحاماة أونلاين. جميع الحقوق محفوظة.</p>
        </div>
    </footer>
    
    <script src="assets/js/main.js"></script>
    <script>
        // تأثير العداد للإحصائيات
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.number[data-count]');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const target = parseInt(counter.getAttribute('data-count'));
                        let current = 0;
                        const increment = target / 100;
                        
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                counter.textContent = target.toLocaleString();
                                clearInterval(timer);
                            } else {
                                counter.textContent = Math.floor(current).toLocaleString();
                            }
                        }, 20);
                        
                        observer.unobserve(counter);
                    }
                });
            });
            
            counters.forEach(counter => observer.observe(counter));
        });
    </script>
</body>
</html>
