/**
 * ملف JavaScript للتأثيرات الفخمة والتفاعلية
 * تطوير: يونس ضاعني
 */

// تهيئة التأثيرات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    initPremiumEffects();
    initInteractiveElements();
    initScrollEffects();
    initFormEnhancements();
    initLoadingEffects();
});

/**
 * تهيئة التأثيرات الفخمة الأساسية
 */
function initPremiumEffects() {
    // تأثير الماوس على البطاقات
    const cards = document.querySelectorAll('.premium-card, .premium-container, .premium-stat-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
            this.style.boxShadow = '0 25px 50px rgba(0, 0, 0, 0.25)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = '0 10px 15px rgba(0, 0, 0, 0.1)';
        });
    });
    
    // تأثير الماوس على الأزرار
    const buttons = document.querySelectorAll('.premium-btn, .premium-btn-secondary');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 15px 30px rgba(102, 126, 234, 0.4)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        });
    });
}

/**
 * تهيئة العناصر التفاعلية
 */
function initInteractiveElements() {
    // تأثير النقر على العناصر التفاعلية
    const interactiveElements = document.querySelectorAll('.premium-interactive, .premium-list-item');
    interactiveElements.forEach(element => {
        element.addEventListener('click', function() {
            // تأثير الموجة
            createRippleEffect(this, event);
            
            // تأثير الاهتزاز الخفيف
            this.style.animation = 'none';
            setTimeout(() => {
                this.style.animation = 'pulse 0.3s ease-in-out';
            }, 10);
        });
    });
    
    // تأثير التمرير السلس للروابط الداخلية
    const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
    smoothScrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/**
 * إنشاء تأثير الموجة عند النقر
 */
function createRippleEffect(element, event) {
    const ripple = document.createElement('span');
    const rect = element.getBoundingClientRect();
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;
    
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    ripple.classList.add('ripple-effect');
    
    element.style.position = 'relative';
    element.style.overflow = 'hidden';
    element.appendChild(ripple);
    
    setTimeout(() => {
        ripple.remove();
    }, 600);
}

/**
 * تهيئة تأثيرات التمرير
 */
function initScrollEffects() {
    // تأثير الشريط العلوي عند التمرير
    let lastScrollTop = 0;
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const header = document.querySelector('ion-header, .premium-header');
        
        if (header) {
            if (scrollTop > lastScrollTop && scrollTop > 100) {
                // التمرير لأسفل
                header.style.transform = 'translateY(-100%)';
                header.style.opacity = '0.9';
            } else {
                // التمرير لأعلى
                header.style.transform = 'translateY(0)';
                header.style.opacity = '1';
            }
        }
        
        lastScrollTop = scrollTop;
        
        // تأثير الشفافية للخلفية
        const body = document.body;
        const scrollPercent = scrollTop / (document.documentElement.scrollHeight - window.innerHeight);
        body.style.setProperty('--scroll-opacity', Math.min(scrollPercent * 2, 1));
    });
    
    // تأثير الظهور التدريجي للعناصر
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // مراقبة العناصر القابلة للرسوم المتحركة
    const animatedElements = document.querySelectorAll('.premium-card, .premium-stat-card, .premium-list-item');
    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

/**
 * تحسينات النماذج
 */
function initFormEnhancements() {
    // تأثيرات حقول الإدخال
    const inputs = document.querySelectorAll('.premium-input, input, textarea, select');
    inputs.forEach(input => {
        // تأثير التركيز
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 25px rgba(102, 126, 234, 0.15)';
        });
        
        // تأثير فقدان التركيز
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';
        });
        
        // تأثير الكتابة
        input.addEventListener('input', function() {
            if (this.value.length > 0) {
                this.classList.add('has-value');
            } else {
                this.classList.remove('has-value');
            }
        });
    });
    
    // تحسين أزرار الإرسال
    const submitButtons = document.querySelectorAll('button[type="submit"], .submit-btn');
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            // تأثير التحميل
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري المعالجة...';
            this.disabled = true;
            
            // إعادة تعيين النص بعد 3 ثوان (في حالة عدم إعادة تحميل الصفحة)
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 3000);
        });
    });
}

/**
 * تأثيرات التحميل
 */
function initLoadingEffects() {
    // تأثير التحميل للصور
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '0';
            this.style.transform = 'scale(1.1)';
            setTimeout(() => {
                this.style.transition = 'all 0.5s ease-out';
                this.style.opacity = '1';
                this.style.transform = 'scale(1)';
            }, 100);
        });
    });
    
    // تأثير التحميل للمحتوى
    window.addEventListener('load', function() {
        document.body.classList.add('loaded');
        
        // إخفاء شاشة التحميل إذا كانت موجودة
        const loader = document.querySelector('.loader, .loading-screen');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 500);
        }
    });
}

/**
 * تأثيرات الإشعارات
 */
