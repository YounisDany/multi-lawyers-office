<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استشاراتي - <?php echo SITENAME; ?></title>
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
                <ion-title>استشاراتي</ion-title>
            </ion-toolbar>
        </ion-header>
        
        <ion-content class="ion-padding">
            <div class="content-section">
                <ion-card>
                    <ion-card-header>
                        <ion-card-title>طلب استشارة جديدة</ion-card-title>
                    </ion-card-header>
                    <ion-card-content>
                        <form action="<?php echo URLROOT; ?>/client/consultations" method="POST">
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">اختر المحامي</ion-label>
                                <ion-select name="lawyer_id" placeholder="اختر محامي" required>
                                    <?php foreach ($lawyers as $lawyer): ?>
                                        <ion-select-option value="<?php echo $lawyer->id; ?>">
                                            <?php echo htmlspecialchars($lawyer->name); ?>
                                        </ion-select-option>
                                    <?php endforeach; ?>
                                </ion-select>
                            </ion-item>
                            
                            <ion-item class="ion-margin-bottom">
                                <ion-label position="stacked">سؤالك</ion-label>
                                <ion-textarea name="question" placeholder="اكتب سؤالك هنا..." rows="5" required></ion-textarea>
                            </ion-item>
                            
                            <ion-button type="submit" expand="block" shape="round" class="ion-margin-top">
                                <ion-icon slot="start" name="send"></ion-icon>
                                إرسال الاستشارة
                            </ion-button>
                        </form>
                    </ion-card-content>
                </ion-card>
                
                <h2 class="section-title ion-margin-top">استشاراتي السابقة</h2>
                <?php if (!empty($consultations)): ?>
                    <ion-list>
                        <?php foreach ($consultations as $consultation): ?>
                            <ion-item-sliding>
                                <ion-item>
                                    <ion-icon name="help-circle-outline" slot="start"></ion-icon>
                                    <ion-label>
                                        <h3>استشارة مع: <?php echo htmlspecialchars($consultation->lawyer_name); ?></h3>
                                        <p><?php echo substr(htmlspecialchars($consultation->question), 0, 70) . (strlen($consultation->question) > 70 ? '...' : ''); ?></p>
                                        <p class="ion-text-right">
                                            <ion-badge color="<?php echo $consultation->status == 'pending' ? 'warning' : 'success'; ?>">
                                                <?php echo $consultation->status == 'pending' ? 'معلقة' : 'تم الرد'; ?>
                                            </ion-badge>
                                        </p>
                                    </ion-label>
                                    <ion-note slot="end"><?php echo date('Y-m-d', strtotime($consultation->created_at)); ?></ion-note>
                                </ion-item>
                                <ion-item-options side="end">
                                    <ion-item-option color="primary" onclick="alert('تفاصيل الاستشارة: <?php echo htmlspecialchars(addslashes($consultation->question)); ?>\n\nالرد: <?php echo htmlspecialchars(addslashes($consultation->answer ?? 'لا يوجد رد بعد.')); ?>')">
                                        <ion-icon slot="icon-only" name="eye"></ion-icon>
                                    </ion-item-option>
                                </ion-item-options>
                            </ion-item-sliding>
                        <?php endforeach; ?>
                    </ion-list>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                        <h3>لا توجد استشارات سابقة.</h3>
                        <p>يمكنك طلب استشارة جديدة الآن.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="<?php echo URLROOT; ?>/assets/js/main.js"></script>
</body>
</html>
