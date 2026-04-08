@extends('layouts.admin')

@section('content')
@php($statusLabels = trans('statuses'))
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase fw-bold small" style="color:#0891b2; letter-spacing:.08em;">Huấn luyện viên</div>
        <h2 class="h3 mb-1">Thêm huấn luyện viên</h2>
        <div class="text-secondary">Nhập thông tin PT mới vào hệ thống.</div>
    </div>
    <a href="{{ route('admin.trainers.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.trainers.store') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Họ tên</label>
                <input class="form-control" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input class="form-control" name="email" type="email" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Mật khẩu</label>
                <input class="form-control" name="password" type="password" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Kinh nghiệm (năm)</label>
                <input class="form-control" name="experience" type="number" min="0" value="{{ old('experience') }}" required>
            </div>
            <div class="col-md-12">
                <label class="form-label">Chuyên môn</label>
                <input class="form-control" name="specialty" value="{{ old('specialty') }}" placeholder="Ví dụ: Giảm mỡ, tăng cơ, cardio...">
            </div>
            <div class="col-md-12">
                <label class="form-label">Trạng thái</label>
                <select class="form-select" name="status" required>
                    @foreach (['active', 'inactive'] as $status)
                        <option value="{{ $status }}" @selected(old('status', 'active') === $status)>{{ $statusLabels[$status] ?? $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="card-footer bg-white border-0 d-flex justify-content-end gap-2">
        <a href="{{ route('admin.trainers.index') }}" class="btn btn-outline-secondary">Hủy</a>
        <button class="btn btn-success px-4">Lưu huấn luyện viên</button>
    </div>
</form>
@endsection
