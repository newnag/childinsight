@extends('layouts.app')

@section('title', 'เพิ่มนักเรียน')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">เพิ่มนักเรียนใหม่</h3>
    </div>
    <form action="{{ route('students.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @if(!Auth::user()->center_id)
            <div class="mb-3">
                <label for="center_id" class="form-label">ศูนย์เด็กเล็ก</label>
                <select name="center_id" id="center_id" class="form-select @error('center_id') is-invalid @enderror" required>
                    <option value="">เลือกศูนย์เด็กเล็ก</option>
                    @foreach($centers as $center)
                    <option value="{{ $center->id }}" {{ old('center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                    @endforeach
                </select>
                @error('center_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">ชื่อจริง</label>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                    @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">นามสกุล</label>
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                    @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">เพศ</label>
                    <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                        <option value="">เลือกเพศ</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ชาย</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>หญิง</option>
                    </select>
                    @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="dob" class="form-label">วันเกิด</label>
                    <input type="date" class="form-control @error('dob') is-invalid @enderror" id="dob" name="dob" value="{{ old('dob') }}" required>
                    @error('dob')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="parent_name" class="form-label">ชื่อผู้ปกครอง</label>
                    <input type="text" class="form-control @error('parent_name') is-invalid @enderror" id="parent_name" name="parent_name" value="{{ old('parent_name') }}">
                    @error('parent_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="parent_contact" class="form-label">เบอร์โทรศัพท์ผู้ปกครอง</label>
                    <input type="text" class="form-control @error('parent_contact') is-invalid @enderror" id="parent_contact" name="parent_contact" value="{{ old('parent_contact') }}">
                    @error('parent_contact')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection
