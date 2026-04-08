@extends('layouts.gym')

@section('content')
@php($statusLabels = trans('statuses'))
<h1 class="h4 mb-3">Lịch sử đăng ký gói tập</h1>
<div class="table-responsive">
    <table class="table table-striped table-bordered bg-white">
        <thead><tr><th>Gói tập</th><th>PT đã chọn</th><th>Bắt đầu</th><th>Kết thúc</th><th>Trạng thái</th><th>Thanh toán</th><th>Thao tác</th></tr></thead>
        <tbody>
            @forelse ($registrations as $item)
                <tr>
                    <td>{{ $item->gymPackage->name }}</td>
                    <td>{{ $item->preferredTrainer?->user?->name ?? 'Không chọn' }}</td>
                    <td>{{ $item->start_date->format('d/m/Y') }}</td>
                    <td>{{ $item->end_date->format('d/m/Y') }}</td>
                    <td>{{ $statusLabels[$item->status] ?? $item->status }}</td>
                    <td>{{ $item->payment?->status ? ($statusLabels[$item->payment->status] ?? $item->payment->status) : 'Không có' }}</td>
                    <td>
                        @if ($item->status === 'pending')
                            <form action="{{ route('site.registrations.cancel', $item) }}" method="POST" class="mb-2" onsubmit="return confirm('Bạn có chắc muốn hủy đăng ký này?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hủy đăng ký</button>
                            </form>
                        @endif

                        @if (in_array($item->status, ['paid', 'active']))
                            <form action="{{ route('site.registrations.schedule', $item) }}" method="POST" class="d-grid gap-2">
                                @csrf
                                <select name="trainer_id" class="form-select form-select-sm" required>
                                    <option value="">Chọn PT</option>
                                    @foreach ($trainers as $trainer)
                                        <option value="{{ $trainer->id }}">{{ $trainer->user->name }} - {{ $trainer->specialty }}</option>
                                    @endforeach
                                </select>
                                <input type="date" name="date" class="form-control form-control-sm" min="{{ now()->toDateString() }}" required>
                                <input type="time" name="time" class="form-control form-control-sm" required>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Đặt lịch với PT</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">Bạn chưa đăng ký gói tập nào.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
