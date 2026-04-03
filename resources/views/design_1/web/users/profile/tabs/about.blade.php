<div class="profile-overview-metrics d-flex align-items-center mt-16 border-gray-200 rounded-16">

    <div class="profile-overview-metric flex-1 px-12 py-16 p-lg-20">
        <div class="profile-overview-metric__icon">
            <x-iconsax-bol-teacher class="icons" width="42px" height="42px"/>
        </div>
        <div class="profile-overview-metric__label font-14 mt-8">{{ trans('quiz.student') }}</div>
        <div class="profile-overview-metric__value font-16 font-weight-bold mt-4">{{ $user->getTeacherStudentsCount() ?? '-' }}</div>
    </div>

    <div class="profile-overview-metric flex-1 px-12 py-16 p-lg-20 border-left-gray-200">
        <div class="profile-overview-metric__icon">
            <x-iconsax-lin-book class="icons" width="42px" height="42px"/>
        </div>
        <div class="profile-overview-metric__label font-14 mt-8">{{ trans('update.courses') }}</div>
        <div class="profile-overview-metric__value font-16 font-weight-bold mt-4">{{ $coursesCount ?? '-' }}</div>
    </div>

    {{-- Temporarily hidden to match the requested teacher profile overview.
    <div class="profile-overview-metric flex-1 px-12 py-16 p-lg-20 border-left-gray-200">
        <div class="profile-overview-metric__icon">
            <x-iconsax-bul-note-2 class="icons text-success" width="36px" height="36px"/>
        </div>
        <div class="profile-overview-metric__label font-14 mt-8">{{ trans('update.articles') }}</div>
        <div class="profile-overview-metric__value font-16 font-weight-bold mt-4">{{ $user->reviewsCount() ?? '-' }}</div>
    </div>

    <div class="profile-overview-metric flex-1 px-12 py-16 p-lg-20 border-left-gray-200">
        <div class="profile-overview-metric__icon">
            <x-iconsax-bul-calendar-2 class="icons text-warning" width="36px" height="36px"/>
        </div>
        <div class="profile-overview-metric__label font-14 mt-8">{{ trans('panel.meetings') }}</div>
        <div class="profile-overview-metric__value font-16 font-weight-bold mt-4">{{ $appointments ?? '-' }}</div>
    </div>
    --}}

</div>


