@extends('layouts.app')

@section('title', 'การประเมินศูนย์เด็กเล็กใหม่')

@section('content')
<form action="{{ route('assessments.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="assessment_date" class="form-label">วันที่ประเมิน</label>
                    <input type="date" class="form-control" id="assessment_date" name="assessment_date" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
        </div>
    </div>

    @foreach($criterias as $category => $items)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">{{ $category }}</h5>
            </div>
            <div class="card-body">
                @foreach($items as $item)
                    <div class="row mb-4 border-bottom pb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ $item->topic }}</label>
                            <p class="text-muted small">คะแนนเต็ม: {{ $item->max_score }}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">คะแนน (0-3)</label>
                                <div class="btn-group w-100" role="group">
                                    @for($i = 0; $i <= 3; $i++)
                                        <input type="radio" class="btn-check" name="scores[{{ $item->id }}]" id="score_{{ $item->id }}_{{ $i }}" value="{{ $i }}" {{ $i == 3 ? 'checked' : '' }}>
                                        <label class="btn btn-outline-primary" for="score_{{ $item->id }}_{{ $i }}">{{ $i }}</label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">หลักฐาน (รูปภาพ)</label>
                                <input type="file" class="form-control" name="evidence[{{ $item->id }}][]" multiple accept="image/*">
                            </div>
                            <div>
                                <textarea class="form-control" name="comments[{{ $item->id }}]" placeholder="ความคิดเห็น..." rows="1"></textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <div class="d-grid gap-2 mb-5">
        <button type="submit" class="btn btn-success btn-lg">ส่งแบบประเมิน</button>
    </div>
</form>
@endsection
