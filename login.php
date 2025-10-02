<?php
// ملف تسجيل الدخول - يونس ضاعني
require_once 'config.php';

// إذا كان المستخدم مسجل دخول بالفعل، توجيهه إلى لوحة التحكم المناسبة
if (isLoggedIn()) {
    if (hasRole('client')) {
        header("Location: client_dashboard.php");
        exit();
    } elseif (hasRole('lawyer')) {
        header("Location: lawyer_dashboard.php");
        exit();
    } elseif (hasRole('admin')) {
        header("Location: admin/dashboard.php");
        exit();
    }
}

$error = '';
$success = '';

// معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && verifyPassword($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_role'] = $user->role;
            
            // توجيه المستخدم حسب دوره
            if ($user->role == 'client') {
                header("Location: client_dashboard.php");
                exit();
            } elseif ($user->role == 'lawyer') {
                header("Location: lawyer_dashboard.php");
                exit();
            } elseif ($user->role == 'admin') {
                header("Location: admin/dashboard.php");
                exit();
            }
        } else {
            $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
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
    <title>تسجيل الدخول - <?php echo SITENAME; ?></title>
    
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
            <div class="col-12 col-md-6 col-lg-4">
                <!-- Login Card -->
                <div class="premium-container animate__animated animate__fadeInUp" data-aos="fade-up">
                    <!-- Header -->
                    <div class="text-center mb-4" data-aos="fade-down" data-aos-delay="200">
                        <div class="premium-icon mx-auto mb-3 animate__animated animate__pulse animate__infinite">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h2 class="premium-text-gradient mb-2">مرحباً بعودتك</h2>
                        <p class="text-muted">سجل دخولك للوصول إلى حسابك</p>
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
                    
                    <!-- Login Form -->
                    <form method="POST" class="premium-form" data-aos="fade-up" data-aos-delay="400">
                        <div class="premium-form-group" data-aos="fade-right" data-aos-delay="500">
                            <label class="premium-form-label">
                                <i class="fas fa-envelope me-2"></i>
                                البريد الإلكتروني
                            </label>
                            <input type="email" name="email" class="premium-input" placeholder="أدخل بريدك الإلكتروني" required>
                        </div>
                        
                        <div class="premium-form-group" data-aos="fade-left" data-aos-delay="600">
                            <label class="premium-form-label">
                                <i class="fas fa-lock me-2"></i>
                                كلمة المرور
                            </label>
                            <input type="password" name="password" class="premium-input" placeholder="أدخل كلمة المرور" required>
                        </div>
                        
                        <button type="submit" class="premium-btn w-100 mt-4" data-aos="zoom-in" data-aos-delay="700">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            تسجيل الدخول
                        </button>
                    </form>
                    
                    <!-- Footer Links -->
                    <div class="text-center mt-4" data-aos="fade-up" data-aos-delay="800">
                        <p class="text-muted mb-3">ليس لديك حساب؟</p>
                        <a href="register.php" class="premium-btn-secondary">
                            <i class="fas fa-user-plus me-2"></i>
                            إنشاء حساب جديد
                        </a>
                    </div>
                    
                    <!-- Back to Home -->
                    <div class="text-center mt-3" data-aos="fade-up" data-aos-delay="900">
                        <a href="index.php" class="text-decoration-none text-muted">
                            <i class="fas fa-arrow-right me-2"></i>
                            العودة إلى الصفحة الرئيسية
                        </a>
                    </div>
                </div>
                
                <!-- Features Cards -->
                <div class="row mt-4">
                    <div class="col-4" data-aos="fade-up" data-aos-delay="1000">
                        <div class="premium-card text-center p-3">
                            <i class="fas fa-shield-alt premium-text-gradient fs-3 mb-2"></i>
                            <small class="text-muted">آمن ومحمي</small>
                        </div>
                    </div>
                    <div class="col-4" data-aos="fade-up" data-aos-delay="1100">
                        <div class="premium-card text-center p-3">
                            <i class="fas fa-clock premium-text-gradient fs-3 mb-2"></i>
                            <small class="text-muted">متاح 24/7</small>
                        </div>
                    </div>
                    <div class="col-4" data-aos="fade-up" data-aos-delay="1200">
                        <div class="premium-card text-center p-3">
                            <i class="fas fa-users premium-text-gradient fs-3 mb-2"></i>
                            <small class="text-muted">دعم فني</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Elements -->
    <div class="position-fixed" style="top: 10%; left: 5%; z-index: -1;">
        <i class="fas fa-gavel text-white opacity-25 fs-1 animate__animated animate__pulse animate__infinite animate__slow"></i>
    </div>
    <div class="position-fixed" style="bottom: 15%; right: 8%; z-index: -1;">
        <i class="fas fa-scales text-white opacity-25 fs-2 animate__animated animate__bounce animate__infinite animate__slower"></i>
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
            
            // تأثير الإرسال
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري تسجيل الدخول...';
                submitBtn.disabled = true;
            });
        });
    </script>
    
    <script src="public/assets/js/main.js"></script>
</body>
</html>
