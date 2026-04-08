@extends('layouts.gym')

@section('content')
@php($statusLabels = trans('statuses'))
<h1 class="h4 mb-3">Lịch tập của tôi</h1>
<div class="table-responsive">
    <table class="table table-striped table-bordered bg-white">
        <thead><tr><th>Ngày</th><th>Giờ</th><th>PT</th><th>Trạng thái</th></tr></thead>
        <tbody>
            @forelse ($schedules as $item)
                <tr>
                    <td>{{ $item->date->format('d/m/Y') }}</td>
                    <td>{{ $item->time }}</td>
                    <td>{{ $item->trainer->user->name }}</td>
                    <td>{{ $statusLabels[$item->status] ?? $item->status }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Bạn chưa có lịch tập.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
