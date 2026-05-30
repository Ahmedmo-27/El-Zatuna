<style>
    .js-custom-modal .course-report-modal .course-report-icon {
        width: 96px;
        height: 96px;
        border-radius: 28px;
        border: 0;
        background: linear-gradient(145deg, #ffb127 0%, #f29200 100%);
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.35), 0 12px 24px rgba(242, 146, 0, 0.22);
    }

    .js-custom-modal .course-report-modal .form-group-label {
        font-size: 13px;
        font-weight: 600;
        color: #2c3346;
    }

    .js-custom-modal .course-report-modal .form-control {
        min-height: 48px;
        border-radius: 14px;
        border-color: #dfe5ec;
        background-color: #fff;
    }

    .js-custom-modal .course-report-modal textarea.form-control {
        min-height: 136px;
        padding-top: 12px;
    }

    .js-custom-modal .course-report-modal .select2-container {
        width: 100% !important;
    }

    .js-custom-modal .course-report-modal .select2-container--default .select2-selection--single {
        min-height: 48px;
        border-radius: 14px;
        border-color: #dfe5ec;
        display: flex;
        align-items: center;
    }

    .js-custom-modal .course-report-modal .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: normal;
        padding-left: 14px;
        padding-right: 28px;
    }

    .js-custom-modal .course-report-modal-footer {
        display: flex;
        justify-content: flex-end;
    }

    .js-custom-modal .course-report-submit-btn {
        min-width: 132px;
        min-height: 44px;
        padding: 10px 18px;
        border-radius: 12px;
        border: 1px solid #072923;
        background: #072923;
        color: #faffe0;
        font-weight: 600;
        box-shadow: 0 6px 16px rgba(7, 41, 35, 0.2);
        transition: all 0.2s ease;
    }

    .js-custom-modal .course-report-submit-btn:hover,
    .js-custom-modal .course-report-submit-btn:focus,
    .js-custom-modal .course-report-submit-btn:active {
        background: #0e4d42;
        border-color: #0e4d42;
        color: #faffe0;
        transform: translateY(-1px);
    }
</style>

<div class="course-report-modal">
    <div class="d-flex-center flex-column text-center mt-16">
        <div class="course-report-icon d-flex-center">
            <x-iconsax-bul-danger class="icons text-white" width="32px" height="32px"/>
        </div>

        <h6 class="font-12 font-weight-bold mt-12">{{ trans('update.report_abuse') }}</h6>
        <p class="mt-4 font-12 text-gray-500">{{ trans('update.report_course_modal_hint') }}</p>
    </div>

    <form action="/upcoming_courses/{{ $upcomingCourse->id }}/report" method="post" class="js-course-report-form mt-24">

        <div class="form-group">
            <label class="form-group-label">{{ trans('product.reason') }}</label>
            <select id="reason" name="reason" class="js-ajax-reason form-control select2" data-width="100%">
                <option value="" selected disabled>{{ trans('product.select_reason') }}</option>

                @foreach(getReportReasons() as $reason)
                    <option value="{{ $reason }}">{{ $reason }}</option>
                @endforeach
            </select>

            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group">
            <label class="form-group-label" for="message_to_reviewer">{{ trans('public.message_to_reviewer') }}</label>
            <textarea name="message" id="message_to_reviewer" class="js-ajax-message form-control" rows="8"></textarea>
            <div class="invalid-feedback"></div>
        </div>
    </form>
</div>
