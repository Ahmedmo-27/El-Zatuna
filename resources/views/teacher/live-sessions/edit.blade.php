@extends('design_1.web.layouts.app', ['appFooter' => false])

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("create-course") }}">
@endpush

@section('content')
    <div class="container mt-80 pb-100">
        <form method="POST" action="{{ route('teacher.live_sessions.update', $session->id) }}" class="bg-white p-24 rounded-24 shadow-sm">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="{{ old('title', $session->title) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $session->description) }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>University</label>
                    <select name="university_id" class="form-control" required>
                        <option value="">Select university</option>
                        @foreach($universities as $university)
                            <option value="{{ $university->id }}" {{ old('university_id', $session->university_id) == $university->id ? 'selected' : '' }}>{{ $university->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Faculty</label>
                    <select name="faculty_id" class="form-control" required>
                        <option value="">Select faculty</option>
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ old('faculty_id', $session->faculty_id) == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Start At</label>
                    <input type="datetime-local" name="start_at" value="{{ old('start_at', optional($session->start_at)->format('Y-m-d\TH:i')) }}" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label>End At</label>
                    <input type="datetime-local" name="end_at" value="{{ old('end_at', optional($session->end_at)->format('Y-m-d\TH:i')) }}" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $session->price) }}" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Max Students</label>
                    <input type="number" name="max_students" value="{{ old('max_students', $session->max_students) }}" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Provider Type</label>
                    <select name="provider_type" class="form-control" required>
                        <option value="manual_zoom" {{ old('provider_type', $session->provider) === 'manual_zoom' ? 'selected' : '' }}>Manual Zoom</option>
                        <option value="manual_meet" {{ old('provider_type', $session->provider) === 'manual_meet' ? 'selected' : '' }}>Manual Meet</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Join Link</label>
                <input type="url" name="provider_url" value="{{ old('provider_url', $session->provider_url) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Instructions</label>
                <textarea name="instructions" class="form-control" rows="3">{{ old('instructions', $session->instructions) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Session</button>
        </form>
    </div>
@endsection