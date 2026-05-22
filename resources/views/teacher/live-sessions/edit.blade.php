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
                <label class="form-group-label">Title</label>
                <input type="text" name="title" value="{{ old('title', $session->title) }}" class="form-control form-control-lg" required>
            </div>
            <div class="form-group">
                <label class="form-group-label">Description</label>
                <textarea name="description" class="form-control" rows="4">{{ old('description', $session->description) }}</textarea>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label class="form-group-label">University</label>
                        <select name="university_id" id="university_id" class="form-control form-control-lg" required>
                            <option value="" {{ empty(old('university_id', $session->university_id)) ? 'selected' : '' }}>Select university</option>
                            @foreach($universities as $university)
                                <option value="{{ $university->id }}" {{ old('university_id', $session->university_id) == $university->id ? 'selected' : '' }}>{{ $university->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label class="form-group-label">Faculty</label>
                        <select name="faculty_id" id="faculty_id" class="form-control form-control-lg" required disabled>
                            <option value="" {{ empty(old('faculty_id', $session->faculty_id)) ? 'selected' : '' }}>Select university first</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}" data-university-id="{{ $faculty->university_id }}" {{ old('faculty_id', $session->faculty_id) == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label class="form-group-label">Start At</label>
                        <input type="datetime-local" name="start_at" value="{{ old('start_at', optional($session->start_at)->format('Y-m-d\TH:i')) }}" class="form-control form-control-lg" required>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label class="form-group-label">End At</label>
                        <input type="datetime-local" name="end_at" value="{{ old('end_at', optional($session->end_at)->format('Y-m-d\TH:i')) }}" class="form-control form-control-lg" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Price</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $session->price) }}" class="form-control form-control-lg" required>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Max Students</label>
                        <input type="number" name="max_students" value="{{ old('max_students', $session->max_students) }}" class="form-control form-control-lg" required>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Provider Type</label>
                        <select name="provider_type" class="form-control form-control-lg" required>
                            <option value="manual_zoom" {{ old('provider_type', $session->provider) === 'manual_zoom' ? 'selected' : '' }}>Manual Zoom</option>
                            <option value="manual_meet" {{ old('provider_type', $session->provider) === 'manual_meet' ? 'selected' : '' }}>Manual Meet</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-group-label">Join Link</label>
                <input type="url" name="provider_url" value="{{ old('provider_url', $session->provider_url) }}" class="form-control form-control-lg" required>
            </div>
            <div class="form-group">
                <label class="form-group-label">Instructions</label>
                <textarea name="instructions" class="form-control" rows="3">{{ old('instructions', $session->instructions) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-lg px-24">Update Session</button>
        </form>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        (function ($) {
            'use strict';

            const $university = $('#university_id');
            const $faculty = $('#faculty_id');
            const allFacultyOptions = $faculty.find('option').clone();

            function resetFacultyOptions() {
                const universityId = $university.val();

                $faculty.empty();

                if (!universityId) {
                    $faculty.append('<option value="">Select university first</option>');
                    $faculty.prop('disabled', true);
                    return;
                }

                $faculty.append('<option value="">Select faculty</option>');

                allFacultyOptions.each(function () {
                    const option = $(this);
                    const facultyUniversityId = option.data('university-id');

                    if (!option.val()) {
                        return;
                    }

                    if (String(facultyUniversityId) === String(universityId)) {
                        $faculty.append(option.clone());
                    }
                });

                $faculty.prop('disabled', false);
            }

            $university.on('change', function () {
                $faculty.val('');
                resetFacultyOptions();
            });

            resetFacultyOptions();
        })(jQuery);
    </script>
@endpush