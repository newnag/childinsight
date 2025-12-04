@extends('layouts.app')

@section('title', 'บันทึกประจำวัน: ' . $student->first_name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                บันทึกสำหรับ <strong>{{ $student->first_name }} {{ $student->last_name }}</strong> วันที่ {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
            </div>
            <div class="card-body">
                <form action="{{ route('daily_logs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <input type="hidden" name="date" value="{{ $date }}">

                    <h5 class="mb-3 text-primary"><i class="bi bi-cup-hot"></i> นมและโภชนาการ</h5>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="milk_requisition" name="milk_requisition" value="1" {{ ($log && $log->milk_requisition) ? 'checked' : '' }}>
                        <label class="form-check-label" for="milk_requisition">การเบิกนม</label>
                    </div>

                    <div class="mb-3">
                        <label for="milk_amount" class="form-label">ปริมาณนม / รายละเอียด</label>
                        <input type="text" class="form-control" id="milk_amount" name="milk_amount" value="{{ $log->milk_amount ?? '' }}" placeholder="เช่น 200 มล., 1 กล่อง">
                    </div>

                    <hr>
                    <h5 class="mb-3 text-primary"><i class="bi bi-egg-fried"></i> การบริโภคอาหาร</h5>

                    <div class="mb-3">
                        <label for="food_consumed" class="form-label">เมนู / อาหารที่บริโภค</label>
                        <textarea class="form-control" id="food_consumed" name="food_consumed" rows="2">{{ $log->food_consumed ?? '' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="food_quantity" class="form-label">ปริมาณที่ทาน</label>
                        <select class="form-select" id="food_quantity" name="food_quantity">
                            <option value="">เลือกปริมาณ</option>
                            <option value="high" {{ ($log && $log->food_quantity == 'high') ? 'selected' : '' }}>มาก (เกินครึ่ง / หมด)</option>
                            <option value="medium" {{ ($log && $log->food_quantity == 'medium') ? 'selected' : '' }}>ปานกลาง (ครึ่งหนึ่ง)</option>
                            <option value="low" {{ ($log && $log->food_quantity == 'low') ? 'selected' : '' }}>น้อย (น้อยกว่าครึ่ง)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nutrient_quality" class="form-label">ความถี่และคุณภาพสารอาหาร</label>
                        <textarea class="form-control" id="nutrient_quality" name="nutrient_quality" rows="2" placeholder="เช่น ครบ 5 หมู่, ทานผัก...">{{ $log->nutrient_quality ?? '' }}</textarea>
                    </div>

                    <hr>
                    <h5 class="mb-3 text-primary"><i class="bi bi-camera"></i> ภาพกิจกรรม</h5>

                    <div class="mb-3">
                        <label for="photos" class="form-label">อัปโหลดรูปภาพกิจกรรม</label>
                        <input type="file" class="form-control" id="photos" name="photos[]" multiple accept="image/*">
                    </div>

                    @if($log && !empty($log->activity_photos))
                    <div class="mb-3">
                        <label class="form-label">รูปภาพปัจจุบัน:</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($log->activity_photos as $photo)
                                <div class="border p-1 rounded">
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Activity Photo" style="height: 100px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                        <a href="{{ route('daily_logs.index', ['date' => $date]) }}" class="btn btn-secondary">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
