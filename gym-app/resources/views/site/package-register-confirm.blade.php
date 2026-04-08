@extends('layouts.gym')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h4 mb-2">Xác nhận đăng ký gói tập</h1>
                <p class="text-muted mb-4">Vui lòng kiểm tra thông tin gói tập trước khi gửi yêu cầu. Hệ thống sẽ tạo hóa đơn thanh toán tự động ngay sau khi đăng ký.</p>

                <div class="border rounded-3 p-3 mb-4 bg-light-subtle">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small text-muted">Gói tập đã chọn</div>
                            <h2 class="h5 mb-1">{{ $gymPackage->name }}</h2>
                            <p class="mb-0 text-muted small">{{ $gymPackage->description }}</p>
                        </div>
                        <span class="badge text-bg-warning">{{ $gymPackage->duration }} ngày</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Giá gói:</span>
                        <strong>{{ number_format($gymPackage->price) }} VND</strong>
                    </div>
                </div>

                <form method="POST" action="{{ route('site.package.register', $gymPackage) }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $today) }}" min="{{ $today }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Huấn luyện viên cá nhân (tùy chọn)</label>
                        <select name="trainer_id" class="form-select">
                            <option value="">Không chọn PT</option>
                            @foreach ($trainers as $trainer)
                                <option value="{{ $trainer->id }}" @selected((string) old('trainer_id') === (string) $trainer->id)>
                                    {{ $trainer->user->name }} - {{ $trainer->specialty }} ({{ $trainer->experience }} năm)
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Bạn có thể bỏ qua và chọn PT sau.</div>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="confirm_registration" id="confirm_registration" checked>
                            <label class="form-check-label" for="confirm_registration">
                                Xác nhận đăng ký gói tập <strong>{{ $gymPackage->name }}</strong> với giá <strong>{{ number_format($gymPackage->price) }} VND</strong> và thời hạn {{ $gymPackage->duration }} ngày.
                            </label>
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-warning fw-semibold" type="submit">Xác nhận đăng ký</button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
