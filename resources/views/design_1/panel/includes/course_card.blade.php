@php
    $item = $item ?? $course ?? $saleItem ?? null;
    $course = $course ?? $item;
    $saleItem = $saleItem ?? $item;

    $itemUrl = $itemUrl ?? (!empty($item) && method_exists($item, 'getUrl') ? $item->getUrl() : '#');
    $itemImage = $itemImage ?? (!empty($item) && method_exists($item, 'getImage') ? $item->getImage() : '');
    $itemTitle = $itemTitle ?? (!empty($item) ? $item->title : '');
    $itemRate = $itemRate ?? (!empty($item) && method_exists($item, 'getRate') ? round($item->getRate(), 1) : null);
    $itemRateCount = $itemRateCount ?? (!empty($item) && method_exists($item, 'reviews') ? $item->reviews()->where('status', 'active')->count() : null);

    $wrapInLink = $wrapInLink ?? true;
    $actionsOutsideLink = $actionsOutsideLink ?? false;
    $statsLinkUrl = $statsLinkUrl ?? $itemUrl;
    $cardLinkTarget = $cardLinkTarget ?? '_blank';

    $footerLeftWrapperClass = $footerLeftWrapperClass ?? 'col-12 col-lg-10';
    $footerRightWrapperClass = $footerRightWrapperClass ?? 'col-12 col-lg-2 d-flex align-items-center justify-content-end mt-12 mt-lg-0';

    $itemType = $itemType ?? null;
    if (empty($itemType) && !empty($item)) {
        if (method_exists($item, 'isWebinar') && $item->isWebinar()) {
            $itemType = 'webinar';
        } elseif (method_exists($item, 'isTextCourse') && $item->isTextCourse()) {
            $itemType = 'text_lesson';
        } elseif (method_exists($item, 'isCourse') && $item->isCourse()) {
            $itemType = 'course';
        } elseif (!empty($item->type)) {
            $itemType = $item->type;
        }
    }
@endphp

<div class="panel-course-card-1 position-relative {{ !empty($isInvitedCoursesPage) ? 'is-invited-course-card' : '' }}">
    <div class="card-mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row gap-12 z-index-2 bg-white p-12 rounded-24">
        @if($wrapInLink)
            <a href="{{ $itemUrl }}" target="{{ $cardLinkTarget }}" class="d-flex flex-column flex-lg-row gap-12 flex-grow-1 text-decoration-none">
                {{-- Image --}}
                <div class="panel-course-card-1__image position-relative rounded-16 bg-gray-100">
                    <img src="{{ $itemImage }}" alt="" class="img-cover rounded-16">

                    @if(!empty($badgesView))
                        @include($badgesView)
                    @endif

                    @if($itemType === 'webinar')
                        <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                            <x-iconsax-bol-video class="icons text-white" width="24px" height="24px"/>
                        </div>
                    @elseif($itemType === 'text_lesson')
                        <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                            <x-iconsax-bol-note-2 class="icons text-white" width="24px" height="24px"/>
                        </div>
                    @elseif($itemType === 'course')
                        <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                            <x-iconsax-bol-video-play class="icons text-white" width="24px" height="24px"/>
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="panel-course-card-1__content flex-1 d-flex flex-column">
                    <div class="bg-gray-100 p-16 rounded-16 mb-12">
                        <div class="d-flex align-items-start justify-content-between gap-12">
                            <div class="">
                                <h3 class="font-16 text-dark">{{ truncate($itemTitle, 46) }}</h3>

                                @if(!is_null($itemRate))
                                    @include("design_1.web.components.rate", [
                                        'rate' => $itemRate,
                                        'rateCount' => $itemRateCount,
                                        'rateClassName' => 'mt-8',
                                    ])
                                @endif
                            </div>

                            @if(!empty($actionsView) && !$actionsOutsideLink)
                                @include($actionsView)
                            @endif
                        </div>

                        @if(!empty($statsView))
                            @include($statsView)
                        @endif
                    </div>

                    <div class="row align-items-center justify-content-between mt-auto">
                        <div class="{{ $footerLeftWrapperClass }}">
                            @if(!empty($progressView))
                                @include($progressView)
                            @endif
                        </div>

                        @if(!empty($footerRightView))
                            <div class="{{ $footerRightWrapperClass }}">
                                @include($footerRightView)
                            </div>
                        @endif
                    </div>
                </div>
            </a>
        @else
            <div class="d-flex flex-column flex-lg-row gap-12 flex-grow-1">
                {{-- Image --}}
                <div class="panel-course-card-1__image position-relative rounded-16 bg-gray-100">
                    <a href="{{ $itemUrl }}" target="{{ $cardLinkTarget }}" class="d-flex w-100 h-100">
                        <img src="{{ $itemImage }}" alt="" class="img-cover rounded-16">
                    </a>

                    @if(!empty($badgesView))
                        @include($badgesView)
                    @endif

                    @if($itemType === 'webinar')
                        <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                            <a href="{{ $itemUrl }}" target="{{ $cardLinkTarget }}" class="d-flex-center w-100 h-100">
                                <x-iconsax-bol-video class="icons text-white" width="24px" height="24px"/>
                            </a>
                        </div>
                    @elseif($itemType === 'text_lesson')
                        <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                            <a href="{{ $itemUrl }}" target="{{ $cardLinkTarget }}" class="d-flex-center w-100 h-100">
                                <x-iconsax-bol-note-2 class="icons text-white" width="24px" height="24px"/>
                            </a>
                        </div>
                    @elseif($itemType === 'course')
                        <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                            <a href="{{ $itemUrl }}" target="{{ $cardLinkTarget }}" class="d-flex-center w-100 h-100">
                                <x-iconsax-bol-video-play class="icons text-white" width="24px" height="24px"/>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="panel-course-card-1__content flex-1 d-flex flex-column">
                    <div class="bg-gray-100 p-16 rounded-16 mb-12">
                        <div class="d-flex align-items-start justify-content-between gap-12">
                            <div class="">
                                <h3 class="font-16 text-dark">
                                    <a href="{{ $itemUrl }}" target="{{ $cardLinkTarget }}" class="text-decoration-none text-dark">
                                        {{ truncate($itemTitle, 46) }}
                                    </a>
                                </h3>

                                @if(!is_null($itemRate))
                                    @include("design_1.web.components.rate", [
                                        'rate' => $itemRate,
                                        'rateCount' => $itemRateCount,
                                        'rateClassName' => 'mt-8',
                                    ])
                                @endif
                            </div>

                            @if(!empty($actionsView) && !$actionsOutsideLink)
                                @include($actionsView)
                            @endif
                        </div>

                        @if(!empty($statsView))
                            @if(!empty($statsLinkUrl))
                                <a href="{{ $statsLinkUrl }}" target="{{ $cardLinkTarget }}" class="text-decoration-none">
                                    @include($statsView)
                                </a>
                            @else
                                @include($statsView)
                            @endif
                        @endif
                    </div>

                    <div class="row align-items-center justify-content-between mt-auto">
                        <div class="{{ $footerLeftWrapperClass }}">
                            @if(!empty($progressView))
                                @include($progressView)
                            @endif
                        </div>

                        @if(!empty($footerRightView))
                            <div class="{{ $footerRightWrapperClass }}">
                                @include($footerRightView)
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if(!empty($actionsView) && $actionsOutsideLink)
        <div class="actions-dropdown-container position-absolute" style="top: 28px; right: 28px; z-index: 10;">
            @include($actionsView)
        </div>
    @endif
</div>
