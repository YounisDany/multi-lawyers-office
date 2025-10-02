# منصة مكاتب المحاماة أونلاين - الإصدار 2.0

## نظرة عامة

منصة متكاملة تربط بين المحامين والعملاء لتقديم الخدمات القانونية بكفاءة وسهولة. تم تطويرها باستخدام Ionic Framework لتوفير تجربة تطبيق هاتف حديثة ومتجاوبة.

## المميزات الرئيسية

### 1. تصميم تطبيق هاتف حديث
- واجهة مستخدم عصرية باستخدام Ionic Framework
- تصميم متجاوب بالكامل يعمل على جميع الأجهزة
- مكونات Ionic تفاعلية (Buttons, Cards, Icons)
- دعم PWA (Progressive Web App)

### 2. شريط التنقل السفلي
- شريط تنقل ثابت في أسفل الشاشة
- أيقونات واضحة لسهولة الاستخدام
- يتكيف حسب نوع المستخدم (عميل، محامي، أدمن)

### 3. الأيقونات والأصول المرئية
- مكتبة Ionicons الكاملة
- أيقونات قانونية متخصصة
- شعار التطبيق وأيقونات PWA

### 4. الصفحات المحدثة
- الصفحة الرئيسية (index.php)
- تسجيل الدخول (login.php)
- التسجيل (register.php)
- لوحة تحكم العميل (client/dashboard.php)
- لوحة تحكم المحامي (lawyer/dashboard.php)
- صفحة المحامين (lawyers.php)
- صفحة الخدمات (services.php)

## التقنيات المستخدمة

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework**: Ionic Framework 8.x (via CDN)
- **Icons**: Ionicons 5.5.2
- **Backend**: PHP 7.4+
- **Database**: MySQL/MariaDB
- **PWA**: Service Worker, Manifest.json

## المتطلبات

- PHP 7.4 أو أحدث
- MySQL/MariaDB 5.7 أو أحدث
- متصفح حديث يدعم ES6
- اتصال بالإنترنت (لتحميل Ionic من CDN)

## التثبيت

1. **رفع الملفات إلى الخادم**
   ```bash
   # رفع جميع ملفات المشروع إلى مجلد الويب
   ```

2. **إعداد قاعدة البيانات**
   ```bash
   # استيراد ملف database.sql إلى MySQL
   mysql -u username -p database_name < database.sql
   ```

3. **تحديث إعدادات الاتصال**
   ```php
   // تحرير ملف config.php
   $host = 'localhost';
   $dbname = 'your_database_name';
   $username = 'your_username';
   $password = 'your_password';
   ```

4. **الوصول إلى التطبيق**
   ```
   افتح المتصفح وانتقل إلى:
   http://your-domain.com/index.php
   ```

## هيكل المشروع

```
multi-lawyers-office/
├── admin/              # صفحات لوحة تحكم الأدمن
├── assets/             # الأصول (CSS, JS, Images)
│   ├── css/
│   │   ├── style.css
│   │   └── mobile-ionic.css
│   ├── js/
│   │   └── main.js
│   └── images/
│       ├── lawyer-icon-1.png
│       ├── lawyer-icon-2.png
│       └── legal-icons-set.jpg
├── client/             # صفحات لوحة تحكم العميل
├── includes/           # ملفات مشتركة
│   ├── ionic_header.php
│   └── bottom_nav.php
├── lawyer/             # صفحات لوحة تحكم المحامي
├── config.php          # إعدادات قاعدة البيانات
├── index.php           # الصفحة الرئيسية
├── login.php           # صفحة تسجيل الدخول
├── register.php        # صفحة التسجيل
├── lawyers.php         # صفحة المحامين
├── services.php        # صفحة الخدمات
├── manifest.json       # ملف PWA
└── service-worker.js   # Service Worker للعمل دون اتصال
```

## الاستخدام

### للعملاء
1. التسجيل كعميل جديد
2. تصفح المحامين المتاحين
3. إنشاء قضية جديدة
4. التواصل مع المحامي
5. متابعة حالة القضية

### للمحامين
1. التسجيل كمحامي
2. عرض القضايا المسندة
3. الرد على الاستشارات
4. إدارة العملاء
5. إنشاء التقارير

### للأدمن
1. مراقبة المنصة
2. إدارة المحامين والعملاء
3. مراجعة القضايا
4. إنشاء التقارير الإحصائية

## ميزات PWA

التطبيق يدعم Progressive Web App:

- **التثبيت**: يمكن تثبيت التطبيق على الشاشة الرئيسية
- **العمل دون اتصال**: Service Worker يوفر إمكانية العمل دون إنترنت
- **الإشعارات**: دعم إشعارات Push (قيد التطوير)

## التخصيص

### تغيير الألوان
```css
/* في ملف assets/css/mobile-ionic.css */
:root {
    --ion-color-primary: #667eea;  /* اللون الأساسي */
    --ion-color-secondary: #764ba2; /* اللون الثانوي */
}
```

### إضافة صفحات جديدة
1. إنشاء ملف PHP جديد
2. تضمين `includes/ionic_header.php` في الـ `<head>`
3. تضمين `includes/bottom_nav.php` قبل `</body>`
4. استخدام الأنماط من `assets/css/mobile-ionic.css`

## الأمان

- جميع المدخلات يتم تنظيفها باستخدام `htmlspecialchars()`
- استخدام Prepared Statements لمنع SQL Injection
- التحقق من الصلاحيات قبل الوصول للصفحات
- تشفير كلمات المرور باستخدام `password_hash()`

## الدعم الفني

للحصول على الدعم أو الإبلاغ عن مشاكل:
- البريد الإلكتروني: support@example.com
- GitHub Issues: [رابط المستودع]

## الترخيص

هذا المشروع مرخص تحت رخصة MIT.

## المطور

**يونس ضاعني**
- تاريخ التطوير: أكتوبر 2025
- الإصدار: 2.0.0

## الميزات المستقبلية

- [ ] إشعارات Push
- [ ] وضع الظلام (Dark Mode)
- [ ] دعم لغات متعددة
- [ ] دردشة مباشرة مع WebSocket
- [ ] نظام دفع إلكتروني
- [ ] تطبيقات هاتف أصلية (iOS/Android) باستخدام Capacitor

---

**ملاحظة**: هذا المشروع تم تطويره باستخدام أحدث تقنيات الويب لتوفير تجربة مستخدم ممتازة على جميع الأجهزة.
