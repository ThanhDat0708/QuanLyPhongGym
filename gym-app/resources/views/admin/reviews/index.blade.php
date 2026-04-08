@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Đánh giá</div>
        <h1 class="h4 mb-0">Quản lý đánh giá</h1>
    </div>
    <a href="{{ route('admin.reviews.create') }}" class="btn btn-success fw-bold">+ Thêm đánh giá</a>
</div>

<form class="row g-2 mb-3" method="GET" action="{{ route('admin.reviews.index') }}">
    <div class="col-md-8 col-lg-6">
        <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Tìm người dùng, huấn luyện viên, điểm, nhận xét...">
    </div>
    <div class="col-auto">
        <button class="btn btn-dark" type="submit">Tìm kiếm</button>
    </div>
    @if($search !== '')
        <div class="col-auto">
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
        </div>
    @endif
</form>

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
