@extends('layouts.app')

@section('title', 'เพิ่มศูนย์เด็กเล็ก')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">เพิ่มศูนย์เด็กเล็กใหม่</h3>
    </div>
    <form action="{{ route('centers.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">ชื่อศูนย์</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="code" class="form-label">รหัสศูนย์</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                    @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="district" class="form-label">อำเภอ/เขต</label>
                    <input type="text" class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district') }}">
                    @error('district')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="province" class="form-label">จังหวัด</label>
                    <input type="text" class="form-control @error('province') is-invalid @enderror" id="province" name="province" value="{{ old('province') }}">
                    @error('province')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">ที่อยู่</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">สถานะ</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>ใช้งาน</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                </select>
                @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
            <a href="{{ route('centers.index') }}" class="btn btn-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection
