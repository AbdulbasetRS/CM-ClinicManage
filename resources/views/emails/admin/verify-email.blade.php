<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>تفعيل البريد الإلكتروني</title>
    <style>
        body { font-family: Tahoma, Arial, sans-serif; background: #f7f7f7; margin: 0; padding: 20px; }
        .wrap { max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #eee; }
        .header { background: #2d3436; color: #dfe6e9; padding: 16px 20px; font-weight: 600; }
        .content { padding: 20px; color: #2d3436; line-height: 1.8; }
        .btn { display: inline-block; padding: 10px 16px; background: #0d6efd; color: #ffffff !important; text-decoration: none; border-radius: 6px; margin: 12px 0; }
        .muted { color: #6c757d; font-size: 12px; }
        .footer { padding: 14px 20px; background: #f2f2f2; color: #6c757d; font-size: 12px; text-align: center; }
        a { color: #0d6efd; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="header">{{ config('app.name') }}</div>
        <div class="content">
            <p>مرحبًا {{ $user->username ?? $user->name ?? $user->email }},</p>
            <p>شكرًا لتسجيلك في {{ config('app.name') }}. لتفعيل حسابك، يرجى الضغط على الزر التالي:</p>
            <p>
                <a href="{{ $verifyUrl }}" class="btn" target="_blank" rel="noopener">تفعيل الحساب</a>
            </p>
            <p class="muted">أو قم بنسخ الرابط التالي ولصقه في المتصفح:</p>
            <p class="muted" style="direction:ltr; text-align:left; word-break: break-all;">{{ $verifyUrl }}</p>
            <p class="muted">صلاحية هذا الرابط لمدة 60 دقيقة فقط.</p>
            <p>إذا لم تقم بإنشاء هذا الحساب، يمكنك تجاهل هذه الرسالة بأمان.</p>
            <p>تحياتنا،<br>{{ config('app.name') }}</p>
        </div>
        <div class="footer">© {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.</div>
    </div>
</body>
</html>
