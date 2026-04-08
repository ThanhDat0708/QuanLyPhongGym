<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quản trị phòng gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --panel-bg: #08111f;
            --panel-accent: #14b8a6;
            --panel-warm: #f97316;
            --page-bg: #eef2f7;
            --surface: #ffffff;
        }
        body {
            background: radial-gradient(circle at top left, #f7fafc 0, var(--page-bg) 48%, #dde7f2 100%);
            min-height: 100vh;
        }
        .admin-shell {
            display: grid;
            grid-template-columns: 270px 1fr;
            min-height: 100vh;
        }
        .admin-sidebar {
            background: linear-gradient(180deg, var(--panel-bg), #0f172a 55%, #111827 100%);
            color: #e5eef8;
            padding: 1.25rem;
            position: sticky;
            top: 0;
            height: 100vh;
        }
        .brand-badge {
            background: linear-gradient(135deg, var(--panel-accent), var(--panel-warm));
            color: white;
            border-radius: 18px;
            padding: 0.9rem 1rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            box-shadow: 0 16px 35px rgba(20, 184, 166, 0.24);
        }
        .side-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 14px;
            padding: 0.8rem 0.95rem;
            margin-bottom: 0.4rem;
            transition: all 0.2s ease;
        }
        .side-link:hover, .side-link.active {
            background: rgba(20, 184, 166, 0.14);
            color: #fff;
            transform: translateX(4px);
        }
        .admin-main {
            padding: 1.25rem;
        }
        .topbar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0b1120 100%);
            color: #fff;
            border-radius: 22px;
            padding: 1rem 1.25rem;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.25);
        }
        .content-card {
            background: rgba(255,255,255,0.82);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 22px;
            padding: 1.25rem;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        }
        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            border-radius: 999px;
            background: rgba(20, 184, 166, 0.14);
            color: #14b8a6;
            font-weight: 700;
            font-size: 0.875rem;
        }
        @media (max-width: 992px) {
            .admin-shell { grid-template-columns: 1fr; }
            .admin-sidebar { position: relative; height: auto; }
        }
    </style>
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="brand-badge mb-4">Quản trị Gym</div>
        <div class="small text-uppercase text-secondary mb-2">Điều hướng</div>
        <a class="side-link" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge-high"></i> Tổng quan</a>
        <a class="side-link" href="{{ route('admin.members.index') }}"><i class="fa-solid fa-users"></i> Hội viên</a>
        <a class="side-link" href="{{ route('admin.trainers.index') }}"><i class="fa-solid fa-dumbbell"></i> Huấn luyện viên</a>
        <a class="side-link" href="{{ route('admin.packages.index') }}"><i class="fa-solid fa-box-open"></i> Gói tập</a>
        <a class="side-link" href="{{ route('admin.registrations.index') }}"><i class="fa-solid fa-clipboard-list"></i> Đăng ký</a>
        <a class="side-link" href="{{ route('admin.schedules.index') }}"><i class="fa-solid fa-calendar-check"></i> Lịch tập</a>
        <a class="side-link" href="{{ route('admin.payments.index') }}"><i class="fa-solid fa-receipt"></i> Thanh toán</a>
        <a class="side-link" href="{{ route('admin.reviews.index') }}"><i class="fa-solid fa-star"></i> Đánh giá</a>
        <hr class="border-secondary my-4">
        <div class="small text-secondary mb-2">Phiên đăng nhập</div>
        <div class="mb-2 fw-semibold">{{ auth()->user()->name }}</div>
        <div class="text-secondary small mb-3">{{ auth()->user()->role }}</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-warning w-100 fw-bold" type="submit">Đăng xuất</button>
        </form>
    </aside>

    <main class="admin-main">
        <div class="topbar mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div class="hero-chip mb-2"><i class="fa-solid fa-shield-halved"></i> Bảng điều khiển</div>
                <h1 class="h4 mb-0">Quản lý phòng gym</h1>
            </div>
            <div class="text-end small text-white-50">
                {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="content-card">
            @yield('content')
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