<div class="row">
    @if(!empty($user->about))
        <div class="col-12 col-md-6 mt-24">
            {{-- Match course page "About This Course" structure exactly --}}
            <div class="profile-about-me-section px-24 pt-24 pb-28">
                <h2 class="font-16 font-weight-bold mb-16">{{ trans('update.about_me') }}</h2>
                <div class="profile-about-me-description text-gray-500">
                    {!! nl2br($user->about) !!}
                </div>
            </div>
        </div>
    @endif

    @if(!empty($user->profile_video))
        <div class="col-12 col-md-6 mt-24">
            @push('styles_top')
                <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
            @endpush

            @push('scripts_bottom')
                <script src="/assets/vendors/plyr.io/plyr.min.js"></script>
            @endpush

            <div class="profile-video-card">
                <video class="js-init-plyr-io plyr-io-video" controls preload="auto" width="100%">
                    <source src="{{ $user->getProfileVideoUrl() }}" type="video/mp4"/>
                </video>
            </div>
        </div>
    @endif

    @if(!empty($educations) and !$educations->isEmpty())
        <div class="col-12 col-md-6 mt-24">
            <h4 class="font-16 font-weight-bold">{{ trans('site.education') }}</h4>

            @foreach($educations as $education)
                <div class="profile-education-card d-flex align-items-center {{ $loop->first ? 'mt-12' : 'mt-16' }}">
                    <div class="d-flex-center profile-education-card__icon rounded-16">
                        <x-iconsax-bol-teacher class="icons" width="36px" height="36px"/>
                    </div>
                    <div class="ml-12 profile-education-card__value">{{ $education->value }}</div>
                </div>
            @endforeach
        </div>
    @endif

    @if(!empty($experiences) and !$experiences->isEmpty())
        <div class="col-12 col-md-6 mt-24">
            <h4 class="font-16 font-weight-bold">{{ trans('site.experiences') }}</h4>

            @foreach($experiences as $experience)
                <div class="profile-education-card d-flex align-items-center {{ $loop->first ? 'mt-12' : 'mt-16' }}">
                    <div class="d-flex-center profile-education-card__icon rounded-16">
                        <x-iconsax-bul-briefcase class="icons" width="36px" height="36px"/>
                    </div>
                    <div class="ml-12 profile-education-card__value">{{ $experience->value }}</div>
                </div>
            @endforeach
        </div>
    @endif

    @if(!empty($occupations) and !$occupations->isEmpty())
        <div class="col-12 mt-24">
            <h4 class="font-16 font-weight-bold">{{ trans('update.skills_&_interests') }}</h4>

            <div class="d-flex align-items-center mt-8 gap-12 flex-wrap">
                @foreach($occupations as $occupation)
                    <div class="bg-gray-100 p-10 rounded-8 font-12 text-gray-500">{{ $occupation->category->title }}</div>
                @endforeach
            </div>
        </div>
    @endif

    @php
        $profileLinksData = [];

        if (!empty($user->profile_links)) {
            $decodedProfileLinks = json_decode($user->profile_links, true);
            $profileLinksData = is_array($decodedProfileLinks) ? $decodedProfileLinks : [];
        }

        $knownProfileLinks = [
            ['key' => 'portfolio', 'title' => trans('update.portfolio'), 'type' => 'portfolio'],
            ['key' => 'website', 'title' => trans('update.website'), 'type' => 'website'],
            ['key' => 'linkedin', 'title' => trans('update.linkedin'), 'type' => 'linkedin'],
            ['key' => 'github', 'title' => trans('update.github'), 'type' => 'github'],
            ['key' => 'twitter', 'title' => trans('update.twitter'), 'type' => 'twitter'],
            ['key' => 'facebook', 'title' => trans('update.facebook'), 'type' => 'facebook'],
            ['key' => 'youtube', 'title' => trans('update.youtube'), 'type' => 'youtube'],
            ['key' => 'instagram', 'title' => trans('update.instagram'), 'type' => 'instagram'],
            ['key' => 'behance', 'title' => trans('update.behance'), 'type' => 'behance'],
            ['key' => 'dribbble', 'title' => trans('update.dribbble'), 'type' => 'dribbble'],
            ['key' => 'medium', 'title' => trans('update.medium'), 'type' => 'medium'],
        ];

        $customDomainTypeMap = [
            'linkedin.com' => 'linkedin',
            'github.com' => 'github',
            'twitter.com' => 'twitter',
            'x.com' => 'twitter',
            'facebook.com' => 'facebook',
            'youtube.com' => 'youtube',
            'youtu.be' => 'youtube',
            'instagram.com' => 'instagram',
            'behance.net' => 'behance',
            'dribbble.com' => 'dribbble',
            'medium.com' => 'medium',
        ];

        $publicProfileLinks = [];

        if ($user->isTeacher()) {
            foreach ($knownProfileLinks as $knownProfileLink) {
                if (!empty($profileLinksData[$knownProfileLink['key']])) {
                    $publicProfileLinks[] = [
                        'title' => $knownProfileLink['title'],
                        'url' => $profileLinksData[$knownProfileLink['key']],
                        'type' => $knownProfileLink['type'],
                    ];
                }
            }
        }

        if (!empty($profileLinksData['custom']) && is_array($profileLinksData['custom'])) {
            foreach ($profileLinksData['custom'] as $customLink) {
                if (!is_array($customLink) || empty($customLink['url'])) {
                    continue;
                }

                $type = 'custom';
                $customHost = mb_strtolower((string)(parse_url($customLink['url'], PHP_URL_HOST) ?? ''));

                if (!empty($customHost)) {
                    foreach ($customDomainTypeMap as $domain => $domainType) {
                        if (str_contains($customHost, $domain)) {
                            $type = $domainType;
                            break;
                        }
                    }
                }

                $publicProfileLinks[] = [
                    'title' => !empty($customLink['title']) ? $customLink['title'] : trans('update.custom_link'),
                    'url' => $customLink['url'],
                    'type' => $type,
                ];
            }
        }
    @endphp

    @if(!empty($publicProfileLinks))
        <div class="col-12 mt-24">
            <h4 class="font-16 font-weight-bold">{{ trans('update.portfolio_and_links') }}</h4>

            <div class="row mt-4">
                @foreach($publicProfileLinks as $profileLink)
                    <div class="col-12 col-md-6 col-lg-4 mt-12">
                        <a href="{{ $profileLink['url'] }}" target="_blank" rel="nofollow" class="profile-external-link-card d-flex align-items-center rounded-16 p-12 border-gray-200 bg-white bg-hover-gray-100">
                            <div class="profile-external-link-card__icon d-flex-center size-48 rounded-12 bg-gray-100 border-gray-200">
                                @include('design_1.web.users.profile.tabs.components.link_icon', [
                                    'type' => $profileLink['type'],
                                    'size' => 24,
                                    'className' => 'icons text-primary',
                                ])
                            </div>

                            <div class="ml-10 overflow-hidden">
                                <div class="profile-external-link-card__title font-14 font-weight-bold text-dark">{{ $profileLink['title'] }}</div>
                                <div class="profile-external-link-card__url font-12 text-gray-500 text-truncate">{{ parse_url($profileLink['url'], PHP_URL_HOST) ?: $profileLink['url'] }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
