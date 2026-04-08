@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Huấn luyện viên</div>
        <h2 class="h4 mb-0">Thêm huấn luyện viên</h2>
    </div>
    <a href="{{ route('admin.trainers.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.trainers.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
    <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" name="email" type="email" required></div>
    <div class="col-md-6"><label class="form-label">Password</label><input class="form-control" name="password" type="password" required></div>
    <div class="col-md-6"><label class="form-label">Experience</label><input class="form-control" name="experience" type="number" required></div>
    <div class="col-md-12"><label class="form-label">Specialty</label><input class="form-control" name="specialty"></div>
    <div class="col-md-12"><label class="form-label">Status</label><input class="form-control" name="status" value="active" required></div>
    <div class="col-12"><button class="btn btn-success px-4">Lưu huấn luyện viên</button></div>
</form>
@endsection
