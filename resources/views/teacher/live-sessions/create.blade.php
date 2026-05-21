@extends('design_1.web.layouts.app', ['appFooter' => false])

@php
    $pageTitle = 'Create Live Session';
@endphp

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("create-course") }}">
@endpush

@section('content')
    <div class="container mt-80 pb-100">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="d-flex align-items-start align-items-md-center mb-24">
                    <div class="d-flex-center size-56 rounded-20 bg-primary bg-opacity-10 text-primary mr-16 flex-shrink-0">
                        <span class="font-20 font-weight-bold">LS</span>
                    </div>

                    <div>
                        <h1 class="font-32 font-weight-bold mb-4">Create Live Session</h1>
                        <p class="text-gray-500 mb-0">Set the session scope, schedule, and meeting details for your students.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('teacher.live_sessions.store') }}" class="bg-white p-24 p-lg-32 rounded-24 shadow-sm">
                    @csrf

                    <div class="d-flex align-items-center justify-content-between flex-wrap border-bottom-gray-100 pb-16 mb-24">
                        <div>
                            <h3 class="font-18 font-weight-bold mb-4">Session Details</h3>
                            <p class="text-gray-500 font-14 mb-0">Use clear labels so the session is easy to find later.</p>
                        </div>

                        <div class="mt-12 mt-md-0 px-14 py-8 rounded-16 bg-gray-100 text-gray-500 font-12">
                            Drafts can be updated before publishing.
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="form-control form-control-lg" placeholder="Example: Pharmacology Revision Session" required>
                    </div>

                    <div class="form-group bg-white-editor">
                        <label class="form-group-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Add a short summary of what the session covers">{{ old('description') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="form-group-label">University</label>
                                <select name="university_id" class="form-control form-control-lg">
                                    <option value="" {{ empty(old('university_id')) ? 'selected' : '' }}>All universities</option>
                                    @foreach($universities as $university)
                                        <option value="{{ $university->id }}" {{ old('university_id') == $university->id ? 'selected' : '' }}>{{ $university->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="form-group-label">Faculty</label>
                                <select name="faculty_id" class="form-control form-control-lg">
                                    <option value="" {{ empty(old('faculty_id')) ? 'selected' : '' }}>All faculties</option>
                                    @foreach($faculties as $faculty)
                                        <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="form-group-label">Start At</label>
                                <input type="datetime-local" name="start_at" value="{{ old('start_at') }}" class="form-control form-control-lg" required>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="form-group-label">End At</label>
                                <input type="datetime-local" name="end_at" value="{{ old('end_at') }}" class="form-control form-control-lg" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <label class="form-group-label">Price</label>
                                <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="form-control form-control-lg" placeholder="0.00" required>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <label class="form-group-label">Max Students</label>
                                <input type="number" name="max_students" value="{{ old('max_students') }}" class="form-control form-control-lg" placeholder="50" required>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <label class="form-group-label">Provider Type</label>
                                <select name="provider_type" class="form-control form-control-lg" required>
                                    <option value="manual_zoom">Manual Zoom</option>
                                    <option value="manual_meet">Manual Meet</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label">Join Link</label>
                        <input type="url" name="provider_url" value="{{ old('provider_url') }}" class="form-control form-control-lg" placeholder="https://..." required>
                    </div>

                    <div class="form-group bg-white-editor">
                        <label class="form-group-label">Instructions</label>
                        <textarea name="instructions" class="form-control" rows="4" placeholder="Add notes or access instructions for students">{{ old('instructions') }}</textarea>
                    </div>

                    <div class="d-flex align-items-center justify-content-between flex-column flex-md-row border-top-gray-100 pt-16 mt-24">
                        <div class="d-flex align-items-center mb-16 mb-md-0">
                            <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                                <span class="font-16 font-weight-bold text-gray-500">i</span>
                            </div>
                            <div class="ml-8">
                                <h5 class="font-14 mb-2">Ready to save</h5>
                                <p class="mt-0 font-12 text-gray-500 mb-0">You can save as a draft and publish later.</p>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg px-24">Save Draft</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection