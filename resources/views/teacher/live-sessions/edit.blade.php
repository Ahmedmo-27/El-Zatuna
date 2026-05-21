@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Edit Live Session</h1>
        </div>

        <div class="section-body">
            <form method="POST" action="{{ route('teacher.live_sessions.update', $session->id) }}" class="bg-white p-4 rounded shadow-sm">
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
                        <label>University ID</label>
                        <input type="number" name="university_id" value="{{ old('university_id', $session->university_id) }}" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Faculty ID</label>
                        <input type="number" name="faculty_id" value="{{ old('faculty_id', $session->faculty_id) }}" class="form-control" required>
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
                    <input type="url" name="provider_url" value="{{ old('provider_url') }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Instructions</label>
                    <textarea name="instructions" class="form-control" rows="3">{{ old('instructions', $session->instructions) }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Session</button>
            </form>
        </div>
    </section>
@endsection