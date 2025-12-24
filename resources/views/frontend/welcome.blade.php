@extends('frontend.structure')

@section('title', __('admin.home'))

@section('main.style')
    <style>
        /* Import Modern Font */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary-color: #06b6d4;
            --accent-color: #ec4899;
            --success-color: #10b981;
            --dark-bg: #0f172a;
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-dark: #1e293b;
            --text-light: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            padding: 120px 0 100px;
            background: linear-gradient(135deg,
                    rgba(102, 126, 234, 0.95) 0%,
                    rgba(118, 75, 162, 0.95) 100%);
            color: white;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            margin-bottom: 2rem;
            opacity: 0.95;
            line-height: 1.6;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, #ec4899 0%, #f43f5e 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
            border: none;
        }

        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.5);
            color: white;
        }

        .btn-outline-light {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            color: white;
        }

        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: white;
            position: relative;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
            text-align: center;
        }

        .section-subtitle {
            font-size: 1.125rem;
            color: var(--text-light);
            text-align: center;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .feature-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid rgba(99, 102, 241, 0.1);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.15);
            border-color: rgba(99, 102, 241, 0.3);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .feature-description {
            color: var(--text-light);
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* Premium Badge */
        .feature-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.4);
            z-index: 10;
        }

        [dir="rtl"] .feature-badge {
            right: auto;
            left: 1rem;
        }

        .feature-card {
            position: relative;
        }

        .feature-card.premium {
            border-color: rgba(245, 158, 11, 0.2);
        }

        .feature-card.premium:hover {
            border-color: rgba(245, 158, 11, 0.4);
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .stats-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveBackground 20s linear infinite;
        }

        @keyframes moveBackground {
            0% {
                transform: translate(0, 0);
            }

            100% {
                transform: translate(50px, 50px);
            }
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
            position: relative;
            z-index: 2;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #fff 0%, rgba(255, 255, 255, 0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            font-size: 1.125rem;
            opacity: 0.9;
            font-weight: 500;
        }

        /* CTA Section */
        .cta-section {
            background: white;
            padding: 80px 0;
        }

        .cta-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 24px;
            padding: 4rem 3rem;
            text-align: center;
            color: white;
            box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .cta-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            position: relative;
            z-index: 2;
        }

        .cta-subtitle {
            font-size: 1.125rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            position: relative;
            z-index: 2;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .section-title {
                font-size: 2rem;
            }

            .cta-title {
                font-size: 2rem;
            }

            .stat-number {
                font-size: 2.5rem;
            }
        }

        /* Floating Animation for Icons */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        /* Developer Section */
        .developer-section {
            padding: 100px 0;
            background: #f8fafc;
            position: relative;
            overflow: hidden;
        }

        .developer-card {
            background: white;
            border-radius: 30px;
            padding: 3rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(99, 102, 241, 0.1);
            position: relative;
            z-index: 2;
            transition: all 0.4s ease;
        }

        .developer-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(99, 102, 241, 0.1);
        }

        .developer-image-container {
            position: relative;
            width: 250px;
            height: 250px;
            margin: 0 auto 2rem;
        }

        .developer-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 8px solid white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }

        .developer-image-ring {
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 2px dashed var(--primary-color);
            border-radius: 50%;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .developer-info {
            text-align: center;
        }

        .dev-badge {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .dev-name {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .dev-title {
            font-size: 1.25rem;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .dev-description {
            color: var(--text-light);
            font-size: 1.1rem;
            line-height: 1.7;
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .social-link {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            background: #f1f5f9;
            color: var(--text-dark);
            text-decoration: none;
        }

        .social-link:hover {
            transform: translateY(-5px) scale(1.1);
            color: white;
        }

        .social-link.github:hover {
            background: #333;
        }

        .social-link.linkedin:hover {
            background: #0077b5;
        }

        .social-link.whatsapp:hover {
            background: #25d366;
        }

        .social-link.email:hover {
            background: #ea4335;
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 hero-content">
                    <h1 class="hero-title">
                        {{ __('frontend.clinic_management_system') }}
                    </h1>
                    <p class="hero-subtitle">
                        نظام إدارة متكامل وحديث للعيادات الطبية. سهل الاستخدام، آمن، ومصمم خصيصاً لتلبية احتياجات العيادات
                        من جميع الأحجام والتخصصات.
                    </p>
                    <div class="hero-buttons">
                        <a href="{{ route('admin.register') }}" class="btn-primary-gradient">
                            <i class="fas fa-rocket"></i>
                            ابدأ الآن مجاناً
                        </a>
                        <a href="{{ route('admin.login') }}" class="btn-outline-light">
                            <i class="fas fa-sign-in-alt"></i>
                            تسجيل الدخول
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 text-center d-none d-lg-block">
                    <div class="float-animation">
                        <i class="fas fa-hospital-alt" style="font-size: 15rem; opacity: 0.15;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title animate-on-scroll">الميزات الرئيسية</h2>
            <p class="section-subtitle animate-on-scroll">
                كل ما تحتاجه لإدارة عيادتك بكفاءة وسهولة
            </p>

            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="feature-title">إدارة المرضى</h3>
                        <p class="feature-description">
                            نظام شامل لإدارة سجلات المرضى، التاريخ الطبي، والبيانات الشخصية بشكل منظم وآمن
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3 class="feature-title">جدولة المواعيد</h3>
                        <p class="feature-description">
                            جدول مواعيد ذكي مع تنبيهات تلقائية، منع التعارضات، وإدارة فعالة لأوقات العيادة
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <h3 class="feature-title">السجلات الطبية</h3>
                        <p class="feature-description">
                            تسجيل شامل للزيارات، التشخيصات، العلاجات، والملفات الطبية بطريقة رقمية منظمة
                        </p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h3 class="feature-title">إدارة الفواتير</h3>
                        <p class="feature-description">
                            نظام فوترة متكامل مع تتبع المدفوعات، إحصائيات مالية، وإصدار فواتير احترافية
                        </p>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">الأمان والحماية</h3>
                        <p class="feature-description">
                            مصادقة ثنائية، تشفير البيانات، وصلاحيات متعددة لحماية معلومات المرضى الحساسة
                        </p>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card premium">
                        <span class="feature-badge">
                            <i class="fas fa-star"></i> Premium
                        </span>
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3 class="feature-title">الإشعارات الفورية</h3>
                        <p class="feature-description">
                            تنبيهات لحظية للمواعيد، الزيارات، والفواتير (تتطلب إعداد Pusher)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">مجاني ومفتوح المصدر</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">60%</div>
                        <div class="stat-label">توفير في الوقت</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">15+</div>
                        <div class="stat-label">تخصص طبي</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">الوصول للبيانات</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Developer Section -->
    <section class="developer-section">
        <div class="container text-center mb-5">
            <h2 class="section-title animate-on-scroll">{{ __('frontend.meet_the_developer') }}</h2>
            <div class="section-subtitle animate-on-scroll">
                فخور بتطوير هذا المشروع ومساندة الكوادر الطبية بالتقنيات الحديثة
            </div>
        </div>
        <div class="container">
            <div class="developer-card animate-on-scroll">
                <div class="row align-items-center">
                    <div class="col-lg-5">
                        <div class="developer-image-container">
                            <div class="developer-image-ring"></div>
                            <img src="{{ asset('images/developer_avatar.png') }}"
                                alt="{{ __('frontend.developer_name') }}" class="developer-image">
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="developer-info text-lg-start">
                            <span class="dev-badge">Project Creator</span>
                            <h2 class="dev-name">{{ __('frontend.developer_name') }}</h2>
                            <h3 class="dev-title">{{ __('frontend.developer_title') }}</h3>
                            <p class="dev-description">
                                {{ __('frontend.developer_description') }}
                            </p>
                            <div class="social-links justify-content-lg-start">
                                <a href="https://github.com/AbdulbasetRS" target="_blank" class="social-link github"
                                    title="{{ __('frontend.github') }}">
                                    <i class="fab fa-github"></i>
                                </a>
                                <a href="https://www.linkedin.com/in/abdulbaset-r-sayed/" target="_blank"
                                    class="social-link linkedin" title="{{ __('frontend.linkedin') }}">
                                    <i class="fab fa-linkedin"></i>
                                </a>
                                <a href="https://wa.me/201097579845" target="_blank" class="social-link whatsapp"
                                    title="{{ __('frontend.whatsapp') }}">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="mailto:abdulbasetredasayedhf@gmail.com" class="social-link email"
                                    title="{{ __('frontend.email') }}">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-card">
                <h2 class="cta-title">جاهز لتطوير عيادتك؟</h2>
                <p class="cta-subtitle">
                    ابدأ باستخدام CM-ClinicManage اليوم واكتشف كيف يمكن للتكنولوجيا أن تحسن من<br>
                    كفاءة عيادتك وجودة الخدمة المقدمة للمرضى
                </p>
                <div
                    style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; position: relative; z-index: 2;">
                    <a href="{{ route('admin.register') }}" class="btn-primary-gradient"
                        style="background: white; color: var(--primary-color);">
                        <i class="fas fa-user-plus"></i>
                        إنشاء حساب جديد
                    </a>
                    <a href="https://github.com/AbdulbasetRS/CM-ClinicManage" target="_blank" class="btn-outline-light">
                        <i class="fab fa-github"></i>
                        عرض على GitHub
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('main.script')
    <script>
        // Animate on Scroll
        document.addEventListener('DOMContentLoaded', function() {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.animate-on-scroll').forEach(element => {
                observer.observe(element);
            });
        });

        // Add stagger delay to feature cards
        document.querySelectorAll('.features-section .animate-on-scroll').forEach((element, index) => {
            element.style.transitionDelay = `${index * 0.1}s`;
        });
    </script>
@endsection
