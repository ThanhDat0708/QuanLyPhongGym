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
