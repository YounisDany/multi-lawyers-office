<?php
$current_page = basename($_SERVER['PHP_SELF']);
$user_role = $_SESSION['user_role'] ?? '';
?>

<!-- Bottom Navigation -->
<div class="bottom-nav">
    <?php if ($user_role == 'client'): ?>
        <a href="../index.php" class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <ion-icon name="home"></ion-icon>
            <span>الرئيسية</span>
        </a>
        <a href="dashboard.php" class="nav-item <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <ion-icon name="grid"></ion-icon>
            <span>لوحة التحكم</span>
        </a>
        <a href="new_case.php" class="nav-item <?php echo ($current_page == 'new_case.php') ? 'active' : ''; ?>">
            <ion-icon name="add-circle"></ion-icon>
            <span>قضية جديدة</span>
        </a>
        <a href="messages.php" class="nav-item <?php echo ($current_page == 'messages.php') ? 'active' : ''; ?>">
            <ion-icon name="chatbubbles"></ion-icon>
            <span>الرسائل</span>
        </a>
    <?php elseif ($user_role == 'lawyer'): ?>
        <a href="../index.php" class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <ion-icon name="home"></ion-icon>
            <span>الرئيسية</span>
        </a>
        <a href="dashboard.php" class="nav-item <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <ion-icon name="grid"></ion-icon>
            <span>لوحة التحكم</span>
        </a>
        <a href="cases.php" class="nav-item <?php echo ($current_page == 'cases.php') ? 'active' : ''; ?>">
            <ion-icon name="briefcase"></ion-icon>
            <span>القضايا</span>
        </a>
        <a href="consultations.php" class="nav-item <?php echo ($current_page == 'consultations.php') ? 'active' : ''; ?>">
            <ion-icon name="chatbubble-ellipses"></ion-icon>
            <span>الاستشارات</span>
        </a>
    <?php elseif ($user_role == 'admin'): ?>
        <a href="../index.php" class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <ion-icon name="home"></ion-icon>
            <span>الرئيسية</span>
        </a>
        <a href="dashboard.php" class="nav-item <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <ion-icon name="grid"></ion-icon>
            <span>لوحة التحكم</span>
        </a>
        <a href="lawyers.php" class="nav-item <?php echo ($current_page == 'lawyers.php') ? 'active' : ''; ?>">
            <ion-icon name="people"></ion-icon>
            <span>المحامون</span>
        </a>
        <a href="reports.php" class="nav-item <?php echo ($current_page == 'reports.php') ? 'active' : ''; ?>">
            <ion-icon name="bar-chart"></ion-icon>
            <span>التقارير</span>
        </a>
    <?php else: ?>
        <a href="index.php" class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <ion-icon name="home"></ion-icon>
            <span>الرئيسية</span>
        </a>
        <a href="services.php" class="nav-item <?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">
            <ion-icon name="briefcase"></ion-icon>
            <span>الخدمات</span>
        </a>
        <a href="lawyers.php" class="nav-item <?php echo ($current_page == 'lawyers.php') ? 'active' : ''; ?>">
            <ion-icon name="people"></ion-icon>
            <span>المحامون</span>
        </a>
        <a href="login.php" class="nav-item <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">
            <ion-icon name="person-circle"></ion-icon>
            <span>حسابي</span>
        </a>
    <?php endif; ?>
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
</style>
