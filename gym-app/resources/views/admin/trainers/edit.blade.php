@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Huấn luyện viên</div>
        <h2 class="h4 mb-0">Sửa huấn luyện viên</h2>
    </div>
    <a href="{{ route('admin.trainers.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.trainers.update', $trainer) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" value="{{ $trainer->user->name }}" required></div>
    <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" name="email" type="email" value="{{ $trainer->user->email }}" required></div>
    <div class="col-md-6"><label class="form-label">Experience</label><input class="form-control" name="experience" type="number" value="{{ $trainer->experience }}" required></div>
    <div class="col-md-6"><label class="form-label">Status</label><input class="form-control" name="status" value="{{ $trainer->status }}" required></div>
    <div class="col-12"><label class="form-label">Specialty</label><input class="form-control" name="specialty" value="{{ $trainer->specialty }}"></div>
    <div class="col-12"><button class="btn btn-warning px-4 fw-bold">Cập nhật huấn luyện viên</button></div>
</form>
@endsection
