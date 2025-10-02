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

            <!-- Premium Footer -->
            <div class="premium-footer">
                <div class="premium-footer-content">
                    <h3 class="animate__animated animate__fadeInUp">منصة مكاتب المحاماة</h3>
                    <p class="animate__animated animate__fadeInUp animate__delay-1s">&copy; 2024 جميع الحقوق محفوظة</p>
                    <p class="animate__animated animate__fadeInUp animate__delay-2s">تطوير: <strong>يونس ضاعني</strong></p>
                </div>
            </div>
        </ion-content>
    </ion-app>
    
    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- AOS (Animate On Scroll) Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    </script>
    
    <!-- Custom Premium JavaScript -->
    <script src="<?php echo URLROOT; ?>/public/assets/js/premium.js"></script>
    <script src="<?php echo URLROOT; ?>/public/assets/js/main.js"></script>
</body>
</html>
