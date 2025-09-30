<?php
// إعدادات SMTP للبريد الإلكتروني
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com'); // يجب تغييرها
define('SMTP_PASSWORD', 'your-app-password'); // يجب تغييرها
define('SMTP_FROM_EMAIL', 'your-email@gmail.com'); // يجب تغييرها
define('SMTP_FROM_NAME', 'منصة مكاتب المحاماة');

// دالة إرسال البريد الإلكتروني
function sendEmail($to_email, $to_name, $subject, $message) {
    // استخدام PHPMailer أو mail() function
    // هذا مثال بسيط باستخدام mail()
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: ' . SMTP_FROM_NAME . ' <' . SMTP_FROM_EMAIL . '>' . "\r\n";
    
    $html_message = "
    <!DOCTYPE html>
    <html dir='rtl' lang='ar'>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; direction: rtl; text-align: right; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #667eea; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f8f9fa; }
            .footer { padding: 10px; text-align: center; color: #6c757d; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>منصة مكاتب المحاماة</h2>
            </div>
            <div class='content'>
                <h3>$subject</h3>
                <p>مرحباً $to_name،</p>
                $message
            </div>
            <div class='footer'>
                <p>هذه رسالة تلقائية من منصة مكاتب المحاماة</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    return mail($to_email, $subject, $html_message, $headers);
}

// دالة إرسال إشعار قضية جديدة للمحامي
function notifyLawyerNewCase($lawyer_email, $lawyer_name, $case_title, $client_name) {
    $subject = "قضية جديدة - $case_title";
    $message = "
        <p>تم إرسال قضية جديدة إليك من العميل <strong>$client_name</strong></p>
        <p><strong>عنوان القضية:</strong> $case_title</p>
        <p>يرجى تسجيل الدخول إلى المنصة لمراجعة تفاصيل القضية والرد على العميل.</p>
        <p><a href='#' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>دخول إلى المنصة</a></p>
    ";
    
    return sendEmail($lawyer_email, $lawyer_name, $subject, $message);
}

// دالة إرسال إشعار رد على الاستشارة للعميل
function notifyClientConsultationAnswer($client_email, $client_name, $lawyer_name) {
    $subject = "تم الرد على استشارتك";
    $message = "
        <p>تم الرد على استشارتك من قبل المحامي <strong>$lawyer_name</strong></p>
        <p>يرجى تسجيل الدخول إلى المنصة لمراجعة الرد.</p>
        <p><a href='#' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>دخول إلى المنصة</a></p>
    ";
    
    return sendEmail($client_email, $client_name, $subject, $message);
}

// دالة إرسال إشعار رسالة جديدة
function notifyNewMessage($recipient_email, $recipient_name, $sender_name, $case_title) {
    $subject = "رسالة جديدة - $case_title";
    $message = "
        <p>تم إرسال رسالة جديدة إليك من <strong>$sender_name</strong> في القضية:</p>
        <p><strong>$case_title</strong></p>
        <p>يرجى تسجيل الدخول إلى المنصة لمراجعة الرسالة والرد عليها.</p>
        <p><a href='#' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>دخول إلى المنصة</a></p>
    ";
    
    return sendEmail($recipient_email, $recipient_name, $subject, $message);
}

// دالة إرسال إشعار استشارة جديدة للمحامي
function notifyLawyerNewConsultation($lawyer_email, $lawyer_name, $client_name) {
    $subject = "استشارة جديدة من $client_name";
    $message = "
        <p>تم إرسال استشارة قانونية جديدة إليك من العميل <strong>$client_name</strong></p>
        <p>يرجى تسجيل الدخول إلى المنصة لمراجعة الاستشارة والرد عليها.</p>
        <p><a href='#' style='background: #667eea; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>دخول إلى المنصة</a></p>
    ";
    
    return sendEmail($lawyer_email, $lawyer_name, $subject, $message);
}
?>
