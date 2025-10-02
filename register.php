<?php
// ملف التسجيل - يونس ضاعني
require_once 'config.php';

$error = '';
$success = '';

// معالجة التسجيل
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = sanitize_input($_POST['role']);
    
    if (!empty($name) && !empty($email) && !empty($password) && !empty($confirm_password) && !empty($role)) {
        if ($password === $confirm_password) {
            // التحقق من عدم وجود البريد الإلكتروني مسبقاً
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() == 0) {
                $hashed_password = hashPassword($password);
                
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
                
                if ($stmt->execute([$name, $email, $hashed_password, $role])) {
                    $success = 'تم إنشاء الحساب بنجاح. يمكنك الآن تسجيل الدخول.';
                } else {
                    $error = 'حدث خطأ أثناء إنشاء الحساب';
                }
            } else {
                $error = 'البريد الإلكتروني مستخدم بالفعل';
            }
        } else {
            $error = 'كلمات المرور غير متطابقة';
        }
    } else {
        $error = 'يرجى ملء جميع الحقول';
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب جديد - <?php echo SITENAME; ?></title>
    
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Animate.css CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Ionic Framework CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css"/>
    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/assets/css/style.css">
    <link rel="stylesheet" href="public/assets/css/premium-style.css">
    <link rel="stylesheet" href="public/assets/css/mobile-ionic.css">
</head>
<body class="premium-bg-animated">
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center p-4">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <!-- Register Card -->
                <div class="premium-container animate__animated animate__fadeInUp" data-aos="fade-up">
                    <!-- Header -->
                    <div class="text-center mb-4" data-aos="fade-down" data-aos-delay="200">
                        <div class="premium-icon mx-auto mb-3 animate__animated animate__pulse animate__infinite">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h2 class="premium-text-gradient mb-2">انضم إلينا</h2>
                        <p class="text-muted">أنشئ حسابك الجديد للبدء في رحلتك القانونية</p>
                    </div>
                    
                    <!-- Error/Success Messages -->
                    <?php if ($error): ?>
                        <div class="premium-alert premium-alert-error animate__animated animate__shakeX" data-aos="fade-in">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="premium-alert premium-alert-success animate__animated animate__bounceIn" data-aos="fade-in">
                            <i class="fas fa-check-circle"></i>
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Register Form -->
                    <form method="POST" class="premium-form" data-aos="fade-up" data-aos-delay="400">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="premium-form-group" data-aos="fade-right" data-aos-delay="500">
                                    <label class="premium-form-label">
                                        <i class="fas fa-user me-2"></i>
                                        الاسم الكامل
                                    </label>
                                    <input type="text" name="name" class="premium-input" placeholder="أدخل اسمك الكامل" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="premium-form-group" data-aos="fade-left" data-aos-delay="600">
                                    <label class="premium-form-label">
                                        <i class="fas fa-envelope me-2"></i>
                                        البريد الإلكتروني
                                    </label>
                                    <input type="email" name="email" class="premium-input" placeholder="أدخل بريدك الإلكتروني" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="premium-form-group" data-aos="fade-up" data-aos-delay="700">
                            <label class="premium-form-label">
                                <i class="fas fa-user-tag me-2"></i>
                                نوع الحساب
                            </label>
                            <select name="role" class="premium-input" required>
                                <option value="">اختر نوع الحساب</option>
                                <option value="client">
                                    <i class="fas fa-user"></i> عميل - للحصول على الخدمات القانونية
                                </option>
                                <option value="lawyer">
                                    <i class="fas fa-briefcase"></i> محامي - لتقديم الخدمات القانونية
                                </option>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="premium-form-group" data-aos="fade-right" data-aos-delay="800">
                                    <label class="premium-form-label">
                                        <i class="fas fa-lock me-2"></i>
                                        كلمة المرور
                                    </label>
                                    <input type="password" name="password" class="premium-input" placeholder="أدخل كلمة المرور" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="premium-form-group" data-aos="fade-left" data-aos-delay="900">
                                    <label class="premium-form-label">
                                        <i class="fas fa-lock me-2"></i>
                                        تأكيد كلمة المرور
                                    </label>
                                    <input type="password" name="confirm_password" class="premium-input" placeholder="أعد إدخال كلمة المرور" required>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="premium-btn w-100 mt-4" data-aos="zoom-in" data-aos-delay="1000">
                            <i class="fas fa-user-plus me-2"></i>
                            إنشاء الحساب
                        </button>
                    </form>
                    
                    <!-- Footer Links -->
                    <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="1100">
                        <p class="text-muted mb-3">لديك حساب بالفعل؟</p>
                        <a href="login.php" class="premium-btn-secondary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            تسجيل الدخول
                        </a>
                    </div>
                    
                    <!-- Back to Home -->
                    <div class="text-center mt-3" data-aos="fade-up" data-aos-delay="1200">
                        <a href="index.php" class="text-decoration-none text-muted">
                            <i class="fas fa-arrow-right me-2"></i>
                            العودة إلى الصفحة الرئيسية
                        </a>
                    </div>
                </div>
                
                <!-- Benefits Cards -->
                <div class="row mt-4">
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="1300">
                        <div class="premium-card text-center p-4">
                            <i class="fas fa-gavel premium-text-gradient fs-2 mb-3"></i>
                            <h5>خدمات قانونية متميزة</h5>
                            <p class="text-muted small">احصل على أفضل الاستشارات القانونية من محامين معتمدين</p>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="1400">
                        <div class="premium-card text-center p-4">
                            <i class="fas fa-handshake premium-text-gradient fs-2 mb-3"></i>
                            <h5>ثقة وموثوقية</h5>
                            <p class="text-muted small">منصة آمنة وموثوقة لجميع احتياجاتك القانونية</p>
                        </div>
                    </div>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="1500">
                        <div class="premium-card text-center p-4">
                            <i class="fas fa-clock premium-text-gradient fs-2 mb-3"></i>
                            <h5>متاح على مدار الساعة</h5>
                            <p class="text-muted small">خدمة عملاء متاحة 24/7 لمساعدتك في أي وقت</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Elements -->
    <div class="position-fixed" style="top: 15%; left: 3%; z-index: -1;">
        <i class="fas fa-balance-scale text-white opacity-25 fs-1 animate__animated animate__float animate__infinite animate__slow"></i>
    </div>
    <div class="position-fixed" style="bottom: 20%; right: 5%; z-index: -1;">
        <i class="fas fa-briefcase text-white opacity-25 fs-2 animate__animated animate__bounce animate__infinite animate__slower"></i>
    </div>
    <div class="position-fixed" style="top: 50%; left: 2%; z-index: -1;">
        <i class="fas fa-gavel text-white opacity-25 fs-3 animate__animated animate__swing animate__infinite animate__slower"></i>
    </div>
    
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
    <script>
        // تأثيرات إضافية للنموذج
        document.addEventListener('DOMContentLoaded', function() {
            // تأثير التركيز على الحقول
            const inputs = document.querySelectorAll('.premium-input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('animate__animated', 'animate__pulse');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('animate__animated', 'animate__pulse');
                });
            });
            
            // التحقق من تطابق كلمات المرور
            const password = document.querySelector('input[name="password"]');
            const confirmPassword = document.querySelector('input[name="confirm_password"]');
            
            confirmPassword.addEventListener('input', function() {
                if (this.value !== password.value) {
                    this.style.borderColor = '#e53e3e';
                } else {
                    this.style.borderColor = '#48bb78';
                }
            });
            
            // تأثير الإرسال
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري إنشاء الحساب...';
                submitBtn.disabled = true;
            });
        });
    </script>
    
    <script src="public/assets/js/main.js"></script>
</body>
</html>
