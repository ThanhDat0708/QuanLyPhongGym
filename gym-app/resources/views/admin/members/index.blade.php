@extends('layouts.admin')

@section('content')
@php($statusLabels = trans('statuses'))
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Hội viên</div>
        <h1 class="h4 mb-0">Quan ly hoi vien</h1>
    </div>
    <a href="{{ route('admin.members.create') }}" class="btn btn-success fw-bold">+ Thêm hội viên</a>
</div>

<table class="table table-bordered table-hover align-middle bg-white">
    <thead class="table-dark">
        <tr><th>Ten</th><th>Email</th><th>Phone</th><th>Address</th><th>Height</th><th>Weight</th><th>Status</th><th>Actions</th></tr>
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
