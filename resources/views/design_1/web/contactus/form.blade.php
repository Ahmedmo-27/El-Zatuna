<h5 class="font-16 font-weight-bold contact-page-title">{{ trans('update.have_a_question?') }} 👋</h5>
<h1 class="font-32 font-weight-bold mt-4 contact-page-subtitle">{{ trans('update.contact_our_team') }}</h1>
<p class="font-14 mt-8 contact-page-help">
    support@elzatuna.com
</p>

@php
    $defaultContactType = old('contact_type', $selectedContactType ?? 'message');
@endphp

<form action="/contact/store" method="post" class="contact-page-form mt-20">
    {{ csrf_field() }}

    <input type="hidden" name="contact_type" id="contactTypeInput" value="{{ $defaultContactType }}">

    <div class="d-flex flex-wrap align-items-center mb-20" style="gap: 12px;">
        <button type="button" class="btn btn-sm contact-type-btn {{ $defaultContactType === 'message' ? 'active' : '' }}" data-contact-type="message">Send Message</button>
        <button type="button" class="btn btn-sm contact-type-btn {{ $defaultContactType === 'request_course' ? 'active' : '' }}" data-contact-type="request_course">Request Course</button>
    </div>

    <div class="form-group mt-28">
        <label class="form-group-label">{{ trans('site.your_name') }}</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control contact-page-control @error('name')  is-invalid @enderror"/>
        @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label class="form-group-label">{{ trans('public.email') }}</label>
                <input type="text" name="email" value="{{ old('email') }}" class="form-control contact-page-control @error('email')  is-invalid @enderror"/>
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="form-group">
                <label class="form-group-label">{{ trans('site.phone_number') }}</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control contact-page-control @error('phone')  is-invalid @enderror"/>
                @error('phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-group-label">{{ trans('site.message') }}</label>
        <textarea name="message" id="" rows="7" class="form-control contact-page-control @error('message')  is-invalid @enderror">{{ old('message') }}</textarea>
        @error('message')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div id="requestCourseFields" class="{{ $defaultContactType === 'request_course' ? '' : 'd-none' }}">
        <p class="font-14 mt-4 mb-16 contact-page-help">
            Use this option to request a course that is not currently available on the website.
        </p>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label class="form-group-label">University Name</label>
                    <input type="text" name="university_name" value="{{ old('university_name') }}" class="form-control contact-page-control @error('university_name')  is-invalid @enderror"/>
                    @error('university_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label class="form-group-label">College Name</label>
                    <input type="text" name="college_name" value="{{ old('college_name') }}" class="form-control contact-page-control @error('college_name')  is-invalid @enderror"/>
                    @error('college_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label class="form-group-label">Field of Study</label>
                    <input type="text" name="study_field" value="{{ old('study_field') }}" placeholder="e.g. Computer Science, Business" class="form-control contact-page-control @error('study_field')  is-invalid @enderror"/>
                    @error('study_field')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label class="form-group-label">Course Name</label>
                    <input type="text" name="course_name" value="{{ old('course_name') }}" class="form-control contact-page-control @error('course_name')  is-invalid @enderror"/>
                    @error('course_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="form-group-label">Study Year</label>
            <select name="study_year" class="form-control contact-page-control @error('study_year')  is-invalid @enderror">
                <option value="">Select year</option>
                <option value="1" @selected(old('study_year') == 1)>1st Year</option>
                <option value="2" @selected(old('study_year') == 2)>2nd Year</option>
                <option value="3" @selected(old('study_year') == 3)>3rd Year</option>
                <option value="4" @selected(old('study_year') == 4)>4th Year</option>
                <option value="5" @selected(old('study_year') == 5)>5th Year</option>
            </select>
            @error('study_year')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-group-label">Can you provide materials for this course?</label>
            <select name="can_provide_materials" class="form-control contact-page-control @error('can_provide_materials')  is-invalid @enderror">
                <option value="">Select option</option>
                <option value="yes" @selected(old('can_provide_materials') === 'yes')>Yes</option>
                <option value="no" @selected(old('can_provide_materials') === 'no')>No</option>
            </select>
            @error('can_provide_materials')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>

    @include('design_1.web.includes.captcha_input')

    <button type="submit" id="contactSubmitButton" class="btn btn-lg btn-block mt-20 contact-page-send-btn">🚀 {{ $defaultContactType === 'request_course' ? 'Request Course' : trans('site.send_message') }}</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.querySelector('.contact-page-form');
    const typeButtons = document.querySelectorAll('.contact-type-btn');
    const typeInput = document.getElementById('contactTypeInput');
    const requestFields = document.getElementById('requestCourseFields');
    const submitButton = document.getElementById('contactSubmitButton');

    function applyType(type) {
        if (!typeInput || !requestFields || !submitButton) {
            return;
        }

        typeInput.value = type;

        typeButtons.forEach((btn) => {
            if (btn.getAttribute('data-contact-type') === type) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        const isRequestCourse = type === 'request_course';
        requestFields.classList.toggle('d-none', !isRequestCourse);
        submitButton.innerHTML = isRequestCourse ? '🚀 Request Course' : '🚀 {{ trans('site.send_message') }}';
    }

    typeButtons.forEach((btn) => {
        btn.addEventListener('click', function() {
            applyType(btn.getAttribute('data-contact-type'));
        });
    });

    @if(session()->has('toast') && (session('toast.status') ?? null) === 'success')
        if (contactForm) {
            contactForm.reset();
        }
    @endif

    applyType(typeInput ? typeInput.value : 'message');
});
</script>
