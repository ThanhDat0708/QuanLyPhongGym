@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Đánh giá</div>
        <h2 class="h4 mb-0">Sửa đánh giá</h2>
    </div>
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.reviews.update', $review) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">User</label>
        <select class="form-select" disabled>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected($user->id === $review->user_id)>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Trainer</label>
        <select class="form-select" disabled>
            @foreach ($trainers as $trainer)
                <option value="{{ $trainer->id }}" @selected($trainer->id === $review->trainer_id)>{{ $trainer->user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label">Rating</label><input class="form-control" name="rating" type="number" min="1" max="5" value="{{ $review->rating }}" required></div>
    <div class="col-md-8"><label class="form-label">Comment</label><input class="form-control" name="comment" value="{{ $review->comment }}"></div>
    <div class="col-12"><button class="btn btn-warning px-4 fw-bold">Cập nhật đánh giá</button></div>
</form>
@endsection
