<x-guest-layout>
    <style>
        .auth-title {
            margin: 0;
            font-size: 30px;
            line-height: 1.2;
            font-weight: 800;
            color: #0f172a;
        }

        .auth-subtitle {
            margin: 6px 0 18px;
            color: #64748b;
            font-size: 14px;
        }

        .auth-status {
            margin-bottom: 14px;
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #166534;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 14px;
        }

        .auth-group {
            margin-bottom: 14px;
        }

        .auth-label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 700;
            color: #334155;
        }

        .auth-input {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 11px 13px;
            font-size: 15px;
            background: #fff;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .auth-input:focus {
            border-color: #ea580c;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.15);
        }

        .auth-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin: 8px 0 18px;
        }

        .auth-check {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            font-size: 14px;
        }

        .auth-link {
            color: #c2410c;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
        }

        .auth-link:hover {
            color: #9a3412;
        }

        .auth-submit {
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(90deg, #111827, #1f2937);
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.4px;
            padding: 12px 16px;
            cursor: pointer;
        }

        .auth-submit:hover {
            filter: brightness(1.07);
        }

        .auth-bottom {
            margin-top: 14px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
    </style>

    <div class="mb-6">
        <h2 class="auth-title">Đăng nhập</h2>
        <p class="auth-subtitle">Chào mừng quay lại. Vui lòng nhập thông tin tài khoản của bạn.</p>
    </div>

    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="auth-group">
            <label for="email" class="auth-label">Email</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="auth-group">
            <label for="password" class="auth-label">Mật khẩu</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="current-password" placeholder="Nhập mật khẩu">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="auth-row">
            <label for="remember_me" class="auth-check">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Ghi nhớ đăng nhập') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    {{ __('Quên mật khẩu?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="auth-submit">ĐĂNG NHẬP</button>

        <p class="auth-bottom">
            Chưa có tài khoản?
            <a href="{{ route('register') }}" class="auth-link">Đăng ký ngay</a>
        </p>
    </form>
</x-guest-layout>
