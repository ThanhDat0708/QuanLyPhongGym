@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-uppercase text-info fw-bold small">Tổng quan</div>
        <h1 class="h4 mb-0">Bảng điều khiển quản trị</h1>
    </div>
    <div class="badge text-bg-dark p-3">Tổng hợp</div>
</div>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff;">
            <div class="card-body">
                <div class="text-white-50">Hội viên</div>
                <div class="display-6 fw-bold">{{ $membersCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #0f766e, #14b8a6); color: #fff;">
            <div class="card-body">
                <div class="text-white-50">Huấn luyện viên</div>
                <div class="display-6 fw-bold">{{ $trainersCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #c2410c, #f97316); color: #fff;">
            <div class="card-body">
                <div class="text-white-50">Đăng ký</div>
                <div class="display-6 fw-bold">{{ $registrationsCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #111827, #374151); color: #fff;">
            <div class="card-body">
                <div class="text-white-50">Doanh thu đã thu</div>
                <div class="display-6 fw-bold">{{ number_format($revenue) }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
