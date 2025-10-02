<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - <?php echo SITENAME; ?></title>
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
            
            <div class="register-wrapper">
                <!-- Header -->
                <div class="register-header">
                    <div class="register-logo">
                        <ion-icon name="person-add"></ion-icon>
                    </div>
                    <h1 class="register-title">إنشاء حساب جديد</h1>
                    <p class="register-subtitle">انضم إلى منصة مكاتب المحاماة</p>
                </div>
                
                <!-- Register Card -->
                <ion-card class="register-card">
                    <ion-card-content>
                        <form action="<?php echo URLROOT; ?>/register" method="POST">
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">الاسم الكامل</ion-label>
                                <ion-input 
                                    type="text" 
                                    name="name" 
                                    value="<?php echo $data['name']; ?>"
                                    placeholder="أدخل اسمك الكامل"
                                    required
                                ></ion-input>
                                <ion-icon name="person" slot="start"></ion-icon>
                            </ion-item>
                            <?php if (!empty($data['name_err'])): ?>
                                <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                            <?php endif; ?>
                            
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
                            <?php if (!empty($data['email_err'])): ?>
                                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                            <?php endif; ?>
                            
                            <ion-item class="ion-margin-bottom">
                                <ion-select label="نوع الحساب:" label-placement="stacked" placeholder="اختر نوع الحساب" name="role" value="<?php echo $data['role']; ?>" required>
                                    <ion-select-option value="client">عميل</ion-select-option>
                                    <ion-select-option value="lawyer">محامي</ion-select-option>
                                </ion-select>
                                <ion-icon name="briefcase" slot="start"></ion-icon>
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
                            <?php if (!empty($data['password_err'])): ?>
                                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                            <?php endif; ?>
                            
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">تأكيد كلمة المرور</ion-label>
                                <ion-input 
                                    type="password" 
                                    name="confirm_password" 
                                    placeholder="••••••••"
                                    required
                                ></ion-input>
                                <ion-icon name="lock-closed" slot="start"></ion-icon>
                            </ion-item>
                            <?php if (!empty($data['confirm_password_err'])): ?>
                                <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                            <?php endif; ?>
                            
                            <ion-button 
                                type="submit" 
                                expand="block" 
                                shape="round" 
                                size="large"
                                class="ion-margin-top"
                            >
                                <ion-icon slot="start" name="person-add"></ion-icon>
                                إنشاء الحساب
                            </ion-button>
                        </form>
                        
                        <div class="divider">
                            <span>أو</span>
                        </div>
                        
                        <div class="login-link">
                            <p class="login-text">
                                لديك حساب بالفعل؟ 
                                <a href="<?php echo URLROOT; ?>/login">سجل دخولك</a>
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
