@extends('layouts.admin')

@section('content')
@php($statusLabels = trans('statuses'))
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Đăng ký</div>
        <h2 class="h4 mb-0">Sửa đăng ký</h2>
    </div>
    <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.registrations.update', $registration) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">Hội viên</label>
        <select name="member_id" class="form-select" disabled>
            @foreach ($members as $member)
                <option value="{{ $member->id }}" @selected($member->id === $registration->member_id)>{{ $member->user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Gói tập</label>
        <select name="gym_package_id" class="form-select" disabled>
            @foreach ($packages as $package)
                <option value="{{ $package->id }}" @selected($package->id === $registration->gym_package_id)>{{ $package->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">PT phụ trách (tùy chọn)</label>
        <select name="preferred_trainer_id" class="form-select">
            <option value="">Chưa chọn PT</option>
            @foreach ($trainers as $trainer)
                <option value="{{ $trainer->id }}" @selected((string) old('preferred_trainer_id', $registration->preferred_trainer_id) === (string) $trainer->id)>
                    {{ $trainer->user->name }} - {{ $trainer->specialty }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label">Ngày bắt đầu</label><input type="date" class="form-control" name="start_date" value="{{ $registration->start_date->format('Y-m-d') }}" required></div>
    <div class="col-md-4"><label class="form-label">Ngày kết thúc</label><input type="date" class="form-control" name="end_date" value="{{ $registration->end_date->format('Y-m-d') }}" required></div>
    <div class="col-md-4">
        <label class="form-label">Trạng thái</label>
        <select class="form-select" name="status" required>
            @foreach (['pending', 'paid', 'active', 'done', 'cancel'] as $status)
                <option value="{{ $status }}" @selected(old('status', $registration->status) === $status)>{{ $statusLabels[$status] ?? $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12"><button class="btn btn-warning px-4 fw-bold">Cập nhật đăng ký</button></div>
</form>
@endsection
