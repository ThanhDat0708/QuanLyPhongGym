@extends('layouts.admin')

@section('content')
@php($statusLabels = trans('statuses'))
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Huấn luyện viên</div>
        <h1 class="h4 mb-0">Quản lý huấn luyện viên</h1>
    </div>
    <a href="{{ route('admin.trainers.create') }}" class="btn btn-success fw-bold">+ Thêm huấn luyện viên</a>
</div>

<table class="table table-bordered table-hover align-middle bg-white">
    <thead class="table-dark"><tr><th>Tên</th><th>Email</th><th>Kinh nghiệm</th><th>Chuyên môn</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
    <tbody>
    @foreach ($trainers as $trainer)
        <tr>
            <td>{{ $trainer->user->name }}</td>
            <td>{{ $trainer->user->email }}</td>
            <td>{{ $trainer->experience }}</td>
            <td>{{ $trainer->specialty }}</td>
            <td><span class="badge text-bg-success">{{ $statusLabels[$trainer->status] ?? $trainer->status }}</span></td>
            <td class="text-nowrap">
                <a href="{{ route('admin.trainers.edit', $trainer) }}" class="btn btn-sm btn-warning fw-bold">Sửa</a>
                <form method="POST" action="{{ route('admin.trainers.destroy', $trainer) }}" class="d-inline">
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
