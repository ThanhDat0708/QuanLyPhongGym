@extends('layouts.admin')

@section('content')
@php($statusLabels = trans('statuses'))
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <div class="text-uppercase fw-bold small" style="color:#0891b2; letter-spacing:.08em;">Huấn luyện viên</div>
        <h1 class="h3 mb-1">Quản lý huấn luyện viên</h1>
        <div class="text-secondary">Theo dõi thông tin, chuyên môn và trạng thái làm việc của từng PT.</div>
    </div>
    <a href="{{ route('admin.trainers.create') }}" class="btn btn-success fw-bold">+ Thêm huấn luyện viên</a>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #0f172a, #1e3a8a); color:#fff;">
            <div class="card-body">
                <div class="text-white-50">Tổng huấn luyện viên</div>
                <div class="display-6 fw-bold mb-0">{{ number_format($totalCount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #0f766e, #14b8a6); color:#fff;">
            <div class="card-body">
                <div class="text-white-50">Đang hoạt động</div>
                <div class="display-6 fw-bold mb-0">{{ number_format($activeCount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #9a3412, #f97316); color:#fff;">
            <div class="card-body">
                <div class="text-white-50">Ngừng hoạt động</div>
                <div class="display-6 fw-bold mb-0">{{ number_format($inactiveCount) }}</div>
            </div>
        </div>
    </div>
</div>

<form class="card border-0 shadow-sm mb-3" method="GET" action="{{ route('admin.trainers.index') }}">
    <div class="card-body">
        <div class="row g-2 align-items-center">
            <div class="col-md-8 col-lg-6">
                <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Tìm tên, email, chuyên môn, trạng thái...">
            </div>
            <div class="col-auto">
                <button class="btn btn-dark" type="submit">Tìm kiếm</button>
            </div>
            @if($search !== '')
                <div class="col-auto">
                    <a href="{{ route('admin.trainers.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                </div>
            @endif
        </div>
    </div>
</form>

<div class="table-responsive shadow-sm rounded-4 overflow-hidden bg-white">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr><th>Tên</th><th>Email</th><th>Kinh nghiệm</th><th>Chuyên môn</th><th>Trạng thái</th><th>Thao tác</th></tr>
        </thead>
        <tbody>
        @forelse ($trainers as $trainer)
            <tr>
                <td>
                    <div class="fw-semibold">{{ $trainer->user->name }}</div>
                    <div class="small text-secondary">PT #{{ str_pad((string) $trainer->id, 4, '0', STR_PAD_LEFT) }}</div>
                </td>
                <td>{{ $trainer->user->email }}</td>
                <td>{{ $trainer->experience }} năm</td>
                <td>{{ $trainer->specialty ?: 'Chưa cập nhật' }}</td>
                <td>
                    <span class="badge text-bg-{{ $trainer->status === 'active' ? 'success' : 'secondary' }}">
                        {{ $statusLabels[$trainer->status] ?? $trainer->status }}
                    </span>
                </td>
                <td class="text-nowrap">
                    <a href="{{ route('admin.trainers.edit', $trainer) }}" class="btn btn-sm btn-warning fw-bold">Sửa</a>
                    <form method="POST" action="{{ route('admin.trainers.destroy', $trainer) }}" class="d-inline" onsubmit="return confirm('Xóa huấn luyện viên này?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger fw-bold">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-secondary py-4">Không có huấn luyện viên phù hợp.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
