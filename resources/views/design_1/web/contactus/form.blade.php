<h5 class="font-16 font-weight-bold" style="color: #FAFFE0 !important;">{{ trans('update.have_a_question?') }} 👋</h5>
<h1 class="font-24 font-weight-bold mt-4" style="color: #FAFFE0 !important;">{{ trans('update.contact_our_team') }}</h1>

<form action="/contact/store" method="post" class="mt-20">
    {{ csrf_field() }}

    <div class="form-group mt-28">
        <label class="form-group-label" style="color: #000000 !important; font-weight: bold;">{{ trans('site.your_name') }}</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name')  is-invalid @enderror" style="background-color: #FAFFE0 !important; color: #000000 !important; border: none !important; padding: 16px !important; border-radius: 8px !important;"/>
        @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label class="form-group-label" style="color: #000000 !important; font-weight: bold;">{{ trans('public.email') }}</label>
                <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email')  is-invalid @enderror" style="background-color: #FAFFE0 !important; color: #000000 !important; border: none !important; padding: 16px !important; border-radius: 8px !important;"/>
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="form-group">
                <label class="form-group-label" style="color: #000000 !important; font-weight: bold;">{{ trans('site.phone_number') }}</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone')  is-invalid @enderror" style="background-color: #FAFFE0 !important; color: #000000 !important; border: none !important; padding: 16px !important; border-radius: 8px !important;"/>
                @error('phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-group-label" style="color: #000000 !important; font-weight: bold;">{{ trans('site.subject') }}</label>
        <input type="text" name="subject" value="{{ old('subject') }}" class="form-control @error('subject')  is-invalid @enderror" style="background-color: #FAFFE0 !important; color: #000000 !important; border: none !important; padding: 16px !important; border-radius: 8px !important;"/>
        @error('subject')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-group-label" style="color: #000000 !important; font-weight: bold;">{{ trans('site.message') }}</label>
        <textarea name="message" id="" rows="10" class="form-control @error('message')  is-invalid @enderror" style="background-color: #FAFFE0 !important; color: #000000 !important; border: none !important; padding: 16px !important; border-radius: 8px !important;">{{ old('message') }}</textarea>
        @error('message')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    @include('design_1.web.includes.captcha_input')

    <button type="submit" class="btn btn-lg btn-block mt-20" style="background-color: #C8CD06 !important; color: #072923 !important; font-weight: bold; border-radius: 50px; padding: 16px 32px;">🚀 {{ trans('site.send_message') }}</button>
</form>
