@extends('layouts.app')

@section('title', 'แจ้งซ่อมบำรุง')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">รายการแจ้งซ่อม</h3>
        <div class="card-tools">
            <a href="{{ route('maintenance.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> แจ้งซ่อมใหม่
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>วันที่</th>
                    <th>หัวข้อ</th>
                    <th>ความสำคัญ</th>
                    <th>สถานะ</th>
                    <th>ผู้แจ้ง</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                <tr>
                    <td>{{ $request->created_at->format('d M Y') }}</td>
                    <td>
                        {{ $request->title }}
                        @if($request->photo_path)
                            <i class="bi bi-image text-muted" title="มีรูปภาพ"></i>
                        @endif
                    </td>
                    <td>
                        @if($request->priority == 'high')
                            <span class="badge text-bg-danger">สูง</span>
                        @elseif($request->priority == 'medium')
                            <span class="badge text-bg-warning">ปานกลาง</span>
                        @else
                            <span class="badge text-bg-info">ต่ำ</span>
                        @endif
                    </td>
                    <td>
                        @if($request->status == 'pending')
                            <span class="badge text-bg-secondary">รอดำเนินการ</span>
                        @elseif($request->status == 'in_progress')
                            <span class="badge text-bg-primary">กำลังดำเนินการ</span>
                        @else
                            <span class="badge text-bg-success">เสร็จสิ้น</span>
                        @endif
                    </td>
                    <td>{{ $request->user->name }}</td>
                    <td>
                        <form action="{{ route('maintenance.update', $request->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>รอดำเนินการ</option>
                                    <option value="in_progress" {{ $request->status == 'in_progress' ? 'selected' : '' }}>กำลังดำเนินการ</option>
                                    <option value="resolved" {{ $request->status == 'resolved' ? 'selected' : '' }}>เสร็จสิ้น</option>
                                </select>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
