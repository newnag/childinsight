@extends('layouts.app')

@section('title', 'บันทึกประจำวัน')

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('daily_logs.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="date" class="col-form-label">วันที่:</label>
            </div>
            <div class="col-auto">
                <input type="date" id="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ชื่อนักเรียน</th>
                    <th>นม</th>
                    <th>อาหาร</th>
                    <th>รูปภาพ</th>
                    <th style="width: 150px">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                @php
                    $log = $logs[$student->id] ?? null;
                @endphp
                <tr>
                    <td>
                        {{ $student->first_name }} {{ $student->last_name }}
                        @if(!Auth::user()->center_id)
                            <div class="small text-muted text-info">{{ $student->center->name ?? '-' }}</div>
                        @endif
                    </td>
                    <td>
                        @if($log && $log->milk_requisition)
                            <span class="badge bg-success">เบิก</span>
                            @if($log->milk_amount) <small>({{ $log->milk_amount }})</small> @endif
                        @else
                            <span class="badge bg-secondary text-opacity-50">ไม่เบิก</span>
                        @endif
                    </td>
                    <td>
                        @if($log && $log->food_quantity)
                            @php
                                $foodLabel = match($log->food_quantity) {
                                    'high' => 'มาก',
                                    'medium' => 'ปานกลาง',
                                    'low' => 'น้อย',
                                    default => '-'
                                };
                            @endphp
                            <span class="badge bg-{{ $log->food_quantity == 'high' ? 'success' : ($log->food_quantity == 'medium' ? 'warning' : 'danger') }}">
                                {{ $foodLabel }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($log && !empty($log->activity_photos))
                            <span class="badge bg-info">{{ count($log->activity_photos) }} รูป</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('daily_logs.create', ['student_id' => $student->id, 'date' => $date]) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil-square"></i> {{ $log ? 'แก้ไข' : 'บันทึก' }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">ไม่พบข้อมูลนักเรียน</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        {{ $students->links() }}
    </div>
</div>
@endsection
