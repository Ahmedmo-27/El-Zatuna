@if(!empty($course->chapters) and count($course->chapters))
    @php
        $user = auth()->user();
        $hasBought = $course->checkUserHasBought($user) || !empty($course->getInstallmentOrder());
    @endphp
    <div id="chaptersAccordion">
        @foreach($course->chapters as $chapter)
            @php
                $canAccessChapter = $hasBought || canUserAccessCourseContent($course, $user, $chapter);
                $isLocked = !$canAccessChapter;
                $sectionPrice = isset($chapter->price) ? (float) $chapter->price : 0;
            @endphp
            <div class="js-accordion-parent accordion p-12 rounded-20 bg-gray-100 mb-16 {{ $isLocked ? 'learning-sidebar-chapter-locked' : '' }}">
                <div class="accordion__title d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center {{ $canAccessChapter ? 'cursor-pointer' : '' }}" href="#collapseChapter{{ $chapter->id }}" data-parent="#chaptersAccordion" role="button" data-toggle="collapse">
                        <div class="d-flex-center size-48 rounded-12 {{ $isLocked ? 'bg-gray-200' : 'bg-primary-20' }}">
                            @if($isLocked)
                                <x-iconsax-bol-lock-circle class="icons text-gray-500" width="24px" height="24px"/>
                            @else
                                <x-iconsax-bul-category class="icons text-primary" width="24px" height="24px"/>
                            @endif
                        </div>
                        <div class="ml-8">
                            <div class="font-14 font-weight-bold">{{ $chapter->title }}</div>
                            <div class="d-flex align-items-center flex-wrap gap-8 mt-4 font-12 text-gray-500">
                                <span>{{ $chapter->getTopicsCount(true) }} {{ trans('public.parts') }}</span>
                                <span class="sidebar-item-dot-separator rounded-circle bg-gray-300"></span>
                                <span class="d-flex align-items-center gap-4">
                                    <x-iconsax-lin-clock-1 class="icons text-gray-400" width="14px" height="14px"/>
                                    <span>{{ convertMinutesToHourAndMinute($chapter->getDuration()) }}</span>
                                </span>
                                @if($isLocked && $sectionPrice > 0)
                                    <span class="sidebar-item-dot-separator rounded-circle bg-gray-300"></span>
                                    <span class="text-primary font-weight-bold">{{ handlePrice($sectionPrice) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="js-accordion-collapse-arrow collapse-arrow-icon d-flex cursor-pointer" href="#collapseChapter{{ $chapter->id }}" data-parent="#chaptersAccordion" role="button" data-toggle="collapse">
                        <x-iconsax-lin-arrow-up-1 class="icons text-gray-400" width="16px" height="16px"/>
                    </div>
                </div>

                <div id="collapseChapter{{ $chapter->id }}" class="js-accordion-collapse accordion__collapse pt-0 mt-20 border-0 " role="tabpanel">
                    @if($isLocked)
                        <div class="p-16 rounded-12 bg-gray-50 border border-gray-200">
                            <p class="font-12 text-gray-600 mb-12">{{ trans('public.add_to_cart') }} to unlock this section.</p>
                            <form action="/cart/store" method="post" class="d-inline">
                                @csrf
                                <input type="hidden" name="item_id" value="{{ $chapter->id }}">
                                <input type="hidden" name="item_name" value="chapter_id">
                                <button type="submit" class="btn btn-sm btn-primary">{{ trans('public.add_to_cart') }} — {{ handlePrice($sectionPrice) }}</button>
                            </form>
                        </div>
                    @elseif(!empty($chapter->chapterItems) and count($chapter->chapterItems))
                        @foreach($chapter->chapterItems as $chapterItem)
                            @if($chapterItem->type == \App\Models\WebinarChapterItem::$chapterSession and !empty($chapterItem->session) and $chapterItem->session->status == 'active')
                                @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.session' , ['session' => $chapterItem->session, 'type' => \App\Models\WebinarChapter::$chapterSession])
                            @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterFile and !empty($chapterItem->file) and $chapterItem->file->status == 'active')
                                @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.file' , ['file' => $chapterItem->file, 'type' => \App\Models\WebinarChapter::$chapterFile])
                            @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterTextLesson and !empty($chapterItem->textLesson) and $chapterItem->textLesson->status == 'active')
                                @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.text_lesson' , ['textLesson' => $chapterItem->textLesson, 'type' => \App\Models\WebinarChapter::$chapterTextLesson])
                            @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterAssignment and !empty($chapterItem->assignment) and $chapterItem->assignment->status == 'active')
                                @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.assignment' ,['assignment' => $chapterItem->assignment])
                            @elseif($chapterItem->type == \App\Models\WebinarChapterItem::$chapterQuiz and !empty($chapterItem->quiz) and $chapterItem->quiz->status == 'active')
                                @include('design_1.web.courses.learning_page.includes.sidebar.tabs.contents.quiz' ,['quiz' => $chapterItem->quiz, 'type' => 'quiz'])
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif
