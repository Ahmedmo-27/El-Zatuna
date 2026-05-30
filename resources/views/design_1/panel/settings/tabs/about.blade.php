<div class="custom-tabs-content active">
    <div class="row">
        <div class="col-12 col-lg-6">

            {{-- Education History --}}
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                            <x-iconsax-bul-teacher class="icons text-primary" width="24px" height="24px"/>
                        </div>

                        <div class="ml-8">
                            <h5 class="font-14 font-weight-bold">{{ trans('update.education_history') }}</h5>
                            <p class="font-12 text-gray-500 mt-4">{{ trans('update.education_history_hint') }}</p>
                        </div>
                    </div>

                    <div class="js-add-education d-flex align-items-center text-primary cursor-pointer">
                        <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
                        <span class="ml-4 ">{{ trans('site.add_education') }}</span>
                    </div>
                </div>

                {{-- Items --}}
                @if(!empty($educations) and !$educations->isEmpty())
                    @foreach($educations as $education)
                        <div class="js-education-card d-flex align-items-center justify-content-between bg-white p-16 mt-16 rounded-16 border-gray-200">
                            <span class="js-education-value font-14 font-weight-bold">{{ $education->value }}</span>

                            <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                                <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                                    <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                                </button>

                                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                                    <ul class="my-8">

                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <button type="button"
                                                    data-education-id="{{ $education->id }}"
                                                    data-user-id="{{ (!empty($user) and empty($new_user)) ? $user->id : '' }}"
                                                    class="js-edit-education"
                                            >
                                                {{ trans('public.edit') }}
                                            </button>
                                        </li>

                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <a href="/panel/setting/metas/{{ $education->id }}/delete?user_id={{ (!empty($user) and empty($new_user)) ? $user->id : '' }}" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="d-flex-center flex-column px-32 py-120 text-center">
                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                            <x-iconsax-bul-teacher class="icons text-primary" width="32px" height="32px"/>
                        </div>
                        <h3 class="font-16 font-weight-bold mt-12">{{ trans('auth.education_no_result') }}</h3>
                        <p class="mt-4 font-12 text-gray-500">{!! trans('auth.education_no_result_hint') !!}</p>
                    </div>
                @endif
            </div>

            {{-- About --}}
            <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                <h3 class="font-14 font-weight-bold mb-24">{{ trans('public.about') }}</h3>

                <div class="form-group">
                    <label class="form-group-label">{{ trans('panel.job_title') }}</label>
                    <textarea name="bio" rows="3" class="form-control @error('bio') is-invalid @enderror">{{ $user->bio }}</textarea>

                    @error('bio')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                    <div class="bg-gray-100 border-gray-300 mt-16 rounded-16 p-16">
                        <p class="font-12 text-gray-500">- {{ trans('panel.bio_hint_1') }}</p>
                        <p class="font-12 text-gray-500 mt-12">- {{ trans('panel.bio_hint_2') }}</p>
                    </div>
                </div>

                <div class="form-group mb-0">
                    <label class="form-group-label">{{ trans('panel.bio') }}</label>
                    <textarea name="about" rows="9" class="form-control @error('about')  is-invalid @enderror">{!! (!empty($user) and empty($new_user)) ? $user->about : old('about')  !!}</textarea>

                    @error('about')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

            </div>

        </div>

        <div class="col-12 col-lg-6 mt-20 mt-lg-0">
            {{-- Experiences History --}}
            <div class="bg-white p-16 rounded-16 border-gray-200">
                <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                            <x-iconsax-bul-briefcase class="icons text-primary" width="24px" height="24px"/>
                        </div>

                        <div class="ml-8">
                            <h5 class="font-14 font-weight-bold">{{ trans('update.experiences_history') }}</h5>
                            <p class="font-12 text-gray-500 mt-4">{{ trans('update.experiences_history_hint') }}</p>
                        </div>
                    </div>

                    <div class="js-add-experience d-flex align-items-center text-primary cursor-pointer">
                        <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
                        <span class="ml-4 ">{{ trans('site.add_experiences') }}</span>
                    </div>
                </div>

                {{-- Items --}}
                @if(!empty($experiences) and !$experiences->isEmpty())
                    @foreach($experiences as $experience)
                        <div class="js-experience-card d-flex align-items-center justify-content-between bg-white p-16 mt-16 rounded-16 border-gray-200">
                            <span class="js-experience-value font-14 font-weight-bold">{{ $experience->value }}</span>

                            <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                                <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                                    <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                                </button>

                                <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220 dropdown-menu-top-32">
                                    <ul class="my-8">

                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <button type="button" data-experience-id="{{ $experience->id }}" data-user-id="{{ (!empty($user) and empty($new_user)) ? $user->id : '' }}" class="js-edit-experience">{{ trans('public.edit') }}</button>
                                        </li>

                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <a href="/panel/setting/metas/{{ $experience->id }}/delete?user_id={{ (!empty($user) and empty($new_user)) ? $user->id : '' }}" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="d-flex-center flex-column px-32 py-120 text-center">
                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                            <x-iconsax-bul-briefcase class="icons text-primary" width="32px" height="32px"/>
                        </div>
                        <h3 class="font-16 font-weight-bold mt-12">{{ trans('auth.experience_no_result') }}</h3>
                        <p class="mt-4 font-12 text-gray-500">{!! trans('auth.experience_no_result_hint') !!}</p>
                    </div>
                @endif
            </div>

            {{-- Occupuations --}}
            @if(!$user->isUser())
                <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                    <h3 class="font-14 font-weight-bold mb-24">{{ trans('public.occupations') }}</h3>

                    <div class="form-group  mb-0">
                        <label class="form-group-label">{{ trans('update.select_your_occupations') }}</label>
                        <select name="occupations[]" class="form-control select2" multiple>
                            <option value="">{{ trans('update.select_your_occupations') }}</option>

                            @foreach($categories as $category)
                                @if(!empty($category->subCategories) and count($category->subCategories))
                                    @foreach($category->subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ (in_array($subCategory->id, $occupations)) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                                    @endforeach
                                @else
                                    <option value="{{ $category->id }}" {{ (in_array($category->id, $occupations)) ? 'selected' : '' }}>{{ $category->title }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="bg-gray-100 border-gray-300 mt-16 rounded-16 p-16">
                        <p class="font-12 text-gray-500">- {{ trans('panel.interests_hint_1') }}</p>
                        <p class="font-12 text-gray-500 mt-12">- {{ trans('panel.interests_hint_2') }}</p>
                    </div>

                </div>
            @endif

            @if($user->isTeacher())
                @php
                    $profileLinks = [];

                    if (!empty($user->profile_links)) {
                        $decodedProfileLinks = json_decode($user->profile_links, true);
                        $profileLinks = is_array($decodedProfileLinks) ? $decodedProfileLinks : [];
                    }

                    $customProfileLinks = (!empty($profileLinks['custom']) && is_array($profileLinks['custom'])) ? array_values($profileLinks['custom']) : [];

                    $knownProfileLinks = [
                        ['key' => 'portfolio', 'title' => trans('update.portfolio'), 'placeholder' => 'https://your-portfolio.com', 'type' => 'portfolio'],
                        ['key' => 'website', 'title' => trans('update.website'), 'placeholder' => 'https://your-website.com', 'type' => 'website'],
                        ['key' => 'linkedin', 'title' => trans('update.linkedin'), 'placeholder' => 'https://linkedin.com/in/username', 'type' => 'linkedin'],
                        ['key' => 'github', 'title' => trans('update.github'), 'placeholder' => 'https://github.com/username', 'type' => 'github'],
                        ['key' => 'twitter', 'title' => trans('update.twitter'), 'placeholder' => 'https://x.com/username', 'type' => 'twitter'],
                        ['key' => 'facebook', 'title' => trans('update.facebook'), 'placeholder' => 'https://facebook.com/username', 'type' => 'facebook'],
                        ['key' => 'youtube', 'title' => trans('update.youtube'), 'placeholder' => 'https://youtube.com/@channel', 'type' => 'youtube'],
                        ['key' => 'instagram', 'title' => trans('update.instagram'), 'placeholder' => 'https://instagram.com/username', 'type' => 'instagram'],
                        ['key' => 'behance', 'title' => trans('update.behance'), 'placeholder' => 'https://behance.net/username', 'type' => 'behance'],
                        ['key' => 'dribbble', 'title' => trans('update.dribbble'), 'placeholder' => 'https://dribbble.com/username', 'type' => 'dribbble'],
                        ['key' => 'medium', 'title' => trans('update.medium'), 'placeholder' => 'https://medium.com/@username', 'type' => 'medium'],
                    ];
                @endphp

                <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                    <h3 class="font-14 font-weight-bold">{{ trans('update.portfolio_and_links') }}</h3>
                    <p class="font-12 text-gray-500 mt-4">{{ trans('update.portfolio_and_links_hint') }}</p>

                    @foreach($knownProfileLinks as $knownProfileLink)
                        <div class="d-flex align-items-center gap-12 mt-20">
                            <div class="d-flex-center size-48 bg-gray-100 border-gray-300 rounded-12">
                                @include('design_1.web.users.profile.tabs.components.link_icon', [
                                    'type' => $knownProfileLink['type'],
                                    'size' => 24,
                                    'className' => 'icons text-gray-500',
                                ])
                            </div>

                            <div class="form-group mb-0 flex-1">
                                <label class="form-group-label">{{ $knownProfileLink['title'] }}</label>
                                <input type="text" name="profile_links[{{ $knownProfileLink['key'] }}]" value="{{ $profileLinks[$knownProfileLink['key']] ?? '' }}" class="form-control" placeholder="{{ $knownProfileLink['placeholder'] }}">
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex align-items-center justify-content-between mt-24 pt-16 border-top-gray-100">
                        <div>
                            <h4 class="font-14 font-weight-bold">{{ trans('update.custom_links') }}</h4>
                            <p class="font-12 text-gray-500 mt-4">{{ trans('update.custom_links_hint') }}</p>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-primary js-add-profile-link">
                            <x-iconsax-lin-add class="icons" width="14px" height="14px"/>
                            <span class="ml-4">{{ trans('update.add_custom_link') }}</span>
                        </button>
                    </div>

                    <div class="js-profile-links-repeater mt-12" data-index="{{ count($customProfileLinks) }}">
                        @if(!empty($customProfileLinks))
                            @foreach($customProfileLinks as $customIndex => $customLink)
                                <div class="js-profile-link-row border-gray-200 rounded-12 p-12 mt-12">
                                    <div class="d-flex align-items-center justify-content-between mb-8">
                                        <span class="font-12 text-gray-500">{{ trans('update.custom_link') }}</span>

                                        <button type="button" class="btn btn-sm btn-outline-danger js-remove-profile-link">{{ trans('public.delete') }}</button>
                                    </div>

                                    <div class="form-group mb-8">
                                        <label class="form-group-label">{{ trans('public.title') }}</label>
                                        <input type="text" name="profile_links[custom][{{ $customIndex }}][title]" value="{{ $customLink['title'] ?? '' }}" class="form-control" placeholder="{{ trans('update.my_custom_link') }}">
                                    </div>

                                    <div class="form-group mb-8">
                                        <label class="form-group-label">{{ trans('public.link') }}</label>
                                        <input type="text" name="profile_links[custom][{{ $customIndex }}][url]" value="{{ $customLink['url'] ?? '' }}" class="form-control" placeholder="https://example.com">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="js-profile-links-empty bg-gray-100 border-gray-300 rounded-12 p-12 font-12 text-gray-500">
                                {{ trans('update.no_custom_links_added_yet') }}
                            </div>
                        @endif
                    </div>

                    <div class="d-none js-profile-link-template">
                        <div class="js-profile-link-row border-gray-200 rounded-12 p-12 mt-12">
                            <div class="d-flex align-items-center justify-content-between mb-8">
                                <span class="font-12 text-gray-500">{{ trans('update.custom_link') }}</span>

                                <button type="button" class="btn btn-sm btn-outline-danger js-remove-profile-link">{{ trans('public.delete') }}</button>
                            </div>

                            <div class="form-group mb-8">
                                <label class="form-group-label">{{ trans('public.title') }}</label>
                                <input type="text" name="profile_links[custom][__INDEX__][title]" class="form-control" placeholder="{{ trans('update.my_custom_link') }}">
                            </div>

                            <div class="form-group mb-8">
                                <label class="form-group-label">{{ trans('public.link') }}</label>
                                <input type="text" name="profile_links[custom][__INDEX__][url]" class="form-control" placeholder="https://example.com">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mt-16 pt-16 border-top-gray-100">
                        <div class="d-flex-center size-48 rounded-12 bg-gray-300">
                            <x-iconsax-bol-info-circle class="icon text-gray-500" width="24px" height="24px"/>
                        </div>
                        <div class="ml-8">
                            <h4 class="font-14">{{ trans('update.note') }}</h4>
                            <p class="font-12 text-gray-500">{{ trans('update.links_will_be_shown_on_public_profile') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Files & Attachments --}}
            <div class="bg-white p-16 rounded-16 border-gray-200 mt-20">
                <div class="d-flex align-items-center justify-content-between p-12 rounded-16 border-gray-300 border-dashed">
                    <div class="d-flex align-items-center">
                        <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                            <x-iconsax-bul-document-download class="icons text-primary" width="24px" height="24px"/>
                        </div>

                        <div class="ml-8">
                            <h5 class="font-14 font-weight-bold">{{ trans('update.files_&_attachments') }}</h5>
                            <p class="font-12 text-gray-500 mt-4">{{ trans('update.files_&_attachments_hint') }}</p>
                        </div>
                    </div>

                    <div class="js-add-attachment d-flex align-items-center text-primary cursor-pointer" data-user="{{ (!empty($organization_id)) ? $user->id : '' }}">
                        <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
                        <span class="ml-4 ">{{ trans('update.add_a_file') }}</span>
                    </div>
                </div>

                {{-- Items --}}
                @if(!empty($attachments) and !$attachments->isEmpty())
                    @foreach($attachments as $attachment)
                        <div class="d-flex align-items-center justify-content-between bg-white p-16 mt-16 rounded-16 border-gray-200">
                            <div class="d-flex align-items-center">
                                <div class="">
                                    @switch($attachment->file_type)
                                        @case("pdf")
                                            <x-iconsax-lin-archive-book class="icons text-gray-500" width="20px" height="20px"/>
                                            @break
                                        @case("powerpoint")
                                            <x-iconsax-lin-presention-chart class="icons text-gray-500" width="20px" height="20px"/>
                                            @break
                                        @case("sound")
                                            <x-iconsax-lin-music class="icons text-gray-500" width="20px" height="20px"/>
                                            @break
                                        @case("video")
                                            <x-iconsax-lin-video-vertical class="icons text-gray-500" width="20px" height="20px"/>
                                            @break
                                        @case("image")
                                            <x-iconsax-lin-gallery class="icons text-gray-500" width="20px" height="20px"/>
                                            @break
                                        @case("archive")
                                            <x-iconsax-lin-book class="icons text-gray-500" width="20px" height="20px"/>
                                            @break
                                        @case("document")
                                            <x-iconsax-lin-document class="icons text-gray-500" width="20px" height="20px"/>
                                            @break
                                        @case("project")
                                            <x-iconsax-lin-document-1 class="icons text-gray-500" width="20px" height="20px"/>
                                            @break
                                    @endswitch
                                </div>

                                <div class="ml-8">
                                    <h5 class="font-14 font-weight-bold">{{ $attachment->title }}</h5>
                                    <p class="mt-4 font-12 text-gray-500">{{ truncate($attachment->description, 150) }}</p>
                                </div>
                            </div>

                            <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                                <button type="button" class="d-flex-center size-36 bg-gray border-gray-200 rounded-10">
                                    <x-iconsax-lin-more class="icons text-gray-500" width="18"/>
                                </button>

                                <div class="actions-dropdown__dropdown-menu dropdown-menu-top-32">
                                    <ul class="my-8">

                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <button type="button" class="js-edit-attachment" data-path="/panel/setting/attachments/{{ $attachment->id }}/edit{{ (!empty($organization_id)) ? "?user_id={$user->id}" : '' }}">{{ trans('public.edit') }}</button>
                                        </li>

                                        <li class="actions-dropdown__dropdown-menu-item">
                                            <a href="/panel/setting/attachments/{{ $attachment->id }}/delete" class="delete-action text-danger">{{ trans('public.delete') }}</a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="d-flex-center flex-column px-32 py-120 text-center">
                        <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                            <x-iconsax-bul-document-download class="icons text-primary" width="32px" height="32px"/>
                        </div>
                        <h3 class="font-16 font-weight-bold mt-12">{{ trans('update.user_profile_attachment_no_result') }}</h3>
                        <p class="mt-4 font-12 text-gray-500">{!! trans('update.user_profile_attachment_no_result_hint') !!}</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>


<div class="d-none" id="newEducationModal">
    <div class="mt-20 text-center">
        <img src="/assets/design_1/img/panel/settings/education_experience_modal.png" width="216px" height="160px" class="rounded-circle" alt="">

        <h4 class="font-14 mt-12 font-weight-bold">{{ trans('site.new_education_hint') }}</h4>
        <span class="d-block mt-8 text-gray-500 font-12">{{ trans('site.new_education_exam') }}</span>

        <div class="form-group mt-16">
            <label class="form-group-label">{{ trans('public.title') }}</label>
            <input type="text" id="new_education_val" class="form-control">
            <div class="invalid-feedback">{{ trans('validation.required',['attribute' => 'value']) }}</div>
        </div>
    </div>
</div>

<div class="d-none" id="newExperienceModal">
    <div class="mt-20 text-center">
        <img src="/assets/design_1/img/panel/settings/education_experience_modal.png" width="216px" height="160px" class="rounded-circle" alt="">

        <h4 class="font-14 mt-12 font-weight-bold">{{ trans('site.new_experience_hint') }}</h4>
        <span class="d-block mt-8 text-gray-500 font-12">{{ trans('site.new_experience_exam') }}</span>

        <div class="form-group mt-16">
            <label class="form-group-label">{{ trans('public.title') }}</label>
            <input type="text" id="new_experience_val" class="form-control">
            <div class="invalid-feedback">{{ trans('validation.required',['attribute' => 'value']) }}</div>
        </div>
    </div>
</div>

@push('scripts_bottom')
    <script>
        (function ($) {
            "use strict";

            function updateCustomLinksEmptyState($repeater) {
                const hasRows = $repeater.find('.js-profile-link-row').length > 0;
                $repeater.find('.js-profile-links-empty').toggleClass('d-none', hasRows);
            }

            $('body').on('click', '.js-add-profile-link', function (e) {
                e.preventDefault();

                const $card = $(this).closest('.bg-white');
                const $repeater = $card.find('.js-profile-links-repeater');
                const $template = $card.find('.js-profile-link-template .js-profile-link-row').first();

                if (!$repeater.length || !$template.length) {
                    return;
                }

                const currentIndex = Number($repeater.attr('data-index') || 0);
                const html = $template.prop('outerHTML').replaceAll('__INDEX__', String(currentIndex));

                $repeater.append(html);
                $repeater.attr('data-index', currentIndex + 1);

                updateCustomLinksEmptyState($repeater);
            });

            $('body').on('click', '.js-remove-profile-link', function (e) {
                e.preventDefault();

                const $row = $(this).closest('.js-profile-link-row');
                const $repeater = $row.closest('.js-profile-links-repeater');

                $row.remove();
                updateCustomLinksEmptyState($repeater);
            });

            $(document).ready(function () {
                $('.js-profile-links-repeater').each(function () {
                    updateCustomLinksEmptyState($(this));
                });
            });
        })(jQuery);
    </script>
@endpush
