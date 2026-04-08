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
                            <form
                                action="{{ route('site.registrations.schedule', $item) }}"
                                method="POST"
                                class="d-grid gap-2 schedule-form"
                                data-available-url="{{ route('site.registrations.available-trainers', $item) }}"
                                data-preferred-trainer-id="{{ $item->preferred_trainer_id }}"
                            >
                                @csrf
                                <input type="date" name="date" class="form-control form-control-sm js-date" min="{{ now()->toDateString() }}" required>
                                <input type="time" name="time" class="form-control form-control-sm js-time" required>
                                <select name="trainer_id" class="form-select form-select-sm js-trainer" required disabled>
                                    <option value="">Chọn ngày và giờ trước</option>
                                </select>
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

<script>
document.querySelectorAll('.schedule-form').forEach(function (form) {
    const dateInput = form.querySelector('.js-date');
    const timeInput = form.querySelector('.js-time');
    const trainerSelect = form.querySelector('.js-trainer');
    const availableUrl = form.dataset.availableUrl;
    const preferredTrainerId = form.dataset.preferredTrainerId;

    const loadAvailableTrainers = async function () {
        const date = dateInput.value;
        const time = timeInput.value;

        if (!date || !time) {
            trainerSelect.innerHTML = '<option value="">Chọn ngày và giờ trước</option>';
            trainerSelect.disabled = true;
            return;
        }

        trainerSelect.disabled = true;
        trainerSelect.innerHTML = '<option value="">Đang tải PT trống lịch...</option>';

        try {
            const response = await fetch(availableUrl + '?date=' + encodeURIComponent(date) + '&time=' + encodeURIComponent(time));
            if (!response.ok) {
                throw new Error('Không thể tải danh sách PT.');
            }

            const trainers = await response.json();
            trainerSelect.innerHTML = '';

            if (!trainers.length) {
                trainerSelect.innerHTML = '<option value="">Không có PT trống ở khung giờ này</option>';
                trainerSelect.disabled = true;
                return;
            }

            trainerSelect.innerHTML = '<option value="">Chọn PT</option>';
            trainers.forEach(function (trainer) {
                const option = document.createElement('option');
                option.value = trainer.id;
                option.textContent = trainer.name + ' - ' + (trainer.specialty || 'Tổng quát');
                trainerSelect.appendChild(option);
            });

            if (preferredTrainerId) {
                const preferredOption = trainerSelect.querySelector('option[value="' + preferredTrainerId + '"]');
                if (preferredOption) {
                    trainerSelect.value = preferredTrainerId;
                }
            }

            trainerSelect.disabled = false;
        } catch (error) {
            trainerSelect.innerHTML = '<option value="">Không tải được danh sách PT</option>';
            trainerSelect.disabled = true;
        }
    };

    dateInput.addEventListener('change', loadAvailableTrainers);
    timeInput.addEventListener('change', loadAvailableTrainers);
});
</script>
@endsection
