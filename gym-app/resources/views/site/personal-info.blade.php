@extends('layouts.gym')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h4 mb-2">Cập nhật thông tin cá nhân</h1>
                <p class="text-muted mb-4">Bạn cần cập nhật đủ thông tin cá nhân trước khi đăng ký gói tập.</p>

                <form method="POST" action="{{ route('site.personal-info.update') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $member->address) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Chiều cao (m)</label>
                        <input type="number" step="0.01" min="1" max="3" name="height" class="form-control" value="{{ old('height', $member->height) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cân nặng (kg)</label>
                        <input type="number" step="0.1" min="20" max="300" name="weight" class="form-control" value="{{ old('weight', $member->weight) }}" required>
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-success">Lưu thông tin</button>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Quay lại</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
