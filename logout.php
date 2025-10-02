<?php
// ملف تسجيل الخروج - يونس ضاعني
require_once 'config.php';

// إنهاء الجلسة
session_start();
$_SESSION = array();
session_destroy();

// إعادة التوجيه إلى صفحة تسجيل الدخول أو الصفحة الرئيسية
redirect('login.php');
?>
