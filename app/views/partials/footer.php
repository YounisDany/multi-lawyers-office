<?php
// ملف التذييل المشترك - يونس ضاعني
// هذا الملف يتم تضمينه في نهاية كل صفحة لعرض تذييل الصفحة المشترك.
?>

            <!-- Bottom Navigation -->
            <ion-footer>
                <ion-toolbar>
                    <ion-tabs>
                        <ion-tab-bar slot="bottom">
                            <ion-tab-button href="<?php echo URLROOT; ?>/index.php">
                                <ion-icon name="home-outline"></ion-icon>
                                <ion-label>الرئيسية</ion-label>
                            </ion-tab-button>
                            <?php if (isLoggedIn()): ?>
                                <?php if (hasRole("client")): ?>
                                    <ion-tab-button href="<?php echo URLROOT; ?>/client_dashboard.php">
                                        <ion-icon name="speedometer-outline"></ion-icon>
                                        <ion-label>لوحة التحكم</ion-label>
                                    </ion-tab-button>
                                    <ion-tab-button href="<?php echo URLROOT; ?>/client_new_case.php">
                                        <ion-icon name="add-circle-outline"></ion-icon>
                                        <ion-label>قضية جديدة</ion-label>
                                    </ion-tab-button>
                                    <ion-tab-button href="<?php echo URLROOT; ?>/client_consultations.php">
                                        <ion-icon name="chatbubbles-outline"></ion-icon>
                                        <ion-label>استشاراتي</ion-label>
                                    </ion-tab-button>
                                <?php elseif (hasRole("lawyer")): ?>
                                    <ion-tab-button href="<?php echo URLROOT; ?>/lawyer_dashboard.php">
                                        <ion-icon name="speedometer-outline"></ion-icon>
                                        <ion-label>لوحة التحكم</ion-label>
                                    </ion-tab-button>
                                    <ion-tab-button href="<?php echo URLROOT; ?>/lawyer_cases.php">
                                        <ion-icon name="folder-open-outline"></ion-icon>
                                        <ion-label>القضايا</ion-label>
                                    </ion-tab-button>
                                    <ion-tab-button href="<?php echo URLROOT; ?>/lawyer_consultations.php">
                                        <ion-icon name="chatbubbles-outline"></ion-icon>
                                        <ion-label>الاستشارات</ion-label>
                                    </ion-tab-button>
                                <?php elseif (hasRole("admin")): ?>
                                    <ion-tab-button href="<?php echo URLROOT; ?>/admin/dashboard.php">
                                        <ion-icon name="speedometer-outline"></ion-icon>
                                        <ion-label>لوحة التحكم</ion-label>
                                    </ion-tab-button>
                                    <ion-tab-button href="<?php echo URLROOT; ?>/admin/cases.php">
                                        <ion-icon name="folder-open-outline"></ion-icon>
                                        <ion-label>القضايا</ion-label>
                                    </ion-tab-button>
                                    <ion-tab-button href="<?php echo URLROOT; ?>/admin/lawyers.php">
                                        <ion-icon name="briefcase-outline"></ion-icon>
                                        <ion-label>المحامون</ion-label>
                                    </ion-tab-button>
                                    <ion-tab-button href="<?php echo URLROOT; ?>/admin/reports.php">
                                        <ion-icon name="bar-chart-outline"></ion-icon>
                                        <ion-label>التقارير</ion-label>
                                    </ion-tab-button>
                                <?php endif; ?>
                                <ion-tab-button href="<?php echo URLROOT; ?>/logout.php">
                                    <ion-icon name="log-out-outline"></ion-icon>
                                    <ion-label>خروج</ion-label>
                                </ion-tab-button>
                            <?php else: ?>
                                <ion-tab-button href="<?php echo URLROOT; ?>/login.php">
                                    <ion-icon name="log-in-outline"></ion-icon>
                                    <ion-label>دخول</ion-label>
                                </ion-tab-button>
                                <ion-tab-button href="<?php echo URLROOT; ?>/register.php">
                                    <ion-icon name="person-add-outline"></ion-icon>
                                    <ion-label>تسجيل</ion-label>
                                </ion-tab-button>
                            <?php endif; ?>
                        </ion-tab-bar>
                    </ion-tabs>
                </ion-toolbar>
            </ion-footer>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-content">
                    <p>&copy; 2024 منصة مكاتب المحاماة. جميع الحقوق محفوظة.</p>
                    <p>تطوير: <strong>يونس ضاعني</strong></p>
                </div>
            </div>
        </ion-content>
    </ion-app>
    
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js"></script>
</body>
</html>
