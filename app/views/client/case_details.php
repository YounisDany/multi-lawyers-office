<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل القضية - <?php echo SITENAME; ?></title>
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
                <ion-title>تفاصيل القضية</ion-title>
            </ion-toolbar>
        </ion-header>
        
        <ion-content class="ion-padding">
            <div class="content-section">
                <?php if ($case): ?>
                    <ion-card>
                        <ion-card-header>
                            <ion-card-title><?php echo htmlspecialchars($case->title); ?></ion-card-title>
                            <ion-card-subtitle>المحامي: <?php echo htmlspecialchars($case->lawyer_name); ?></ion-card-subtitle>
                        </ion-card-header>
                        
                        <ion-card-content>
                            <ion-list lines="none">
                                <ion-item>
                                    <ion-label>الحالة:</ion-label>
                                    <ion-badge color="<?php echo $case->status == 'new' ? 'primary' : ($case->status == 'in_progress' ? 'warning' : 'success'); ?>">
                                        <?php 
                                        $statusText = [
                                            'new' => 'جديدة',
                                            'in_progress' => 'قيد المعالجة',
                                            'closed' => 'مغلقة',
                                            'archived' => 'مؤرشفة'
                                        ];
                                        echo $statusText[$case->status] ?? $case->status;
                                        ?>
                                    </ion-badge>
                                </ion-item>
                                <ion-item>
                                    <ion-label>تاريخ الإنشاء:</ion-label>
                                    <ion-note slot="end"><?php echo date('Y-m-d H:i', strtotime($case->created_at)); ?></ion-note>
                                </ion-item>
                                <ion-item>
                                    <ion-label>آخر تحديث:</ion-label>
                                    <ion-note slot="end"><?php echo date('Y-m-d H:i', strtotime($case->updated_at)); ?></ion-note>
                                </ion-item>
                            </ion-list>
                            
                            <h3 class="ion-padding-top">تفاصيل القضية:</h3>
                            <p><?php echo nl2br(htmlspecialchars($case->details)); ?></p>
                            
                            <ion-button expand="block" href="<?php echo URLROOT; ?>/client/messages?case_id=<?php echo $case->id; ?>" class="ion-margin-top">
                                <ion-icon slot="start" name="chatbubbles"></ion-icon>
                                محادثة القضية
                            </ion-button>
                        </ion-card-content>
                    </ion-card>
                    
                    <ion-card>
                        <ion-card-header>
                            <ion-card-title>المحادثات والتحديثات</ion-card-title>
                        </ion-card-header>
                        <ion-card-content>
                            <?php if (!empty($messages)): ?>
                                <?php foreach ($messages as $message): ?>
                                    <div class="message-bubble <?php echo $message->sender_id == $_SESSION['user_id'] ? 'sent' : 'received'; ?>">
                                        <div class="message-header">
                                            <strong><?php echo htmlspecialchars($message->sender_name); ?></strong>
                                            <span><?php echo date('Y-m-d H:i', strtotime($message->created_at)); ?></span>
                                        </div>
                                        <?php if ($message->message): ?>
                                            <div class="message-content">
                                                <?php echo nl2br(htmlspecialchars($message->message)); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($message->attachment): ?>
                                            <div class="message-attachment">
                                                <a href="<?php echo URLROOT; ?>/uploads/<?php echo htmlspecialchars($message->attachment); ?>" target="_blank">
                                                    <ion-icon name="attach"></ion-icon> <?php echo htmlspecialchars($message->attachment); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <ion-text color="medium"><p class="ion-text-center">لا توجد رسائل بعد.</p></ion-text>
                            <?php endif; ?>
                        </ion-card-content>
                    </ion-card>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                        <h3>القضية غير موجودة أو ليس لديك صلاحية لعرضها.</h3>
                        <ion-button href="<?php echo URLROOT; ?>/client/dashboard" fill="outline" class="ion-margin-top">
                            العودة إلى لوحة التحكم
                        </ion-button>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="<?php echo URLROOT; ?>/assets/js/main.js"></script>
</body>
</html>
