<?php

use Illuminate\Support\Facades\Route;

// TEMPORARY: Include Geidea diagnostic routes (REMOVE AFTER TESTING)
if (file_exists(__DIR__ . '/web-geidea-test.php')) {
    require __DIR__ . '/web-geidea-test.php';
}
if (file_exists(__DIR__ . '/geidea-debug.php')) {
    require __DIR__ . '/geidea-debug.php';
}
if (file_exists(__DIR__ . '/geidea-diagnostic.php')) {
    require __DIR__ . '/geidea-diagnostic.php';
}
if (file_exists(__DIR__ . '/geidea-return.php')) {
    require __DIR__ . '/geidea-return.php';
}

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'my_api', 'namespace' => 'Api\Panel', 'middleware' => ['signed', 'x_frame_headers'], 'as' => 'my_api.web.'], function () {
    Route::get('checkout/{user}', 'CartController@webCheckoutRender')->name('checkout');
    Route::get('/charge/{user}', 'PaymentsController@webChargeRender')->name('charge');
    Route::get('/subscribe/{user}/{subscribe}', 'SubscribesController@webPayRender')->name('subscribe');
    Route::get('/registration_packages/{user}/{package}', 'RegistrationPackagesController@webPayRender')->name('registration_packages');
    Route::get('/courses/learning_file/{user}', 'CoursesLearningContent@renderWebUrl')->name('courses_learning_file');
});

Route::group(['prefix' => 'api_sessions'], function () {
    Route::get('/{session_id}/big_blue_button', ['uses' => 'Api\Panel\SessionController@BigBlueButton'])->name('big_blue_button');
    Route::get('/agora', ['uses' => 'Api\Panel\SessionController@agora'])->name('agora');

});

// R2 Course-Assets proxy (thumbnail, cover, icon, demo video) when R2_PUBLIC_URL is not set
Route::get('/r2-asset/{path}', 'Web\R2AssetController@show')->where('path', '.*')->name('r2.asset');

Route::get('/mobile-app', 'Web\MobileAppController@index')->middleware(['share', 'impersonate'])->name('mobileAppRoute');
Route::get('/maintenance', 'Web\MaintenanceController@index')->middleware(['share', 'impersonate'])->name('maintenanceRoute');
Route::get('/restriction', 'Web\RestrictionController@index')->middleware(['share', 'impersonate'])->name('restrictionRoute');
Route::get('/sitemap.xml', 'Web\SitemapController@index')->name('sitemap.index');
Route::get('/sitemaps/main.xml', 'Web\SitemapController@main')->name('sitemap.main');
Route::get('/sitemaps/courses.xml', 'Web\SitemapController@courses')->name('sitemap.courses');
Route::get('/sitemaps/blog.xml', 'Web\SitemapController@blog')->name('sitemap.blog');
Route::get('/sitemaps/products.xml', 'Web\SitemapController@products')->name('sitemap.products');
Route::get('/sitemaps/teachers.xml', 'Web\SitemapController@teachers')->name('sitemap.teachers');

Route::group(['prefix' => 'cookie-security', 'middleware' => ['share', 'impersonate']], function () {
    Route::post('/all', 'Web\CookieSecurityController@setAll');
    Route::get('/customize-modal', 'Web\CookieSecurityController@getCustomizeModal');
    Route::post('/customize', 'Web\CookieSecurityController@setCustomize');
});

// Captcha
Route::group(['prefix' => 'captcha'], function () {
    Route::post('create', function () {
        $response = ['status' => 'success', 'captcha_src' => captcha_src('flat')];

        return response()->json($response);
    });
    Route::get('{config?}', '\Mews\Captcha\CaptchaController@getCaptcha');
});


/* Emergency Database Update */
Route::get('/emergencyDatabaseUpdate', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate', [
        '--force' => true
    ]);
    $msg1 = \Illuminate\Support\Facades\Artisan::output();

    \Illuminate\Support\Facades\Artisan::call('db:seed', [
        '--force' => true
    ]);
    $msg2 = \Illuminate\Support\Facades\Artisan::output();

    \Illuminate\Support\Facades\Artisan::call('clear:all', [
        '--force' => true
    ]);

    return response()->json([
        'migrations' => $msg1,
        'sections' => $msg2,
    ]);
});

