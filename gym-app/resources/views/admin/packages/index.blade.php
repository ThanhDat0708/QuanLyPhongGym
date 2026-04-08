@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Gói tập</div>
        <h1 class="h4 mb-0">Quản lý gói tập</h1>
    </div>
    <a href="{{ route('admin.packages.create') }}" class="btn btn-success fw-bold">+ Thêm gói tập</a>
</div>

<table class="table table-bordered table-hover align-middle bg-white">
    <thead class="table-dark"><tr><th>Tên</th><th>Giá</th><th>Thời hạn</th><th>Mô tả</th><th>Thao tác</th></tr></thead>
    <tbody>
    @foreach ($packages as $package)
        <tr>
            <td>{{ $package->name }}</td>
            <td>{{ number_format($package->price) }}</td>
            <td>{{ $package->duration }}</td>
            <td>{{ $package->description }}</td>
            <td class="text-nowrap">
                <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-warning fw-bold">Sửa</a>
                <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger fw-bold">Xóa</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
