@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Hội viên</div>
        <h2 class="h4 mb-0">Thêm hội viên</h2>
    </div>
    <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.members.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6"><label class="form-label">Họ tên</label><input class="form-control" name="name" required></div>
    <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" name="email" type="email" required></div>
    <div class="col-md-6"><label class="form-label">Mật khẩu</label><input class="form-control" name="password" type="password" required></div>
    <div class="col-md-6"><label class="form-label">Số điện thoại</label><input class="form-control" name="phone"></div>
    <div class="col-md-12"><label class="form-label">Địa chỉ</label><input class="form-control" name="address"></div>
    <div class="col-md-4"><label class="form-label">Chiều cao</label><input class="form-control" name="height" type="number" step="0.01"></div>
    <div class="col-md-4"><label class="form-label">Cân nặng</label><input class="form-control" name="weight" type="number" step="0.01"></div>
    <div class="col-md-4"><label class="form-label">Trạng thái</label><input class="form-control" name="status" value="active" required></div>
    <div class="col-12"><button class="btn btn-success px-4">Lưu hội viên</button></div>
</form>
@endsection
