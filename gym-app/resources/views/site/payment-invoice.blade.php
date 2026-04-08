@extends('layouts.gym')

@section('content')
@php($statusLabels = trans('statuses'))
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h1 class="h4 mb-1">Hóa đơn thanh toán</h1>
                        <div class="text-muted">Mã hóa đơn: HD-{{ str_pad((string) $payment->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <span class="badge text-bg-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">
                        {{ $statusLabels[$payment->status] ?? $payment->status }}
                    </span>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle mb-0">
                        <tbody>
                            <tr>
                                <th class="bg-light" style="width: 35%;">Hội viên</th>
                                <td>{{ $payment->registration->member->user->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Gói tập</th>
                                <td>{{ $payment->registration->gymPackage->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Thời gian gói</th>
                                <td>{{ $payment->registration->start_date->format('d/m/Y') }} - {{ $payment->registration->end_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Số tiền cần thanh toán</th>
                                <td class="fw-bold text-danger">{{ number_format($payment->amount) }} VND</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Ngày tạo hóa đơn</th>
                                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Ngày thanh toán</th>
                                <td>{{ $payment->payment_date?->format('d/m/Y') ?? 'Chưa thanh toán' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex gap-2">
                    @if ($payment->status === 'pending')
                        <form method="POST" action="{{ route('site.payments.pay', $payment) }}" onsubmit="return confirm('Xác nhận thanh toán hóa đơn này?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">Xác nhận thanh toán</button>
                        </form>
                    @endif
                    <a href="{{ route('site.payments') }}" class="btn btn-outline-secondary">Quay lại danh sách hóa đơn</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
