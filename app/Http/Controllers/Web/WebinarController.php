<?php

namespace App\Http\Controllers\Web;

use App\Enums\MorphTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\traits\CheckContentLimitationTrait;
use App\Http\Controllers\Web\traits\CourseShowTrait;
use App\Http\Controllers\Web\traits\InstallmentsTrait;
use App\Mixins\Cashback\CashbackRules;
use App\Mixins\Installment\InstallmentPlans;
use App\Mixins\Logs\VisitLogMixin;
use App\Models\AdvertisingBanner;
use App\Models\Cart;
use App\Models\Certificate;
use App\Models\Discount;
use App\Models\Favorite;
use App\Models\File;
use App\Models\QuizzesResult;
use App\Models\RewardAccounting;
use App\Models\Sale;
use App\Models\TextLesson;
use App\Models\CourseLearning;
use App\Models\WebinarChapter;
use App\Models\WebinarReport;
use App\Models\Webinar;
use App\Models\WebinarReview;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Aws\S3\S3Client;

class WebinarController extends Controller
{
    use CheckContentLimitationTrait;
    use InstallmentsTrait;
    use CourseShowTrait;

    public function course(Request $request, $slug, $justReturnData = false)
    {
        $user = null;

        if (auth()->check()) {
            $user = auth()->user();
        }


        if (!$justReturnData) {
            $contentLimitation = $this->checkContentLimitation($user, true);
            if ($contentLimitation != "ok") {
                return $contentLimitation;
            }
        }

        $course = Webinar::where('slug', $slug)
            ->with([
                'quizzes' => function ($query) {
                    $query->where('status', 'active')
                        ->with(['quizResults', 'quizQuestions']);
                },
                'tags',
                'prerequisites' => function ($query) {
                    $query->with(['prerequisiteWebinar' => function ($query) {
                        $query->with(['teacher' => function ($qu) {
                            $qu->select('id', 'username', 'full_name', 'role_id', 'role_name', 'avatar', 'avatar_settings');
                        }]);
                    }]);
                    $query->orderBy('order', 'asc');
                },
                'relatedCourses' => function ($query) {
                    $query->whereHas('course', function ($query) {
                        $query->where('status', 'active');
                    });
                },
                'faqs' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'webinarExtraDescription' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'chapters' => function ($query) use ($user) {
                    $query->where('status', WebinarChapter::$chapterActive);
                    $query->orderBy('order', 'asc');

                    $query->with([
                        'chapterItems' => function ($query) {
                            $query->orderBy('order', 'asc');
                        }
                    ]);
                },
                'files' => function ($query) use ($user) {
                    $query->join('webinar_chapters', 'webinar_chapters.id', '=', 'files.chapter_id')
                        ->select('files.*', DB::raw('webinar_chapters.order as chapterOrder'))
                        ->where('files.status', WebinarChapter::$chapterActive)
                        ->orderBy('chapterOrder', 'asc')
                        ->orderBy('files.order', 'asc')
                        ->with([
                            'learningStatus' => function ($query) use ($user) {
                                $query->where('user_id', !empty($user) ? $user->id : null);
                            }
                        ]);
                },
                'textLessons' => function ($query) use ($user) {
                    $query->where('status', WebinarChapter::$chapterActive)
                        ->withCount(['attachments'])
                        ->orderBy('order', 'asc')
                        ->with([
                            'learningStatus' => function ($query) use ($user) {
                                $query->where('user_id', !empty($user) ? $user->id : null);
                            }
                        ]);
                },
                'sessions' => function ($query) use ($user) {
                    $query->where('status', WebinarChapter::$chapterActive)
                        ->orderBy('order', 'asc')
                        ->with([
                            'learningStatus' => function ($query) use ($user) {
                                $query->where('user_id', !empty($user) ? $user->id : null);
                            }
                        ]);
                },
                'assignments' => function ($query) {
                    $query->where('status', WebinarChapter::$chapterActive);
                },
                'tickets' => function ($query) {
                    $query->orderBy('order', 'asc');
                },
                'filterOptions',
                'category',
                'teacher',
                'reviews' => function ($query) {
                    $query->where('status', 'active');
                },
            ])
            ->withCount([
                'sales' => function ($query) {
                    $query->whereNull('refund_at');
                },
                'noticeboards'
            ])
            // Status is validated below; keep pending/non-active visible only when allowed.
            ->first();

        if (empty($course)) {
            return $justReturnData ? false : back();
        }

        if (!$justReturnData) {

            /* Check Not Active - allow pending so visitors see "Coming soon" on course page */
            if ($course->status != "active" and $course->status != "pending" and (empty($user) or (!$user->isAdmin() and !$course->canAccess($user)))) {
                $data = [
                    'pageTitle' => trans('update.access_denied'),
                    'pageRobot' => getPageRobotNoIndex(),
                ];
                return view('design_1.web.courses.not_access.index', $data);
            }

            // Guests may only see fully global courses (all universities & all faculties).
            if (empty($user)) {
                if (!is_null($course->university_id) || !is_null($course->faculty_id)) {
                    $data = [
                        'pageTitle' => trans('update.access_denied'),
                        'pageRobot' => getPageRobotNoIndex(),
                    ];
                    return view('design_1.web.courses.not_access.index', $data);
                }
            }

            /* Installment Check */
            $installmentLimitation = $this->installmentContentLimitation($user, $course->id, 'webinar_id');

            if ($installmentLimitation != "ok") {
                return $installmentLimitation;
            }
        }

        $hasBought = $course->checkUserHasBought($user, true, true);
        $isPrivate = $course->private;

        if (!empty($user) and ($user->id == $course->creator_id or $user->organ_id == $course->creator_id or $user->isAdmin())) {
            $isPrivate = false;
        }

        if ($isPrivate and $hasBought) { // check the user has bought the course or not
            $isPrivate = false;
        }

        if ($isPrivate) {
            return $justReturnData ? false : back();
        }

        $isFavorite = $course->isFavoriteAuthUser();

        $webinarContentCount = 0;
        if (!empty($course->sessions)) {
            $webinarContentCount += $course->sessions->count();
        }
        if (!empty($course->files)) {
            $webinarContentCount += $course->files->count();
        }
        if (!empty($course->textLessons)) {
            $webinarContentCount += $course->textLessons->count();
        }
        if (!empty($course->quizzes)) {
            $webinarContentCount += $course->quizzes->count();
        }
        if (!empty($course->assignments)) {
            $webinarContentCount += $course->assignments->count();
        }

        $advertisingBanners = AdvertisingBanner::where('published', true)
            ->whereIn('position', ['course', 'course_sidebar'])
            ->get();

        $sessionsWithoutChapter = $course->sessions->whereNull('chapter_id');

        $filesWithoutChapter = $course->files->whereNull('chapter_id');

        $textLessonsWithoutChapter = $course->textLessons->whereNull('chapter_id');

        $quizzes = $course->quizzes->whereNull('chapter_id');

        if ($user) {
            $quizzes = $this->checkQuizzesResults($user, $quizzes);

            if (!empty($course->chapters) and count($course->chapters)) {
                foreach ($course->chapters as $chapter) {
                    if (!empty($chapter->chapterItems) and count($chapter->chapterItems)) {
                        foreach ($chapter->chapterItems as $chapterItem) {
                            if (!empty($chapterItem->quiz)) {
                                $chapterItem->quiz = $this->checkQuizResults($user, $chapterItem->quiz);
                            }
                        }
                    }
                }
            }

            if (!empty($course->quizzes) and count($course->quizzes)) {
                $course->quizzes = $this->checkQuizzesResults($user, $course->quizzes);
            }
        }

        $pageRobot = getPageRobot('course_show'); // index
        $canSale = ($course->canSale() and !$hasBought);

        /* Installments */
        $showInstallments = true;
        $overdueInstallmentOrders = $this->checkUserHasOverdueInstallment($user);

        if ($overdueInstallmentOrders->isNotEmpty() and getInstallmentsSettings('disable_instalments_when_the_user_have_an_overdue_installment')) {
            $showInstallments = false;
        }

        if ($canSale and !empty($course->price) and $course->price > 0 and $showInstallments and getInstallmentsSettings('status') and (empty($user) or $user->enable_installments)) {
            $installmentPlans = new InstallmentPlans($user);
            $installments = $installmentPlans->getPlans('courses', $course->id, $course->type, $course->category_id, $course->teacher_id);
        }

        /* Cashback Rules */
        if ($canSale and !empty($course->price) and getFeaturesSettings('cashback_active') and (empty($user) or !$user->disable_cashback)) {
            $cashbackRulesMixin = new CashbackRules($user);
            $cashbackRules = $cashbackRulesMixin->getRules('courses', $course->id, $course->type, $course->category_id, $course->teacher_id);
        }

        $instructorDiscounts = null;

        if (!empty(getFeaturesSettings('frontend_coupons_status'))) {
            $instructorDiscounts = Discount::query()
                ->where(function (Builder $query) use ($course) {
                    $query->where('creator_id', $course->creator_id);
                    $query->orWhere('creator_id', $course->teacher_id);
                })
                ->where(function (Builder $query) use ($course) {
                    $query->where('source', 'all');
                    $query->orWhere(function (Builder $query) use ($course) {
                        $query->where('source', Discount::$discountSourceCourse);

                        $query->where(function (Builder $query) use ($course) {
                            $query->whereHas('discountCourses', function ($query) use ($course) {
                                $query->where('course_id', $course->id);
                            });

                            $query->whereDoesntHave('discountCourses');
                        });
                    });
                })
                ->where('status', 'active')
                ->where('expired_at', '>', time())
                ->get();
        }

        $webinarReviewController = new WebinarReviewController();
        $courseReviews = $webinarReviewController->getReviewsByCourseSlug($request, $course->slug);

        $commentController = new CommentController();
        $courseComments = $commentController->getComments($request, 'webinar', $course->id);

        // SEO: clean description with sensible fallbacks (seo_description -> summary -> description).
        $courseSeoDescription = $course->seo_description ?: ($course->summary ?: $course->description);
        $courseUrl = url('/course/' . $course->slug);
        $courseSellPrice = $course->bestTicket();
        $courseRate = $course->getRate();
        $courseRateCount = $course->getRateCount();

        $courseSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $course->title,
            'description' => \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string)$courseSeoDescription))), 300),
            'url' => $courseUrl,
            'image' => url($course->getImage()),
            'provider' => [
                '@type' => 'Organization',
                'name' => getGeneralSettings('site_name') ?? config('app.name'),
                'sameAs' => url('/'),
            ],
            'hasCourseInstance' => [
                '@type' => 'CourseInstance',
                'courseMode' => 'Online',
                'name' => $course->title,
            ],
        ];

        // Price offer (Google shows price in Course rich results).
        if (is_numeric($courseSellPrice)) {
            $courseSchema['offers'] = [
                '@type' => 'Offer',
                'price' => number_format((float)$courseSellPrice, 2, '.', ''),
                'priceCurrency' => currency(),
                'availability' => 'https://schema.org/InStock',
                'url' => $courseUrl,
            ];
        }

        // Aggregate rating from real reviews (renders star ratings in search results).
        if (!empty($courseRateCount) && (float)$courseRate > 0) {
            $courseSchema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => (string)$courseRate,
                'reviewCount' => (int)$courseRateCount,
                'bestRating' => '5',
                'worstRating' => '1',
            ];
        }

        $data = [
            'pageTitle' => $course->title,
            'pageDescription' => $courseSeoDescription,
            'pageRobot' => $pageRobot,
            'pageMetaImage' => $course->getImage(),
            'pageCanonicalUrl' => $courseUrl,
            'pageOgType' => 'course',
            'pageSchema' => $courseSchema,
            'course' => $course,
            'isFavorite' => $isFavorite,
            'hasBought' => $hasBought,
            'user' => $user,
            'webinarContentCount' => $webinarContentCount,
            'advertisingBanners' => $advertisingBanners->where('position', 'course'),
            'advertisingBannersSidebar' => $advertisingBanners->where('position', 'course_sidebar'),
            'activeSpecialOffer' => $course->activeSpecialOffer(),
            'sessionsWithoutChapter' => $sessionsWithoutChapter,
            'filesWithoutChapter' => $filesWithoutChapter,
            'textLessonsWithoutChapter' => $textLessonsWithoutChapter,
            'quizzes' => $quizzes,
            'installments' => $installments ?? null,
            'cashbackRules' => $cashbackRules ?? null,
            'instructorDiscounts' => $instructorDiscounts,
            'courseReviews' => $courseReviews,
            'courseComments' => $courseComments,
            'recentReviews' => $this->getCourseRecentReviews($course->id),
        ];

        // check for certificate
        if (!empty($user)) {
            $course->makeCertificateForUser($user);
        }

        if ($justReturnData) {
            return $data;
        }

        $visitLogMixin = new VisitLogMixin();
        $visitLogMixin->storeVisit($request, $course->creator_id, $course->id, MorphTypesEnum::WEBINAR);

        return view('design_1.web.courses.show.index', $data);
    }

    private function getCourseRecentReviews($courseId)
    {
        $recentReviews = null;

        if (!empty(getFeaturesSettings("course_recent_reviews_status"))) {
            $recentReviews = WebinarReview::query()
                ->where('webinar_id', $courseId)
                ->where('status', 'active')
                ->whereNotNull('rates')
                ->orderBy('rates', 'desc')
                ->orderBy('created_at', 'desc')
                ->with([
                    'creator' => function ($query) {
                        $query->select('id', 'full_name', 'role_name', 'role_id', 'username', 'avatar', 'avatar_settings', 'bio', 'about');
                    }
                ])
                ->limit(5)
                ->get();
        }

        return $recentReviews;
    }

    private function checkQuizzesResults($user, $quizzes)
    {
        $canDownloadCertificate = false;

        foreach ($quizzes as $quiz) {
            $quiz = $this->checkQuizResults($user, $quiz);
        }

        return $quizzes;
    }

    private function checkQuizResults($user, $quiz)
    {
        $canDownloadCertificate = false;

        $canTryAgainQuiz = false;
        $userQuizDone = QuizzesResult::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        if (count($userQuizDone)) {
            $quiz->user_grade = $userQuizDone->first()->user_grade;
            $quiz->result_count = $userQuizDone->count();
            $quiz->result = $userQuizDone->first();

            $status_pass = false;
            foreach ($userQuizDone as $result) {
                if ($result->status == QuizzesResult::$passed) {
                    $status_pass = true;
                }
            }

            $quiz->result_status = $status_pass ? QuizzesResult::$passed : $userQuizDone->first()->status;

            if ($quiz->certificate and $quiz->result_status == QuizzesResult::$passed) {
                $canDownloadCertificate = true;
            }
        }

        if (!isset($quiz->attempt) or (count($userQuizDone) < $quiz->attempt and $quiz->result_status !== QuizzesResult::$passed)) {
            $canTryAgainQuiz = true;
        }

        $quiz->can_try = $canTryAgainQuiz;
        $quiz->can_download_certificate = $canDownloadCertificate;

        return $quiz;
    }

    private function checkCanAccessToPrivateCourse($course, $user = null): bool
    {
        if (empty($user)) {
            $user = auth()->user();
        }

        if (empty($user)) {
            $user = apiAuth();
        }

        $canAccess = !$course->private;
        $hasBought = $course->checkUserHasBought($user);

        if (!empty($user) and ($user->id == $course->creator_id or $user->organ_id == $course->creator_id or $user->isAdmin() or $hasBought)) {
            $canAccess = true;
        }

        return $canAccess;
    }

    public function downloadFile($slug, $file_id)
    {
        $webinar = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($webinar) and $this->checkCanAccessToPrivateCourse($webinar)) {
            $file = File::where('webinar_id', $webinar->id)
                ->where('id', $file_id)
                ->first();

            if (!empty($file) and ($file->downloadable || strtolower((string)$file->file_type) === 'pdf')) {
                $canAccess = true;

                if ($file->accessibility == 'paid') {
                    $canAccess = canUserAccessCourseContent($webinar, auth()->user(), $file->chapter) || $file->checkUserHasBought();
                }

                if ($canAccess) {
                    if (in_array($file->storage, ['s3', 'external_link'])) {
                        return redirect($file->file);
                    }

                    if ($file->storage === 'r2') {
                        return $this->downloadR2File($file);
                    }

                    $filePath = public_path($file->file);

                    if (file_exists($filePath)) {
                        $extension = \Illuminate\Support\Facades\File::extension($filePath);

                        $fileName = str_replace(' ', '-', $file->title);
                        $fileName = str_replace('.', '-', $fileName);
                        $fileName .= '.' . $extension;

                        $headers = array(
                            'Content-Type: application/' . $file->file_type,
                        );

                        return response()->download($filePath, $fileName, $headers);
                    }
                } else {
                    $toastData = [
                        'title' => trans('public.not_access_toast_lang'),
                        'msg' => trans('public.not_access_toast_msg_lang'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }
            }
        }

        return back();
    }

    /**
     * Stream downloadable files from private R2 storage.
     */
    private function downloadR2File(File $file)
    {
        try {
            $r2Path = $this->extractR2Path($file->file);

            if (empty($r2Path)) {
                \Log::warning('R2 download: invalid file path', [
                    'file_id' => $file->id,
                    'file_field' => $file->file,
                ]);

                return back();
            }

            $r2Disk = Storage::disk('r2');

            if (!$r2Disk->exists($r2Path)) {
                \Log::warning('R2 download: file not found', [
                    'file_id' => $file->id,
                    'r2_path' => $r2Path,
                ]);

                return back();
            }

            $stream = $r2Disk->readStream($r2Path);

            if ($stream === false) {
                \Log::warning('R2 download: failed to open stream', [
                    'file_id' => $file->id,
                    'r2_path' => $r2Path,
                ]);

                return back();
            }

            $extension = pathinfo($r2Path, PATHINFO_EXTENSION) ?: $file->file_type;
            $fileName = str_replace(' ', '-', $file->title);
            $fileName = str_replace('.', '-', $fileName);
            $fileName .= '.' . $extension;
            $mimeType = $r2Disk->mimeType($r2Path) ?: 'application/octet-stream';

            return response()->streamDownload(function () use ($stream) {
                fpassthru($stream);

                if (is_resource($stream)) {
                    fclose($stream);
                }
            }, $fileName, [
                'Content-Type' => $mimeType,
            ]);
        } catch (\Throwable $e) {
            \Log::error('R2 download error', [
                'file_id' => $file->id,
                'error' => $e->getMessage(),
            ]);
        }

        return back();
    }

    public function showHtmlFile($slug, $file_id)
    {
        $webinar = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($webinar) and $this->checkCanAccessToPrivateCourse($webinar)) {
            $file = File::where('webinar_id', $webinar->id)
                ->where('id', $file_id)
                ->first();

            if (!empty($file)) {
                $canAccess = true;

                if ($file->accessibility == 'paid') {
                    $canAccess = canUserAccessCourseContent($webinar, auth()->user(), $file->chapter) || $file->checkUserHasBought();
                }

                if ($canAccess) {
                    $filePath = $file->interactive_file_path;

                    if (\Illuminate\Support\Facades\File::exists(public_path($filePath))) {
                        $data = [
                            'pageTitle' => $file->title,
                            'path' => url($filePath)
                        ];
                        return view('design_1.web.courses.free_contents.interactive_file', $data);
                    }

                    abort(404);
                } else {
                    $toastData = [
                        'title' => trans('public.not_access_toast_lang'),
                        'msg' => trans('public.not_access_toast_msg_lang'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }
            }
        }

        abort(403);
    }

    public function getFilePath(Request $request)
    {
        $this->validate($request, [
            'file_id' => 'required'
        ]);

        $file_id = $request->get('file_id');

        $file = File::where('id', $file_id)
            ->first();

        if (!empty($file)) {
            $webinar = Webinar::where('id', $file->webinar_id)
                ->where('status', 'active')
                ->with([
                    'files' => function ($query) {
                        $query->select('id', 'webinar_id', 'file_type')
                            ->where('status', 'active')
                            ->orderBy('order', 'asc');
                    }
                ])
                ->first();

            if (!empty($webinar)) {
                $canAccess = true;

                if ($file->accessibility == 'paid') {
                    $canAccess = canUserAccessCourseContent($webinar, auth()->user(), $file->chapter) || $file->checkUserHasBought();
                }

                if ($canAccess) {
                    $path = $file->file;

                    if ($file->storage == 'upload') {
                        $path = url("/course/$webinar->slug/file/$file->id/play");
                    } elseif ($file->storage == 'upload_archive') {
                        $path = url("/course/$webinar->slug/file/$file->id/showHtml");
                    } elseif ($file->storage == 'r2' && $file->isVideo()) {
                        // Never expose worker/signed URLs to the client — use same-origin play route.
                        $path = url("/course/$webinar->slug/file/$file->id/play");
                    }

                    // Use MIME from actual playback path (may be MP4 when original was MKV)
                    $playbackPath = $file->storage === 'r2' && $file->isVideo()
                        ? \App\Helpers\R2Helper::getPreferredPlaybackPath($this->extractR2Path($file->file) ?? '')
                        : $file->file;
                    $mimeType = $file->isVideo() ? \App\Helpers\R2Helper::getMimeTypeFromPath($playbackPath ?: $file->file) : null;

                    return response()->json([
                        'code' => 200,
                        'storage' => $file->storage,
                        'path' => $path,
                        'storageService' => $file->storage,
                        'mime_type' => $mimeType,
                    ], 200);
                }
            }
        }

        abort(403);
    }

    public function playFile($slug, $file_id)
    {
        // Securely stream video files with proper headers for all sources
        $webinar = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!empty($webinar) && $this->checkCanAccessToPrivateCourse($webinar)) {
            $file = File::where('webinar_id', $webinar->id)
                ->where('id', $file_id)
                ->first();

            if (!empty($file)) {
                $canAccess = true;
                if ($file->accessibility == 'paid') {
                    $canAccess = canUserAccessCourseContent($webinar, auth()->user(), $file->chapter) || $file->checkUserHasBought();
                }
                if ($canAccess) {
                    $notVideoSource = ['iframe', 'google_drive', 'dropbox'];
                    if (in_array($file->storage, $notVideoSource)) {
                        $data = [
                            'pageTitle' => $file->title,
                            'iframe' => $file->file
                        ];
                        return view('design_1.web.courses.free_contents.interactive_file', $data);
                    } else if ($file->isVideo()) {
                        if ($file->storage == 'r2') {
                            $workerBase = config('services.stream.worker_base');

                            if (!empty($workerBase)) {
                                return $this->streamR2VideoViaWorker($file, $webinar);
                            }

                            return $this->streamR2Video($file, $webinar);
                        }
                        
                        // Local upload videos
                        $filePath = public_path($file->file);
                        if (!file_exists($filePath)) {
                            abort(404);
                        }
                        $mime = mime_content_type($filePath);
                        $size = filesize($filePath);
                        $start = 0;
                        $length = $size;
                        $headers = [
                            'Content-Type' => $mime,
                            'Content-Disposition' => 'inline; filename="video.mp4"',
                            'Cache-Control' => 'no-store, no-cache, must-revalidate, private, max-age=0',
                            'Pragma' => 'no-cache',
                            'Expires' => '0',
                            'X-Content-Type-Options' => 'nosniff',
                            'X-Frame-Options' => 'SAMEORIGIN',
                            'X-Robots-Tag' => 'noindex, nofollow, noarchive, nosnippet',
                            'Content-Transfer-Encoding' => 'binary',
                            'Accept-Ranges' => 'bytes',
                        ];
                        if (isset($_SERVER['HTTP_RANGE'])) {
                            $range = $_SERVER['HTTP_RANGE'];
                            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                                $start = intval($matches[1]);
                                $end = ($matches[2] !== '') ? intval($matches[2]) : ($size - 1);
                                $length = $end - $start + 1;
                                $headers['Content-Range'] = "bytes $start-$end/$size";
                                $headers['Content-Length'] = $length;
                                http_response_code(206);
                                $stream = function () use ($filePath, $start, $length) {
                                    $fp = fopen($filePath, 'rb');
                                    fseek($fp, $start);
                                    $buffer = 8192;
                                    $bytesSent = 0;
                                    while (!feof($fp) && $bytesSent < $length) {
                                        $read = min($buffer, $length - $bytesSent);
                                        echo fread($fp, $read);
                                        $bytesSent += $read;
                                    }
                                    fclose($fp);
                                };
                                return response()->stream($stream, 206, $headers);
                            }
                        }
                        $headers['Content-Length'] = $size;
                        $stream = function () use ($filePath) {
                            readfile($filePath);
                        };
                        return response()->stream($stream, 200, $headers);
                    }
                }
            }
        }
        abort(403);
    }

    /**
     * Proxy R2 video stream through Laravel so the browser never sees worker URLs.
     */
    private function streamR2VideoViaWorker(File $file, Webinar $webinar)
    {
        $r2Path = $this->extractR2Path($file->file);

        if (empty($r2Path)) {
            return $this->streamR2Video($file, $webinar);
        }

        $playPath = \App\Helpers\R2Helper::getPreferredPlaybackPath($r2Path);
        $token = $this->makeStreamToken($playPath, $file->id);
        $workerBase = config('services.stream.worker_base');
        $workerUrl = rtrim($workerBase, '/') . '/v?t=' . urlencode($token);

        $forwardHeaders = [];

        if (request()->header('Range')) {
            $forwardHeaders['Range'] = request()->header('Range');
        }

        try {
            $workerResponse = Http::withHeaders($forwardHeaders)
                ->withOptions(['stream' => true, 'http_errors' => false])
                ->get($workerUrl);

            $status = $workerResponse->status();

            if (!in_array($status, [200, 206], true)) {
                \Log::warning('Worker stream proxy failed, falling back to Laravel R2 stream', [
                    'file_id' => $file->id,
                    'status' => $status,
                ]);

                return $this->streamR2Video($file, $webinar);
            }

            $responseHeaders = [
                'Content-Type' => $workerResponse->header('Content-Type') ?: 'video/mp4',
                'Content-Disposition' => 'inline; filename="video.mp4"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, private, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'SAMEORIGIN',
                'X-Robots-Tag' => 'noindex, nofollow, noarchive, nosnippet',
                'Accept-Ranges' => 'bytes',
            ];

            foreach (['Content-Length', 'Content-Range'] as $headerName) {
                $headerValue = $workerResponse->header($headerName);

                if (!empty($headerValue)) {
                    $responseHeaders[$headerName] = $headerValue;
                }
            }

            return response()->stream(function () use ($workerResponse) {
                $body = $workerResponse->toPsrResponse()->getBody();

                while (!$body->eof()) {
                    echo $body->read(8192);
                }
            }, $status, $responseHeaders);
        } catch (\Throwable $e) {
            \Log::error('Worker stream proxy exception, falling back to Laravel R2 stream', [
                'file_id' => $file->id,
                'error' => $e->getMessage(),
            ]);

            return $this->streamR2Video($file, $webinar);
        }
    }

    /**
     * Stream R2 video files with all security protections
     * 
     * @param File $file
     * @param Webinar $webinar
     * @return \Illuminate\Http\Response
     */
    private function streamR2Video(File $file, Webinar $webinar)
    {
        try {
            // Extract R2 path from file field (handles both URLs and paths)
            $r2Path = $this->extractR2Path($file->file);
            
            if (empty($r2Path)) {
                \Log::error('R2 video: Could not extract path', [
                    'file_id' => $file->id,
                    'file_field' => $file->file
                ]);
                abort(404, 'R2 video file path not found');
            }

            // Prefer MP4 for playback when it exists (fixes black screen for MKV/AVI/WMV)
            $r2Path = \App\Helpers\R2Helper::getPreferredPlaybackPath($r2Path);

            // Check if file exists in R2
            $r2Disk = Storage::disk('r2');
            if (!$r2Disk->exists($r2Path)) {
                \Log::error('R2 video: File not found in R2', [
                    'file_id' => $file->id,
                    'r2_path' => $r2Path
                ]);
                abort(404, 'R2 video file not found');
            }

            // Get file size and mime type
            $size = $r2Disk->size($r2Path);
            $mime = $r2Disk->mimeType($r2Path) ?: 'video/mp4';

            // Get S3Client from adapter for Range requests
            // FilesystemAdapter -> getAdapter() -> AwsS3V3Adapter -> getClient()
            $adapter = $r2Disk->getAdapter();
            
            // AwsS3V3Adapter has getClient() method
            if (!method_exists($adapter, 'getClient')) {
                \Log::error('R2 adapter does not have getClient method', [
                    'adapter_class' => get_class($adapter)
                ]);
                abort(500, 'R2 adapter configuration error');
            }
            
            $s3Client = $adapter->getClient();
            $bucket = config('r2.bucket');
            
            if (!$s3Client instanceof S3Client) {
                \Log::error('R2 adapter client is not S3Client', [
                    'client_class' => get_class($s3Client)
                ]);
                abort(500, 'R2 client configuration error');
            }

            // Enhanced security headers
            $headers = [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="video.mp4"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, private, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'SAMEORIGIN',
                'X-Robots-Tag' => 'noindex, nofollow, noarchive, nosnippet',
                'Content-Transfer-Encoding' => 'binary',
                'Accept-Ranges' => 'bytes',
            ];

            // Handle range requests for video seeking
            $start = 0;
            $end = $size - 1;
            $isRangeRequest = false;
            
            if (isset($_SERVER['HTTP_RANGE'])) {
                $range = $_SERVER['HTTP_RANGE'];
                if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                    $isRangeRequest = true;
                    $start = intval($matches[1]);
                    $end = ($matches[2] !== '') ? intval($matches[2]) : ($size - 1);
                    
                    // Validate range
                    if ($start >= $size) {
                        // Invalid range - return 416 Range Not Satisfiable
                        $headers['Content-Range'] = "bytes */$size";
                        return response('', 416, $headers);
                    }
                    
                    // Clamp end to size-1
                    if ($end >= $size) {
                        $end = $size - 1;
                    }
                    
                    // Ensure end >= start
                    if ($end < $start) {
                        $end = $start;
                    }
                    
                    $length = $end - $start + 1;
                    $headers['Content-Range'] = "bytes $start-$end/$size";
                    $headers['Content-Length'] = $length;
                }
            }

            // Stream using AWS SDK Range header (server-side range, no fseek)
            if ($isRangeRequest) {
                return response()->stream(function () use ($s3Client, $bucket, $r2Path, $start, $end) {
                    $this->streamR2FileWithRange($s3Client, $bucket, $r2Path, $start, $end);
                }, 206, $headers);
            }

            // Full file stream
            $headers['Content-Length'] = $size;
            return response()->stream(function () use ($s3Client, $bucket, $r2Path, $size) {
                $this->streamR2FileWithRange($s3Client, $bucket, $r2Path, 0, $size - 1);
            }, 200, $headers);

        } catch (\Exception $e) {
            \Log::error('R2 video streaming error', [
                'file_id' => $file->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Error streaming R2 video');
        }
    }

    /**
     * Generate signed token for Cloudflare Worker streaming
     * 
     * @param string $key R2 object key/path
     * @param int $fileId File ID
     * @param int|null $ttlSeconds Token TTL in seconds (default from config)
     * @return string Base64url-encoded token (payload.signature)
     */
    private function makeStreamToken(string $key, int $fileId, ?int $ttlSeconds = null): string
    {
        $secret = config('services.stream.token_secret');
        
        if (empty($secret)) {
            \Log::error('STREAM_TOKEN_SECRET not configured');
            throw new \RuntimeException('Stream token secret not configured');
        }

        // Keep token valid long enough for typical lessons even if env is misconfigured too low.
        $configuredTtl = (int) config('services.stream.token_ttl', 120);
        $ttl = $ttlSeconds ?? max(1800, $configuredTtl);
        
        $payload = [
            'key' => $key,
            'fid' => $fileId,
            'exp' => time() + $ttl,
        ];

        // Base64url encode payload
        $payloadB64 = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');
        
        // HMAC-SHA256 signature
        $sig = hash_hmac('sha256', $payloadB64, $secret, true);
        $sigB64 = rtrim(strtr(base64_encode($sig), '+/', '-_'), '=');

        return $payloadB64 . '.' . $sigB64;
    }

    /**
     * Extract R2 path from file field (handles URLs and paths)
     * 
     * @param string $fileField
     * @return string|null
     */
    private function extractR2Path($fileField)
    {
        if (empty($fileField)) {
            return null;
        }

        // If it's already a path (not a URL), return as-is
        if (!filter_var($fileField, FILTER_VALIDATE_URL)) {
            return ltrim($fileField, '/');
        }

        // Extract path from URL
        $parsedUrl = parse_url($fileField);
        
        if (!isset($parsedUrl['path'])) {
            return null;
        }

        $path = ltrim($parsedUrl['path'], '/');
        $bucket = config('r2.bucket');

        // Remove bucket name if present (path-style endpoint)
        if (!empty($bucket) && strpos($path, $bucket . '/') === 0) {
            $path = substr($path, strlen($bucket) + 1);
        }

        return $path;
    }

    /**
     * Stream R2 file using AWS SDK Range header (server-side range, fast and reliable)
     * 
     * @param \Aws\S3\S3Client $s3Client
     * @param string $bucket
     * @param string $r2Path
     * @param int $start
     * @param int $end
     * @return void
     */
    private function streamR2FileWithRange($s3Client, $bucket, $r2Path, $start, $end)
    {
        try {
            // Use AWS SDK getObject with Range header (server-side range, no fseek needed)
            $result = $s3Client->getObject([
                'Bucket' => $bucket,
                'Key' => $r2Path,
                'Range' => "bytes=$start-$end",
            ]);

            // Get the stream from the result (AWS SDK returns Guzzle Stream object)
            $stream = $result['Body'];
            
            // AWS SDK returns GuzzleHttp\Psr7\Stream, not a PHP resource
            if (!is_object($stream) || !method_exists($stream, 'read')) {
                \Log::error('R2 stream: Invalid stream from S3Client', [
                    'path' => $r2Path,
                    'stream_type' => gettype($stream)
                ]);
                return;
            }

            // Stream in optimal chunks (512KB for good performance, no delays)
            $chunkSize = 524288; // 512KB - good balance between performance and memory
            
            while (!$stream->eof()) {
                $chunk = $stream->read($chunkSize);
                
                // read() returns false on error, empty string on EOF
                if ($chunk === false) {
                    \Log::warning('R2 stream: Read error', ['path' => $r2Path]);
                    break;
                }
                
                if (strlen($chunk) === 0) {
                    break;
                }

                echo $chunk;
                flush();
            }

            // Stream is automatically closed by AWS SDK when it goes out of scope

        } catch (\Exception $e) {
            \Log::error('R2 stream range error', [
                'path' => $r2Path,
                'start' => $start,
                'end' => $end,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getLesson(Request $request, $slug, $lesson_id)
    {
        $user = null;

        if (auth()->check()) {
            $user = auth()->user();
        }

        $course = Webinar::where('slug', $slug)
            ->where('status', 'active')
            ->with(['teacher', 'textLessons' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->first();

        if (!empty($course) and $this->checkCanAccessToPrivateCourse($course)) {
            $textLesson = TextLesson::where('id', $lesson_id)
                ->where('webinar_id', $course->id)
                ->where('status', WebinarChapter::$chapterActive)
                ->with([
                    'attachments' => function ($query) {
                        $query->with('file');
                    },
                    'learningStatus' => function ($query) use ($user) {
                        $query->where('user_id', !empty($user) ? $user->id : null);
                    }
                ])
                ->first();

            if (!empty($textLesson)) {
                $userHasBought = $course->checkUserHasBought($user);
                $canAccess = $userHasBought;

                if ($textLesson->accessibility == 'paid' and !$canAccess) {
                    $toastData = [
                        'title' => trans('public.request_failed'),
                        'msg' => trans('cart.you_not_purchased_this_course'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $checkSequenceContent = $textLesson->checkSequenceContent();
                $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

                if (!empty($checkSequenceContent) and $sequenceContentHasError) {
                    $toastData = [
                        'title' => trans('public.request_failed'),
                        'msg' => ($checkSequenceContent['all_passed_items_error'] ? $checkSequenceContent['all_passed_items_error'] . ' - ' : '') . ($checkSequenceContent['access_after_day_error'] ?? ''),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                $nextLesson = null;
                $previousLesson = null;
                if (!empty($course->textLessons) and count($course->textLessons)) {
                    $nextLesson = $course->textLessons->where('order', '>', $textLesson->order)->first();
                    $previousLesson = $course->textLessons->where('order', '<', $textLesson->order)->first();
                }

                if (!empty($nextLesson)) {
                    $nextLesson->not_purchased = ($nextLesson->accessibility == 'paid' and !$canAccess);
                }


                $data = [
                    'pageTitle' => $textLesson->title,
                    'textLesson' => $textLesson,
                    'course' => $course,
                    'nextLesson' => $nextLesson,
                    'previousLesson' => $previousLesson,
                    'userHasBought' => $userHasBought,
                ];

                return view('design_1.web.courses.free_contents.text_lesson', $data);
            }
        }

        abort(404);
    }

    public function free(Request $request, $slug)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $course = Webinar::where('slug', $slug)
                ->where('status', 'active')
                ->first();

            if (!empty($course)) {
                $checkCourseForSale = checkCourseForSale($course, $user);

                if ($checkCourseForSale != 'ok') {
                    return $checkCourseForSale;
                }

                if (!empty($course->price) and $course->price > 0) {
                    $toastData = [
                        'title' => trans('cart.fail_purchase'),
                        'msg' => trans('cart.course_not_free'),
                        'status' => 'error'
                    ];
                    return back()->with(['toast' => $toastData]);
                }

                Sale::create([
                    'buyer_id' => $user->id,
                    'seller_id' => $course->creator_id,
                    'webinar_id' => $course->id,
                    'type' => Sale::$webinar,
                    'payment_method' => Sale::$credit,
                    'amount' => 0,
                    'total_amount' => 0,
                    'created_at' => time(),
                ]);

                $notifyOptions = [
                    '[u.name]' => $user->full_name,
                    '[u.mobile]' => $user->mobile,
                    '[c.title]' => $course->title,
                    '[amount]' => trans('public.free'),
                    '[time.date]' => dateTimeFormat(time(), 'j M Y H:i'),
                ];
                sendNotification("new_course_enrollment", $notifyOptions, 1);

                $toastData = [
                    'title' => '',
                    'msg' => trans('cart.success_pay_msg_for_free_course'),
                    'status' => 'success'
                ];
                return back()->with(['toast' => $toastData]);
            }

            abort(404);
        } else {
            return redirect('/login');
        }
    }

    public function learningStatus(Request $request, $slug)
    {
        if (!auth()->check()) {
            abort(403);
        }

        $user = auth()->user();
        $course = Webinar::where('slug', $slug)->first();

        if (empty($course)) {
            abort(404);
        }

        $data = $request->all();
        $itemKey = $data['item'] ?? null;   // file_id | session_id | text_lesson_id
        $itemId  = $data['item_id'] ?? null;
        $status  = $data['status'] ?? null;

        $allowedItems = ['file_id', 'session_id', 'text_lesson_id'];

        if (empty($itemKey) || empty($itemId) || !in_array($itemKey, $allowedItems, true)) {
            abort(403);
        }

        // Detect the item's chapter so we can use section-based access
        $chapter = null;
        switch ($itemKey) {
            case 'file_id':
                $file = \App\Models\File::with('chapter')->find($itemId);
                $chapter = $file->chapter ?? null;
                break;

            case 'session_id':
                $session = \App\Models\Session::with('chapter')->find($itemId);
                $chapter = $session->chapter ?? null;
                break;

            case 'text_lesson_id':
                $textLesson = \App\Models\TextLesson::with('chapter')->find($itemId);
                $chapter = $textLesson->chapter ?? null;
                break;
        }

        $hasFullCourse = $course->checkUserHasBought($user);
        $hasSectionAccess = !empty($chapter) && canUserAccessCourseContent($course, $user, $chapter);

        if (!$hasFullCourse && !$hasSectionAccess) {
            abort(403);
        }

        CourseLearning::where('user_id', $user->id)
            ->where($itemKey, $itemId)
            ->delete();

        if ($status && $status === "true") {
            CourseLearning::create([
                'user_id'    => $user->id,
                $itemKey     => $itemId,
                'created_at' => time(),
            ]);
        }

        // check for certificate
        $course->makeCertificateForUser($user);

        $percent = $course->getProgress(true);

        return response()->json([
            'code' => 200,
            'learning_progress_percent' => $percent,
            'title' => trans('public.request_success'),
            'msg' => trans('update.section_learning_status_changed_successful'),
        ]);
    }

    public function autoMarkComplete(Request $request, $slug)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $course = Webinar::where('slug', $slug)->first();

            if (!empty($course)) {
                $data = $request->all();

                $item = $data['item'] ?? null;
                $item_id = $data['item_id'] ?? null;
                $allowedItems = ['file_id', 'session_id', 'text_lesson_id'];

                if (empty($item) || empty($item_id) || !in_array($item, $allowedItems, true)) {
                    abort(403);
                }

                // Detect the item's chapter so section-access users can auto-complete accessible content
                $chapter = null;
                switch ($item) {
                    case 'file_id':
                        $file = \App\Models\File::with('chapter')->find($item_id);
                        $chapter = $file->chapter ?? null;
                        break;

                    case 'session_id':
                        $session = \App\Models\Session::with('chapter')->find($item_id);
                        $chapter = $session->chapter ?? null;
                        break;

                    case 'text_lesson_id':
                        $textLesson = \App\Models\TextLesson::with('chapter')->find($item_id);
                        $chapter = $textLesson->chapter ?? null;
                        break;
                }

                $hasFullCourse = $course->checkUserHasBought($user);
                $hasSectionAccess = !empty($chapter) && canUserAccessCourseContent($course, $user, $chapter);

                if (!$hasFullCourse && !$hasSectionAccess) {
                    abort(403);
                }

                // Check if already marked as complete
                $exists = CourseLearning::where('user_id', $user->id)
                    ->where($item, $item_id)
                    ->first();

                if (empty($exists)) {
                    CourseLearning::create([
                        'user_id' => $user->id,
                        $item => $item_id,
                        'created_at' => time()
                    ]);

                    // Check for certificate
                    $course->makeCertificateForUser($user);

                    $percent = $course->getProgress(true);

                    return response()->json([
                        'code' => 200,
                        'learning_progress_percent' => $percent,
                        'already_completed' => false,
                        'title' => trans('public.request_success'),
                        'msg' => trans('update.section_auto_completed_successful'),
                    ]);
                }

                return response()->json([
                    'code' => 200,
                    'already_completed' => true,
                ]);
            }
        }

        abort(403);
    }

    public function learningStatusCompletedModal($slug)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $course = Webinar::where('slug', $slug)->first();

            if (!empty($course) and $course->checkUserHasBought($user)) {
                $percent = $course->getProgress(true);

                if ($percent >= 100) {
                    $courseCertificate = Certificate::where('type', 'course')
                        ->where('student_id', $user->id)
                        ->where('webinar_id', $course->id)
                        ->first();

                    $data = [
                        'course' => $course,
                        'courseCertificate' => $courseCertificate,
                        'percent' => $percent,
                        'user' => $user,
                    ];

                    $html = (string)view()->make("design_1.web.courses.learning_page.includes.modals.learning_status_completed_modal", $data);

                    return response()->json([
                        'code' => 200,
                        'html' => $html,
                    ]);
                }
            }
        }

        return response()->json([], 403);
    }

    public function directPayment(Request $request)
    {
        $user = auth()->user();

        if (!empty($user) and !empty(getFeaturesSettings('direct_classes_payment_button_status'))) {
            $this->validate($request, [
                'item_id' => 'required',
                'item_name' => 'nullable',
            ]);

            $data = $request->except('_token');

            $webinarId = $data['item_id'];
            $ticketId = $data['ticket_id'] ?? null;

            $webinar = Webinar::where('id', $webinarId)
                ->where('private', false)
                ->where('status', 'active')
                ->first();

            if (!empty($webinar)) {
                $checkCourseForSale = checkCourseForSale($webinar, $user);

                if ($checkCourseForSale != 'ok') {
                    return $checkCourseForSale;
                }

                $fakeCarts = collect();

                $fakeCart = new Cart();
                $fakeCart->creator_id = $user->id;
                $fakeCart->webinar_id = $webinarId;
                $fakeCart->ticket_id = $ticketId;
                $fakeCart->special_offer_id = null;
                $fakeCart->created_at = time();

                $fakeCarts->add($fakeCart);

                $cartController = new CartController();

                return $cartController->checkout(new Request(), $fakeCarts);
            }
        }

        abort(404);
    }
}
