@extends('layouts.app')

@section('title', 'การประเมินศูนย์')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title">ประวัติการประเมิน</h3>
            @if(Auth::user()->role !== 'inspector' && Auth::user()->role !== 'manager')
            <a href="{{ route('assessments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> สร้างการประเมินใหม่
            </a>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>วันที่</th>
                        @if(!Auth::user()->center_id)
                        <th>ศูนย์</th>
                        @endif
                        <th>ผู้ประเมิน</th>
                        <th>คะแนนรวม</th>
                        <th>สถานะ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assessments as $assessment)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($assessment->assessment_date)->format('d M Y') }}</td>
                            @if(!Auth::user()->center_id)
                            <td>{{ $assessment->center->name ?? 'N/A' }}</td>
                            @endif
                            <td>{{ $assessment->assessor->name }}</td>
                            <td>
                                <span class="badge bg-primary fs-6">{{ $assessment->total_score }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $assessment->status == 'approved' ? 'success' : 'warning' }}">
                                    {{ $assessment->status == 'approved' ? 'อนุมัติแล้ว' : 'ส่งแล้ว' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('assessments.show', $assessment->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> ดูรายละเอียด
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
