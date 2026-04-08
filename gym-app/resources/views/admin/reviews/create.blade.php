@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Đánh giá</div>
        <h2 class="h4 mb-0">Thêm đánh giá</h2>
    </div>
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<form method="POST" action="{{ route('admin.reviews.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
        <label class="form-label">User</label>
        <select name="user_id" class="form-select" required>
            <option value="">Select user</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Trainer</label>
        <select name="trainer_id" class="form-select" required>
            <option value="">Select trainer</option>
            @foreach ($trainers as $trainer)
                <option value="{{ $trainer->id }}">{{ $trainer->user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label">Rating</label><input class="form-control" name="rating" type="number" min="1" max="5" required></div>
    <div class="col-md-8"><label class="form-label">Comment</label><input class="form-control" name="comment"></div>
    <div class="col-12"><button class="btn btn-success px-4">Lưu đánh giá</button></div>
</form>
@endsection
