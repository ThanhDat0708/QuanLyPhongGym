@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Gói tập</div>
        <h2 class="h4 mb-0">Sửa gói tập</h2>
    </div>
    <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.packages.update', $package) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" value="{{ $package->name }}" required></div>
    <div class="col-md-6"><label class="form-label">Price</label><input class="form-control" name="price" type="number" step="0.01" value="{{ $package->price }}" required></div>
    <div class="col-md-6"><label class="form-label">Duration</label><input class="form-control" name="duration" type="number" value="{{ $package->duration }}" required></div>
    <div class="col-md-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="4">{{ $package->description }}</textarea></div>
    <div class="col-12"><button class="btn btn-warning px-4 fw-bold">Cập nhật gói tập</button></div>
</form>
@endsection
