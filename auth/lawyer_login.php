<?php
require_once '../includes/session.php';
require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (empty($email) || empty($password)) {
        $error = 'يرجى ملء جميع الحقول';
    } else {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("SELECT id, name, email, password, status FROM lawyers WHERE email = ?");
            $stmt->execute([$email]);
            $lawyer = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($lawyer && sha1($password) === $lawyer['password']) {
                if ($lawyer['status'] === 'active') {
                    $_SESSION['user_id'] = $lawyer['id'];
                    $_SESSION['user_type'] = 'lawyer';
                    $_SESSION['user_name'] = $lawyer['name'];
                    $_SESSION['user_email'] = $lawyer['email'];
                    
                    header('Location: ../lawyer/dashboard.php');
                    exit();
                } else {
                    $error = 'حسابك غير مفعل. يرجى التواصل مع الإدارة';
                }
            } else {
                $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ في النظام. يرجى المحاولة لاحقاً';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول المحامي - منصة مكاتب محاماة أونلاين</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚖️</text></svg>">
</head>
<body>
    <!-- Background Particles -->
    <div id="particles-js"></div>
    
    <div class="container">
        <!-- Header Section -->
        <header class="header" data-aos="fade-down" data-aos-duration="1000">
            <div class="text-center">
                <i class="fas fa-user-tie feature-icon mb-3" style="font-size: 4rem;"></i>
                <h1 class="animate__animated animate__fadeInDown">
                    تسجيل دخول المحامي
                </h1>
                <p class="animate__animated animate__fadeInUp animate__delay-1s">
                    أدخل بياناتك للوصول إلى لوحة التحكم المتقدمة
                </p>
                <div class="mt-3">
                    <span class="badge bg-gradient text-white px-3 py-2">
                        <i class="fas fa-shield-alt"></i> دخول آمن ومشفر
                    </span>
                </div>
            </div>
        </header>

        <!-- Login Form Section -->
        <section class="login-form-section">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card premium-card" data-aos="zoom-in" data-aos-duration="1000">
                        <div class="card-content">
                            <div class="text-center mb-4">
                                <div class="login-icon mb-3">
                                    <i class="fas fa-user-tie" style="font-size: 3rem; color: #667eea;"></i>
                                </div>
                                <h3 class="text-gradient mb-2">مرحباً بعودتك</h3>
                                <p class="text-muted">سجل دخولك للوصول إلى حسابك</p>
                            </div>

                            <form method="POST" action="" id="loginForm" novalidate>
                                <?php if ($error): ?>
                                    <div class="alert alert-danger animate__animated animate__shakeX" role="alert">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <?php echo htmlspecialchars($error); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($success): ?>
                                    <div class="alert alert-success animate__animated animate__bounceIn" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <?php echo htmlspecialchars($success); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="form-floating mb-4">
                                    <input type="email" 
                                           class="form-control form-control-lg" 
                                           id="email" 
                                           name="email" 
                                           placeholder="البريد الإلكتروني"
                                           required 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                    <label for="email">
                                        <i class="fas fa-envelope me-2"></i>
                                        البريد الإلكتروني
                                    </label>
                                    <div class="invalid-feedback">
                                        يرجى إدخال بريد إلكتروني صحيح
                                    </div>
                                </div>

                                <div class="form-floating mb-4">
                                    <input type="password" 
                                           class="form-control form-control-lg" 
                                           id="password" 
                                           name="password" 
                                           placeholder="كلمة المرور"
                                           required>
                                    <label for="password">
                                        <i class="fas fa-lock me-2"></i>
                                        كلمة المرور
                                    </label>
                                    <div class="invalid-feedback">
                                        يرجى إدخال كلمة المرور
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">
                                            تذكرني
                                        </label>
                                    </div>
                                    <a href="#" class="text-decoration-none">
                                        <i class="fas fa-key me-1"></i>
                                        نسيت كلمة المرور؟
                                    </a>
                                </div>

                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        تسجيل الدخول
                                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                                    </button>
                                </div>

                                <hr class="my-4">

                                <div class="text-center">
                                    <p class="mb-3">ليس لديك حساب؟</p>
                                    <a href="lawyer_register.php" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>
                                        إنشاء حساب جديد
                                    </a>
                                </div>

                                <div class="text-center mt-4">
                                    <a href="../index.php" class="text-muted text-decoration-none">
                                        <i class="fas fa-arrow-right me-1"></i>
                                        العودة إلى الصفحة الرئيسية
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Preview -->
        <section class="features-preview mt-5" data-aos="fade-up">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-center mb-4 text-gradient">
                        <i class="fas fa-star me-2"></i>
                        مميزات حساب المحامي
                    </h5>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-briefcase text-primary mb-2" style="font-size: 2rem;"></i>
                            <h6>إدارة القضايا</h6>
                            <small class="text-muted">نظام متقدم لإدارة جميع قضاياك</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-users text-success mb-2" style="font-size: 2rem;"></i>
                            <h6>إدارة العملاء</h6>
                            <small class="text-muted">تواصل مباشر مع عملائك</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-chart-line text-warning mb-2" style="font-size: 2rem;"></i>
                            <h6>التقارير</h6>
                            <small class="text-muted">تقارير مفصلة عن أدائك</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS (Animate On Scroll) -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="../js/main.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
        
        // Initialize Particles
        particlesJS('particles-js', {
            particles: {
                number: { value: 30 },
                color: { value: '#ffffff' },
                shape: { type: 'circle' },
                opacity: { value: 0.1 },
                size: { value: 2 },
                move: {
                    enable: true,
                    speed: 0.5,
                    direction: 'none',
                    random: true,
                    out_mode: 'out'
                }
            }
        });
        
        // Form validation and submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const spinner = submitBtn.querySelector('.spinner-border');
            
            // Add loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            
            // Basic validation
            const email = form.querySelector('#email');
            const password = form.querySelector('#password');
            
            let isValid = true;
            
            if (!email.value.trim()) {
                email.classList.add('is-invalid');
                isValid = false;
            } else {
                email.classList.remove('is-invalid');
                email.classList.add('is-valid');
            }
            
            if (!password.value.trim()) {
                password.classList.add('is-invalid');
                isValid = false;
            } else {
                password.classList.remove('is-invalid');
                password.classList.add('is-valid');
            }
            
            if (!isValid) {
                e.preventDefault();
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
                
                // Shake animation for invalid form
                form.classList.add('animate__animated', 'animate__shakeX');
                setTimeout(() => {
                    form.classList.remove('animate__animated', 'animate__shakeX');
                }, 1000);
            }
        });
        
        // Real-time validation
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                }
            });
        });
    </script>
    
    <style>
        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #667eea;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
        }
        
        .login-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .login-icon i {
            color: white !important;
        }
    </style>
</body>
</html>
