<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Your Password – {{ config('app.name') }}</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap');

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { font-size: 16px; }

        body {
            background: #eef0f8;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #1e1b2e;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            padding: 48px 16px;
        }

        /* ── Outer wrapper ── */
        .outer {
            max-width: 560px;
            margin: 0 auto;
        }

        /* ── Brand ── */
        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 11px;
            margin-bottom: 28px;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 13px;
            background: linear-gradient(135deg, #6c63ff 0%, #4f46e5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.38);
        }

        .brand-mark svg {
            width: 22px;
            height: 22px;
            stroke: #fff;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .brand-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 21px;
            font-weight: 700;
            color: #1e1b2e;
            letter-spacing: -0.02em;
        }

        /* ── Card ── */
        .card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow:
                0 1px 2px rgba(0,0,0,0.04),
                0 8px 24px rgba(0,0,0,0.06),
                0 32px 64px rgba(79, 70, 229, 0.09);
            overflow: hidden;
        }

        /* ── Hero ── */
        .card-hero {
            background: linear-gradient(140deg, #5b50f0 0%, #7c3aed 55%, #9f67fa 100%);
            padding: 44px 40px 52px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* decorative blobs */
        .card-hero::before {
            content: '';
            position: absolute;
            top: -70px; right: -70px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .card-hero::after {
            content: '';
            position: absolute;
            bottom: -50px; left: -50px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }

        /* lock icon pill */
        .hero-icon-wrap {
            position: relative;
            z-index: 2;
            margin-bottom: 22px;
        }

        .hero-icon-bg {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: rgba(255,255,255,0.18);
            border: 2px solid rgba(255,255,255,0.3);
        }

        /* inline SVG lock — fully visible */
        .hero-icon-bg svg {
            width: 34px;
            height: 34px;
            display: block;
        }

        .hero-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.025em;
            line-height: 1.25;
            position: relative;
            z-index: 2;
            margin-bottom: 10px;
        }

        .hero-sub {
            font-size: 14.5px;
            color: rgba(255,255,255,0.76);
            font-weight: 400;
            line-height: 1.65;
            position: relative;
            z-index: 2;
        }

        .hero-sub strong {
            color: #fff;
            font-weight: 600;
        }

        /* wave divider between hero and body */
        .hero-wave {
            display: block;
            width: 100%;
            margin-bottom: -1px;
        }

        /* ── Card body ── */
        .card-body {
            padding: 36px 40px 40px;
        }

        /* ── Code section ── */
        .code-section {
            background: #f5f4ff;
            border: 1.5px solid #e0dcff;
            border-radius: 18px;
            padding: 28px 20px 22px;
            text-align: center;
            margin-bottom: 24px;
        }

        .code-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #9b96cc;
            margin-bottom: 20px;
        }

        .code-boxes {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .code-box {
            flex: 0 0 auto;
            width: 62px;
            height: 74px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 2px solid #cbc5f8;
            border-radius: 14px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: #4f46e5;
            box-shadow: 0 2px 10px rgba(79,70,229,0.10);
            position: relative;
        }

        /* bottom accent bar */
        .code-box::after {
            content: '';
            position: absolute;
            bottom: 9px;
            left: 50%;
            transform: translateX(-50%);
            width: 22px;
            height: 3px;
            background: linear-gradient(90deg, #6c63ff, #9f67fa);
            border-radius: 99px;
        }

        .code-hint {
            margin-top: 14px;
            font-size: 12px;
            color: #b3aedd;
            font-weight: 400;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .code-hint svg {
            width: 12px;
            height: 12px;
            stroke: #b3aedd;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        /* ── Info row ── */
        .info-row {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .info-card {
            flex: 1;
            background: #fafafa;
            border: 1px solid #eeecff;
            border-radius: 14px;
            padding: 16px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-icon {
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-icon.timer { background: #fff3e0; }
        .info-icon.shield { background: #e8f5e9; }

        .info-icon svg {
            width: 17px;
            height: 17px;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .info-icon.timer svg { stroke: #ef6c00; }
        .info-icon.shield svg { stroke: #2e7d32; }

        .info-text p {
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: #aeaac8;
            margin-bottom: 2px;
        }

        .info-text span {
            font-size: 13px;
            font-weight: 600;
            color: #2d2b45;
        }

        /* ── Divider ── */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e5e2ff 30%, #e5e2ff 70%, transparent);
            margin: 22px 0;
        }

        /* ── Ignore notice ── */
        .ignore-notice {
            background: #fffbf0;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 14px 16px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .ignore-notice svg {
            width: 16px;
            height: 16px;
            stroke: #d97706;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .ignore-notice p {
            font-size: 13px;
            color: #92400e;
            line-height: 1.6;
        }

        .ignore-notice p strong {
            font-weight: 600;
        }

        /* ── Footer ── */
        .footer {
            text-align: center;
            margin-top: 28px;
        }

        .footer p {
            font-size: 12px;
            color: #a8a8c0;
            line-height: 1.9;
            font-weight: 400;
        }

        .footer a {
            color: #7c72d4;
            text-decoration: none;
            font-weight: 500;
        }

        .footer-dot {
            display: inline-block;
            width: 3px;
            height: 3px;
            background: #c4c2d8;
            border-radius: 50%;
            vertical-align: middle;
            margin: 0 8px 2px;
        }

        /* ── Responsive ── */
        @media only screen and (max-width: 520px) {
            body { padding: 24px 12px; }
            .card-hero { padding: 34px 24px 42px; }
            .hero-title { font-size: 22px; }
            .card-body { padding: 28px 20px 32px; }
            .code-boxes { gap: 7px; }
            .code-box { width: 48px; height: 60px; font-size: 26px; border-radius: 11px; }
            .code-box::after { width: 16px; bottom: 7px; }
            .info-row { flex-direction: column; gap: 10px; }
            .brand-name { font-size: 18px; }
            .hero-icon-bg { width: 64px; height: 64px; }
            .hero-icon-bg svg { width: 28px; height: 28px; }
        }

        @media only screen and (max-width: 360px) {
            .code-boxes { gap: 5px; }
            .code-box { width: 42px; height: 54px; font-size: 22px; border-radius: 9px; }
        }
    </style>
</head>
<body>

<div class="outer">

    {{-- ── Brand ── --}}
    <div class="brand">
        <div class="brand-mark">
            <svg viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                <path d="M2 17l10 5 10-5"/>
                <path d="M2 12l10 5 10-5"/>
            </svg>
        </div>
        <span class="brand-name">{{ config('app.name') }}</span>
    </div>

    {{-- ── Card ── --}}
    <div class="card">

        {{-- Hero --}}
        <div class="card-hero">
            <div class="hero-icon-wrap">
                <div class="hero-icon-bg">
                    {{-- Lock icon drawn with explicit white fill/stroke so it always shows --}}
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <!-- shackle -->
                        <path d="M8 11V7a4 4 0 1 1 8 0v4"
                              stroke="#ffffff"
                              stroke-width="2.2"
                              stroke-linecap="round"
                              fill="none"/>
                        <!-- body -->
                        <rect x="4" y="11" width="16" height="11" rx="2.5"
                              fill="rgba(255,255,255,0.22)"
                              stroke="#ffffff"
                              stroke-width="2"/>
                        <!-- keyhole dot -->
                        <circle cx="12" cy="16" r="1.5" fill="#ffffff"/>
                    </svg>
                </div>
            </div>
            <h1 class="hero-title">Password Reset Code</h1>
            <p class="hero-sub">
                Hey <strong>{{ $name ?? 'there' }}</strong> 👋 — enter the code below<br>
                to securely reset your password.
            </p>
        </div>

        {{-- Wave transition --}}
        <svg class="hero-wave" viewBox="0 0 560 32" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" style="background:#9f67fa; display:block;">
            <path d="M0 0 Q140 32 280 16 Q420 0 560 24 L560 32 L0 32 Z" fill="#ffffff"/>
        </svg>

        {{-- Body --}}
        <div class="card-body">

            {{-- Code boxes --}}
            <div class="code-section">
                <p class="code-label">Your one-time code</p>
                <div class="code-boxes">
                    @foreach(str_split($code) as $digit)
                        <div class="code-box">{{ $digit }}</div>
                    @endforeach
                </div>
                <p class="code-hint">
                    <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Do not share this code with anyone
                </p>
            </div>

            {{-- Info cards --}}
            <div class="info-row">
                <div class="info-card">
                    <div class="info-icon timer">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div class="info-text">
                        <p>Expires in</p>
                        <span>{{ $expiry ?? '10 minutes' }}</span>
                    </div>
                </div>
                <div class="info-card">
                    <div class="info-icon shield">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div class="info-text">
                        <p>Single use</p>
                        <span>Invalidates after use</span>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            {{-- Ignore notice --}}
            <div class="ignore-notice">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <p>
                    Didn't request this? Safely ignore this email — your password will
                    <strong>not</strong> be changed unless you enter this code.
                </p>
            </div>

        </div>
    </div>

    {{-- ── Footer ── --}}
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p>
            <a href="#">Privacy Policy</a>
            <span class="footer-dot"></span>
            <a href="#">Terms of Service</a>
            <span class="footer-dot"></span>
            <a href="#">Help Center</a>
        </p>
    </div>

</div>

</body>
</html>
