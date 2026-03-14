<div class="contact-page-info-inner d-flex flex-column w-100 h-100">
    <p class="contact-info-kicker mb-0">{{ trans('update.have_a_question?') }}</p>
    <h3 class="contact-info-heading">{{ trans('update.contact_our_team') }}</h3>

    <div>
        {{-- Contact Numbers --}}
        <div class="d-flex align-items-start contact-info-item">
            <div class="contact-info-icon-badge d-flex-center mr-10">
                <x-iconsax-bul-call class="icons contact-info-icon" width="20px" height="20px"/>
            </div>
            <div class="ml-8 mt-4">
                <h4 class="font-18 font-weight-bold contact-info-label">{{ trans('update.contact_numbers') }}</h4>

                @if(!empty($contactSettings['phones']))
                    @php
                        $contactNumbers = explode(',', $contactSettings['phones']);
                    @endphp

                    @if(!empty($contactNumbers) and is_array($contactNumbers))
                        @foreach($contactNumbers as $contactNumber)
                            <p class="font-14 contact-info-value mt-8">{{ $contactNumber }}</p>
                        @endforeach
                    @endif
                @else
                    <p class="font-14 contact-info-value mt-8">{{ trans('site.not_defined') }}</p>
                @endif
            </div>
        </div>

        {{-- Email --}}
        <div class="d-flex align-items-start contact-info-item">
            <div class="contact-info-icon-badge d-flex-center mr-10">
                <x-iconsax-bul-sms class="icons contact-info-icon" width="20px" height="20px"/>
            </div>
            <div class="ml-8 mt-4">
                <h4 class="font-18 font-weight-bold contact-info-label">{{ trans('public.email') }}</h4>

                @php
                    $contactEmails = [];

                    if (!empty($contactSettings['emails'])) {
                        $contactEmails = explode(',', $contactSettings['emails']);
                    }

                    $contactEmails[] = 'support@elzatuna.com';

                    $contactEmails = array_values(array_unique(array_filter(array_map(function ($email) {
                        return trim($email);
                    }, $contactEmails))));
                @endphp

                @foreach($contactEmails as $contactEmail)
                    <a href="mailto:{{ $contactEmail }}" class="d-block font-14 contact-info-value mt-8">{{ $contactEmail }}</a>
                @endforeach
            </div>
        </div>
    </div>

</div>
