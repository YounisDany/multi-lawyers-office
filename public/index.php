<?php
require_once __DIR__ . "/../config.php";

// منطق الصفحة الرئيسية
$title = "الرئيسية";
$description = "منصة متكاملة لإدارة مكاتب المحاماة";
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - <?php echo SITENAME; ?></title>
    
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Animate.css CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Ionic Framework CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/premium-style.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/mobile-ionic.css">
</head>
<body class="premium-bg-animated">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg premium-container position-fixed w-100" style="top: 20px; left: 50%; transform: translateX(-50%); max-width: 95%; z-index: 1000;" data-aos="fade-down">
        <div class="container-fluid">
            <a class="navbar-brand premium-text-gradient fw-bold fs-3" href="#">
                <i class="fas fa-balance-scale me-2"></i>
                <?php echo SITENAME; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">خدماتنا</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">من نحن</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">اتصل بنا</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="premium-btn-secondary" href="<?php echo URLROOT; ?>/logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                تسجيل الخروج
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item me-2">
                            <a class="premium-btn-secondary" href="<?php echo URLROOT; ?>/login.php">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                دخول
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="premium-btn" href="<?php echo URLROOT; ?>/register.php">
                                <i class="fas fa-user-plus me-2"></i>
                                تسجيل
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="min-vh-100 d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="premium-header text-start">
                        <h1 class="display-4 premium-text-gradient mb-4 animate__animated animate__fadeInUp">
                            مرحباً بك في عالم العدالة
                        </h1>
                        <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                            منصة متكاملة لإدارة مكاتب المحاماة تجمع بين الخبرة القانونية والتكنولوجيا المتطورة لتقديم أفضل الخدمات القانونية.
                        </p>
                        <div class="d-flex gap-3 flex-wrap animate__animated animate__fadeInUp animate__delay-2s">
                            <a href="<?php echo URLROOT; ?>/register.php" class="premium-btn">
                                <i class="fas fa-rocket me-2"></i>
                                ابدأ رحلتك معنا
                            </a>
                            <a href="#services" class="premium-btn-secondary">
                                <i class="fas fa-info-circle me-2"></i>
                                اكتشف خدماتنا
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
                    <div class="text-center">
                        <div class="premium-icon mx-auto mb-4 animate__animated animate__pulse animate__infinite" style="width: 200px; height: 200px; font-size: 5rem;">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <div class="premium-stats-grid">
                            <div class="premium-stat-card" data-aos="zoom-in" data-aos-delay="500">
                                <div class="premium-stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="premium-stat-number">500+</div>
                                <div class="premium-stat-label">عميل راضٍ</div>
                            </div>
                            <div class="premium-stat-card" data-aos="zoom-in" data-aos-delay="700">
                                <div class="premium-stat-icon">
                                    <i class="fas fa-gavel"></i>
                                </div>
                                <div class="premium-stat-number">1000+</div>
                                <div class="premium-stat-label">قضية منجزة</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-5 premium-text-gradient mb-3">خدماتنا المتميزة</h2>
                <p class="lead text-muted">نقدم مجموعة شاملة من الخدمات القانونية المتخصصة</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="premium-card h-100 text-center premium-interactive">
                        <div class="premium-icon mx-auto mb-3">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h4 class="premium-text-gradient mb-3">إدارة القضايا</h4>
                        <p class="text-muted mb-4">نظام متطور لإدارة ومتابعة جميع قضاياك القانونية بكفاءة عالية وشفافية كاملة.</p>
                        <div class="premium-badge premium-badge-primary">
                            <i class="fas fa-star me-1"></i>
                            الأكثر طلباً
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="premium-card h-100 text-center premium-interactive">
                        <div class="premium-icon mx-auto mb-3">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h4 class="premium-text-gradient mb-3">استشارات قانونية</h4>
                        <p class="text-muted mb-4">احصل على استشارات قانونية فورية من محامين متخصصين في جميع المجالات القانونية.</p>
                        <div class="premium-badge premium-badge-success">
                            <i class="fas fa-clock me-1"></i>
                            متاح 24/7
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="premium-card h-100 text-center premium-interactive">
                        <div class="premium-icon mx-auto mb-3">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4 class="premium-text-gradient mb-3">فريق محامين</h4>
                        <p class="text-muted mb-4">فريق من أمهر المحامين المتخصصين في مختلف المجالات القانونية لخدمتك.</p>
                        <div class="premium-badge premium-badge-warning">
                            <i class="fas fa-certificate me-1"></i>
                            معتمد
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="premium-card h-100 text-center premium-interactive">
                        <div class="premium-icon mx-auto mb-3">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="premium-text-gradient mb-3">الحماية القانونية</h4>
                        <p class="text-muted mb-4">حماية شاملة لحقوقك القانونية مع ضمان السرية والخصوصية التامة.</p>
                        <div class="premium-badge premium-badge-danger">
                            <i class="fas fa-lock me-1"></i>
                            آمن ومحمي
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="premium-card h-100 text-center premium-interactive">
                        <div class="premium-icon mx-auto mb-3">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="premium-text-gradient mb-3">تقارير مفصلة</h4>
                        <p class="text-muted mb-4">تقارير دورية مفصلة عن سير قضاياك وحالة ملفاتك القانونية.</p>
                        <div class="premium-badge premium-badge-primary">
                            <i class="fas fa-chart-bar me-1"></i>
                            تحليلات متقدمة
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="premium-card h-100 text-center premium-interactive">
                        <div class="premium-icon mx-auto mb-3">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4 class="premium-text-gradient mb-3">تطبيق محمول</h4>
                        <p class="text-muted mb-4">تطبيق محمول متطور يتيح لك متابعة قضاياك من أي مكان وفي أي وقت.</p>
                        <div class="premium-badge premium-badge-success">
                            <i class="fas fa-download me-1"></i>
                            قريباً
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="premium-card">
                        <h2 class="premium-text-gradient mb-4">من نحن</h2>
                        <p class="lead mb-4">
                            نحن منصة رائدة في مجال الخدمات القانونية الرقمية، نجمع بين الخبرة القانونية العريقة والتكنولوجيا المتطورة.
                        </p>
                        <p class="mb-4">
                            منذ تأسيسنا، نسعى لتقديم خدمات قانونية متميزة تلبي احتياجات عملائنا وتحقق أهدافهم بأعلى معايير الجودة والمهنية.
                        </p>
                        <div class="d-flex gap-3">
                            <a href="<?php echo URLROOT; ?>/register.php" class="premium-btn">
                                <i class="fas fa-handshake me-2"></i>
                                انضم إلينا
                            </a>
                            <a href="#contact" class="premium-btn-secondary">
                                <i class="fas fa-phone me-2"></i>
                                تواصل معنا
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                    <div class="premium-stats-grid">
                        <div class="premium-stat-card" data-aos="zoom-in" data-aos-delay="300">
                            <div class="premium-stat-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="premium-stat-number">15+</div>
                            <div class="premium-stat-label">سنة خبرة</div>
                        </div>
                        <div class="premium-stat-card" data-aos="zoom-in" data-aos-delay="400">
                            <div class="premium-stat-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="premium-stat-number">50+</div>
                            <div class="premium-stat-label">محامي متخصص</div>
                        </div>
                        <div class="premium-stat-card" data-aos="zoom-in" data-aos-delay="500">
                            <div class="premium-stat-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="premium-stat-number">95%</div>
                            <div class="premium-stat-label">معدل النجاح</div>
                        </div>
                        <div class="premium-stat-card" data-aos="zoom-in" data-aos-delay="600">
                            <div class="premium-stat-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="premium-stat-number">20+</div>
                            <div class="premium-stat-label">دولة نخدمها</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-5 premium-text-gradient mb-3">تواصل معنا</h2>
                <p class="lead text-muted">نحن هنا لمساعدتك في جميع احتياجاتك القانونية</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="premium-card text-center h-100">
                        <div class="premium-icon mx-auto mb-3">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h5 class="premium-text-gradient mb-3">اتصل بنا</h5>
                        <p class="text-muted">+966 50 123 4567</p>
                        <p class="text-muted">+966 11 234 5678</p>
                    </div>
                </div>
                
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="premium-card text-center h-100">
                        <div class="premium-icon mx-auto mb-3">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h5 class="premium-text-gradient mb-3">راسلنا</h5>
                        <p class="text-muted">info@lawfirm.com</p>
                        <p class="text-muted">support@lawfirm.com</p>
                    </div>
                </div>
                
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="premium-card text-center h-100">
                        <div class="premium-icon mx-auto mb-3">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h5 class="premium-text-gradient mb-3">زورنا</h5>
                        <p class="text-muted">الرياض، المملكة العربية السعودية</p>
                        <p class="text-muted">شارع الملك فهد، برج الأعمال</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating Action Button -->
    <div class="position-fixed" style="bottom: 30px; right: 30px; z-index: 1000;">
        <a href="<?php echo URLROOT; ?>/register.php" class="premium-btn rounded-circle p-3 animate__animated animate__pulse animate__infinite">
            <i class="fas fa-plus fs-4"></i>
        </a>
    </div>

    <!-- Floating Elements -->
    <div class="position-fixed" style="top: 20%; left: 5%; z-index: -1;">
        <i class="fas fa-gavel text-white opacity-25 fs-1 animate__animated animate__float animate__infinite animate__slow"></i>
    </div>
    <div class="position-fixed" style="bottom: 30%; right: 8%; z-index: -1;">
        <i class="fas fa-scales text-white opacity-25 fs-2 animate__animated animate__bounce animate__infinite animate__slower"></i>
    </div>
    <div class="position-fixed" style="top: 60%; left: 3%; z-index: -1;">
        <i class="fas fa-briefcase text-white opacity-25 fs-3 animate__animated animate__swing animate__infinite animate__slower"></i>
    </div>

    <!-- Premium Footer -->
    <footer class="premium-footer">
        <div class="premium-footer-content">
            <h3 class="animate__animated animate__fadeInUp">منصة مكاتب المحاماة</h3>
            <p class="animate__animated animate__fadeInUp animate__delay-1s">نحو عدالة رقمية متطورة</p>
            <p class="animate__animated animate__fadeInUp animate__delay-2s">&copy; 2024 جميع الحقوق محفوظة</p>
            <p class="animate__animated animate__fadeInUp animate__delay-3s">تطوير: <strong>يونس ضاعني</strong></p>
        </div>
    </footer>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- AOS (Animate On Scroll) Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    </script>
    
    <!-- Custom Premium JavaScript -->
    <script src="<?php echo URLROOT; ?>/public/assets/js/premium.js"></script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js"></script>
    
    <!-- Additional Interactive Effects -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Navbar background on scroll
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 100) {
                    navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                    navbar.style.backdropFilter = 'blur(20px)';
                } else {
                    navbar.style.background = 'rgba(255, 255, 255, 0.95)';
                }
            });
            
            // Counter animation for statistics
            const counters = document.querySelectorAll('.premium-stat-number');
            const observerOptions = {
                threshold: 0.7
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const counter = entry.target;
                        const target = parseInt(counter.textContent.replace(/\D/g, ''));
                        const suffix = counter.textContent.replace(/\d/g, '');
                        let current = 0;
                        const increment = target / 50;
                        
                        const updateCounter = () => {
                            if (current < target) {
                                current += increment;
                                counter.textContent = Math.floor(current) + suffix;
                                requestAnimationFrame(updateCounter);
                            } else {
                                counter.textContent = target + suffix;
                            }
                        };
                        
                        updateCounter();
                        observer.unobserve(counter);
                    }
                });
            }, observerOptions);
            
            counters.forEach(counter => {
                observer.observe(counter);
            });
        });
    </script>
</body>
</html>
