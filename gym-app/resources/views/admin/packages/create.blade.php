@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Gói tập</div>
        <h2 class="h4 mb-0">Thêm gói tập</h2>
    </div>
    <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.packages.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
    <div class="col-md-6"><label class="form-label">Price</label><input class="form-control" name="price" type="number" step="0.01" required></div>
    <div class="col-md-6"><label class="form-label">Duration</label><input class="form-control" name="duration" type="number" required></div>
    <div class="col-md-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="4"></textarea></div>
    <div class="col-12"><button class="btn btn-success px-4">Lưu gói tập</button></div>
</form>
@endsection
