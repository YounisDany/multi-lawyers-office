<?php
$current_page = basename($_SERVER['REQUEST_URI']);
$user_role = Auth::userRole() ?? '';
?>

<!-- Bottom Navigation -->
<div class="bottom-nav">
    <?php if ($user_role == 'client'): ?>
        <a href="<?php echo URLROOT; ?>/" class="nav-item <?php echo ($current_page == '') ? 'active' : ''; ?>">
            <ion-icon name="home"></ion-icon>
            <span>الرئيسية</span>
        </a>
        <a href="<?php echo URLROOT; ?>/client/dashboard" class="nav-item <?php echo (strpos($current_page, 'dashboard') !== false) ? 'active' : ''; ?>">
            <ion-icon name="grid"></ion-icon>
            <span>لوحة التحكم</span>
        </a>
        <a href="<?php echo URLROOT; ?>/client/new_case" class="nav-item <?php echo (strpos($current_page, 'new_case') !== false) ? 'active' : ''; ?>">
            <ion-icon name="add-circle"></ion-icon>
            <span>قضية جديدة</span>
        </a>
        <a href="<?php echo URLROOT; ?>/client/consultations" class="nav-item <?php echo (strpos($current_page, 'consultations') !== false) ? 'active' : ''; ?>">
            <ion-icon name="chatbubbles"></ion-icon>
            <span>الاستشارات</span>
        </a>
    <?php elseif ($user_role == 'lawyer'): ?>
        <a href="<?php echo URLROOT; ?>/" class="nav-item <?php echo ($current_page == '') ? 'active' : ''; ?>">
            <ion-icon name="home"></ion-icon>
            <span>الرئيسية</span>
        </a>
        <a href="<?php echo URLROOT; ?>/lawyer/dashboard" class="nav-item <?php echo (strpos($current_page, 'dashboard') !== false) ? 'active' : ''; ?>">
            <ion-icon name="grid"></ion-icon>
            <span>لوحة التحكم</span>
        </a>
        <a href="<?php echo URLROOT; ?>/lawyer/cases" class="nav-item <?php echo (strpos($current_page, 'cases') !== false) ? 'active' : ''; ?>">
            <ion-icon name="briefcase"></ion-icon>
            <span>القضايا</span>
        </a>
        <a href="<?php echo URLROOT; ?>/lawyer/consultations" class="nav-item <?php echo (strpos($current_page, 'consultations') !== false) ? 'active' : ''; ?>">
            <ion-icon name="chatbubble-ellipses"></ion-icon>
            <span>الاستشارات</span>
        </a>
    <?php elseif ($user_role == 'admin'): ?>
        <a href="<?php echo URLROOT; ?>/" class="nav-item <?php echo ($current_page == '') ? 'active' : ''; ?>">
            <ion-icon name="home"></ion-icon>
            <span>الرئيسية</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/dashboard" class="nav-item <?php echo (strpos($current_page, 'dashboard') !== false) ? 'active' : ''; ?>">
            <ion-icon name="grid"></ion-icon>
            <span>لوحة التحكم</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/lawyers" class="nav-item <?php echo (strpos($current_page, 'lawyers') !== false) ? 'active' : ''; ?>">
            <ion-icon name="people"></ion-icon>
            <span>المحامون</span>
        </a>
        <a href="<?php echo URLROOT; ?>/admin/reports" class="nav-item <?php echo (strpos($current_page, 'reports') !== false) ? 'active' : ''; ?>">
            <ion-icon name="bar-chart"></ion-icon>
            <span>التقارير</span>
        </a>
    <?php else: ?>
        <a href="<?php echo URLROOT; ?>/" class="nav-item <?php echo ($current_page == '') ? 'active' : ''; ?>">
            <ion-icon name="home"></ion-icon>
            <span>الرئيسية</span>
        </a>
        <a href="<?php echo URLROOT; ?>/services" class="nav-item <?php echo (strpos($current_page, 'services') !== false) ? 'active' : ''; ?>">
            <ion-icon name="briefcase"></ion-icon>
            <span>الخدمات</span>
        </a>
        <a href="<?php echo URLROOT; ?>/lawyers" class="nav-item <?php echo (strpos($current_page, 'lawyers') !== false) ? 'active' : ''; ?>">
            <ion-icon name="people"></ion-icon>
            <span>المحامون</span>
        </a>
        <a href="<?php echo URLROOT; ?>/login" class="nav-item <?php echo (strpos($current_page, 'login') !== false) ? 'active' : ''; ?>">
            <ion-icon name="person-circle"></ion-icon>
            <span>حسابي</span>
        </a>
    <?php endif; ?>
</div>

<!-- Footer -->
<div class="footer">
    <div class="footer-content">
        <p>&copy; 2024 منصة مكاتب المحاماة. جميع الحقوق محفوظة.</p>
        <p>تطوير: <strong>يونس ضاعني</strong></p>
    </div>
</div>

<style>
/* Bottom Navigation */
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-around;
    padding: 8px 0 calc(8px + env(safe-area-inset-bottom));
    z-index: 1000;
}

.nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 8px 16px;
    text-decoration: none;
    color: #666;
    transition: all 0.3s ease;
}

.nav-item.active {
    color: #667eea;
}

.nav-item ion-icon {
    font-size: 24px;
    margin-bottom: 4px;
}

.nav-item span {
    font-size: 0.7rem;
    font-weight: 500;
}

/* Footer */
.footer {
    background: #f8f9fa;
    padding: 20px 0;
    margin-top: 40px;
    margin-bottom: 80px; /* Space for bottom nav */
    text-align: center;
    border-top: 1px solid #e9ecef;
}

.footer-content p {
    margin: 5px 0;
    color: #6c757d;
    font-size: 0.9rem;
}
</style>
