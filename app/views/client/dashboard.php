<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - <?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/mobile-ionic.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>
</head>
<body>
    <ion-app>
        <ion-content>
            <!-- Header -->
            <div class="app-header">
                <div class="header-content">
                    <ion-button href="<?php echo URLROOT; ?>/" fill="clear" color="light" class="back-button">
                        <ion-icon slot="icon-only" name="arrow-forward"></ion-icon>
                    </ion-button>
                    <div>
                        <h1>مرحباً، <?php echo htmlspecialchars($user_name); ?></h1>
                        <p>إدارة قضاياك واستشاراتك القانونية</p>
                    </div>
                    <ion-button href="<?php echo URLROOT; ?>/logout" fill="clear" color="light">
                        <ion-icon slot="icon-only" name="log-out"></ion-icon>
                    </ion-button>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="content-section">
                <h2 class="section-title">نظرة عامة</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <ion-icon name="folder" class="stat-icon" color="primary"></ion-icon>
                        <span class="stat-number"><?php echo count($cases); ?></span>
                        <span class="stat-label">إجمالي القضايا</span>
                    </div>
                    <div class="stat-card">
                        <ion-icon name="pulse" class="stat-icon" color="warning"></ion-icon>
                        <span class="stat-number"><?php echo count(array_filter($cases, function($case) { return $case->status == 'in_progress'; })); ?></span>
                        <span class="stat-label">قضايا نشطة</span>
                    </div>
                    <div class="stat-card">
                        <ion-icon name="chatbubbles" class="stat-icon" color="secondary"></ion-icon>
                        <span class="stat-number"><?php echo count($consultations); ?></span>
                        <span class="stat-label">استشارات</span>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <h2 class="section-title">إجراءات سريعة</h2>
                <div class="stats-grid">
                    <ion-card button href="<?php echo URLROOT; ?>/client/new_case">
                        <ion-card-content class="ion-text-center">
                            <ion-icon name="add-circle" color="primary" style="font-size: 48px;"></ion-icon>
                            <ion-card-title>قضية جديدة</ion-card-title>
                        </ion-card-content>
                    </ion-card>
                    <ion-card button href="<?php echo URLROOT; ?>/client/consultations">
                        <ion-card-content class="ion-text-center">
                            <ion-icon name="help-circle" color="secondary" style="font-size: 48px;"></ion-icon>
                            <ion-card-title>استشارة</ion-card-title>
                        </ion-card-content>
                    </ion-card>
                </div>
                
                <!-- Recent Cases -->
                <h2 class="section-title">القضايا الحالية</h2>
                <?php if (count($cases) > 0): ?>
                    <ion-list>
                        <?php foreach (array_slice($cases, 0, 3) as $case): ?>
                            <ion-item-sliding>
                                <ion-item href="<?php echo URLROOT; ?>/client/case_details?id=<?php echo $case->id; ?>">
                                    <ion-icon name="briefcase-outline" slot="start"></ion-icon>
                                    <ion-label>
                                        <h3><?php echo htmlspecialchars($case->title); ?></h3>
                                        <p>المحامي: <?php echo htmlspecialchars($case->lawyer_name); ?></p>
                                        <p class="ion-text-right">
                                            <span class="status-badge status-<?php echo $case->status; ?>">
                                                <?php 
                                                $status_names = [
                                                    'new' => 'جديدة',
                                                    'in_progress' => 'قيد المعالجة',
                                                    'closed' => 'مغلقة',
                                                    'archived' => 'مؤرشفة'
                                                ];
                                                echo $status_names[$case->status] ?? $case->status;
                                                ?>
                                            </span>
                                        </p>
                                    </ion-label>
                                    <ion-note slot="end"><?php echo date('Y-m-d', strtotime($case->created_at)); ?></ion-note>
                                </ion-item>
                                <ion-item-options side="end">
                                    <ion-item-option href="<?php echo URLROOT; ?>/client/case_details?id=<?php echo $case->id; ?>">
                                        <ion-icon slot="icon-only" name="eye"></ion-icon>
                                    </ion-item-option>
                                    <ion-item-option href="<?php echo URLROOT; ?>/client/messages?case_id=<?php echo $case->id; ?>" color="primary">
                                        <ion-icon slot="icon-only" name="chatbubbles"></ion-icon>
                                    </ion-item-option>
                                </ion-item-options>
                            </ion-item-sliding>
                        <?php endforeach; ?>
                    </ion-list>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="folder-open-outline"></ion-icon>
                        <h3>لا توجد قضايا حالياً</h3>
                        <p>ابدأ بإضافة قضية جديدة</p>
                    </div>
                <?php endif; ?>
                
                <!-- Recent Consultations -->
                <h2 class="section-title">الاستشارات الأخيرة</h2>
                <?php if (count($consultations) > 0): ?>
                    <ion-list>
                        <?php foreach (array_slice($consultations, 0, 3) as $consultation): ?>
                            <ion-item-sliding>
                                <ion-item href="<?php echo URLROOT; ?>/client/consultations">
                                    <ion-icon name="chatbubble-ellipses-outline" slot="start"></ion-icon>
                                    <ion-label>
                                        <h3>استشارة مع <?php echo htmlspecialchars($consultation->lawyer_name); ?></h3>
                                        <p><?php echo htmlspecialchars(substr($consultation->question, 0, 50)); ?>...</p>
                                        <p class="ion-text-right">
                                            <span class="status-badge status-<?php echo $consultation->status; ?>">
                                                <?php 
                                                $status_names = [
                                                    'pending' => 'معلقة',
                                                    'answered' => 'تم الرد'
                                                ];
                                                echo $status_names[$consultation->status] ?? $consultation->status;
                                                ?>
                                            </span>
                                        </p>
                                    </ion-label>
                                    <ion-note slot="end"><?php echo date('Y-m-d', strtotime($consultation->created_at)); ?></ion-note>
                                </ion-item>
                            </ion-item-sliding>
                        <?php endforeach; ?>
                    </ion-list>
                <?php else: ?>
                    <div class="empty-state">
                        <ion-icon name="chatbubble-ellipses-outline"></ion-icon>
                        <h3>لا توجد استشارات حالياً</h3>
                        <p>يمكنك طلب استشارة جديدة</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="<?php echo URLROOT; ?>/assets/js/main.js"></script>
</body>
</html>
