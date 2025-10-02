<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/mobile-ionic.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>
</head>
<body>
    <ion-app>
        <ion-content>
            <!-- Back Button -->
            <ion-button href="<?php echo URLROOT; ?>/" fill="clear" class="back-button" color="light">
                <ion-icon slot="icon-only" name="arrow-forward"></ion-icon>
            </ion-button>
            
            <div class="login-wrapper">
                <!-- Header -->
                <div class="login-header">
                    <div class="login-logo">
                        <ion-icon name="scale"></ion-icon>
                    </div>
                    <h1 class="login-title">مرحباً بعودتك</h1>
                    <p class="login-subtitle">سجل دخولك للوصول إلى حسابك</p>
                </div>
                
                <!-- Login Card -->
                <ion-card class="login-card">
                    <ion-card-content>
                        <?php if (!empty($data['email_err']) || !empty($data['password_err'])): ?>
                            <ion-item color="danger" class="ion-margin-bottom">
                                <ion-icon slot="start" name="alert-circle"></ion-icon>
                                <ion-label>
                                    <?php echo !empty($data['email_err']) ? $data['email_err'] : $data['password_err']; ?>
                                </ion-label>
                            </ion-item>
                        <?php endif; ?>
                        
                        <form action="<?php echo URLROOT; ?>/login" method="POST">
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">البريد الإلكتروني</ion-label>
                                <ion-input 
                                    type="email" 
                                    name="email" 
                                    value="<?php echo $data['email']; ?>"
                                    placeholder="example@domain.com"
                                    required
                                ></ion-input>
                                <ion-icon name="mail" slot="start"></ion-icon>
                            </ion-item>
                            
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">كلمة المرور</ion-label>
                                <ion-input 
                                    type="password" 
                                    name="password" 
                                    placeholder="••••••••"
                                    required
                                ></ion-input>
                                <ion-icon name="lock-closed" slot="start"></ion-icon>
                            </ion-item>
                            
                            <ion-button 
                                type="submit" 
                                expand="block" 
                                shape="round" 
                                size="large"
                                class="ion-margin-top"
                            >
                                <ion-icon slot="start" name="log-in"></ion-icon>
                                تسجيل الدخول
                            </ion-button>
                        </form>
                        
                        <div class="divider">
                            <span>أو</span>
                        </div>
                        
                        <div class="register-link">
                            <p class="register-text">
                                ليس لديك حساب؟ 
                                <a href="<?php echo URLROOT; ?>/register">سجل الآن</a>
                            </p>
                        </div>
                    </ion-card-content>
                </ion-card>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="<?php echo URLROOT; ?>/assets/js/main.js"></script>
</body>
</html>
