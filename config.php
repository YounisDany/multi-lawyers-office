<?php
// ملف الإعدادات الرئيسي - يونس ضاعني

// إعدادات قاعدة البيانات
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "password"); // استخدم كلمة المرور التي قمت بتعيينها
define("DB_NAME", "lawyers_office");

// اسم الموقع
define("SITENAME", "منصة مكاتب المحاماة");

// المسار الأساسي للموقع
define("URLROOT", "http://localhost/multi-lawyers-office"); // تأكد من تعديل هذا المسار حسب بيئتك

// بدء الجلسة
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// الاتصال بقاعدة البيانات
function connectDB() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $pdo->exec("set names utf8");
        return $pdo;
    } catch (PDOException $e) {
        die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
    }
}

// دوال المساعدة الأساسية
function redirect($location) {
    header("Location: " . URLROOT . "/" . $location);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION["user_id"]);
}

function hasRole($role) {
    return isset($_SESSION["user_role"]) && $_SESSION["user_role"] == $role;
}

function view_partial($partialName) {
    // المسار الجديد لملفات الأجزاء (partials) بعد التبسيط
    include __DIR__ . "/app/views/partials/" . $partialName . ".php";
}

// تضمين ملفات الإعدادات والدوال
$pdo = connectDB();

// تعريف بعض المتغيرات العامة
$current_user_id = isLoggedIn() ? $_SESSION["user_id"] : null;
$current_user_name = isLoggedIn() ? $_SESSION["user_name"] : null;
$current_user_role = isLoggedIn() ? $_SESSION["user_role"] : null;

// دالة لتنظيف المدخلات
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// دالة لعرض رسائل الفلاش
function flash($name = ", $message = ", $class = 'success'){
    if(!empty($name)){
        if(!empty($message) && empty($_SESSION[$name])){
            if(!empty($_SESSION[$name.'_class'])){
                unset($_SESSION[$name.'_class']);
            }
            $_SESSION[$name] = $message;
            $_SESSION[$name.'_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name.'_class']) ? $_SESSION[$name.'_class'] : '';
            echo '<div class="alert alert-'.$class.'">'.$_SESSION[$name].'</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name.'_class']);
        }
    }
}

// دالة لإرسال إشعارات البريد الإلكتروني (مثال)
function notifyLawyerNewConsultation($lawyerEmail, $lawyerName, $clientName) {
    $subject = "استشارة جديدة من عميل";
    $message = "مرحباً $lawyerName،\n\nلديك استشارة جديدة من العميل $clientName. يرجى تسجيل الدخول إلى المنصة للاطلاع عليها والرد.\n\nشكراً لك،\nفريق منصة مكاتب المحاماة";
    $headers = "From: no-reply@" . $_SERVER["HTTP_HOST"] . "\r\n" .
               "Reply-To: no-reply@" . $_SERVER["HTTP_HOST"] . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // mail($lawyerEmail, $subject, $message, $headers);
    // في بيئة التطوير، قد لا يكون إرسال البريد الإلكتروني متاحاً أو مرغوباً فيه.
    // يمكنك استخدام مكتبة مثل PHPMailer لإرسال بريد إلكتروني حقيقي.
    error_log("Email sent to $lawyerEmail: $subject");
}

// دالة لتجزئة كلمة المرور
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// دالة للتحقق من كلمة المرور
function verifyPassword($password, $hashedPassword) {
    return password_verify($password, $hashedPassword);
}

