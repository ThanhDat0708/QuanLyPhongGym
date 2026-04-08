@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Đăng ký</div>
        <h2 class="h4 mb-0">Thêm đăng ký</h2>
    </div>
    <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.registrations.store') }}" class="row g-3">
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
        <label class="form-label">Package</label>
        <select name="gym_package_id" class="form-select" required>
            <option value="">Select package</option>
            @foreach ($packages as $package)
                <option value="{{ $package->id }}">{{ $package->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label">Start date</label><input type="date" class="form-control" name="start_date" required></div>
    <div class="col-md-4"><label class="form-label">End date</label><input type="date" class="form-control" name="end_date" required></div>
    <div class="col-md-4"><label class="form-label">Status</label><input class="form-control" name="status" value="pending" required></div>
    <div class="col-12"><button class="btn btn-success px-4">Lưu đăng ký</button></div>
</form>
@endsection
