<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/mobile-ionic.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/style.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>
</head>
<body>
    <ion-app>
        <ion-content>
            <!-- Hero Section -->
            <div class="hero-section">
                <div class="hero-content">
                    <h1 class="hero-title"><?php echo $title; ?></h1>
                    <p class="hero-subtitle"><?php echo $description; ?></p>
                    <div class="hero-buttons">
                        <ion-button href="<?php echo URLROOT; ?>/register" color="light" size="default" shape="round">
                            <ion-icon slot="start" name="person-add"></ion-icon>
                            ابدأ الآن
                        </ion-button>
                        <ion-button href="<?php echo URLROOT; ?>/login" fill="outline" color="light" size="default" shape="round">
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
                    <ion-card class="feature-card">
                        <ion-icon name="scale" class="feature-icon" color="primary"></ion-icon>
                        <ion-card-title class="feature-title">خدمات متخصصة</ion-card-title>
                        <ion-card-content class="feature-description">استشارات قانونية من محامين معتمدين</ion-card-content>
                    </ion-card>
                    
                    <ion-card class="feature-card">
                        <ion-icon name="chatbubbles" class="feature-icon" color="secondary"></ion-icon>
                        <ion-card-title class="feature-title">تواصل مباشر</ion-card-title>
                        <ion-card-content class="feature-description">نظام دردشة متطور وسريع</ion-card-content>
                    </ion-card>
                    
                    <ion-card class="feature-card">
                        <ion-icon name="stats-chart" class="feature-icon" color="primary"></ion-icon>
                        <ion-card-title class="feature-title">متابعة شاملة</ion-card-title>
                        <ion-card-content class="feature-description">تقارير مفصلة عن التقدم</ion-card-content>
                    </ion-card>
                    
                    <ion-card class="feature-card">
                        <ion-icon name="shield-checkmark" class="feature-icon" color="secondary"></ion-icon>
                        <ion-card-title class="feature-title">أمان وخصوصية</ion-card-title>
                        <ion-card-content class="feature-description">حماية كاملة لبياناتك</ion-card-content>
                    </ion-card>
                    
                    <ion-card class="feature-card">
                        <ion-icon name="phone-portrait" class="feature-icon" color="primary"></ion-icon>
                        <ion-card-title class="feature-title">سهولة الاستخدام</ion-card-title>
                        <ion-card-content class="feature-description">واجهة بسيطة وسهلة</ion-card-content>
                    </ion-card>
                    
                    <ion-card class="feature-card">
                        <ion-icon name="time" class="feature-icon" color="secondary"></ion-icon>
                        <ion-card-title class="feature-title">متاح 24/7</ion-card-title>
                        <ion-card-content class="feature-description">خدمة على مدار الساعة</ion-card-content>
                    </ion-card>
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
                    <ion-card-header>
                        <ion-card-title class="action-card-title">ابدأ رحلتك القانونية اليوم</ion-card-title>
                    </ion-card-header>
                    <ion-card-content>
                        <p class="action-card-text">انضم إلى آلاف العملاء والمحامين الذين يثقون في منصتنا</p>
                        <ion-button href="<?php echo URLROOT; ?>/register" expand="block" shape="round" color="primary">
                            <ion-icon slot="start" name="rocket"></ion-icon>
                            إنشاء حساب جديد
                        </ion-button>
                    </ion-card-content>
                </ion-card>
                
                <ion-card class="action-card">
                    <ion-card-header>
                        <ion-card-title class="action-card-title">لديك حساب بالفعل؟</ion-card-title>
                    </ion-card-header>
                    <ion-card-content>
                        <p class="action-card-text">سجل دخولك للوصول إلى لوحة التحكم الخاصة بك</p>
                        <ion-button href="<?php echo URLROOT; ?>/login" expand="block" shape="round" fill="outline" color="primary">
                            <ion-icon slot="start" name="log-in"></ion-icon>
                            تسجيل الدخول
                        </ion-button>
                    </ion-card-content>
                </ion-card>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="<?php echo URLROOT; ?>/assets/js/main.js"></script>
    <script>
        // Counter Animation
        document.addEventListener("DOMContentLoaded", function() {
            const counters = document.querySelectorAll(".stat-number[data-count]");
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = parseInt(entry.target.getAttribute("data-count"));
                        let current = 0;
                        const increment = target / 200; // Adjust speed
                        
                        const updateCounter = () => {
                            if (current < target) {
                                current += increment;
                                entry.target.textContent = Math.floor(current) + (target >= 1000 ? "K+" : "+");
                                requestAnimationFrame(updateCounter);
                            } else {
                                entry.target.textContent = target + (target >= 1000 ? "K+" : "+");
                            }
                        };
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.5 });
            
            counters.forEach(counter => {
                observer.observe(counter);
            });
        });
    </script>
</body>
</html>
