@extends('layouts.gym')

@section('content')
@php
    $statusLabels = trans('statuses');
    $pendingCount = $payments->where('status', 'pending')->count();
    $paidCount = $payments->where('status', 'paid')->count();
    $cancelCount = $payments->where('status', 'cancel')->count();
    $totalAmount = $payments->sum('amount');
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <h1 class="h4 mb-2">Thanh toán và hóa đơn</h1>
        <p class="text-muted mb-0">Theo dõi toàn bộ hóa đơn của bạn trong một nơi, không cần chọn phương thức thủ công.</p>
    </div>
    <div class="text-end">
        <div class="small text-muted">Tổng tiền hóa đơn</div>
        <div class="h4 mb-0 text-danger">{{ number_format($totalAmount) }} VND</div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="small text-muted">Đang chờ</div>
                <div class="h4 mb-0 text-warning">{{ $pendingCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="small text-muted">Đã thanh toán</div>
                <div class="h4 mb-0 text-success">{{ $paidCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="small text-muted">Đã hủy</div>
                <div class="h4 mb-0 text-secondary">{{ $cancelCount }}</div>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle bg-white border">
        <thead class="table-light">
            <tr>
                <th>Mã hóa đơn</th>
                <th>Gói tập</th>
                <th>Số tiền</th>
                <th>Trạng thái</th>
                <th>Ngày thanh toán</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $item)
                <tr>
                    <td>
                        <div class="fw-semibold text-primary">HD-{{ str_pad((string) $item->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="small text-muted">Đăng ký #{{ $item->registration_id }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $item->registration->gymPackage->name }}</div>
                        <div class="small text-muted">{{ $item->registration->start_date->format('d/m/Y') }} - {{ $item->registration->end_date->format('d/m/Y') }}</div>
                    </td>
                    <td class="fw-bold text-danger">{{ number_format($item->amount) }} VND</td>
                    <td>
                        <span class="badge text-bg-{{ $item->status === 'paid' ? 'success' : ($item->status === 'cancel' ? 'secondary' : 'warning') }}">
                            {{ $statusLabels[$item->status] ?? $item->status }}
                        </span>
                    </td>
                    <td>{{ $item->payment_date?->format('d/m/Y') ?? 'Chưa cập nhật' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Bạn chưa có hóa đơn nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
