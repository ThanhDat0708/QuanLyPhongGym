@extends('layouts.admin')

@section('content')
@php($statusLabels = trans('statuses'))
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Hội viên</div>
        <h1 class="h4 mb-0">Quản lý hội viên</h1>
    </div>
    <a href="{{ route('admin.members.create') }}" class="btn btn-success fw-bold">+ Thêm hội viên</a>
</div>

<form class="row g-2 mb-3" method="GET" action="{{ route('admin.members.index') }}">
    <div class="col-md-8 col-lg-6">
        <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Tìm tên, email, số điện thoại, địa chỉ, trạng thái...">
    </div>
    <div class="col-auto">
        <button class="btn btn-dark" type="submit">Tìm kiếm</button>
    </div>
    @if($search !== '')
        <div class="col-auto">
            <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
        </div>
    @endif
</form>

<table class="table table-bordered table-hover align-middle bg-white">
    <thead class="table-dark">
        <tr><th>Tên</th><th>Email</th><th>Số điện thoại</th><th>Địa chỉ</th><th>Chiều cao</th><th>Cân nặng</th><th>Trạng thái</th><th>Thao tác</th></tr>
    </thead>
    <tbody>
    @foreach ($members as $member)
        <tr>
            <td>{{ $member->user->name }}</td>
            <td>{{ $member->user->email }}</td>
            <td>{{ $member->phone }}</td>
            <td>{{ $member->address }}</td>
            <td>{{ $member->height }}</td>
            <td>{{ $member->weight }}</td>
            <td><span class="badge text-bg-success">{{ $statusLabels[$member->status] ?? $member->status }}</span></td>
            <td class="text-nowrap">
                <a href="{{ route('admin.members.edit', $member) }}" class="btn btn-sm btn-warning fw-bold">Sửa</a>
                <form method="POST" action="{{ route('admin.members.destroy', $member) }}" class="d-inline">
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