Route::group(['namespace' => 'Auth', 'middleware' => ['check_mobile_app', 'share', 'check_maintenance', 'check_restriction']], function () {
    Route::get('/login', 'LoginController@showLoginForm');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout');
    Route::get('/register', 'RegisterController@showRegistrationForm');
    Route::get('/register/step/{step}', 'RegisterController@showStep'); // Show specific registration step
    Route::post('/register/step/{step}', 'RegisterController@stepRegister'); // New 3-step registration
    Route::get('/verify-email/{token}', '\App\Http\Controllers\Api\Auth\VerifyEmailController@verify'); // Email verification link
    Route::post('/register', 'RegisterController@register'); // Old single-step (deprecated)
    Route::post('/register/form-fields', 'RegisterController@getFormFieldsByUserType');
    Route::get('/verification', 'VerificationController@index');
    Route::post('/verification', 'VerificationController@confirmCode');
    Route::get('/verification/resend', 'VerificationController@resendCode');
    Route::get('/forget-password', 'ForgotPasswordController@showLinkRequestForm');
    Route::post('/forget-password', 'ForgotPasswordController@forgot');
    Route::get('reset-password/{token}', 'ResetPasswordController@showResetForm');
    Route::post('/reset-password', 'ResetPasswordController@updatePassword');
    Route::get('/google', 'SocialiteController@redirectToGoogle');
    Route::get('/google/callback', 'SocialiteController@handleGoogleCallback');
    Route::get('/facebook/redirect', 'SocialiteController@redirectToFacebook');
    Route::get('/facebook/callback', 'SocialiteController@handleFacebookCallback');
    Route::get('/reff/{code}', 'ReferralController@referral');
});

