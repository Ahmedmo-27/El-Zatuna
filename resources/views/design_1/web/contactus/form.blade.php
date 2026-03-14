<h5 class="font-16 font-weight-bold contact-page-title">{{ trans('update.have_a_question?') }} 👋</h5>
<h1 class="font-32 font-weight-bold mt-4 contact-page-subtitle">{{ trans('update.contact_our_team') }}</h1>
<p class="font-14 mt-8 contact-page-help">
    support@elzatuna.com
</p>

<form action="/contact/store" method="post" class="contact-page-form mt-20">
    {{ csrf_field() }}

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
        <label class="form-group-label">{{ trans('site.subject') }}</label>
        <input type="text" name="subject" value="{{ old('subject') }}" class="form-control contact-page-control @error('subject')  is-invalid @enderror"/>
        @error('subject')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
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

    @include('design_1.web.includes.captcha_input')

    <button type="submit" class="btn btn-lg btn-block mt-20 contact-page-send-btn">🚀 {{ trans('site.send_message') }}</button>
</form>
