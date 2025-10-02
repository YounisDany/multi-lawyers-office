<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة مكاتب محاماة أونلاين - النظام الأكثر تطوراً لإدارة مكاتب المحاماة</title>
    
    <!-- Meta Tags for SEO -->
    <meta name="description" content="منصة متكاملة وفخمة لإدارة مكاتب المحاماة والتواصل مع العملاء بأحدث التقنيات">
    <meta name="keywords" content="محاماة, مكتب محاماة, نظام إدارة, قضايا, استشارات قانونية">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚖️</text></svg>">
</head>
<body>
    <!-- Background Particles -->
    <div id="particles-js"></div>
    
    <div class="container">
        <!-- Header Section -->
        <header class="header" data-aos="fade-down" data-aos-duration="1000">
            <div class="text-center">
                <i class="fas fa-balance-scale feature-icon mb-3" style="font-size: 4rem;"></i>
                <h1 class="animate__animated animate__fadeInDown">
                    منصة مكاتب محاماة أونلاين
                </h1>
                <p class="animate__animated animate__fadeInUp animate__delay-1s">
                    النظام الأكثر تطوراً وفخامة لإدارة مكاتب المحاماة والتواصل مع العملاء
                </p>
                <div class="mt-4">
                    <span class="badge bg-gradient text-white px-3 py-2 me-2">
                        <i class="fas fa-star"></i> نظام بريميوم
                    </span>
                    <span class="badge bg-gradient text-white px-3 py-2 me-2">
                        <i class="fas fa-shield-alt"></i> آمن ومحمي
                    </span>
                    <span class="badge bg-gradient text-white px-3 py-2">
                        <i class="fas fa-mobile-alt"></i> متجاوب مع الجوال
                    </span>
                </div>
            </div>
        </header>

        <!-- Main Login Section -->
        <section class="login-section mb-5">
            <div class="card premium-card" data-aos="zoom-in" data-aos-duration="1000">
                <div class="card-content">
                    <h2 class="text-center mb-4 text-gradient">
                        <i class="fas fa-crown me-2"></i>
                        مرحباً بك في منصة المحاماة الفخمة
                    </h2>
                    <p class="text-center text-muted mb-5">
                        اختر نوع حسابك للدخول إلى عالم من الخدمات القانونية المتطورة
                    </p>
                    
                    <div class="login-grid">
                        <!-- Lawyer Login Card -->
                        <div class="login-card lawyer" data-aos="fade-up" data-aos-delay="100">
                            <div class="login-card-content">
                                <div class="mb-4">
                                    <i class="fas fa-user-tie" style="font-size: 3.5rem; color: white;"></i>
                                </div>
                                <h3 class="mb-3 text-white">تسجيل دخول المحامي</h3>
                                <p class="mb-4 text-white-50">
                                    إدارة متقدمة للقضايا والعملاء مع أدوات احترافية
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="auth/lawyer_login.php" class="btn btn-light btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        دخول المحامي
                                    </a>
                                    <a href="auth/lawyer_register.php" class="btn btn-outline-light">
                                        <i class="fas fa-user-plus me-2"></i>
                                        إنشاء حساب جديد
                                    </a>
                                </div>
                                <div class="mt-3">
                                    <small class="text-white-50">
                                        <i class="fas fa-users me-1"></i>
                                        +1000 محامي يثق بنا
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Client Login Card -->
                        <div class="login-card client" data-aos="fade-up" data-aos-delay="200">
                            <div class="login-card-content">
                                <div class="mb-4">
                                    <i class="fas fa-user-friends" style="font-size: 3.5rem; color: white;"></i>
                                </div>
                                <h3 class="mb-3 text-white">تسجيل دخول العميل</h3>
                                <p class="mb-4 text-white-50">
                                    رفع القضايا والتواصل المباشر مع أفضل المحامين
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="auth/client_login.php" class="btn btn-light btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        دخول العميل
                                    </a>
                                    <a href="auth/client_register.php" class="btn btn-outline-light">
                                        <i class="fas fa-user-plus me-2"></i>
                                        إنشاء حساب جديد
                                    </a>
                                </div>
                                <div class="mt-3">
                                    <small class="text-white-50">
                                        <i class="fas fa-handshake me-1"></i>
                                        +5000 عميل راضي
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Login Card -->
                        <div class="login-card admin" data-aos="fade-up" data-aos-delay="300">
                            <div class="login-card-content">
                                <div class="mb-4">
                                    <i class="fas fa-user-shield" style="font-size: 3.5rem; color: white;"></i>
                                </div>
                                <h3 class="mb-3 text-white">لوحة تحكم الأدمن</h3>
                                <p class="mb-4 text-white-50">
                                    إدارة شاملة للنظام والمحامين مع تقارير متقدمة
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="auth/admin_login.php" class="btn btn-light btn-lg">
                                        <i class="fas fa-cog me-2"></i>
                                        دخول الأدمن
                                    </a>
                                </div>
                                <div class="mt-3">
                                    <small class="text-white-50">
                                        <i class="fas fa-lock me-1"></i>
                                        دخول محمي ومشفر
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section mb-5">
            <div class="card" data-aos="fade-up" data-aos-duration="1000">
                <div class="text-center mb-5">
                    <h3 class="text-gradient mb-3">
                        <i class="fas fa-gem me-2"></i>
                        مميزات المنصة الفخمة
                    </h3>
                    <p class="text-muted">
                        تجربة لا مثيل لها في عالم الخدمات القانونية الرقمية
                    </p>
                </div>
                
                <div class="feature-grid">
                    <div class="feature-item" data-aos="zoom-in" data-aos-delay="100">
                        <div class="feature-icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <h4>إدارة القضايا المتقدمة</h4>
                        <p>نظام متكامل وذكي لإدارة ومتابعة جميع القضايا بكفاءة عالية</p>
                        <div class="mt-3">
                            <span class="badge bg-primary">AI مدعوم</span>
                        </div>
                    </div>
                    
                    <div class="feature-item" data-aos="zoom-in" data-aos-delay="200">
                        <div class="feature-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h4>المحادثة الفورية</h4>
                        <p>تواصل مباشر وآمن بين المحامي والعميل مع إشعارات فورية</p>
                        <div class="mt-3">
                            <span class="badge bg-success">فوري</span>
                        </div>
                    </div>
                    
                    <div class="feature-item" data-aos="zoom-in" data-aos-delay="300">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h4>الإشعارات الذكية</h4>
                        <p>تنبيهات متقدمة عبر البريد الإلكتروني والرسائل النصية</p>
                        <div class="mt-3">
                            <span class="badge bg-warning">ذكي</span>
                        </div>
                    </div>
                    
                    <div class="feature-item" data-aos="zoom-in" data-aos-delay="400">
                        <div class="feature-icon">
                            <i class="fas fa-archive"></i>
                        </div>
                        <h4>الأرشيف الرقمي</h4>
                        <p>حفظ آمن ومنظم لجميع الملفات والمحادثات مع إمكانية البحث</p>
                        <div class="mt-3">
                            <span class="badge bg-info">آمن</span>
                        </div>
                    </div>
                    
                    <div class="feature-item" data-aos="zoom-in" data-aos-delay="500">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>التقارير التحليلية</h4>
                        <p>تقارير مفصلة وإحصائيات متقدمة لتحسين الأداء</p>
                        <div class="mt-3">
                            <span class="badge bg-secondary">تحليلي</span>
                        </div>
                    </div>
                    
                    <div class="feature-item" data-aos="zoom-in" data-aos-delay="600">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4>التطبيق المحمول</h4>
                        <p>واجهة متجاوبة تعمل بسلاسة على جميع الأجهزة</p>
                        <div class="mt-3">
                            <span class="badge bg-dark">متجاوب</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section class="stats-section mb-5">
            <div class="card" data-aos="fade-up" data-aos-duration="1000">
                <div class="row text-center">
                    <div class="col-md-3 col-6 mb-4">
                        <div class="stat-item">
                            <div class="stat-number text-primary" data-count="1000">0</div>
                            <div class="stat-label">محامي نشط</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="stat-item">
                            <div class="stat-number text-success" data-count="5000">0</div>
                            <div class="stat-label">عميل راضي</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="stat-item">
                            <div class="stat-number text-warning" data-count="10000">0</div>
                            <div class="stat-label">قضية مكتملة</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-4">
                        <div class="stat-item">
                            <div class="stat-number text-info" data-count="99">0</div>
                            <div class="stat-label">% نسبة الرضا</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="text-center mt-5" data-aos="fade-up">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-0">
                        <i class="fas fa-copyright me-1"></i>
                        2024 منصة مكاتب محاماة أونلاين - جميع الحقوق محفوظة
                    </p>
                    <div class="mt-3">
                        <a href="#" class="text-decoration-none me-3">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-decoration-none me-3">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="#" class="text-decoration-none me-3">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#" class="text-decoration-none">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS (Animate On Scroll) -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="js/main.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
        
        // Initialize Particles
        particlesJS('particles-js', {
            particles: {
                number: { value: 50 },
                color: { value: '#ffffff' },
                shape: { type: 'circle' },
                opacity: { value: 0.1 },
                size: { value: 3 },
                move: {
                    enable: true,
                    speed: 1,
                    direction: 'none',
                    random: true,
                    out_mode: 'out'
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: { enable: true, mode: 'repulse' },
                    onclick: { enable: true, mode: 'push' }
                }
            }
        });
        
        // Counter Animation
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                const increment = target / 100;
                let current = 0;
                
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = target + (counter.textContent.includes('%') ? '%' : '+');
                        clearInterval(timer);
                    } else {
                        counter.textContent = Math.floor(current) + (counter.textContent.includes('%') ? '%' : '+');
                    }
                }, 20);
            });
        }
        
        // Trigger counter animation when section is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        });
        
        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            observer.observe(statsSection);
        }
    </script>
    
    <style>
        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-item {
            padding: 20px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: #6c757d;
            font-weight: 500;
        }
        
        .badge {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
        }
        
        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
    </style>
</body>
</html>
