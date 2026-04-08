@extends('layouts.admin')

@section('content')
@php($statusLabels = trans('statuses'))
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Thanh toán</div>
        <h2 class="h4 mb-0">Sửa thanh toán</h2>
    </div>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">Về danh sách</a>
</div>

<div class="alert alert-info py-2 px-3">
    Mã hóa đơn được tự động quản lý bởi hệ thống.
</div>

<form method="POST" action="{{ route('admin.payments.update', $payment) }}" class="row g-3">
    @csrf
    @method('PUT')
    <div class="col-md-6">
        <label class="form-label">Đăng ký gói tập</label>
        <select class="form-select" disabled>
            @foreach ($registrations as $registration)
                <option value="{{ $registration->id }}" @selected($registration->id === $payment->registration_id)>{{ $registration->member->user->name }} - {{ $registration->gymPackage->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6"><label class="form-label">Số tiền</label><input class="form-control" name="amount" type="number" step="0.01" min="0" value="{{ old('amount', $payment->amount) }}" required></div>
    <div class="col-md-6">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select" required>
            @foreach (['pending', 'paid', 'cancel'] as $status)
                <option value="{{ $status }}" @selected(old('status', $payment->status) === $status)>{{ $statusLabels[$status] ?? $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6"><label class="form-label">Ngày thanh toán</label><input type="date" class="form-control" name="payment_date" value="{{ old('payment_date', $payment->payment_date?->format('Y-m-d')) }}"></div>
    <div class="col-12"><button class="btn btn-warning px-4 fw-bold">Cập nhật thanh toán</button></div>
</form>
@endsection
