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

        .auth-link {
            color: #c2410c;
            text-decoration: none;
            font-weight: 700;
        }

        .auth-link:hover {
            color: #9a3412;
        }

        .auth-bottom {
            margin-top: 14px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
        }
    </style>

    <div class="mb-6">
        <h2 class="auth-title">Tạo tài khoản mới</h2>
        <p class="auth-subtitle">Điền thông tin để bắt đầu sử dụng hệ thống quản lý gym.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="auth-group">
            <label for="name" class="auth-label">Họ tên</label>
            <input id="name" class="auth-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nhập họ tên">
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="auth-group">
            <label for="email" class="auth-label">Email</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="auth-group">
            <label for="password" class="auth-label">Mật khẩu</label>
            <input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password" placeholder="Tạo mật khẩu">
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
        </div>

        <div class="auth-group">
            <label for="password_confirmation" class="auth-label">Xác nhận mật khẩu</label>
            <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Nhập lại mật khẩu">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
        </div>

        <button type="submit" class="auth-submit">ĐĂNG KÝ</button>

        <p class="auth-bottom">
            Đã có tài khoản?
            <a href="{{ route('login') }}" class="auth-link">Đăng nhập</a>
        </p>
    </form>
</x-guest-layout>
