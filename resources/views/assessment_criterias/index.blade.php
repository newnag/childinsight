@extends('layouts.app')

@section('title', 'จัดการเกณฑ์การประเมิน')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">เกณฑ์การประเมิน</h3>
        <button type="button" class="btn btn-primary btn-sm" onclick="addRow()">
            <i class="bi bi-plus-lg"></i> เพิ่มเกณฑ์
        </button>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('assessment_criterias.bulk_update') }}" method="POST" id="criteriaForm">
            @csrf
            <div class="table-responsive">
                <table class="table table-bordered" id="criteriaTable">
                    <thead>
                        <tr>
                            <th style="width: 30%">หมวดหมู่</th>
                            <th style="width: 50%">หัวข้อ / คำถาม</th>
                            <th style="width: 10%">คะแนนเต็ม</th>
                            <th style="width: 10%">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $categories = [
                                'ด้านความสะอาด' => 'ด้านความสะอาด',
                                'ด้านการดูแลเด็ก' => 'ด้านการดูแลเด็ก',
                                'ด้านเอกสารและการจัดการ' => 'ด้านเอกสารและการจัดการ',
                                'ด้านนวัตกรรมการเรียนการสอน' => 'ด้านนวัตกรรมการเรียนการสอน',
                                'อื่นๆ' => 'อื่นๆ'
                            ];
                        @endphp
                        @foreach($criterias as $index => $criteria)
                        <tr>
                            <input type="hidden" name="criterias[{{ $index }}][id]" value="{{ $criteria->id }}">
                            <td>
                                <select class="form-select" name="criterias[{{ $index }}][category]" required>
                                    <option value="" disabled>เลือกหมวดหมู่</option>
                                    @foreach($categories as $value => $label)
                                        <option value="{{ $value }}" {{ $criteria->category == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="criterias[{{ $index }}][topic]" value="{{ $criteria->topic }}" required placeholder="เช่น พื้นสะอาดหรือไม่?">
                            </td>
                            <td>
                                <input type="number" class="form-control" name="criterias[{{ $index }}][max_score]" value="{{ $criteria->max_score }}" min="1" required>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> บันทึกการเปลี่ยนแปลง
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let rowIndex = {{ count($criterias) }};

    function addRow() {
        const table = document.getElementById('criteriaTable').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        
        newRow.innerHTML = `
            <input type="hidden" name="criterias[${rowIndex}][id]" value="">
            <td>
                <select class="form-select" name="criterias[${rowIndex}][category]" required>
                    <option value="" disabled selected>เลือกหมวดหมู่</option>
                    <option value="ด้านความสะอาด">ด้านความสะอาด</option>
                    <option value="ด้านการดูแลเด็ก">ด้านการดูแลเด็ก</option>
                    <option value="ด้านเอกสารและการจัดการ">ด้านเอกสารและการจัดการ</option>
                    <option value="ด้านนวัตกรรมการเรียนการสอน">ด้านนวัตกรรมการเรียนการสอน</option>
                    <option value="อื่นๆ">อื่นๆ</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control" name="criterias[${rowIndex}][topic]" required placeholder="เช่น พื้นสะอาดหรือไม่?">
            </td>
            <td>
                <input type="number" class="form-control" name="criterias[${rowIndex}][max_score]" value="3" min="1" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        
        rowIndex++;
    }

    function removeRow(button) {
        const row = button.closest('tr');
        row.remove();
    }
</script>
@endsection
