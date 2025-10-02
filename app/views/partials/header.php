<?php
// ملف الرأس المشترك - يونس ضاعني
// هذا الملف يتم تضمينه في بداية كل صفحة لعرض رأس الصفحة المشترك.
// لا يحتوي على وسم <html> أو <head> أو <body> لأنه جزء من صفحة أكبر.
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/assets/css/mobile-ionic.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>
</head>
<body>
    <ion-app>
        <ion-menu content-id="main-content">
            <ion-header>
                <ion-toolbar>
                    <ion-title>القائمة</ion-title>
                </ion-toolbar>
            </ion-header>
            <ion-content>
                <ion-list>
                    <ion-menu-toggle auto-hide="false">
                        <ion-item href="<?php echo URLROOT; ?>/index.php">
                            <ion-icon slot="start" name="home"></ion-icon>
                            <ion-label>الرئيسية</ion-label>
                        </ion-item>
                        <?php if (isLoggedIn()): ?>
                            <?php if (hasRole("client")): ?>
                                <ion-item href="<?php echo URLROOT; ?>/client_dashboard.php">
                                    <ion-icon slot="start" name="speedometer"></ion-icon>
                                    <ion-label>لوحة تحكم العميل</ion-label>
                                </ion-item>
                                <ion-item href="<?php echo URLROOT; ?>/client_new_case.php">
                                    <ion-icon slot="start" name="add-circle"></ion-icon>
                                    <ion-label>فتح قضية جديدة</ion-label>
                                </ion-item>
                                <ion-item href="<?php echo URLROOT; ?>/client_consultations.php">
                                    <ion-icon slot="start" name="chatbubbles"></ion-icon>
                                    <ion-label>استشاراتي</ion-label>
                                </ion-item>
                            <?php elseif (hasRole("lawyer")): ?>
                                <ion-item href="<?php echo URLROOT; ?>/lawyer_dashboard.php">
                                    <ion-icon slot="start" name="speedometer"></ion-icon>
                                    <ion-label>لوحة تحكم المحامي</ion-label>
                                </ion-item>
                            <?php elseif (hasRole("admin")): ?>
                                <ion-item href="<?php echo URLROOT; ?>/admin/dashboard.php">
                                    <ion-icon slot="start" name="speedometer"></ion-icon>
                                    <ion-label>لوحة تحكم الإدارة</ion-label>
                                </ion-item>
                                <ion-item href="<?php echo URLROOT; ?>/admin/cases.php">
                                    <ion-icon slot="start" name="folder-open"></ion-icon>
                                    <ion-label>إدارة القضايا</ion-label>
                                </ion-item>
                                <ion-item href="<?php echo URLROOT; ?>/admin/lawyers.php">
                                    <ion-icon slot="start" name="briefcase"></ion-icon>
                                    <ion-label>إدارة المحامين</ion-label>
                                </ion-item>
                                <ion-item href="<?php echo URLROOT; ?>/admin/reports.php">
                                    <ion-icon slot="start" name="bar-chart"></ion-icon>
                                    <ion-label>التقارير</ion-label>
                                </ion-item>
                            <?php endif; ?>
                            <ion-item href="<?php echo URLROOT; ?>/logout.php">
                                <ion-icon slot="start" name="log-out"></ion-icon>
                                <ion-label>تسجيل الخروج</ion-label>
                            </ion-item>
                        <?php else: ?>
                            <ion-item href="<?php echo URLROOT; ?>/login.php">
                                <ion-icon slot="start" name="log-in"></ion-icon>
                                <ion-label>تسجيل الدخول</ion-label>
                            </ion-item>
                            <ion-item href="<?php echo URLROOT; ?>/register.php">
                                <ion-icon slot="start" name="person-add"></ion-icon>
                                <ion-label>التسجيل</ion-label>
                            </ion-item>
                        <?php endif; ?>
                    </ion-menu-toggle>
                </ion-list>
            </ion-content>
        </ion-menu>
        <ion-router-outlet id="main-content"></ion-router-outlet>

