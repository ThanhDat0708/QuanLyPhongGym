@extends('layouts.gym')

@section('content')
<h1 class="h4 mb-3">Bảng điều khiển hội viên</h1>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted">Số đăng ký gói tập</div><div class="display-6">{{ $registrationCount }}</div></div></div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted">Số lịch tập</div><div class="display-6">{{ $scheduleCount }}</div></div></div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted">Thanh toán chờ xử lý</div><div class="display-6">{{ $pendingPayments }}</div></div></div>
    </div>
</div>
@endsection
