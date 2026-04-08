@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Lịch tập</div>
        <h2 class="h4 mb-0">Thêm lịch tập</h2>
    </div>
    <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.schedules.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
        <label class="form-label">Member</label>
        <select name="member_id" class="form-select" required>
            <option value="">Select member</option>
            @foreach ($members as $member)
                <option value="{{ $member->id }}">{{ $member->user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Trainer</label>
        <select name="trainer_id" class="form-select" required>
            <option value="">Select trainer</option>
            @foreach ($trainers as $trainer)
                <option value="{{ $trainer->id }}">{{ $trainer->user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label">Date</label><input type="date" class="form-control" name="date" required></div>
    <div class="col-md-4"><label class="form-label">Time</label><input type="time" class="form-control" name="time" required></div>
    <div class="col-md-4"><label class="form-label">Status</label><input class="form-control" name="status" value="pending" required></div>
    <div class="col-12"><button class="btn btn-success px-4">Lưu lịch tập</button></div>
</form>
@endsection
