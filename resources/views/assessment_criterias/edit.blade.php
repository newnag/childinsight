@extends('layouts.app')

@section('title', 'Edit Assessment Criteria')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Criteria</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('assessment_criterias.update', $assessmentCriteria->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $assessmentCriteria->category) }}" required>
                @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="topic" class="form-label">Topic / Question</label>
                <input type="text" class="form-control @error('topic') is-invalid @enderror" id="topic" name="topic" value="{{ old('topic', $assessmentCriteria->topic) }}" required>
                @error('topic')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="max_score" class="form-label">Max Score</label>
                <input type="number" class="form-control @error('max_score') is-invalid @enderror" id="max_score" name="max_score" value="{{ old('max_score', $assessmentCriteria->max_score) }}" min="1" required>
                @error('max_score')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('assessment_criterias.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
