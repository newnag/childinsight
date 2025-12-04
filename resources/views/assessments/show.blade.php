@extends('layouts.app')

@section('title', 'รายละเอียดการประเมิน')

@section('content')
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">คะแนนรวม: <span class="badge bg-primary">{{ $assessment->total_score }}</span></h5>
                    <small class="text-muted">ประเมินโดย {{ $assessment->assessor->name }} เมื่อ {{ \Carbon\Carbon::parse($assessment->assessment_date)->locale('th')->isoFormat('D MMMM YYYY') }}</small>
                </div>
                <a href="{{ route('assessments.index') }}" class="btn btn-secondary">กลับหน้ารายการ</a>
            </div>
        </div>
    </div>
</div>

@foreach($groupedItems as $category => $items)
    <div class="card mb-3">
        <div class="card-header">
            <strong>{{ $category }}</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th style="width: 40%">หัวข้อ</th>
                        <th style="width: 10%">คะแนน</th>
                        <th>หลักฐานและความคิดเห็น</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->criteria->topic }}</td>
                            <td class="text-center fw-bold">{{ $item->score }} / 3</td>
                            <td>
                                @if($item->comment)
                                    <p class="mb-1"><em>"{{ $item->comment }}"</em></p>
                                @endif
                                
                                @if($item->evidence_photos)
                                    <div class="d-flex gap-2 mt-2">
                                        @foreach(json_decode($item->evidence_photos) as $photo)
                                            <a href="{{ asset('storage/' . $photo) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $photo) }}" class="img-thumbnail" style="height: 60px; width: 60px; object-fit: cover;">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach
@endsection
