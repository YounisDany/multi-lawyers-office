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
        <ion-header>
            <ion-toolbar color="primary">
                <ion-title><?php echo $title; ?></ion-title>
                <ion-buttons slot="end">
                    <ion-button href="<?php echo URLROOT; ?>/logout">
                        <ion-icon name="log-out"></ion-icon>
                    </ion-button>
                </ion-buttons>
            </ion-toolbar>
        </ion-header>
        
        <ion-content class="ion-padding">
            <div class="content-section">
                <div class="dashboard-header">
                    <h2>مرحباً، <?php echo $user_name; ?></h2>
                    <p>إليك نظرة عامة على نشاطك</p>
                </div>
                
                <!-- Quick Stats -->
                <div class="stats-grid">
                    <ion-card class="stat-card">
                        <ion-card-content>
                            <ion-icon name="briefcase" color="primary"></ion-icon>
                            <h3><?php echo $stats["total_cases"]; ?></h3>
                            <p>إجمالي القضايا</p>
                        </ion-card-content>
                    </ion-card>
                    
                    <ion-card class="stat-card">
                        <ion-card-content>
                            <ion-icon name="add-circle" color="secondary"></ion-icon>
                            <h3><?php echo $stats["new_cases"]; ?></h3>
                            <p>قضايا جديدة</p>
                        </ion-card-content>
                    </ion-card>
                    
                    <ion-card class="stat-card">
                        <ion-card-content>
                            <ion-icon name="sync-circle" color="warning"></ion-icon>
                            <h3><?php echo $stats["in_progress_cases"]; ?></h3>
                            <p>قيد التنفيذ</p>
                        </ion-card-content>
                    </ion-card>
                    
                    <ion-card class="stat-card">
                        <ion-card-content>
                            <ion-icon name="help-buoy" color="tertiary"></ion-icon>
                            <h3><?php echo $stats["pending_consultations"]; ?></h3>
                            <p>استشارات معلقة</p>
                        </ion-card-content>
                    </ion-card>
                </div>
                
                <!-- Recent Cases -->
                <div class="recent-section">
                    <h3>أحدث القضايا</h3>
                    <?php if (!empty($cases)): ?>
                        <ion-list>
                            <?php foreach (array_slice($cases, 0, 5) as $case): ?>
                                <ion-item href="<?php echo URLROOT; ?>/lawyer/cases#case-<?php echo $case->id; ?>">
                                    <ion-icon name="folder-open-outline" slot="start"></ion-icon>
                                    <ion-label>
                                        <h4><?php echo htmlspecialchars($case->title); ?></h4>
                                        <p>العميل: <?php echo htmlspecialchars($case->client_name); ?></p>
                                    </ion-label>
                                    <ion-badge slot="end" color="<?php echo $case->status == 'new' ? 'primary' : 'warning'; ?>">
                                        <?php echo $case->status == 'new' ? 'جديدة' : 'قيد التنفيذ'; ?>
                                    </ion-badge>
                                </ion-item>
                            <?php endforeach; ?>
                        </ion-list>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>لا توجد قضايا حالياً.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Pending Consultations -->
                <div class="recent-section">
                    <h3>الاستشارات المعلقة</h3>
                    <?php if (!empty($consultations)): ?>
                        <ion-list>
                            <?php foreach (array_filter($consultations, function($c) { return $c->status == 'pending'; }) as $consultation): ?>
                                <ion-item href="<?php echo URLROOT; ?>/lawyer/consultations#consultation-<?php echo $consultation->id; ?>">
                                    <ion-icon name="chatbubble-ellipses-outline" slot="start"></ion-icon>
                                    <ion-label>
                                        <h4>استشارة من: <?php echo htmlspecialchars($consultation->client_name); ?></h4>
                                        <p><?php echo substr(htmlspecialchars($consultation->question), 0, 100) . '...'; ?></p>
                                    </ion-label>
                                </ion-item>
                            <?php endforeach; ?>
                        </ion-list>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>لا توجد استشارات معلقة.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php view_partial("footer"); ?>
        </ion-content>
    </ion-app>
    
    <script src="<?php echo URLROOT; ?>/assets/js/main.js"></script>
</body>
</html>
