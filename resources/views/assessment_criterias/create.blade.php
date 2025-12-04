@extends('layouts.app')

@section('title', 'Add Assessment Criteria')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">New Criteria</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('assessment_criterias.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" placeholder="e.g. Cleanliness, Safety" required>
                @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="topic" class="form-label">Topic / Question</label>
                <input type="text" class="form-control @error('topic') is-invalid @enderror" id="topic" name="topic" value="{{ old('topic') }}" placeholder="e.g. Is the floor clean?" required>
                @error('topic')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="max_score" class="form-label">Max Score</label>
                <input type="number" class="form-control @error('max_score') is-invalid @enderror" id="max_score" name="max_score" value="{{ old('max_score', 3) }}" min="1" required>
                @error('max_score')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('assessment_criterias.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
