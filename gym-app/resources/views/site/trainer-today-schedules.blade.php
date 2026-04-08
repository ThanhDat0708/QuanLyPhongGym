@extends('layouts.gym')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <div class="text-uppercase fw-bold small" style="color:#0891b2; letter-spacing:.08em;">Huấn luyện viên</div>
        <h1 class="h3 mb-1">Lịch tập hôm nay</h1>
        <div class="text-secondary">{{ $trainer->user->name }} đang phụ trách các buổi tập trong ngày {{ \Carbon\Carbon::parse($today)->format('d/m/Y') }}.</div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <div class="badge rounded-pill px-3 py-2" style="background:#0f172a; color:#fff;">Tổng buổi: {{ number_format($totalSessions) }}</div>
        <div class="badge rounded-pill px-3 py-2" style="background:#14b8a6; color:#fff;">Hội viên: {{ number_format($uniqueMembers) }}</div>
        <div class="badge rounded-pill px-3 py-2" style="background:#f97316; color:#fff;">Đăng ký chọn PT: {{ number_format($selectedCount) }}</div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #0f172a, #1e3a8a); color:#fff;">
            <div class="card-body">
                <div class="text-white-50">Trainer</div>
                <div class="h4 mb-1 fw-bold">{{ $trainer->user->name }}</div>
                <div class="small text-white-50">{{ $trainer->specialty ?: 'Chưa cập nhật chuyên môn' }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #0f766e, #14b8a6); color:#fff;">
            <div class="card-body">
                <div class="text-white-50">Buổi hôm nay</div>
                <div class="display-6 fw-bold mb-0">{{ number_format($totalSessions) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #9a3412, #f97316); color:#fff;">
            <div class="card-body">
                <div class="text-white-50">Hội viên khác nhau</div>
                <div class="display-6 fw-bold mb-0">{{ number_format($uniqueMembers) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Giờ</th>
                        <th>Hội viên</th>
                        <th>Email</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                        <tr>
                            <td><span class="badge text-bg-primary">{{ $schedule->time }}</span></td>
                            <td class="fw-semibold">{{ $schedule->member?->user?->name ?? 'N/A' }}</td>
                            <td>{{ $schedule->member?->user?->email ?? 'N/A' }}</td>
                            <td>
                                <span class="badge text-bg-{{ $schedule->status === 'done' ? 'success' : ($schedule->status === 'cancel' ? 'secondary' : 'warning') }}">
                                    {{ __('statuses.' . $schedule->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary py-4">Hôm nay chưa có lịch tập nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h2 class="h5 mb-1">Hội viên đã chọn bạn</h2>
                <div class="text-secondary small">Các đăng ký này sẽ hiện ngay trên trang của trainer để tự xếp lịch.</div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Hội viên</th>
                        <th>Gói tập</th>
                        <th>Bắt đầu</th>
                        <th>Trạng thái</th>
                        <th>Thanh toán</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($selectedRegistrations as $registration)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $registration->member?->user?->name ?? 'N/A' }}</div>
                                <div class="small text-secondary">{{ $registration->member?->user?->email ?? '' }}</div>
                            </td>
                            <td>{{ $registration->gymPackage?->name ?? 'N/A' }}</td>
                            <td>{{ optional($registration->start_date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge text-bg-secondary">{{ __('statuses.' . $registration->status) }}</span>
                            </td>
                            <td>{{ $registration->payment ? __('statuses.' . $registration->payment->status) : 'Chưa có' }}</td>
                            <td>
                                <form method="POST" action="{{ route('trainer.auto-schedule', $registration) }}" onsubmit="return confirm('Tự động xếp lịch cho hội viên này?');">
                                    @csrf
                                    <button class="btn btn-sm btn-dark" type="submit">Tự xếp lịch</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-4">Chưa có hội viên nào chọn PT này.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
