@extends('layouts.app')

@section('title', 'Edit Center')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Center</h3>
    </div>
    <form action="{{ route('centers.update', $center->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Center Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $center->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="code" class="form-label">Center Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $center->code) }}" required>
                    @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="district" class="form-label">District</label>
                    <input type="text" class="form-control @error('district') is-invalid @enderror" id="district" name="district" value="{{ old('district', $center->district) }}">
                    @error('district')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="province" class="form-label">Province</label>
                    <input type="text" class="form-control @error('province') is-invalid @enderror" id="province" name="province" value="{{ old('province', $center->province) }}">
                    @error('province')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $center->address) }}</textarea>
                @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="active" {{ old('status', $center->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $center->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Center</button>
            <a href="{{ route('centers.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
