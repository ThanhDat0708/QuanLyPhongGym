@extends('layouts.gym')

@section('content')
<style>
    .home-title {
        font-weight: 800;
        letter-spacing: 0.2px;
    }

    .home-subtitle {
        color: #5f6b7a;
        margin-bottom: 1rem;
    }

    .package-card,
    .side-card,
    .review-card {
        border: 1px solid #e8edf3;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 26px rgba(16, 24, 40, 0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .package-card:hover,
    .review-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(16, 24, 40, 0.1);
    }

    .package-badge {
        background: #fff7ed;
        color: #c2410c;
        border: 1px solid #fed7aa;
        border-radius: 999px;
        font-size: 0.75rem;
        padding: 0.3rem 0.65rem;
        font-weight: 700;
    }

    .label {
        color: #5f6b7a;
        font-size: 0.9rem;
    }

    .value {
        font-weight: 700;
        color: #111827;
    }

    .trainer-item {
        border-bottom: 1px dashed #d9e1ea;
        padding: 0.7rem 0;
    }

    .trainer-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .rating-star {
        color: #f59e0b;
    }

    .review-head {
        font-weight: 700;
    }
</style>

<div class="row g-4">
    <div class="col-lg-8">
        <h2 class="h3 home-title mb-1">Danh sách gói tập</h2>
        <p class="home-subtitle">Chọn gói phù hợp mục tiêu và bắt đầu luyện tập ngay hôm nay.</p>
        <form method="GET" action="{{ route('home') }}" class="row g-2 mb-3">
            <div class="col-sm-9">
                <input
                    type="text"
                    name="q"
                    value="{{ $search ?? '' }}"
                    class="form-control"
                    placeholder="Tìm gói theo tên hoặc mô tả..."
                >
            </div>
            <div class="col-sm-3 d-grid">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
            @if (! empty($search))
                <div class="col-12">
                    <a href="{{ route('home') }}" class="small">Xóa bộ lọc tìm kiếm</a>
                </div>
            @endif
        </form>
        <div class="row g-3">
            @forelse ($packages as $package)
                <div class="col-md-6">
                    <div class="card h-100 package-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h5 class="card-title mb-0">{{ $package->name }}</h5>
                                <span class="package-badge">{{ $package->duration }} ngày</span>
                            </div>
                            <p class="text-muted small mb-3">{{ $package->description }}</p>
                            <p class="mb-1"><span class="label">Giá:</span> <span class="value">{{ number_format($package->price) }} VND</span></p>
                            <p class="mb-3"><span class="label">Thời hạn:</span> <span class="value">{{ $package->duration }} ngày</span></p>
                            @auth
                                <a href="{{ route('site.package.register.confirm', $package) }}" class="btn btn-warning btn-sm fw-semibold">Xác nhận đăng ký</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Đăng nhập để đăng ký</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <p>{{ !empty($search) ? 'Không tìm thấy gói tập phù hợp từ khóa.' : 'Chưa có gói tập.' }}</p>
            @endforelse
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card side-card mb-4">
            <div class="card-body">
                <h3 class="h6 fw-bold mb-2"><i class="fa-solid fa-dumbbell me-2 text-warning"></i>Huấn luyện viên</h3>
                <ul class="list-group list-group-flush mt-2">
                    @forelse ($trainers as $trainer)
                        <li class="list-group-item px-0 trainer-item">
                            <div class="fw-semibold">{{ $trainer->user->name }}</div>
                            <div class="small text-muted">{{ $trainer->specialty }} | {{ $trainer->experience }} năm</div>
                        </li>
                    @empty
                        <li class="list-group-item px-0">Chưa có huấn luyện viên.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        @auth
            <div class="card side-card">
                <div class="card-body">
                    <h3 class="h6 fw-bold">Gửi đánh giá huấn luyện viên</h3>
                    <form action="{{ route('site.reviews.store') }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-12">
                            <select name="trainer_id" class="form-select form-select-sm" required>
                                <option value="">Chọn huấn luyện viên</option>
                                @foreach ($trainers as $trainer)
                                    <option value="{{ $trainer->id }}">{{ $trainer->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <select name="rating" class="form-select form-select-sm" required>
                                <option value="5">5 sao</option>
                                <option value="4">4 sao</option>
                                <option value="3">3 sao</option>
                                <option value="2">2 sao</option>
                                <option value="1">1 sao</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <textarea name="comment" class="form-control form-control-sm" rows="2" placeholder="Nhận xét"></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-success btn-sm fw-semibold" type="submit">Gửi đánh giá</button>
                        </div>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</div>

<div class="mt-4">
    <h2 class="h4 home-title mb-1">Đánh giá mới nhất</h2>
    <p class="home-subtitle">Phản hồi thật từ hội viên về trải nghiệm luyện tập cùng huấn luyện viên.</p>
    <div class="row g-3">
        @forelse ($reviews as $review)
            <div class="col-md-4">
                <div class="card h-100 review-card">
                    <div class="card-body">
                        <p class="mb-1 review-head">{{ $review->user->name }} -> {{ $review->trainer->user->name }}</p>
                        <p class="mb-2 small">
                            @for ($i = 0; $i < $review->rating; $i++)
                                <i class="fa-solid fa-star rating-star"></i>
                            @endfor
                        </p>
                        <p class="small mb-0">{{ $review->comment }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p>Chưa có đánh giá.</p>
        @endforelse
    </div>
</div>
@endsection
