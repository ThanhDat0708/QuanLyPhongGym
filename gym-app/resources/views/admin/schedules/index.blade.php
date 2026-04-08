@extends('layouts.admin')

@section('content')
@php($statusLabels = trans('statuses'))
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Lịch tập</div>
        <h1 class="h4 mb-0">Quản lý lịch tập</h1>
    </div>
    <a href="{{ route('admin.schedules.create') }}" class="btn btn-success fw-bold">+ Thêm lịch tập</a>
</div>

<form class="row g-2 mb-3" method="GET" action="{{ route('admin.schedules.index') }}">
    <div class="col-md-8 col-lg-6">
        <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Tìm hội viên, PT, ngày, giờ, trạng thái...">
    </div>
    <div class="col-auto">
        <button class="btn btn-dark" type="submit">Tìm kiếm</button>
    </div>
    @if($search !== '')
        <div class="col-auto">
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
        </div>
    @endif
</form>

<table class="table table-bordered table-hover align-middle bg-white">
    <thead class="table-dark"><tr><th>Hội viên</th><th>Huấn luyện viên</th><th>Ngày</th><th>Giờ</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
    <tbody>
    @foreach ($schedules as $schedule)
        <tr>
            <td>{{ $schedule->member->user->name }}</td>
            <td>{{ $schedule->trainer->user->name }}</td>
            <td>{{ $schedule->date->format('d/m/Y') }}</td>
            <td>{{ $schedule->time }}</td>
            <td><span class="badge text-bg-{{ $schedule->status === 'done' ? 'success' : ($schedule->status === 'cancel' ? 'danger' : 'warning') }}">{{ $statusLabels[$schedule->status] ?? $schedule->status }}</span></td>
            <td class="text-nowrap">
                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-sm btn-warning fw-bold">Sửa</a>
                <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}" class="d-inline">
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
