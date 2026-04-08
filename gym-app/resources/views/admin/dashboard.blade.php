@extends('layouts.admin')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <div class="text-uppercase fw-bold small" style="color:#0891b2; letter-spacing: .08em;">Tổng quan</div>
        <h1 class="h3 mb-1">Bảng điều khiển quản trị</h1>
        <div class="text-secondary">Theo dõi vận hành phòng gym theo ngày, tháng, năm.</div>
        <div class="small mt-1" style="color:#475569;">
            <i class="fa-regular fa-clock me-1"></i> Cập nhật lúc {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #0f172a, #1e3a8a); color: #fff;">
            <div class="card-body">
                <div class="text-white-50 mb-2">Tổng hội viên</div>
                <div class="display-6 fw-bold mb-0">{{ number_format($membersCount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #0f766e, #14b8a6); color: #fff;">
            <div class="card-body">
                <div class="text-white-50 mb-2">Tổng huấn luyện viên</div>
                <div class="display-6 fw-bold mb-0">{{ number_format($trainersCount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #c2410c, #f97316); color: #fff;">
            <div class="card-body">
                <div class="text-white-50 mb-2">Tổng đăng ký</div>
                <div class="display-6 fw-bold mb-0">{{ number_format($registrationsCount) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #111827, #374151); color: #fff;">
            <div class="card-body">
                <div class="text-white-50 mb-2">Doanh thu đã thu</div>
                <div class="display-6 fw-bold mb-0">{{ number_format($revenue) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 text-uppercase fw-bold mb-3" style="letter-spacing:.08em; color:#0ea5e9;">Doanh thu</h2>
                <div class="d-flex flex-wrap gap-2">
                    <div class="flex-fill rounded-3 p-3" style="background:#eff6ff; min-width:150px;">
                        <div class="text-secondary small">Theo ngày</div>
                        <div class="h5 mb-0 fw-bold">{{ number_format($dailyRevenue) }} đ</div>
                    </div>
                    <div class="flex-fill rounded-3 p-3" style="background:#ecfeff; min-width:150px;">
                        <div class="text-secondary small">Theo tháng</div>
                        <div class="h5 mb-0 fw-bold">{{ number_format($monthlyRevenue) }} đ</div>
                    </div>
                    <div class="flex-fill rounded-3 p-3" style="background:#fff7ed; min-width:150px;">
                        <div class="text-secondary small">Theo năm</div>
                        <div class="h5 mb-0 fw-bold">{{ number_format($yearlyRevenue) }} đ</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 text-uppercase fw-bold mb-3" style="letter-spacing:.08em; color:#0ea5e9;">Đăng ký mới</h2>
                <div class="d-flex flex-wrap gap-2">
                    <div class="flex-fill rounded-3 p-3" style="background:#f8fafc; min-width:150px;">
                        <div class="text-secondary small">Theo ngày</div>
                        <div class="h5 mb-0 fw-bold">{{ number_format($dailyRegistrations) }}</div>
                    </div>
                    <div class="flex-fill rounded-3 p-3" style="background:#f0fdfa; min-width:150px;">
                        <div class="text-secondary small">Theo tháng</div>
                        <div class="h5 mb-0 fw-bold">{{ number_format($monthlyRegistrations) }}</div>
                    </div>
                    <div class="flex-fill rounded-3 p-3" style="background:#fff7ed; min-width:150px;">
                        <div class="text-secondary small">Theo năm</div>
                        <div class="h5 mb-0 fw-bold">{{ number_format($yearlyRegistrations) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-3">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <h2 class="h5 mb-0">Tìm kiếm đăng ký</h2>
            <form class="d-flex gap-2" method="GET" action="{{ route('admin.dashboard') }}">
                <input
                    type="text"
                    class="form-control"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Tên hội viên, PT, gói tập, trạng thái..."
                    style="min-width: 280px;"
                >
                <button class="btn btn-dark" type="submit">Tìm</button>
                @if($search !== '')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                @endif
            </form>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Hội viên</th>
                        <th>Gói tập</th>
                        <th>PT</th>
                        <th>Bắt đầu</th>
                        <th>Trạng thái</th>
                        <th>Thanh toán</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dashboardRegistrations as $registration)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $registration->member?->user?->name ?? 'N/A' }}</div>
                                <div class="small text-secondary">{{ $registration->member?->user?->email ?? '' }}</div>
                            </td>
                            <td>{{ $registration->gymPackage?->name ?? 'N/A' }}</td>
                            <td>{{ $registration->preferredTrainer?->user?->name ?? 'Chưa chọn' }}</td>
                            <td>{{ optional($registration->start_date)->format('d/m/Y') }}</td>
                            <td><span class="badge text-bg-secondary">{{ __('statuses.' . $registration->status) }}</span></td>
                            <td>{{ $registration->payment ? __('statuses.' . $registration->payment->status) : 'Chưa có' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-4">Không có kết quả phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-2">
            {{ $dashboardRegistrations->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
