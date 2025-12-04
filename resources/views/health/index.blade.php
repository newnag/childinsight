@extends('layouts.app')

@section('title', 'บันทึกสุขภาพและพัฒนาการ')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายชื่อนักเรียน</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ชื่อ-นามสกุล</th>
                        <th>อายุ</th>
                        <th>ตรวจล่าสุด</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>
                                {{ $student->first_name }} {{ $student->last_name }}
                                <div class="small text-muted">
                                    {{ $student->gender == 'male' ? 'ชาย' : 'หญิง' }}
                                    @if(!Auth::user()->center_id)
                                        | <span class="text-info">{{ $student->center->name ?? '-' }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($student->dob)->age }} ปี</td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <a href="{{ route('health.show', $student->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-graph-up"></i> ดูประวัติ
                                </a>
                                <a href="{{ route('health.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg"></i> เพิ่มบันทึก
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        {{ $students->links() }}
    </div>
</div>
@endsection
