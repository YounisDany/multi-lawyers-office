<?php
// دوال مساعدة عامة

/**
 * إعادة توجيه المستخدم إلى رابط معين.
 * @param string $url الرابط المراد التوجيه إليه.
 */
function redirect($url) {
    header("Location: " . URLROOT . "/" . $url);
    exit();
}

/**
 * تشفير كلمة المرور.
 * @param string $password كلمة المرور المراد تشفيرها.
 * @return string كلمة المرور المشفرة.
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * التحقق من تطابق كلمة المرور مع الهاش.
 * @param string $password كلمة المرور.
 * @param string $hash الهاش المشفر.
 * @return bool
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * عرض جزء من الواجهة (View Partial).
 * @param string $partial اسم الملف الجزئي.
 * @param array $data البيانات المراد تمريرها إلى الجزء.
 */
function view_partial($partial, $data = []) {
    extract($data);
    require_once APPROOT . 
    '/views/partials/' . $partial . '.php';
}
?>
