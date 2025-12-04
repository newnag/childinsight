@extends('layouts.app')

@section('title', 'เพิ่มบันทึกสุขภาพ')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                บันทึกใหม่สำหรับ: <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('health.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    
                    <div class="mb-3">
                        <label for="recorded_at" class="form-label">วันที่</label>
                        <input type="date" class="form-control" id="recorded_at" name="recorded_at" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="weight" class="form-label">น้ำหนัก (กก.)</label>
                            <input type="number" step="0.1" class="form-control" id="weight" name="weight" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="height" class="form-label">ส่วนสูง (ซม.)</label>
                            <input type="number" step="0.1" class="form-control" id="height" name="height" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="illness" class="form-label">อาการเจ็บป่วยปัจจุบัน / อาการ</label>
                        <textarea class="form-control" id="illness" name="illness" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="health_constraints" class="form-label">ข้อจำกัดทางสุขภาพ / ภูมิแพ้</label>
                        <textarea class="form-control" id="health_constraints" name="health_constraints" rows="2"></textarea>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">การประเมินพัฒนาการ</h5>

                    <div class="mb-3">
                        <label for="physical_desc" class="form-label">พัฒนาการทางร่างกาย</label>
                        <textarea class="form-control" id="physical_desc" name="physical_desc" rows="2" placeholder="เช่น การวิ่ง, การกระโดด, ทักษะกล้ามเนื้อมัดเล็ก..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="emotional_desc" class="form-label">พัฒนาการทางอารมณ์</label>
                        <textarea class="form-control" id="emotional_desc" name="emotional_desc" rows="2" placeholder="เช่น ความมั่นคงทางอารมณ์, การแสดงออกทางความรู้สึก..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="behavior_desc" class="form-label">พฤติกรรม</label>
                        <textarea class="form-control" id="behavior_desc" name="behavior_desc" rows="2" placeholder="เช่น การปฏิสัมพันธ์ทางสังคม, การปฏิบัติตามกฎ..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="learning_desc" class="form-label">การเรียนรู้และทักษะ</label>
                        <textarea class="form-control" id="learning_desc" name="learning_desc" rows="2" placeholder="เช่น การแก้ปัญหา, ทักษะทางภาษา..."></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                        <a href="{{ route('health.index') }}" class="btn btn-secondary">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
