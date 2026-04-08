<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --auth-ink: #0f172a;
                --auth-muted: #64748b;
                --auth-brand: #ea580c;
                --auth-card: rgba(248, 250, 252, 0.92);
                --auth-border: rgba(226, 232, 240, 0.95);
            }

            .auth-body {
                font-family: 'Be Vietnam Pro', sans-serif;
                background:
                    linear-gradient(rgba(10, 18, 31, 0.52), rgba(10, 18, 31, 0.58)),
                    url('https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=1800&q=80') center/cover fixed no-repeat;
                min-height: 100vh;
                margin: 0;
                color: var(--auth-ink);
            }

            .auth-page {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 28px 16px;
            }

            .auth-shell {
                width: 100%;
                max-width: 1080px;
                display: grid;
                grid-template-columns: 44% 56%;
                overflow: hidden;
                border-radius: 24px;
                border: 1px solid rgba(255, 255, 255, 0.22);
                background: rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(6px);
                box-shadow: 0 22px 50px rgba(2, 8, 23, 0.35);
            }

            .auth-side {
                padding: 40px 34px;
                background: linear-gradient(160deg, rgba(15, 23, 42, 0.94), rgba(30, 41, 59, 0.88));
                color: #fff;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 20px;
            }

            .auth-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                border-radius: 999px;
                padding: 8px 14px;
                font-size: 12px;
                font-weight: 800;
                letter-spacing: 0.5px;
                background: rgba(255, 255, 255, 0.12);
                color: #fff;
                text-decoration: none;
                width: fit-content;
            }

            .auth-badge-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: #fdba74;
            }

            .auth-side h1 {
                margin: 20px 0 10px;
                font-size: 32px;
                line-height: 1.25;
                font-weight: 800;
            }

            .auth-side p {
                margin: 0;
                color: rgba(255, 255, 255, 0.9);
                font-size: 14px;
                line-height: 1.6;
            }

            .auth-content {
                padding: 34px;
                background: var(--auth-card);
            }

            .auth-mobile-top {
                display: none;
                margin-bottom: 16px;
            }

            @media (max-width: 900px) {
                .auth-body {
                    background-attachment: scroll;
                }

                .auth-shell {
                    grid-template-columns: 1fr;
                    max-width: 660px;
                }

                .auth-side {
                    display: none;
                }

                .auth-content {
                    padding: 24px 20px;
                }

                .auth-mobile-top {
                    display: block;
                }
            }
        </style>
    </head>
    <body class="auth-body text-gray-900 antialiased">
        <div class="auth-page">
            <div class="auth-shell">
                <div class="auth-side">
                    <div>
                        <a href="{{ route('home') }}" class="auth-badge">
                            <span class="auth-badge-dot"></span>
                            QUẢN LÝ GYM
                        </a>

                        <h1>
                            Bắt đầu hành trình luyện tập chuyên nghiệp
                        </h1>

                        <p>
                            Đăng nhập để quản lý lịch tập, gói tập, thanh toán và theo dõi quá trình của bạn ngay trên một nền tảng duy nhất.
                        </p>
                    </div>

                    <p>
                        Gym Management Platform
                    </p>
                </div>

                <div class="auth-content">
                    <div class="auth-mobile-top">
                        <a href="{{ route('home') }}" class="auth-badge" style="background: rgba(15, 23, 42, 0.9);">
                            <span class="auth-badge-dot"></span>
                            QUẢN LÝ GYM
                        </a>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
