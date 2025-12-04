@extends('layouts.app')

@section('title', 'นักเรียน')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายชื่อนักเรียน</h3>
        <div class="card-tools">
            <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> เพิ่มนักเรียน
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ชื่อ-นามสกุล</th>
                    <th>เพศ</th>
                    <th>วันเกิด</th>
                    <th>ผู้ปกครอง</th>
                    <th>เบอร์โทรศัพท์</th>
                    @if(!Auth::user()->center_id)
                    <th>ศูนย์</th>
                    @endif
                    <th style="width: 150px">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                    <td>{{ $student->gender == 'male' ? 'ชาย' : 'หญิง' }}</td>
                    <td>{{ \Carbon\Carbon::parse($student->dob)->format('d M Y') }}</td>
                    <td>{{ $student->parent_name }}</td>
                    <td>{{ $student->parent_contact }}</td>
                    @if(!Auth::user()->center_id)
                    <td>{{ $student->center->name ?? '-' }}</td>
                    @endif
                    <td>
                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนักเรียนคนนี้?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ !Auth::user()->center_id ? 7 : 6 }}" class="text-center">ไม่พบข้อมูลนักเรียน</td>
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
