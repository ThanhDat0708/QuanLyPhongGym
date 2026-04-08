@extends('layouts.admin')

@section('content')
@php($statusLabels = trans('statuses'))
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Đăng ký</div>
        <h1 class="h4 mb-0">Quản lý đăng ký gói tập</h1>
    </div>
    <a href="{{ route('admin.registrations.create') }}" class="btn btn-success fw-bold">+ Thêm đăng ký</a>
</div>

<form class="row g-2 mb-3" method="GET" action="{{ route('admin.registrations.index') }}">
    <div class="col-md-8 col-lg-6">
        <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Tìm hội viên, gói tập, PT, trạng thái, ngày...">
    </div>
    <div class="col-auto">
        <button class="btn btn-dark" type="submit">Tìm kiếm</button>
    </div>
    @if($search !== '')
        <div class="col-auto">
            <a href="{{ route('admin.registrations.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
        </div>
    @endif
</form>

<table class="table table-bordered table-hover align-middle bg-white">
    <thead class="table-dark"><tr><th>Hội viên</th><th>Gói tập</th><th>PT phụ trách</th><th>Bắt đầu</th><th>Kết thúc</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
    <tbody>
    @foreach ($registrations as $registration)
        <tr>
            <td>{{ $registration->member->user->name }}</td>
            <td>{{ $registration->gymPackage->name }}</td>
            <td>{{ $registration->preferredTrainer?->user?->name ?? 'Chưa chọn PT' }}</td>
            <td>{{ $registration->start_date->format('d/m/Y') }}</td>
            <td>{{ $registration->end_date->format('d/m/Y') }}</td>
            <td><span class="badge text-bg-{{ $registration->status === 'active' ? 'success' : 'warning' }}">{{ $statusLabels[$registration->status] ?? $registration->status }}</span></td>
            <td class="text-nowrap">
                <a href="{{ route('admin.registrations.edit', $registration) }}" class="btn btn-sm btn-warning fw-bold">Sửa</a>
                <form method="POST" action="{{ route('admin.registrations.destroy', $registration) }}" class="d-inline">
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
