<?php
// إعدادات التطبيق العامة
session_start();

// تعريف مسار الجذر للتطبيق
define("APPROOT", dirname(dirname(__FILE__)));
// تعريف رابط الجذر للموقع
define("URLROOT", "http://localhost/multi-lawyers-office/public"); // قم بتغيير هذا الرابط ليطابق رابط موقعك
// تعريف اسم الموقع
define("SITENAME", "منصة مكاتب المحاماة");

// تحميل ملفات المساعدات الأساسية
require_once APPROOT . 
'/config/database.php';
require_once APPROOT . 
'/functions.php';
require_once APPROOT . 
'/core/Auth.php';
?>
