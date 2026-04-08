@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Đánh giá</div>
        <h1 class="h4 mb-0">Quản lý đánh giá</h1>
    </div>
    <a href="{{ route('admin.reviews.create') }}" class="btn btn-success fw-bold">+ Thêm đánh giá</a>
</div>

<table class="table table-bordered table-hover align-middle bg-white">
    <thead class="table-dark"><tr><th>Người dùng</th><th>Huấn luyện viên</th><th>Điểm</th><th>Nhận xét</th><th>Thao tác</th></tr></thead>
    <tbody>
    @foreach ($reviews as $review)
        <tr>
            <td>{{ $review->user->name }}</td>
            <td>{{ $review->trainer->user->name }}</td>
            <td><span class="badge text-bg-warning text-dark">{{ $review->rating }}/5</span></td>
            <td>{{ $review->comment }}</td>
            <td class="text-nowrap">
                <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-sm btn-warning fw-bold">Sửa</a>
                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="d-inline">
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