function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `premium-notification premium-notification-${type} animate__animated animate__slideInRight`;
    
    const icon = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-circle',
        'warning': 'fas fa-exclamation-triangle',
        'info': 'fas fa-info-circle'
    }[type] || 'fas fa-info-circle';
    
    notification.innerHTML = `
        <i class="${icon}"></i>
        <span>${message}</span>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    // إضافة الإشعار إلى الصفحة
    let container = document.querySelector('.notifications-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'notifications-container';
        document.body.appendChild(container);
    }
    
    container.appendChild(notification);
    
    // إزالة الإشعار تلقائياً
    setTimeout(() => {
        notification.classList.remove('animate__slideInRight');
        notification.classList.add('animate__slideOutRight');
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, duration);
}

/**
 * تأثيرات الجداول
 */
function initTableEffects() {
    const tables = document.querySelectorAll('.premium-table, table');
    tables.forEach(table => {
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.1}s`;
            row.classList.add('animate__animated', 'animate__fadeInUp');
            
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'rgba(102, 126, 234, 0.05)';
                this.style.transform = 'scale(1.01)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
                this.style.transform = 'scale(1)';
            });
        });
    });
}

/**
 * تأثيرات الشارات
 */
function initBadgeEffects() {
    const badges = document.querySelectorAll('.premium-badge, .badge');
    badges.forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1) rotate(5deg)';
        });
        
        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    });
}

/**
 * تأثيرات الأيقونات
 */
function initIconEffects() {
    const icons = document.querySelectorAll('.premium-icon, .fa, .fas, .far, .fab');
    icons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.2) rotate(10deg)';
            this.style.color = 'var(--primary-color)';
        });
        
        icon.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
            this.style.color = '';
        });
    });
}

/**
 * تأثيرات الخلفية المتحركة
 */
function initBackgroundEffects() {
    // إنشاء جسيمات متحركة في الخلفية
    const particlesContainer = document.createElement('div');
    particlesContainer.className = 'particles-container';
    particlesContainer.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -1;
        overflow: hidden;
    `;
    
    // إنشاء الجسيمات
    for (let i = 0; i < 20; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.cssText = `
            position: absolute;
            width: ${Math.random() * 4 + 2}px;
            height: ${Math.random() * 4 + 2}px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float ${Math.random() * 10 + 10}s infinite linear;
            left: ${Math.random() * 100}%;
            top: ${Math.random() * 100}%;
        `;
        particlesContainer.appendChild(particle);
    }
    
    document.body.appendChild(particlesContainer);
}

/**
 * تأثيرات الكتابة المتحركة
 */
function initTypingEffect(element, text, speed = 100) {
    let i = 0;
    element.innerHTML = '';
    
    function typeWriter() {
        if (i < text.length) {
            element.innerHTML += text.charAt(i);
            i++;
            setTimeout(typeWriter, speed);
        }
    }
    
    typeWriter();
}

/**
 * تأثيرات العد التصاعدي للأرقام
 */
function initCounterEffect(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    
    function updateCounter() {
        start += increment;
        if (start < target) {
            element.textContent = Math.floor(start);
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target;
        }
    }
    
    updateCounter();
}

/**
 * تهيئة جميع التأثيرات الإضافية
 */
function initAllEffects() {
    initTableEffects();
    initBadgeEffects();
    initIconEffects();
    initBackgroundEffects();
    
    // تأثيرات العد للإحصائيات
    const statNumbers = document.querySelectorAll('.premium-stat-number, .stat-number');
    statNumbers.forEach(stat => {
        const target = parseInt(stat.textContent);
        if (target) {
            stat.textContent = '0';
            setTimeout(() => {
                initCounterEffect(stat, target);
            }, 1000);
        }
    });
}

// تشغيل جميع التأثيرات عند تحميل الصفحة
window.addEventListener('load', initAllEffects);

// إضافة CSS للتأثيرات الإضافية
const additionalCSS = `
    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .notifications-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }
    
    .premium-notification {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .premium-notification-success {
        border-left: 4px solid #48bb78;
    }
    
    .premium-notification-error {
        border-left: 4px solid #f56565;
    }
    
    .premium-notification-warning {
        border-left: 4px solid #ed8936;
    }
    
    .premium-notification-info {
        border-left: 4px solid #667eea;
    }
    
    .notification-close {
        background: none;
        border: none;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.3s ease;
        margin-left: auto;
    }
    
    .notification-close:hover {
        opacity: 1;
    }
    
    .particles-container .particle {
        animation: float 20s infinite linear;
    }
    
    @keyframes float {
        0% {
            transform: translateY(100vh) rotate(0deg);
        }
        100% {
            transform: translateY(-100px) rotate(360deg);
        }
    }
    
    .focused {
        transform: scale(1.02);
    }
    
    .has-value {
        border-color: var(--primary-color) !important;
    }
    
    body.loaded {
        opacity: 1;
    }
    
    body {
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }
`;

// إضافة CSS إلى الصفحة
const style = document.createElement('style');
style.textContent = additionalCSS;
document.head.appendChild(style);
