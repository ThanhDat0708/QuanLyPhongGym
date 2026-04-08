<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản lý phòng gym</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --gym-bg: #f3f5f8;
            --gym-ink: #111827;
            --gym-muted: #5f6b7a;
            --gym-brand: #ea580c;
            --gym-brand-2: #0f172a;
            --gym-card: #ffffff;
            --gym-line: #e8edf3;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background:
                radial-gradient(circle at 15% -10%, rgba(234, 88, 12, 0.15), transparent 30%),
                radial-gradient(circle at 85% -15%, rgba(15, 23, 42, 0.14), transparent 36%),
                var(--gym-bg);
            color: var(--gym-ink);
        }

        body.gym-home-bg {
            background:
                linear-gradient(rgba(14, 23, 38, 0.42), rgba(14, 23, 38, 0.52)),
                url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1800&q=80') center/cover fixed no-repeat;
        }

        body.gym-home-bg .gym-shell {
            background:
                linear-gradient(145deg, rgba(248, 250, 252, 0.78), rgba(241, 245, 249, 0.72));
            border: 1px solid rgba(255, 255, 255, 0.55);
            border-radius: 20px;
            box-shadow: 0 18px 38px rgba(10, 20, 35, 0.25);
            backdrop-filter: blur(6px);
            padding: 1.35rem;
        }

        @media (max-width: 991.98px) {
            body.gym-home-bg {
                background-attachment: scroll;
            }

            body.gym-home-bg .gym-shell {
                background: rgba(248, 250, 252, 0.9);
                backdrop-filter: blur(3px);
            }
        }

        .gym-nav {
            background: linear-gradient(110deg, #121923 0%, #1b2531 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gym-nav .navbar-brand {
            letter-spacing: 0.4px;
            font-weight: 800;
        }

        .gym-nav .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
        }

        .gym-nav .nav-link:hover {
            color: #fff;
        }

        .gym-shell {
            max-width: 1320px;
        }

        .alert {
            border-radius: 12px;
        }
    </style>
</head>
<body class="{{ request()->routeIs('home') ? 'gym-home-bg' : '' }}">
<nav class="navbar navbar-expand-lg navbar-dark gym-nav">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">QUẢN LÝ GYM</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Gói tập</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Tổng quan</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('site.registrations') }}">Đăng ký</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('site.schedules') }}">Lịch tập</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('site.payments') }}">Thanh toán</a></li>
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item"><a class="nav-link text-warning" href="{{ route('admin.dashboard') }}">Quản trị</a></li>
                    @endif
                @endauth
            </ul>
            <div class="d-flex gap-2">
                @auth
                    <span class="text-white align-self-center small">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-outline-light btn-sm" type="submit">Đăng xuất</button>
                    </form>
                @else
                    <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Đăng nhập</a>
                    <a class="btn btn-warning btn-sm" href="{{ route('register') }}">Đăng ký</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main class="container gym-shell py-4 py-lg-5">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
