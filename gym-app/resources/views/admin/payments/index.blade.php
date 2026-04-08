@extends('layouts.admin')

@section('content')
@php($statusLabels = trans('statuses'))
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Thanh toán</div>
        <h1 class="h4 mb-0">Quản lý thanh toán</h1>
    </div>
    <a href="{{ route('admin.payments.create') }}" class="btn btn-success fw-bold">+ Thêm thanh toán</a>
</div>

<form class="row g-2 mb-3" method="GET" action="{{ route('admin.payments.index') }}">
    <div class="col-md-8 col-lg-6">
        <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Tìm mã hóa đơn (HD-000001), hội viên, gói tập, trạng thái...">
    </div>
    <div class="col-auto">
        <button class="btn btn-dark" type="submit">Tìm kiếm</button>
    </div>
    @if($search !== '')
        <div class="col-auto">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
        </div>
    @endif
</form>

<table class="table table-bordered table-hover align-middle bg-white">
    <thead class="table-dark">
        <tr><th>Mã hóa đơn</th><th>Hội viên</th><th>Gói tập</th><th>Số tiền</th><th>Trạng thái</th><th>Ngày thanh toán</th><th>Thao tác</th></tr>
    </thead>
    <tbody>
    @foreach ($payments as $payment)
        <tr>
            <td><span class="badge text-bg-primary">HD-{{ str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT) }}</span></td>
            <td>{{ $payment->registration->member->user->name }}</td>
            <td>{{ $payment->registration->gymPackage->name }}</td>
            <td>{{ number_format($payment->amount) }} VND</td>
            <td><span class="badge text-bg-{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'cancel' ? 'secondary' : 'warning') }}">{{ $statusLabels[$payment->status] ?? $payment->status }}</span></td>
            <td>{{ $payment->payment_date?->format('d/m/Y') ?? '-' }}</td>
            <td class="text-nowrap">
                <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-sm btn-warning fw-bold">Sửa</a>
                <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}" class="d-inline">
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
