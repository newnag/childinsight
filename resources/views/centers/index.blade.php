@extends('layouts.app')

@section('title', 'ศูนย์เด็กเล็ก')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายชื่อศูนย์เด็กเล็ก</h3>
        <div class="card-tools">
            <a href="{{ route('centers.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> เพิ่มศูนย์
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>รหัส</th>
                    <th>ชื่อศูนย์</th>
                    <th>อำเภอ/เขต</th>
                    <th>จังหวัด</th>
                    <th>สถานะ</th>
                    <th style="width: 150px">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @forelse($centers as $index => $center)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $center->code }}</td>
                    <td>{{ $center->name }}</td>
                    <td>{{ $center->district }}</td>
                    <td>{{ $center->province }}</td>
                    <td>
                        @if($center->status == 'active')
                            <span class="badge bg-success">ใช้งาน</span>
                        @else
                            <span class="badge bg-secondary">ไม่ใช้งาน</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('centers.edit', $center->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('centers.destroy', $center->id) }}" method="POST" class="d-inline" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบศูนย์นี้?');">
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
                    <td colspan="7" class="text-center">ไม่พบข้อมูลศูนย์</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
