@extends('layouts.admin')

@section('content')
@php($statusLabels = trans('statuses'))
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Lịch tập</div>
        <h2 class="h4 mb-0">Sửa lịch tập</h2>
    </div>
    <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">Hội viên</label>
        <select class="form-select" disabled>
            @foreach ($members as $member)
                <option value="{{ $member->id }}" @selected($member->id === $schedule->member_id)>{{ $member->user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Huấn luyện viên</label>
        <select class="form-select" disabled>
            @foreach ($trainers as $trainer)
                <option value="{{ $trainer->id }}" @selected($trainer->id === $schedule->trainer_id)>{{ $trainer->user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label">Ngày</label><input type="date" class="form-control" name="date" value="{{ $schedule->date->format('Y-m-d') }}" required></div>
    <div class="col-md-4"><label class="form-label">Giờ</label><input type="time" class="form-control" name="time" value="{{ $schedule->time }}" required></div>
    <div class="col-md-4">
        <label class="form-label">Trạng thái</label>
        <select class="form-select" name="status" required>
            @foreach (['pending', 'done', 'cancel'] as $status)
                <option value="{{ $status }}" @selected(old('status', $schedule->status) === $status)>{{ $statusLabels[$status] ?? $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12"><button class="btn btn-warning px-4 fw-bold">Cập nhật lịch tập</button></div>
</form>
@endsection