Route::group(['namespace' => 'Web', 'middleware' => ['check_mobile_app', 'impersonate', 'share', 'check_maintenance', 'check_restriction']], function () {
    // set Locale
    Route::post('/locale', 'LocaleController@setLocale')->name('appLocaleRoute');

    // set Currency
    Route::post('/set-currency', 'SetCurrencyController@setCurrency');

    // set Theme Color Mode
    Route::post('/set-theme-color-mode', 'SetThemeColorModeController@setColorMode');

    Route::get('/', 'HomeController@index');

    Route::get('/getDefaultAvatar', 'DefaultAvatarController@make');

    Route::post('/get-advertising-modal', 'AdvertisingModalController@getModal');

    Route::group(['prefix' => 'course'], function () {
        Route::get('/{slug}', 'WebinarController@course');
        Route::get('/{slug}/file/{file_id}/download', 'WebinarController@downloadFile');
        Route::get('/{slug}/file/{file_id}/showHtml', 'WebinarController@showHtmlFile');
        Route::get('/{slug}/lessons/{lesson_id}/read', 'WebinarController@getLesson');
        Route::post('/getFilePath', 'WebinarController@getFilePath');
        Route::get('/{slug}/file/{file_id}/play', 'WebinarController@playFile');
        Route::get('/{slug}/free', 'WebinarController@free');
        Route::get('/{slug}/learning-status-completed-modal', 'WebinarController@learningStatusCompletedModal');
        
        Route::group(['middleware' => 'web.auth'], function () {
            Route::post('/{id}/report', 'WebinarController@reportWebinar');
            Route::post('/{slug}/learningStatus', 'WebinarController@learningStatus');
        });
        Route::get('/{slug}/share-modal', 'WebinarController@getShareModal');
        Route::get('/{slug}/report-modal', 'WebinarController@getReportModal');

        /* Course Points */
        Route::group(['prefix' => '/{slug}/points'], function () {
            Route::get('/apply', 'WebinarController@buyWithPoint');
            Route::get('/get-modal', 'WebinarController@getBuyWithPointModal');
        });

        /* Course waitlist */
        Route::group(['prefix' => '/{slug}/waitlists'], function () {
            Route::post('/join', 'WaitlistController@store');
            Route::get('/get-modal', 'WaitlistController@getWaitlistModal');
        });

        /* Review Load More */
        Route::post('/{slug}/reviews/load-more', 'WebinarReviewController@getReviewsByCourseSlug');

        Route::group(['middleware' => 'web.auth'], function () {
            Route::get('/{slug}/installments', 'WebinarController@getInstallmentsByCourse');

            Route::post('/learning/{slug}/itemInfo', 'LearningPageController@getItemInfo');
            Route::get('/learning/{slug}/itemSequenceContentInfo', 'LearningPageController@getItemSequenceContentInfo');
            Route::get('/learning/{slug}/noticeboards', 'LearningPageController@noticeboards');
            Route::post('/learning/{slug}/track-time', 'LearningPageController@trackSpentTime');
            Route::post('/learning/{slug}/video-performance', 'LearningPageController@trackVideoPerformance');
            Route::post('/learning/{slug}/autoMarkComplete', 'WebinarController@autoMarkComplete');
            Route::get('/learning/{slug}', 'LearningPageController@index');


            /* Assignment */
            Route::group(['prefix' => '/assignment/{assignmentId}'], function () {
                Route::get('/download/{id}/attach', 'LearningPageController@downloadAssignment');
                Route::post('/history/{historyId}/message', 'AssignmentHistoryController@storeMessage');
                Route::get('/history/{historyId}/grade-modal', 'AssignmentHistoryController@getGradeModal');
                Route::post('/history/{historyId}/setGrade', 'AssignmentHistoryController@setGrade');
                Route::get('/history/{historyId}/message/{messageId}/downloadAttach', 'AssignmentHistoryController@downloadAttach');
            });

            Route::group(['prefix' => '/learning/{slug}/forum'], function () { // LearningPageForumTrait
                Route::get('/', 'LearningPageController@forum');
                Route::get('/create', 'LearningPageController@getAskQuestionModal');
                Route::post('/store', 'LearningPageController@forumStoreNewQuestion');
                Route::get('/{forumId}/edit', 'LearningPageController@getForumForEdit');
                Route::post('/{forumId}/update', 'LearningPageController@updateForum');
                Route::post('/{forumId}/pinToggle', 'LearningPageController@forumPinToggle');
                Route::get('/{forumId}/downloadAttach', 'LearningPageController@forumDownloadAttach');

                Route::group(['prefix' => '/{forumId}/answers'], function () {
                    Route::get('/', 'LearningPageController@getForumAnswers');
                    Route::post('/', 'LearningPageController@storeForumAnswers');
                    Route::get('/{answerId}/edit', 'LearningPageController@answerEdit');
                    Route::post('/{answerId}/update', 'LearningPageController@answerUpdate');
                    Route::get('/{answerId}/mark-as-resolved', 'LearningPageController@answerMarkAsResolvedModal');
                    Route::post('/{answerId}/mark-as-resolved', 'LearningPageController@answerMarkAsResolved');
                    Route::post('/{answerId}/{togglePinOrResolved}', 'LearningPageController@answerTogglePinOrResolved');
                });
            });

            Route::group(['prefix' => '/learning/{slug}/personal-note'], function () {
                Route::get('/get-form', 'LearningPageController@getPersonalNoteForm');
                Route::get('/get-details', 'LearningPageController@getPersonalNoteDetails');
                Route::post('/store', 'LearningPageController@storePersonalNote');
            });

            Route::post('/direct-payment', 'WebinarController@directPayment');

            Route::group(['prefix' => 'personal-notes'], function () {
                Route::get('/{id}/delete', 'CoursePersonalNotesController@deleteAttachment');
                Route::get('/{id}/download-attachment', 'CoursePersonalNotesController@downloadAttachment');
            });
        });
    });

    Route::group(['prefix' => 'certificate_validation'], function () {
        Route::get('/', 'CertificateValidationController@index');
        Route::post('/validate', 'CertificateValidationController@checkValidate');
    });


    Route::group(['prefix' => 'cart'], function () {
        Route::get('/get-drawer-info', 'CartManagerController@getDrawerInfo');
        
        Route::group(['middleware' => 'web.auth'], function () {
            Route::post('/store', 'CartManagerController@store');
            Route::post('/{id}/quantity', 'CartManagerController@quantity');
            Route::get('/{id}/delete', 'CartManagerController@destroy');
        });
    });
    
    // Subject search/create (used by registration step 3 and become-instructor; no auth required)
    Route::group(['prefix' => 'become-instructor'], function () {
        Route::get('/search-subjects', 'BecomeInstructorController@searchSubjects');
        Route::post('/create-subject', 'BecomeInstructorController@createSubject');
    });
    
    Route::group(['middleware' => 'web.auth'], function () {
        
        Route::group(['prefix' => 'reviews'], function () {
            Route::post('/store', 'WebinarReviewController@store');
            Route::post('/store-reply-comment', 'WebinarReviewController@storeReplyComment');
            Route::get('/{id}/delete', 'WebinarReviewController@destroy');
            Route::get('/{id}/delete-comment/{commentId}', 'WebinarReviewController@destroy');
        });

        Route::group(['prefix' => 'favorites'], function () {
            Route::get('{slug}/toggle', 'FavoriteController@toggle');
            Route::post('/{id}/update', 'FavoriteController@update');
            Route::get('/{id}/delete', 'FavoriteController@destroy');
        });

        Route::group(['prefix' => 'comments'], function () {
            Route::post("/lists/{itemType}/{itemId}", 'CommentController@getComments');
            Route::post('/store', 'CommentController@store');
            Route::post('/{id}/reply', 'CommentController@storeReply');
            Route::post('/{id}/update', 'CommentController@update');
            Route::post('/{id}/report', 'CommentController@report');
            Route::get('/{id}/delete', 'CommentController@destroy');
            Route::get('/get-report-modal', 'CommentController@getReportModal');
        });

        Route::group(['prefix' => 'cart'], function () {
            Route::get('/', 'CartController@index');

            Route::post('/coupon/validate', 'CartController@couponValidate');
            Route::get('/checkout', function () {
                return redirect('/cart');
            });
            Route::post('/checkout', 'CartController@checkout')->name('checkout');
        });

        Route::group(['prefix' => 'users'], function () {
            Route::post('/{username}/follow', 'UserProfileController@followToggle');
        });

        Route::group(['prefix' => 'become-instructor'], function () {
            Route::get('/', 'BecomeInstructorController@index')->name('becomeInstructor');
            Route::get('/packages', 'BecomeInstructorController@packages')->name('becomeInstructorPackages');
            Route::get('/packages/{id}/checkHasInstallment', 'BecomeInstructorController@checkPackageHasInstallment');
            Route::get('/packages/{id}/installments', 'BecomeInstructorController@getInstallmentsByRegistrationPackage');
            Route::post('/store', 'BecomeInstructorController@store');
            Route::post('/form-fields', 'BecomeInstructorController@getFormFieldsByUserType');
        });

    });

    /*********
     * Profile Routes
     ******* */
    Route::group(['prefix' => 'users'], function () {
        Route::get('/{username}/profile', 'UserProfileController@profile');
        Route::post('/{username}/get-courses', 'UserProfileController@getUserCourses');
        Route::post('/{username}/get-products', 'UserProfileController@getUserProducts');
        Route::post('/{username}/get-posts', 'UserProfileController@getUserPosts');
        Route::post('/{username}/get-topics', 'UserProfileController@getUserForumTopics');
        Route::post('/{username}/get-instructors', 'UserProfileController@getOrganizationInstructors');
        Route::post('/{username}/availableTimes', 'UserProfileController@availableTimes');
        Route::post('/search', 'UserController@search');

        Route::group(['middleware' => 'web.auth'], function () {
            Route::get('/{username}/get-send-message-form', 'UserProfileController@getSendMessageForm');
            Route::post('/{username}/send-message', 'UserProfileController@sendMessage');
        });

        /*********
         * Meeting Routes
         ******* */
        Route::group(['prefix' => '{username}/meetings'], function () {
            Route::get('/', 'MeetingController@index');
            Route::get('/overview', 'MeetingController@overview');
            Route::post('/get-amount', 'MeetingController@getMeetingAmount');

            Route::group(['middleware' => 'web.auth'], function () {
                Route::post('/reserve', 'MeetingController@reserve');
            });
        });

    });

    Route::group(['prefix' => 'payments'], function () {
        Route::post('/payment-request', 'PaymentController@paymentRequest');
        Route::get('/verify/{gateway}', ['as' => 'payment_verify', 'uses' => 'PaymentController@paymentVerify']);
        Route::post('/verify/{gateway}', ['as' => 'payment_verify_post', 'uses' => 'PaymentController@paymentVerify']);
        Route::get('/status', 'PaymentController@payStatus');
    });

    Route::group(['prefix' => 'subscribes', 'middleware' => 'web.auth'], function () {
        Route::get('/apply/bundle/{bundleSlug}', 'SubscribeController@bundleApply');
        Route::get('/apply/{webinarSlug}', 'SubscribeController@apply');
    });

    Route::group(['prefix' => 'search'], function () {
        Route::get('/', 'SearchController@index');
    });

    Route::group(['prefix' => 'tags'], function () {
        Route::get('/{type}/{tag}', 'TagsController@index');
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/{categoryTitle}/{subCategoryTitle?}', 'CategoriesController@index');
    });

    Route::get('/classes', 'ClassesController@index');

    Route::get('/reward-courses', 'RewardCoursesController@index');

    Route::group(['prefix' => 'blog'], function () {
        Route::get('/', 'BlogController@index');
        Route::get('/categories/{category}', 'BlogController@index');
        Route::get('/{slug}', 'BlogController@show');
        Route::get('/{slug}/share-modal', 'BlogController@getShareModal');
    });

    Route::group(['prefix' => 'contact'], function () {
        Route::get('/', 'ContactController@index');
        Route::post('/store', 'ContactController@store');
    });

    Route::get('/about', function () {
        return view('design_1.web.pages.about');
    });

    Route::get('/terms', function () {
        return view('design_1.web.pages.terms');
    });

    Route::get('/privacy', function () {
        return view('design_1.web.pages.privacy');
    });

    Route::get('/cookie', function () {
        return view('design_1.web.pages.cookie');
    });

    Route::get('/faq', function () {
        return view('design_1.web.pages.faq');
    });

    Route::group(['prefix' => 'instructors'], function () {
        Route::get('/', 'InstructorsController@instructors');
    });

    Route::group(['prefix' => 'organizations'], function () {
        Route::get('/', 'InstructorsController@organizations');
    });

    Route::group(['prefix' => 'pages'], function () {
        Route::get('/{link}', 'PagesController@index');
    });

    Route::post('/newsletters', 'UserController@makeNewsletter');

    Route::group(['prefix' => 'jobs'], function () {
        Route::get('/{methodName}', 'JobsController@index');
        Route::post('/{methodName}', 'JobsController@index');
    });

    Route::group(['prefix' => 'regions'], function () {
        Route::get('/countries', 'RegionController@allCountries');
        Route::get('/provincesByCountry/{countryId}', 'RegionController@provincesByCountry');
        Route::get('/citiesByProvince/{provinceId}', 'RegionController@citiesByProvince');
        Route::get('/districtsByCity/{cityId}', 'RegionController@districtsByCity');
    });

    Route::get('/universities/{universityId}/faculties', 'UniversityController@facultiesByUniversity');

    Route::group(['prefix' => 'instructor-finder'], function () {
        Route::get('/', 'InstructorFinderController@index');
        Route::get('/wizard', 'InstructorFinderController@wizard');
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', 'ProductController@index');
        Route::get('/{slug}', 'ProductController@show');
        Route::get('/{slug}/files', 'ProductController@showFiles');

        /* Review Load More */
        Route::post('/{slug}/reviews/load-more', 'ProductReviewController@getReviewsByCourseSlug');
        
        Route::group(['middleware' => 'web.auth'], function () {
            Route::post('/{slug}/points/apply', 'ProductController@buyWithPoint');
            Route::get('/{slug}/installments', 'ProductController@getInstallmentsByProduct');
            Route::post('/direct-payment', 'ProductController@directPayment');

            Route::group(['prefix' => 'reviews'], function () {
                Route::post('/store', 'ProductReviewController@store');
                Route::post('/store-reply-comment', 'ProductReviewController@storeReplyComment');
                Route::get('/{id}/delete', 'ProductReviewController@destroy');
                Route::get('/{id}/delete-comment/{commentId}', 'ProductReviewController@destroy');
            });
        });
    });

    Route::get('/reward-products', 'RewardProductsController@index');

    Route::group(['prefix' => 'bundles'], function () {
        Route::get('/', 'BundleController@index');
        Route::get('/{slug}', 'BundleController@show');
        Route::get('/{slug}/share-modal', 'BundleController@getShareModal');

        /* Review Load More */
        Route::post('/{slug}/reviews/load-more', 'BundleReviewController@getReviewsByBundleSlug');

        Route::group(['middleware' => 'web.auth'], function () {
            Route::get('/{slug}/free', 'BundleController@free');
            Route::get('/{slug}/favorite', 'BundleController@favoriteToggle');
            Route::post('/direct-payment', 'BundleController@directPayment');

            /* Course Points */
            Route::group(['prefix' => '/{slug}/points'], function () {
                Route::get('/apply', 'BundleController@buyWithPoint');
                Route::get('/get-modal', 'BundleController@getBuyWithPointModal');
            });

            Route::group(['prefix' => 'reviews'], function () {
                Route::post('/store', 'BundleReviewController@store');
                Route::post('/store-reply-comment', 'BundleReviewController@storeReplyComment');
                Route::get('/{id}/delete', 'BundleReviewController@destroy');
                Route::get('/{id}/delete-comment/{commentId}', 'BundleReviewController@destroy');
            });
        });
    });

    Route::group(['prefix' => 'forums'], function () {
        Route::get('/', 'ForumController@index');
        Route::get('/search', 'ForumController@search');

        Route::group(['middleware' => 'web.auth'], function () {
            Route::get('/create-topic', 'ForumController@createTopic');
            Route::post('/create-topic', 'ForumController@storeTopic');
            Route::get('/attachments/{attachment_id}/delete', 'ForumController@deleteTopicAttachment');
        });

        Route::group(['prefix' => '/{slug}/topics'], function () {
            Route::get('/', 'ForumController@topics');
            Route::get('/{topic_slug}/downloadAttachment/{attachment_id}', 'ForumController@topicDownloadAttachment');

            Route::group(['middleware' => 'web.auth'], function () {
                Route::post('/{topic_slug}/likeToggle', 'ForumController@topicLikeToggle');
                Route::get('/{topic_slug}/edit', 'ForumController@topicEdit');
                Route::post('/{topic_slug}/edit', 'ForumController@topicUpdate');
                Route::post('/{topic_slug}/bookmark', 'ForumController@topicBookmarkToggle');
            });

            Route::group(['prefix' => '/{topic_slug}/posts'], function () {
                Route::get('/', 'ForumTopicPostsController@posts');
                Route::get('/report-modal', 'ForumTopicPostsController@getReportModal');
                Route::get('/{post_id}/downloadAttachment', 'ForumTopicPostsController@postDownloadAttachment');

                Route::group(['middleware' => 'web.auth'], function () {
                    Route::post('/', 'ForumTopicPostsController@storePost');
                    Route::post('/report', 'ForumTopicPostsController@storeTopicReport');
                    Route::get('/{post_id}/edit', 'ForumTopicPostsController@postEdit');
                    Route::post('/{post_id}/edit', 'ForumTopicPostsController@postUpdate');
                    Route::post('/{post_id}/likeToggle', 'ForumTopicPostsController@postLikeToggle');
                    Route::post('/{post_id}/pin-toggle', 'ForumTopicPostsController@postPinToggle');
                });
            });
        });
    });


    Route::group(['prefix' => 'installments'], function () {
        Route::group(['middleware' => 'web.auth'], function () {
            Route::get('/request_submitted', 'InstallmentsController@requestSubmitted');
            Route::get('/request_rejected', 'InstallmentsController@requestRejected');
            Route::get('/{id}', 'InstallmentsController@index');
            Route::post('/{id}/store', 'InstallmentsController@store');
        });
    });

    Route::group(['prefix' => 'gift'], function () {
        Route::group(['middleware' => 'web.auth'], function () {
            Route::get('/{item_type}/{item_slug}', 'GiftController@index');
            Route::post('/{item_type}/{item_slug}', 'GiftController@store');
        });
    });

    /* Forms */
    Route::get('/forms/{url}', 'FormsController@index');
    Route::post('/forms/{url}/store', 'FormsController@store');

    // Live Sessions (student public area)
    Route::group(['prefix' => 'live-sessions', 'middleware' => ['auth']], function () {
        // List all live sessions (discovery)
        Route::get('/', [\App\Http\Controllers\LiveSessionController::class, 'index'])->name('live_sessions.index');
        // Student dashboard – my booked sessions
        Route::get('/me', [\App\Http\Controllers\LiveSessionController::class, 'mySessions'])->name('live_sessions.me');
        // Session detail page
        Route::get('/{id}', [\App\Http\Controllers\LiveSessionController::class, 'show'])->name('live_sessions.show');
        // Secure join endpoint – backend decides eligibility
        Route::get('/{id}/join', [\App\Http\Controllers\LiveSessionController::class, 'join'])->name('live_sessions.join');
    });

    // Teacher Live Sessions (keep existing structure)
    Route::group(['prefix' => 'teacher/live-sessions', 'middleware' => ['auth', 'role:teacher']], function () {
        Route::get('/', [\App\Http\Controllers\Teacher\LiveSessionController::class, 'index'])->name('teacher.live_sessions.index');
        Route::get('/create', [\App\Http\Controllers\Teacher\LiveSessionController::class, 'create'])->name('teacher.live_sessions.create');
        Route::post('/', [\App\Http\Controllers\Teacher\LiveSessionController::class, 'store'])->name('teacher.live_sessions.store');
        Route::get('/{id}', [\App\Http\Controllers\Teacher\LiveSessionController::class, 'show'])->name('teacher.live_sessions.show');
        Route::get('/{id}/edit', [\App\Http\Controllers\Teacher\LiveSessionController::class, 'edit'])->name('teacher.live_sessions.edit');
        Route::put('/{id}', [\App\Http\Controllers\Teacher\LiveSessionController::class, 'update'])->name('teacher.live_sessions.update');
        Route::post('/{id}/publish', [\App\Http\Controllers\Teacher\LiveSessionController::class, 'publish'])->name('teacher.live_sessions.publish');
        Route::post('/{id}/cancel', [\App\Http\Controllers\Teacher\LiveSessionController::class, 'cancel'])->name('teacher.live_sessions.cancel');
    });

    // Admin Live Sessions overview
    Route::group(['prefix' => 'admin/live-sessions', 'middleware' => ['auth', 'role:admin']], function () {
        Route::get('/', [\App\Http\Controllers\Admin\LiveSessionController::class, 'index'])->name('admin.live_sessions.index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\LiveSessionController::class, 'show'])->name('admin.live_sessions.show');
        Route::post('/{id}/cancel', [\App\Http\Controllers\Admin\LiveSessionController::class, 'cancel'])->name('admin.live_sessions.cancel');
        Route::post('/{id}/override-capacity', [\App\Http\Controllers\Admin\LiveSessionController::class, 'overrideCapacity'])->name('admin.live_sessions.override_capacity');
    });

    // Admin Refunds
    Route::group(['prefix' => 'admin/refunds', 'middleware' => ['auth', 'role:admin']], function () {
        Route::get('/', [\App\Http\Controllers\Admin\RefundController::class, 'index'])->name('admin.refunds.index');
        Route::post('/{id}/retry', [\App\Http\Controllers\Admin\RefundController::class, 'retry'])->name('admin.refunds.retry');
    });

    // Admin Activity Logs
    Route::group(['prefix' => 'admin/live-sessions/logs', 'middleware' => ['auth', 'role:admin']], function () {
        Route::get('/', [\App\Http\Controllers\Admin\LiveSessionLogController::class, 'index'])->name('admin.live_sessions.logs');
    });



