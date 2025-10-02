<?php
require_once __DIR__ . "/../config.php";

// منطق الصفحة الرئيسية
$title = "الرئيسية";
$description = "منصة متكاملة لإدارة مكاتب المحاماة";

// تضمين ملف الرأس
view_partial("header");
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/mobile-ionic.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>
</head>
<body>
    <ion-app>
        <ion-header>
            <ion-toolbar color="primary">
                <ion-title><?php echo SITENAME; ?></ion-title>
                <ion-buttons slot="end">
                    <?php if (isLoggedIn()): ?>
                        <ion-button href="<?php echo URLROOT; ?>/logout.php">
                            <ion-icon name="log-out"></ion-icon>
                        </ion-button>
                    <?php else: ?>
                        <ion-button href="<?php echo URLROOT; ?>/login.php">
                            <ion-icon name="log-in"></ion-icon>
                        </ion-button>
                    <?php endif; ?>
                </ion-buttons>
            </ion-toolbar>
        </ion-header>
        
        <ion-content class="ion-padding">
            <div class="content-section">
                <ion-card class="welcome-card">
                    <ion-card-header>
                        <ion-card-title>مرحباً بك في <?php echo SITENAME; ?></ion-card-title>
                        <ion-card-subtitle>منصة متكاملة لإدارة مكاتب المحاماة</ion-card-subtitle>
                    </ion-card-header>
                    <ion-card-content>
                        <p>نحن نقدم حلولاً قانونية متكاملة لعملائنا الكرام. سواء كنت تبحث عن استشارة قانونية، أو تحتاج إلى متابعة قضية، منصتنا توفر لك كل ما تحتاجه.</p>
                        <ion-button expand="block" href="<?php echo URLROOT; ?>/login.php" class="ion-margin-top">
                            تسجيل الدخول
                        </ion-button>
                        <ion-button expand="block" fill="outline" href="<?php echo URLROOT; ?>/register.php" class="ion-margin-top">
                            إنشاء حساب جديد
                        </ion-button>
                    </ion-card-content>
                </ion-card>
                
                <h2 class="section-title ion-margin-top">خدماتنا</h2>
                <div class="services-grid">
                    <ion-card class="feature-card">
                        <ion-card-content>
                            <ion-icon name="document-text-outline" color="primary"></ion-icon>
                            <h3>إدارة القضايا</h3>
                            <p>تتبع قضاياك القانونية بسهولة وفعالية.</p>
                        </ion-card-content>
                    </ion-card>
                    <ion-card class="feature-card">
                        <ion-card-content>
                            <ion-icon name="chatbubbles-outline" color="secondary"></ion-icon>
                            <h3>استشارات قانونية</h3>
                            <p>احصل على استشارات من محامين متخصصين.</p>
                        </ion-card-content>
                    </ion-card>
                    <ion-card class="feature-card">
                        <ion-card-content>
                            <ion-icon name="people-outline" color="tertiary"></ion-icon>
                            <h3>فريق محامين</h3>
                            <p>تواصل مع فريق من أفضل المحامين.</p>
                        </ion-card-content>
                    </ion-card>
                </div>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js"></script>
</body>
</html>
