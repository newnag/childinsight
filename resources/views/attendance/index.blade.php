@extends('layouts.app')

@section('title', 'บันทึกการเข้าเรียนประจำวัน')

@section('content')
<div class="card">
    <div class="card-header">
        <form action="{{ route('attendance.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="date" class="col-form-label">วันที่:</label>
            </div>
            <div class="col-auto">
                <input type="date" id="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ชื่อนักเรียน</th>
                            <th class="text-center">สถานะ</th>
                            <th>หมายเหตุ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            @php
                                $attendance = $student->attendances->first();
                                $status = $attendance ? $attendance->status : 'present'; // Default to present
                                $remark = $attendance ? $attendance->remark : '';
                            @endphp
                            <tr>
                                <td>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                    <div class="small text-muted">
                                        {{ $student->gender == 'male' ? 'ชาย' : 'หญิง' }}
                                        @if(!$student->center_id || !Auth::user()->center_id)
                                            | <span class="text-info">{{ $student->center->name ?? '-' }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}][status]" id="status_present_{{ $student->id }}" value="present" {{ $status == 'present' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-success" for="status_present_{{ $student->id }}">มา</label>

                                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}][status]" id="status_absent_{{ $student->id }}" value="absent" {{ $status == 'absent' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-danger" for="status_absent_{{ $student->id }}">ขาด</label>

                                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}][status]" id="status_sick_{{ $student->id }}" value="sick" {{ $status == 'sick' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-warning" for="status_sick_{{ $student->id }}">ป่วย</label>

                                        <input type="radio" class="btn-check" name="attendance[{{ $student->id }}][status]" id="status_leave_{{ $student->id }}" value="leave" {{ $status == 'leave' ? 'checked' : '' }}>
                                        <label class="btn btn-outline-info" for="status_leave_{{ $student->id }}">ลา</label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="attendance[{{ $student->id }}][remark]" class="form-control form-control-sm" placeholder="หมายเหตุ..." value="{{ $remark }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="card-footer text-end d-flex justify-content-between align-items-center">
                <div>
                    {{ $students->links() }}
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
