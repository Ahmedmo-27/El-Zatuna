@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
    @php
        $returnTo = request()->get('return_to');
    @endphp

    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header flex-column align-items-start">
                            <h4>{{ trans('admin/main.user_name') }}: <span class="text-black-50">{{ $contact->name }}</span></h4>
                            <h4>{{ trans('admin/main.email') }} : <span class="text-black-50">{{ $contact->email }}</span></h4>
                            <h4>{{ trans('admin/main.phone') }} : <span class="text-black-50">{{ $contact->phone }}</span></h4>
                            <h4>Type : <span class="text-black-50">{{ ($contact->contact_type ?? 'message') === 'request_course' ? 'Course Request' : 'Message' }}</span></h4>

                            @if(($contact->contact_type ?? 'message') === 'request_course')
                                @php
                                    $yearSuffixes = [1 => 'st', 2 => 'nd', 3 => 'rd', 4 => 'th', 5 => 'th'];
                                    $yearLabel = !empty($contact->study_year) ? $contact->study_year . ($yearSuffixes[$contact->study_year] ?? 'th') . ' Year' : '-';
                                @endphp

                                <h4>University : <span class="text-black-50">{{ $contact->university_name ?? '-' }}</span></h4>
                                <h4>College : <span class="text-black-50">{{ $contact->college_name ?? '-' }}</span></h4>
                                <h4>Field : <span class="text-black-50">{{ $contact->study_field ?? '-' }}</span></h4>
                                <h4>Course Name : <span class="text-black-50">{{ $contact->course_name ?? '-' }}</span></h4>
                                <h4>Study Year : <span class="text-black-50">{{ $yearLabel }}</span></h4>
                                <h4>Can Provide Materials : <span class="text-black-50">{{ ($contact->can_provide_materials ?? null) === 'yes' ? 'Yes' : ((($contact->can_provide_materials ?? null) === 'no') ? 'No' : '-') }}</span></h4>
                            @endif

                            <h4>{{ trans('site.message') }} :</h4>
                            <p class="mt-2">{{ nl2br($contact->message) }}</p>
                        </div>

                        <div class="card-body ">
                            <form action="{{ getAdminPanelUrl() }}/contacts/{{ $contact->id }}/reply{{ !empty($returnTo) ? '?return_to='.$returnTo : '' }}" method="post">
                                {{ csrf_field() }}

                                <div class="form-group mt-15">
                                    <label class="input-label">{{ trans('admin/main.reply_comment') }}</label>
                                    <textarea id="summernote" name="reply" class="summernote form-control @error('reply')  is-invalid @enderror">{!! !empty($contact->reply) ? $contact->reply : old('reply')  !!}</textarea>

                                    @error('reply')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <button type="submit" class="mt-3 btn btn-primary">{{ trans('admin/main.save_change') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
@endpush
