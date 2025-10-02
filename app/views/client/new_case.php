<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قضية جديدة - <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/mobile-ionic.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>
</head>
<body>
    <ion-app>
        <ion-header>
            <ion-toolbar color="primary">
                <ion-buttons slot="start">
                    <ion-back-button default-href="<?php echo URLROOT; ?>/client/dashboard"></ion-back-button>
                </ion-buttons>
                <ion-title>إضافة قضية جديدة</ion-title>
            </ion-toolbar>
        </ion-header>

        <ion-content class="ion-padding">
            <div class="content-section">
                <ion-card>
                    <ion-card-content>
                        <form action="<?php echo URLROOT; ?>/client/new_case" method="POST">
                            <ion-item>
                                <ion-select label="اختر المحامي:" label-placement="stacked" placeholder="اختر المحامي" name="lawyer_id" value="<?php echo $lawyer_id; ?>" required>
                                    <?php foreach ($lawyers as $lawyer): ?>
                                        <ion-select-option value="<?php echo $lawyer->id; ?>">
                                            <?php echo htmlspecialchars($lawyer->name); ?>
                                        </ion-select-option>
                                    <?php endforeach; ?>
                                </ion-select>
                            </ion-item>
                            <?php if (!empty($lawyer_err)): ?>
                                <span class="invalid-feedback"><?php echo $lawyer_err; ?></span>
                            <?php endif; ?>
                            
                            <ion-item class="ion-margin-top">
                                <ion-input label="عنوان القضية:" label-placement="stacked" type="text" name="title" value="<?php echo $title; ?>" required 
                                           placeholder="مثال: قضية عمالية - فصل تعسفي"></ion-input>
                            </ion-item>
                            <?php if (!empty($title_err)): ?>
                                <span class="invalid-feedback"><?php echo $title_err; ?></span>
                            <?php endif; ?>
                            
                            <ion-item class="ion-margin-top">
                                <ion-textarea label="تفاصيل القضية:" label-placement="stacked" name="details" rows="8" required 
                                              placeholder="اكتب تفاصيل القضية بشكل واضح ومفصل..."><?php echo $details; ?></ion-textarea>
                            </ion-item>
                            <?php if (!empty($details_err)): ?>
                                <span class="invalid-feedback"><?php echo $details_err; ?></span>
                            <?php endif; ?>
                            
                            <ion-button expand="block" type="submit" class="ion-margin-top">
                                <ion-icon slot="start" name="send"></ion-icon>
                                إرسال القضية
                            </ion-button>
                        </form>
                    </ion-card-content>
                </ion-card>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="<?php echo URLROOT; ?>/assets/js/main.js"></script>
</body>
</html>
