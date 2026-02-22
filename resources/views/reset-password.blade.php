{{-- resources/views/emails/reset-password.blade.php --}}

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            padding: 40px 20px;
        }

        .email-wrapper {
            max-width: 520px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .email-header {
            background-color: #2563eb;
            padding: 36px 40px;
            text-align: center;
        }

        .email-header h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .email-body {
            padding: 40px;
            text-align: center;
        }

        .email-body p {
            color: #555f6e;
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 10px;
        }

        .email-body p.intro {
            font-size: 16px;
            color: #333;
            margin-bottom: 30px;
        }

        .code-blocks {
            text-align: center;
            margin: 30px 0;
        }

        .code-block {
            display: inline-block;
            width: 52px;
            height: 62px;
            line-height: 62px;
            background-color: #f0f4ff;
            border: 2px solid #2563eb;
            border-radius: 10px;
            margin: 0 5px;
            font-size: 26px;
            font-weight: 800;
            color: #2563eb;
            text-align: center;
            vertical-align: middle;
        }

        .expiry-note {
            margin-top: 20px;
            font-size: 13px !important;
            color: #999 !important;
        }

        .warning-note {
            margin-top: 30px;
            padding: 14px 18px;
            background-color: #fff8f0;
            border-left: 4px solid #f59e0b;
            border-radius: 6px;
            text-align: left;
            font-size: 13px !important;
            color: #7c6a3e !important;
        }

        .email-footer {
            background-color: #f8fafc;
            padding: 24px 40px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .email-footer p {
            font-size: 12px;
            color: #aab0b8;
            line-height: 1.6;
        }
    </style>
</head>
<body>

<div class="email-wrapper">

    {{-- Header --}}
    <div class="email-header">
        <h1>🔐 Password Reset Request</h1>
    </div>

    {{-- Body --}}
    <div class="email-body">

        <p class="intro">Hi <strong>{{ $name ?? 'there' }}</strong>, we received a request to reset your password.</p>

        <p>Use the verification code below to proceed. <br> Do not share this code with anyone.</p>

        {{-- 6-Digit Code Blocks --}}
        <div class="code-blocks">
            @foreach(str_split($code) as $digit)
                <span class="code-block">{{ $digit }}</span>
            @endforeach
        </div>

        <p class="expiry-note">⏱ This code expires in <strong>10 minutes</strong>.</p>

        <div class="warning-note">
            ⚠️ If you didn't request a password reset, please ignore this email or contact support immediately. Your account remains secure.
        </div>

    </div>

    {{-- Footer --}}
    <div class="email-footer">
        <p>This is an automated message, please do not reply.<br>
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>

</div>

</body>
</html>
