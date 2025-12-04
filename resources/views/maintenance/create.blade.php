@extends('layouts.app')

@section('title', 'แจ้งซ่อมบำรุงใหม่')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">แจ้งปัญหา</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('maintenance.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="title" class="form-label">หัวข้อ / ปัญหา</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">รายละเอียด</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">ความสำคัญ</label>
                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                    <option value="low">ต่ำ</option>
                    <option value="medium" selected>ปานกลาง</option>
                    <option value="high">สูง</option>
                </select>
                @error('priority')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">รูปภาพ (ไม่บังคับ)</label>
                <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">ส่งคำร้อง</button>
            <a href="{{ route('maintenance.index') }}" class="btn btn-secondary">ยกเลิก</a>
        </form>
    </div>
</div>
@endsection
