@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Hội viên</div>
        <h2 class="h4 mb-0">Sửa hội viên</h2>
    </div>
    <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.members.update', $member) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6"><label class="form-label">Họ tên</label><input class="form-control" name="name" value="{{ $member->user->name }}" required></div>
    <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" name="email" type="email" value="{{ $member->user->email }}" required></div>
    <div class="col-md-6"><label class="form-label">Số điện thoại</label><input class="form-control" name="phone" value="{{ $member->phone }}"></div>
    <div class="col-md-6"><label class="form-label">Trạng thái</label><input class="form-control" name="status" value="{{ $member->status }}" required></div>
    <div class="col-md-12"><label class="form-label">Địa chỉ</label><input class="form-control" name="address" value="{{ $member->address }}"></div>
    <div class="col-md-6"><label class="form-label">Chiều cao</label><input class="form-control" name="height" type="number" step="0.01" value="{{ $member->height }}"></div>
    <div class="col-md-6"><label class="form-label">Cân nặng</label><input class="form-control" name="weight" type="number" step="0.01" value="{{ $member->weight }}"></div>
    <div class="col-12"><button class="btn btn-warning px-4 fw-bold">Cập nhật hội viên</button></div>
</form>
@endsection
