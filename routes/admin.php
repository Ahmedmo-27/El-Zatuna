<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where admin routes are registered. These routes are loaded by
| the RouteServiceProvider within a group which contains the "web" middleware.
|
*/

// Authentication Routes (No auth required)
Route::group(['namespace' => 'Admin'], function () {
    Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'LoginController@login')->name('admin.login.post');
    Route::get('/logout', 'LoginController@logout')->name('admin.logout');
    Route::get('/forget-password', 'ForgotPasswordController@showLinkRequestForm')->name('admin.forget-password');
    Route::post('/forget-password', 'ForgotPasswordController@forgot')->name('admin.forget-password.post');
    Route::get('/reset-password/{token}', 'ResetPasswordController@showResetForm')->name('admin.reset-password');
    Route::post('/reset-password', 'ResetPasswordController@updatePassword')->name('admin.reset-password.post');
});

// Protected Admin Routes
Route::group(['namespace' => 'Admin', 'middleware' => ['admin.auth', 'admin.locale']], function () {

    // Dashboard
    Route::get('/', 'DashboardController@index')->name('admin.dashboard');
    Route::get('/marketing', 'DashboardController@marketing')->name('admin.marketing');
    Route::get('/getSaleStatisticsData', 'DashboardController@getSaleStatisticsData')->name('admin.getSaleStatisticsData');
    Route::get('/getNetProfitChartAjax', 'DashboardController@getNetProfitChartAjax')->name('admin.getNetProfitChartAjax');
    Route::get('/cacheClear', 'DashboardController@cacheClear')->name('admin.cacheClear');
    Route::get('/clear-cache', 'DashboardController@cacheClear'); // blade uses this URL

    // Sidebar AJAX Endpoints
    Route::group(['prefix' => 'sidebar'], function () {
        Route::get('/courses-beep', 'SidebarController@getCoursesBeep');
        Route::get('/bundles-beep', 'SidebarController@getBundlesBeep');
        Route::get('/webinars-beep', 'SidebarController@getWebinarsBeep');
        Route::get('/text-lessons-beep', 'SidebarController@getTextLessonsBeep');
        Route::get('/reviews-beep', 'SidebarController@getReviewsBeep');
        Route::get('/classes-comments-beep', 'SidebarController@getClassesCommentsBeep');
        Route::get('/bundle-comments-beep', 'SidebarController@getBundleCommentsBeep');
        Route::get('/blog-comments-beep', 'SidebarController@getBlogCommentsBeep');
        Route::get('/product-comments-beep', 'SidebarController@getProductCommentsBeep');
        Route::get('/payout-request-beep', 'SidebarController@getPayoutRequestBeep');
        Route::get('/offline-payments-beep', 'SidebarController@getOfflinePaymentsBeep');
    });

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
    Route::get('/staffs', 'UserController@staffs')->name('admin.staffs');
    Route::get('/staffs/excel', 'UserController@exportExcelStaffs')->name('admin.staffs.excel');
    Route::get('/organizations', 'UserController@organizations')->name('admin.organizations');
    Route::get('/organizations/excel', 'UserController@exportExcelOrganizations')->name('admin.organizations.excel');
    Route::get('/students', 'UserController@students')->name('admin.students');
    Route::get('/students/excel', 'UserController@exportExcelStudents')->name('admin.students.excel');
    Route::get('/instructors', 'UserController@instructors')->name('admin.instructors');
    Route::get('/instructors/excel', 'UserController@exportExcelInstructors')->name('admin.instructors.excel');
    Route::get('/all-users', 'UserController@allUsers')->name('admin.all-users');
    Route::get('/all-users/excel', 'UserController@exportExcelAllUsers')->name('admin.all-users.excel');

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'UserController@allUsers')->name('admin.users');
        Route::get('/search', 'UserController@search')->name('admin.users.search');
        Route::get('/create', 'UserController@create')->name('admin.users.create');
        Route::post('/store', 'UserController@store')->name('admin.users.store');
        Route::get('/{id}/edit', 'UserController@edit')->name('admin.users.edit');
        Route::post('/{id}/update', 'UserController@update')->name('admin.users.update');
        Route::post('/{id}/updateImage', 'UserController@updateImage')->name('admin.users.updateImage');
        Route::post('/{id}/updateFormFields', 'UserController@updateFormFields')->name('admin.users.updateFormFields');
        Route::post('/{id}/financialUpdate', 'UserController@financialUpdate')->name('admin.users.financialUpdate');
        Route::post('/{id}/occupationsUpdate', 'UserController@occupationsUpdate')->name('admin.users.occupationsUpdate');
        Route::post('/{id}/badgesUpdate', 'UserController@badgesUpdate')->name('admin.users.badgesUpdate');
        Route::delete('/{id}/deleteBadge', 'UserController@deleteBadge')->name('admin.users.deleteBadge');
        Route::delete('/{id}/delete', 'UserController@destroy')->name('admin.users.delete');
        Route::post('/{id}/acceptRequestToInstructor', 'UserController@acceptRequestToInstructor')->name('admin.users.acceptRequestToInstructor');
        Route::get('/{id}/acceptRequestToInstructor', 'UserController@acceptRequestToInstructor'); // blade uses GET via JS confirm
        Route::get('/{id}/impersonate', 'UserController@impersonate')->name('admin.users.impersonate');
        Route::get('/{id}/userRegistrationPackage', 'UserController@userRegistrationPackage')->name('admin.users.userRegistrationPackage');
        Route::post('/{id}/meetingSettings', 'UserController@meetingSettings')->name('admin.users.meetingSettings');
        Route::post('/{id}/disableCashbackToggle', 'UserController@disableCashbackToggle')->name('admin.users.disableCashbackToggle');
        Route::get('/{id}/disableCashbackToggle', 'UserController@disableCashbackToggle'); // blade uses GET via JS confirm
        Route::post('/{id}/disableRegitrationBonusStatus', 'UserController@disableRegitrationBonusStatus')->name('admin.users.disableRegitrationBonusStatus');
        Route::get('/{id}/disable_registration_bonus', 'UserController@disableRegitrationBonusStatus'); // blade uses this URL
        Route::post('/{id}/disableInstallmentApproval', 'UserController@disableInstallmentApproval')->name('admin.users.disableInstallmentApproval');
        Route::get('/{id}/disable_installment_approval', 'UserController@disableInstallmentApproval'); // blade uses this URL
        Route::post('/{id}/end-all-login-sessions', 'UserLoginHistoryController@endAllUserSessions')->name('admin.users.endAllLoginSessions');

        // User Groups
        Route::group(['prefix' => 'groups'], function () {
            Route::get('/', 'GroupController@index')->name('admin.users.groups');
            Route::get('/create', 'GroupController@create')->name('admin.users.groups.create');
            Route::post('/', 'GroupController@store')->name('admin.users.groups.store');
            Route::post('/store', 'GroupController@store');
            Route::get('/{id}/edit', 'GroupController@edit')->name('admin.users.groups.edit');
            Route::post('/{id}/update', 'GroupController@update')->name('admin.users.groups.update');
            Route::delete('/{id}/delete', 'GroupController@destroy')->name('admin.users.groups.delete');
            Route::get('/{id}/groupRegistrationPackage', 'GroupController@groupRegistrationPackage')->name('admin.users.groups.groupRegistrationPackage');
        });

        // Become Instructor Requests
        Route::group(['prefix' => 'become-instructors'], function () {
            Route::get('/', 'BecomeInstructorController@index')->name('admin.users.become-instructors');
            Route::get('/instructors', 'BecomeInstructorController@index')->name('admin.users.become-instructors.instructors');
            Route::get('/organizations', 'BecomeInstructorController@index')->name('admin.users.become-instructors.organizations');
            Route::post('/{id}/reject', 'BecomeInstructorController@reject')->name('admin.users.become-instructors.reject');
            Route::get('/{id}/reject', 'BecomeInstructorController@reject'); // blade uses GET via JS confirm
            Route::delete('/{id}/delete', 'BecomeInstructorController@delete')->name('admin.users.become-instructors.delete');
            Route::get('/settings', 'BecomeInstructorController@settings')->name('admin.users.become-instructors.settings');
            Route::post('/settings', 'BecomeInstructorController@storeSettings')->name('admin.users.become-instructors.settings.store');
        });

        // Alias for blade template that uses become_instructors instead of become-instructors
        Route::get('/become_instructors/{id}/reject', 'BecomeInstructorController@reject');

        // User Login History
        Route::group(['prefix' => 'login-history'], function () {
            Route::get('/', 'UserLoginHistoryController@index')->name('admin.users.login-history');
            Route::get('/excel', 'UserLoginHistoryController@export')->name('admin.users.login-history.excel');
            Route::post('/{id}/endSession', 'UserLoginHistoryController@endSession')->name('admin.users.login-history.endSession');
            Route::delete('/{id}/delete', 'UserLoginHistoryController@delete')->name('admin.users.login-history.delete');
        });

        // IP Restriction
        Route::group(['prefix' => 'ip-restriction'], function () {
            Route::get('/', 'UserIpRestrictionController@index')->name('admin.users.ip-restriction');
            Route::get('/get-form', 'UserIpRestrictionController@getForm')->name('admin.users.ip-restriction.get-form');
            Route::post('/', 'UserIpRestrictionController@store')->name('admin.users.ip-restriction.store');
            Route::post('/store', 'UserIpRestrictionController@store');
            Route::get('/{id}/edit', 'UserIpRestrictionController@edit')->name('admin.users.ip-restriction.edit');
            Route::post('/{id}/update', 'UserIpRestrictionController@update')->name('admin.users.ip-restriction.update');
            Route::delete('/{id}/delete', 'UserIpRestrictionController@delete')->name('admin.users.ip-restriction.delete');
        });

        // Not Access To Content
        Route::group(['prefix' => 'not-access-to-content'], function () {
            Route::get('/', 'UsersNotAccessToContentController@index')->name('admin.users.not-access-to-content');
            Route::post('/', 'UsersNotAccessToContentController@store')->name('admin.users.not-access-to-content.store');
            Route::post('/store', 'UsersNotAccessToContentController@store'); // for blade forms posting to /users/not-access-to-content/store
            Route::post('/{id}/active', 'UsersNotAccessToContentController@active')->name('admin.users.not-access-to-content.active');
        });

        // Delete Account Requests
        Route::group(['prefix' => 'delete-account-requests'], function () {
            Route::get('/', 'DeleteAccountRequestsController@index')->name('admin.users.delete-account-requests');
            Route::post('/{id}/confirm', 'DeleteAccountRequestsController@confirm')->name('admin.users.delete-account-requests.confirm');
            Route::delete('/{id}/delete', 'DeleteAccountRequestsController@delete')->name('admin.users.delete-account-requests.delete');
        });

        // User Badges
        Route::group(['prefix' => 'badges'], function () {
            Route::get('/', 'BadgesController@index')->name('admin.users.badges');
            Route::post('/', 'BadgesController@store')->name('admin.users.badges.store');
            Route::post('/store', 'BadgesController@store'); // for blade forms posting to /users/badges/store
            Route::get('/{id}/edit', 'BadgesController@edit')->name('admin.users.badges.edit');
            Route::post('/{id}/update', 'BadgesController@update')->name('admin.users.badges.update');
            Route::delete('/{id}/delete', 'BadgesController@delete')->name('admin.users.badges.delete');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Webinars/Courses
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'webinars'], function () {
        Route::get('/', 'WebinarController@index')->name('admin.webinars');
        Route::get('/create', 'WebinarController@create')->name('admin.webinars.create');
        Route::post('/', 'WebinarController@store')->name('admin.webinars.store');
        Route::post('/store', 'WebinarController@store');
        Route::get('/search', 'WebinarController@search')->name('admin.webinars.search');
        Route::get('/excel', 'WebinarController@exportExcel')->name('admin.webinars.excel');
        Route::get('/getContentItemByLocale', 'WebinarController@getContentItemByLocale')->name('admin.webinars.getContentItemByLocale');
        Route::get('/{id}/edit', 'WebinarController@edit')->name('admin.webinars.edit');
        Route::post('/{id}/update', 'WebinarController@update')->name('admin.webinars.update');
        Route::delete('/{id}/delete', 'WebinarController@destroy')->name('admin.webinars.delete');
        Route::post('/{id}/approve', 'WebinarController@approve')->name('admin.webinars.approve');
        Route::get('/{id}/approve', 'WebinarController@approve'); // blade uses GET via JS confirm
        Route::post('/{id}/reject', 'WebinarController@reject')->name('admin.webinars.reject');
        Route::get('/{id}/reject', 'WebinarController@reject'); // blade uses GET via JS confirm
        Route::post('/{id}/unpublish', 'WebinarController@unpublish')->name('admin.webinars.unpublish');
        Route::get('/{id}/unpublish', 'WebinarController@unpublish'); // blade uses GET via JS confirm
        Route::get('/{id}/students', 'WebinarController@studentsLists')->name('admin.webinars.students');
        Route::get('/{id}/notificationToStudents', 'WebinarController@notificationToStudents')->name('admin.webinars.notificationToStudents');
        Route::get('/{id}/sendNotification', 'WebinarController@notificationToStudents'); // blade uses this URL
        Route::post('/{id}/sendNotificationToStudents', 'WebinarController@sendNotificationToStudents')->name('admin.webinars.sendNotificationToStudents');
        Route::post('/{id}/sendNotification', 'WebinarController@sendNotificationToStudents'); // blade uses this URL
        Route::post('/{id}/orderItems', 'WebinarController@orderItems')->name('admin.webinars.orderItems');

        // Featured Webinars
        Route::group(['prefix' => 'features'], function () {
            Route::get('/', 'FeatureWebinarsControllers@index')->name('admin.webinars.features');
            Route::get('/create', 'FeatureWebinarsControllers@create')->name('admin.webinars.features.create');
            Route::post('/', 'FeatureWebinarsControllers@store')->name('admin.webinars.features.store');
            Route::post('/store', 'FeatureWebinarsControllers@store');
            Route::get('/excel', 'FeatureWebinarsControllers@exportExcel')->name('admin.webinars.features.excel');
            Route::get('/{id}/edit', 'FeatureWebinarsControllers@edit')->name('admin.webinars.features.edit');
            Route::post('/{id}/update', 'FeatureWebinarsControllers@update')->name('admin.webinars.features.update');
            Route::post('/{id}/toggle', 'FeatureWebinarsControllers@toggle')->name('admin.webinars.features.toggle');
        });

        // Webinar Statistics
        Route::get('/{id}/statistics', 'WebinarStatisticController@index')->name('admin.webinars.statistics');
        Route::get('/{id}/statistics/student/{studentId}', 'WebinarStatisticController@getCourseProgressForStudent');
        Route::get('/{id}/statistics/chart', 'WebinarStatisticController@handleCourseProgressChart');
        Route::get('/{id}/statistics/line-chart', 'WebinarStatisticController@handleCourseProgressLineChart');

        // Course Forums
        Route::get('/course_forums', 'CourseForumsController@index')->name('admin.webinars.course_forums');
        Route::get('/{id}/forums', 'CourseForumsController@forums')->name('admin.webinars.forums');
        Route::get('/forums/{forumId}/answers', 'CourseForumsController@answers')->name('admin.webinars.forums.answers');
        Route::get('/forums/{id}/edit', 'CourseForumsController@forumEdit')->name('admin.webinars.forums.edit');
        Route::delete('/forums/{id}/delete', 'CourseForumsController@forumDelete')->name('admin.webinars.forums.delete');
        Route::post('/forums/{id}/update', 'CourseForumsController@forumUpdate')->name('admin.webinars.forums.update');
        Route::get('/forums/answers/{id}/edit', 'CourseForumsController@answerEdit')->name('admin.webinars.forums.answers.edit');
        Route::delete('/forums/answers/{id}/delete', 'CourseForumsController@answerDelete')->name('admin.webinars.forums.answers.delete');
        Route::post('/forums/answers/{id}/update', 'CourseForumsController@answerUpdate')->name('admin.webinars.forums.answers.update');

        // Personal Notes
        Route::get('/personal-notes', 'CoursePersonalNotesController@index')->name('admin.webinars.personal-notes');
        Route::get('/personal-notes/{id}/downloadAttachment', 'CoursePersonalNotesController@downloadAttachment');
        Route::post('/personal-notes/{id}/update', 'CoursePersonalNotesController@update');
        Route::delete('/personal-notes/{id}/delete', 'CoursePersonalNotesController@delete');

        // Comments
        Route::get('/comments', 'CommentsController@index')->name('admin.webinars.comments');
        Route::get('/comments/reports', 'CommentsController@reports')->name('admin.webinars.comments.reports');
    });

    // Chapters
    Route::group(['prefix' => 'chapters'], function () {
        Route::get('/{id}', 'ChapterController@getChapter');
        Route::get('/getAllByWebinarId/{webinarId}', 'ChapterController@getAllByWebinarId');
        Route::post('/', 'ChapterController@store')->name('admin.chapters.store');
        Route::post('/store', 'ChapterController@store');
        Route::get('/{id}/edit', 'ChapterController@edit')->name('admin.chapters.edit');
        Route::post('/{id}/update', 'ChapterController@update')->name('admin.chapters.update');
        Route::delete('/{id}/delete', 'ChapterController@destroy')->name('admin.chapters.delete');
        Route::post('/change', 'ChapterController@change')->name('admin.chapters.change');
    });

    // Sessions
    Route::group(['prefix' => 'sessions'], function () {
        Route::post('/', 'SessionController@store')->name('admin.sessions.store');
        Route::post('/store', 'SessionController@store');
        Route::post('/{id}/update', 'SessionController@update')->name('admin.sessions.update');
        Route::delete('/{id}/delete', 'SessionController@destroy')->name('admin.sessions.delete');
    });

    // Files
    Route::group(['prefix' => 'files'], function () {
        Route::post('/', 'FileController@store')->name('admin.files.store');
        Route::post('/store', 'FileController@store');
        Route::get('/{id}/edit', 'FileController@edit')->name('admin.files.edit');
        Route::post('/{id}/update', 'FileController@update')->name('admin.files.update');
        Route::get('/{id}/info', 'FileController@fileInfo')->name('admin.files.info');
        Route::delete('/{id}/delete', 'FileController@destroy')->name('admin.files.delete');
    });

    // Text Lessons
    Route::group(['prefix' => 'text-lessons'], function () {
        Route::post('/', 'TextLessonsController@store')->name('admin.text-lessons.store');
        Route::post('/store', 'TextLessonsController@store');
        Route::post('/{id}/update', 'TextLessonsController@update')->name('admin.text-lessons.update');
        Route::delete('/{id}/delete', 'TextLessonsController@destroy')->name('admin.text-lessons.delete');
    });

    // Alias for test-lesson (blade typo uses test-lesson instead of text-lessons)
    Route::post('/test-lesson/store', 'TextLessonsController@store');

    // Tickets
    Route::group(['prefix' => 'tickets'], function () {
        Route::post('/', 'TicketController@store')->name('admin.tickets.store');
        Route::post('/store', 'TicketController@store');
        Route::get('/{id}/edit', 'TicketController@edit')->name('admin.tickets.edit');
        Route::post('/{id}/update', 'TicketController@update')->name('admin.tickets.update');
        Route::delete('/{id}/delete', 'TicketController@destroy')->name('admin.tickets.delete');
    });

    // Prerequisites
    Route::group(['prefix' => 'prerequisites'], function () {
        Route::post('/', 'PrerequisiteController@store')->name('admin.prerequisites.store');
        Route::post('/store', 'PrerequisiteController@store');
        Route::get('/{id}/edit', 'PrerequisiteController@edit')->name('admin.prerequisites.edit');
        Route::post('/{id}/update', 'PrerequisiteController@update')->name('admin.prerequisites.update');
        Route::delete('/{id}/delete', 'PrerequisiteController@destroy')->name('admin.prerequisites.delete');
    });

    // FAQs
    Route::group(['prefix' => 'faqs'], function () {
        Route::post('/', 'FAQController@store')->name('admin.faqs.store');
        Route::post('/store', 'FAQController@store');
        Route::get('/{id}/description', 'FAQController@description')->name('admin.faqs.description');
        Route::get('/{id}/edit', 'FAQController@edit')->name('admin.faqs.edit');
        Route::post('/{id}/update', 'FAQController@update')->name('admin.faqs.update');
        Route::delete('/{id}/delete', 'FAQController@destroy')->name('admin.faqs.delete');
    });

    // Webinar Extra Description
    Route::group(['prefix' => 'webinar-extra-description'], function () {
        Route::post('/', 'WebinarExtraDescriptionController@store');
        Route::post('/store', 'WebinarExtraDescriptionController@store');
        Route::get('/{id}/edit', 'WebinarExtraDescriptionController@edit');
        Route::post('/{id}/update', 'WebinarExtraDescriptionController@update');
        Route::delete('/{id}/delete', 'WebinarExtraDescriptionController@destroy');
    });

    // Webinar Quiz
    Route::group(['prefix' => 'webinar-quiz'], function () {
        Route::post('/', 'WebinarQuizController@store');
        Route::post('/store', 'WebinarQuizController@store');
        Route::get('/{id}/edit', 'WebinarQuizController@edit');
        Route::post('/{id}/update', 'WebinarQuizController@update');
        Route::delete('/{id}/delete', 'WebinarQuizController@destroy');
    });

    // Related Courses
    Route::group(['prefix' => 'relatedCourses'], function () {
        Route::get('/get-form', 'RelatedCoursesController@getForm');
        Route::post('/', 'RelatedCoursesController@store');
        Route::post('/store', 'RelatedCoursesController@store');
        Route::get('/{id}/edit', 'RelatedCoursesController@edit');
        Route::post('/{id}/update', 'RelatedCoursesController@update');
        Route::delete('/{id}/delete', 'RelatedCoursesController@destroy');
    });

    // Course Noticeboards
    Route::group(['prefix' => 'course-noticeboards'], function () {
        Route::get('/', 'CourseNoticeboardController@index')->name('admin.course-noticeboards');
        Route::get('/send', 'CourseNoticeboardController@create')->name('admin.course-noticeboards.create');
        Route::post('/', 'CourseNoticeboardController@store')->name('admin.course-noticeboards.store');
        Route::post('/store', 'CourseNoticeboardController@store');
        Route::get('/{id}/edit', 'CourseNoticeboardController@edit')->name('admin.course-noticeboards.edit');
        Route::post('/{id}/update', 'CourseNoticeboardController@update')->name('admin.course-noticeboards.update');
        Route::delete('/{id}/delete', 'CourseNoticeboardController@delete')->name('admin.course-noticeboards.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Bundles
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'bundles'], function () {
        Route::get('/', 'BundleController@index')->name('admin.bundles');
        Route::get('/create', 'BundleController@create')->name('admin.bundles.create');
        Route::post('/', 'BundleController@store')->name('admin.bundles.store');
        Route::post('/store', 'BundleController@store');
        Route::get('/search', 'BundleController@search')->name('admin.bundles.search');
        Route::get('/{id}/edit', 'BundleController@edit')->name('admin.bundles.edit');
        Route::post('/{id}/update', 'BundleController@update')->name('admin.bundles.update');
        Route::delete('/{id}/delete', 'BundleController@destroy')->name('admin.bundles.delete');
        Route::get('/{id}/students', 'BundleController@studentsLists')->name('admin.bundles.students');
        Route::get('/{id}/notificationToStudents', 'BundleController@notificationToStudents')->name('admin.bundles.notificationToStudents');
        Route::get('/{id}/sendNotification', 'BundleController@notificationToStudents'); // blade uses this URL
        Route::post('/{id}/sendNotificationToStudents', 'BundleController@sendNotificationToStudents')->name('admin.bundles.sendNotificationToStudents');
        Route::post('/{id}/sendNotification', 'BundleController@sendNotificationToStudents'); // blade uses this URL
        Route::post('/{id}/approve', 'BundleController@approve')->name('admin.bundles.approve');
        Route::get('/{id}/approve', 'BundleController@approve'); // blade uses GET via JS confirm
        Route::post('/{id}/reject', 'BundleController@reject')->name('admin.bundles.reject');
        Route::get('/{id}/reject', 'BundleController@reject'); // blade uses GET via JS confirm
        Route::post('/{id}/unpublish', 'BundleController@unpublish')->name('admin.bundles.unpublish');
        Route::get('/{id}/unpublish', 'BundleController@unpublish'); // blade uses GET via JS confirm

        // Bundle Comments
        Route::get('/comments', 'CommentsController@bundleComments')->name('admin.bundles.comments');
        Route::get('/comments/reports', 'CommentsController@bundleCommentReports')->name('admin.bundles.comments.reports');
    });

    // Bundle Webinars
    Route::group(['prefix' => 'bundle-webinars'], function () {
        Route::post('/', 'BundleWebinarsController@store');
        Route::post('/store', 'BundleWebinarsController@store');
        Route::get('/{id}/edit', 'BundleWebinarsController@edit');
        Route::post('/{id}/update', 'BundleWebinarsController@update');
        Route::delete('/{id}/delete', 'BundleWebinarsController@destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Upcoming Courses
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'upcoming_courses'], function () {
        Route::get('/', 'UpcomingCoursesController@index')->name('admin.upcoming_courses');
        Route::get('/new', 'UpcomingCoursesController@create')->name('admin.upcoming_courses.create');
        Route::post('/', 'UpcomingCoursesController@store')->name('admin.upcoming_courses.store');
        Route::post('/store', 'UpcomingCoursesController@store');
        Route::get('/search', 'UpcomingCoursesController@search')->name('admin.upcoming_courses.search');
        Route::get('/excel', 'UpcomingCoursesController@exportExcel')->name('admin.upcoming_courses.excel');
        Route::get('/{id}/edit', 'UpcomingCoursesController@edit')->name('admin.upcoming_courses.edit');
        Route::post('/{id}/update', 'UpcomingCoursesController@update')->name('admin.upcoming_courses.update');
        Route::delete('/{id}/delete', 'UpcomingCoursesController@destroy')->name('admin.upcoming_courses.delete');
        Route::post('/{id}/approve', 'UpcomingCoursesController@approve')->name('admin.upcoming_courses.approve');
        Route::get('/{id}/approve', 'UpcomingCoursesController@approve'); // blade uses GET via JS confirm
        Route::post('/{id}/reject', 'UpcomingCoursesController@reject')->name('admin.upcoming_courses.reject');
        Route::get('/{id}/reject', 'UpcomingCoursesController@reject'); // blade uses GET via JS confirm
        Route::post('/{id}/unpublish', 'UpcomingCoursesController@unpublish')->name('admin.upcoming_courses.unpublish');
        Route::get('/{id}/unpublish', 'UpcomingCoursesController@unpublish'); // blade uses GET via JS confirm
        Route::get('/{id}/followers', 'UpcomingCoursesController@followers')->name('admin.upcoming_courses.followers');
        Route::delete('/{id}/followers/{followId}/delete', 'UpcomingCoursesController@deleteFollow');

        // Comments
        Route::get('/comments', 'CommentsController@upcomingComments')->name('admin.upcoming_courses.comments');
        Route::get('/comments/reports', 'CommentsController@upcomingCommentReports')->name('admin.upcoming_courses.comments.reports');
    });

    /*
    |--------------------------------------------------------------------------
    | Quizzes
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'quizzes'], function () {
        Route::get('/', 'QuizController@index')->name('admin.quizzes');
        Route::get('/create', 'QuizController@create')->name('admin.quizzes.create');
        Route::post('/', 'QuizController@store')->name('admin.quizzes.store');
        Route::post('/store', 'QuizController@store');
        Route::get('/excel', 'QuizController@exportExcel')->name('admin.quizzes.excel');
        Route::get('/{id}/edit', 'QuizController@edit')->name('admin.quizzes.edit');
        Route::post('/{id}/update', 'QuizController@update')->name('admin.quizzes.update');
        Route::delete('/{id}/delete', 'QuizController@delete')->name('admin.quizzes.delete');
        Route::post('/{id}/orderItems', 'QuizController@orderItems')->name('admin.quizzes.orderItems');

        // Quiz Results
        Route::get('/results', 'QuizResultsController@index')->name('admin.quizzes.results');
        Route::get('/results/excel', 'QuizResultsController@exportExcel')->name('admin.quizzes.results.excel');
        Route::get('/results/{id}/review', 'QuizResultsController@review')->name('admin.quizzes.results.review');
        Route::post('/results/{id}/update', 'QuizResultsController@update')->name('admin.quizzes.results.update');
        Route::delete('/results/{id}/delete', 'QuizResultsController@delete')->name('admin.quizzes.results.delete');
    });

    // Quiz Questions
    Route::group(['prefix' => 'quizzes-questions'], function () {
        Route::post('/', 'QuizQuestionController@store');
        Route::post('/store', 'QuizQuestionController@store');
        Route::get('/{id}/edit', 'QuizQuestionController@edit');
        Route::get('/{id}/getQuestionByLocale', 'QuizQuestionController@getQuestionByLocale');
        Route::post('/{id}/update', 'QuizQuestionController@update');
        Route::delete('/{id}/delete', 'QuizQuestionController@destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Assignments
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'assignments'], function () {
        Route::get('/', 'AssignmentController@index')->name('admin.assignments');
        Route::get('/{id}/students', 'AssignmentController@students')->name('admin.assignments.students');
        Route::get('/{id}/conversations/{historyId}', 'AssignmentController@conversations')->name('admin.assignments.conversations');
        Route::post('/store', 'AssignmentController@store');
        Route::post('/{id}/store', 'AssignmentController@store')->name('admin.assignments.store');
        Route::get('/{id}/edit', 'AssignmentController@edit')->name('admin.assignments.edit');
        Route::post('/{id}/update', 'AssignmentController@update')->name('admin.assignments.update');
        Route::delete('/{id}/delete', 'AssignmentController@destroy')->name('admin.assignments.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'CategoryController@index')->name('admin.categories');
        Route::get('/create', 'CategoryController@create')->name('admin.categories.create');
        Route::post('/', 'CategoryController@store')->name('admin.categories.store');
        Route::post('/store', 'CategoryController@store');
        Route::get('/search', 'CategoryController@search')->name('admin.categories.search');
        Route::get('/{id}/edit', 'CategoryController@edit')->name('admin.categories.edit');
        Route::post('/{id}/update', 'CategoryController@update')->name('admin.categories.update');
        Route::delete('/{id}/delete', 'CategoryController@destroy')->name('admin.categories.delete');
        Route::get('/{id}/setSubCategory', 'CategoryController@setSubCategory')->name('admin.categories.setSubCategory');

        // Trend Categories
        Route::group(['prefix' => 'trends'], function () {
            Route::get('/', 'TrendCategoriesController@index')->name('admin.categories.trends');
            Route::get('/create', 'TrendCategoriesController@create')->name('admin.categories.trends.create');
            Route::post('/', 'TrendCategoriesController@store')->name('admin.categories.trends.store');
            Route::post('/store', 'TrendCategoriesController@store');
            Route::get('/{id}/edit', 'TrendCategoriesController@edit')->name('admin.categories.trends.edit');
            Route::post('/{id}/update', 'TrendCategoriesController@update')->name('admin.categories.trends.update');
            Route::delete('/{id}/delete', 'TrendCategoriesController@destroy')->name('admin.categories.trends.delete');
        });
    });

    // Filters
    Route::group(['prefix' => 'filters'], function () {
        Route::get('/', 'FilterController@index')->name('admin.filters');
        Route::get('/create', 'FilterController@create')->name('admin.filters.create');
        Route::post('/', 'FilterController@store')->name('admin.filters.store');
        Route::post('/store', 'FilterController@store');
        Route::get('/{id}/edit', 'FilterController@edit')->name('admin.filters.edit');
        Route::post('/{id}/update', 'FilterController@update')->name('admin.filters.update');
        Route::delete('/{id}/delete', 'FilterController@destroy')->name('admin.filters.delete');
        Route::get('/{id}/setSubFilters', 'FilterController@setSubFilters')->name('admin.filters.setSubFilters');
        Route::get('/getByCategoryId/{categoryId}', 'FilterController@getByCategoryId');
    });

    // Tags
    Route::group(['prefix' => 'tags'], function () {
        Route::get('/', 'TagController@index')->name('admin.tags');
        Route::get('/create', 'TagController@create')->name('admin.tags.create');
        Route::post('/', 'TagController@store')->name('admin.tags.store');
        Route::post('/store', 'TagController@store');
        Route::get('/{id}/edit', 'TagController@edit')->name('admin.tags.edit');
        Route::post('/{id}/update', 'TagController@update')->name('admin.tags.update');
        Route::delete('/{id}/delete', 'TagController@destroy')->name('admin.tags.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Blog
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'blog'], function () {
        Route::get('/', 'BlogController@index')->name('admin.blog');
        Route::get('/create', 'BlogController@create')->name('admin.blog.create');
        Route::post('/', 'BlogController@store')->name('admin.blog.store');
        Route::post('/store', 'BlogController@store');
        Route::get('/search', 'BlogController@search')->name('admin.blog.search');
        Route::get('/{id}/edit', 'BlogController@edit')->name('admin.blog.edit');
        Route::post('/{id}/update', 'BlogController@update')->name('admin.blog.update');
        Route::delete('/{id}/delete', 'BlogController@delete')->name('admin.blog.delete');

        // Blog Categories
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', 'BlogCategoriesController@index')->name('admin.blog.categories');
            Route::post('/', 'BlogCategoriesController@store')->name('admin.blog.categories.store');
            Route::post('/store', 'BlogCategoriesController@store');
            Route::get('/{id}/edit', 'BlogCategoriesController@edit')->name('admin.blog.categories.edit');
            Route::post('/{id}/update', 'BlogCategoriesController@update')->name('admin.blog.categories.update');
            Route::delete('/{id}/delete', 'BlogCategoriesController@delete')->name('admin.blog.categories.delete');
        });

        // Blog Featured Categories
        Route::group(['prefix' => 'featured-categories'], function () {
            Route::get('/', 'BlogFeaturedCategoriesController@index')->name('admin.blog.featured-categories');
            Route::post('/', 'BlogFeaturedCategoriesController@store')->name('admin.blog.featured-categories.store');
            Route::post('/store', 'BlogFeaturedCategoriesController@store');
            Route::get('/{id}/edit', 'BlogFeaturedCategoriesController@edit')->name('admin.blog.featured-categories.edit');
            Route::post('/{id}/update', 'BlogFeaturedCategoriesController@update')->name('admin.blog.featured-categories.update');
            Route::delete('/{id}/delete', 'BlogFeaturedCategoriesController@delete')->name('admin.blog.featured-categories.delete');
        });

        // Blog Featured Contents
        Route::get('/featured-contents', 'BlogFeaturedContentsController@index')->name('admin.blog.featured-contents');

        // Blog Comments
        Route::get('/comments', 'CommentsController@blogComments')->name('admin.blog.comments');
        Route::get('/comments/reports', 'CommentsController@blogCommentReports')->name('admin.blog.comments.reports');
    });

    /*
    |--------------------------------------------------------------------------
    | Comments & Reviews
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'comments'], function () {
        // Index route defaults to webinars comments
        Route::get('/', 'CommentsController@index')->name('admin.comments')->defaults('page', 'webinars');
        // Dynamic page routes for comments (webinars, bundles, blog, products)
        Route::get('/webinars', 'CommentsController@index')->name('admin.comments.webinars')->defaults('page', 'webinars');
        Route::get('/bundles', 'CommentsController@index')->name('admin.comments.bundles')->defaults('page', 'bundles');
        Route::get('/blog', 'CommentsController@index')->name('admin.comments.blog')->defaults('page', 'blog');
        Route::get('/products', 'CommentsController@index')->name('admin.comments.products')->defaults('page', 'products');

        // Comment reports routes
        Route::get('/webinars/reports', 'CommentsController@reports')->name('admin.comments.webinars.reports')->defaults('page', 'webinars');
        Route::get('/bundles/reports', 'CommentsController@reports')->name('admin.comments.bundles.reports')->defaults('page', 'bundles');
        Route::get('/blog/reports', 'CommentsController@reports')->name('admin.comments.blog.reports')->defaults('page', 'blog');
        Route::get('/products/reports', 'CommentsController@reports')->name('admin.comments.products.reports')->defaults('page', 'products');

        // Comment actions (with page parameter for edit/update/reply)
        Route::get('/{page}/{id}/toggle', 'CommentsController@toggleStatus');
        Route::post('/{page}/{id}/toggle', 'CommentsController@toggleStatus');
        Route::get('/{page}/{id}/edit', 'CommentsController@edit');
        Route::post('/{page}/{id}/update', 'CommentsController@update');
        Route::get('/{page}/{id}/reply', 'CommentsController@reply');
        Route::post('/{page}/{id}/reply', 'CommentsController@storeReply');
        Route::get('/{page}/{id}/delete', 'CommentsController@delete');
        Route::delete('/{page}/{id}/delete', 'CommentsController@delete');
        Route::get('/{page}/reports/{id}/show', 'CommentsController@reportShow');
        Route::get('/{page}/reports/{id}/delete', 'CommentsController@reportDelete');
        Route::delete('/{page}/reports/{id}/delete', 'CommentsController@reportDelete');

        // Reviews reply route (for blade form action /comments/reviews/{id}/reply)
        Route::post('/reviews/{id}/reply', 'ReviewsController@storeReply');
        // Product reviews reply route (for blade form action /comments/product_reviews/{id}/reply)
        Route::post('/product_reviews/{id}/reply', 'Store\\ReviewsController@storeReply');
    });

    Route::group(['prefix' => 'reviews'], function () {
        Route::get('/', 'ReviewsController@index')->name('admin.reviews');
        Route::post('/{id}/toggle', 'ReviewsController@toggleStatus');
        Route::get('/{id}/reply', 'ReviewsController@reply');
        Route::post('/{id}/reply', 'ReviewsController@storeReply');
        Route::get('/{id}/delete', 'ReviewsController@delete');
        Route::delete('/{id}/delete', 'ReviewsController@delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Financial
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'financial'], function () {
        // Sales
        Route::group(['prefix' => 'sales'], function () {
            Route::get('/', 'SaleController@index')->name('admin.financial.sales');
            Route::get('/excel', 'SaleController@exportExcel')->name('admin.financial.sales.excel');
            Route::post('/{id}/refund', 'SaleController@refund')->name('admin.financial.sales.refund');
            Route::get('/{id}/refund', 'SaleController@refund'); // blade uses GET via JS confirm
            Route::get('/{id}/invoice', 'SaleController@invoice')->name('admin.financial.sales.invoice');
        });

        Route::group(['prefix' => 'tutor-earnings'], function () {
            Route::get('/', 'TutorEarningsController@index')->name('admin.financial.tutor_earnings');
        });

        Route::group(['prefix' => 'tutor-payouts'], function () {
            Route::get('/', 'TutorPayoutsController@index')->name('admin.financial.tutor_payouts');
            Route::get('/{id}/paid', 'TutorPayoutsController@markPaid')->name('admin.financial.tutor_payouts.paid');
            Route::post('/{id}/paid', 'TutorPayoutsController@markPaid');
        });

        // Payouts
        Route::group(['prefix' => 'payouts'], function () {
            Route::get('/', 'PayoutController@index')->name('admin.financial.payouts');
            Route::get('/excel', 'PayoutController@exportExcel')->name('admin.financial.payouts.excel');
            Route::post('/{id}/reject', 'PayoutController@reject')->name('admin.financial.payouts.reject');
            Route::get('/{id}/reject', 'PayoutController@reject'); // blade uses GET via JS confirm
            Route::post('/{id}/payout', 'PayoutController@payout')->name('admin.financial.payouts.payout');
            Route::get('/{id}/payout', 'PayoutController@payout'); // blade uses GET via JS confirm
        });

        // Offline Payments
        Route::group(['prefix' => 'offline_payments'], function () {
            Route::get('/', 'OfflinePaymentController@index')->name('admin.financial.offline_payments');
            Route::get('/excel', 'OfflinePaymentController@exportExcel')->name('admin.financial.offline_payments.excel');
            Route::post('/{id}/reject', 'OfflinePaymentController@reject')->name('admin.financial.offline_payments.reject');
            Route::get('/{id}/reject', 'OfflinePaymentController@reject'); // blade uses GET via JS confirm
            Route::post('/{id}/approved', 'OfflinePaymentController@approved')->name('admin.financial.offline_payments.approved');
            Route::get('/{id}/approved', 'OfflinePaymentController@approved'); // blade uses GET via JS confirm
        });

        // Documents
        Route::group(['prefix' => 'documents'], function () {
            Route::get('/', 'DocumentController@index')->name('admin.financial.documents');
            Route::get('/new', 'DocumentController@create')->name('admin.financial.documents.create');
            Route::post('/', 'DocumentController@store')->name('admin.financial.documents.store');
            Route::post('/store', 'DocumentController@store');
            Route::get('/{id}/printer', 'DocumentController@printer')->name('admin.financial.documents.printer');
        });

        // Subscribes
        Route::group(['prefix' => 'subscribes'], function () {
            Route::get('/', 'SubscribesController@index')->name('admin.financial.subscribes');
            Route::get('/new', 'SubscribesController@create')->name('admin.financial.subscribes.create');
            Route::post('/', 'SubscribesController@store')->name('admin.financial.subscribes.store');
            Route::post('/store', 'SubscribesController@store');
            Route::get('/{id}/edit', 'SubscribesController@edit')->name('admin.financial.subscribes.edit');
            Route::post('/{id}/update', 'SubscribesController@update')->name('admin.financial.subscribes.update');
            Route::delete('/{id}/delete', 'SubscribesController@delete')->name('admin.financial.subscribes.delete');
        });

        // Promotions
        Route::group(['prefix' => 'promotions'], function () {
            Route::get('/', 'PromotionsController@index')->name('admin.financial.promotions');
            Route::get('/new', 'PromotionsController@create')->name('admin.financial.promotions.create');
            Route::post('/', 'PromotionsController@store')->name('admin.financial.promotions.store');
            Route::post('/store', 'PromotionsController@store');
            Route::get('/sales', 'PromotionsController@sales')->name('admin.financial.promotions.sales');
            Route::get('/{id}/edit', 'PromotionsController@edit')->name('admin.financial.promotions.edit');
            Route::post('/{id}/update', 'PromotionsController@update')->name('admin.financial.promotions.update');
            Route::delete('/{id}/delete', 'PromotionsController@delete')->name('admin.financial.promotions.delete');
        });

        // Special Offers
        Route::group(['prefix' => 'special_offers'], function () {
            Route::get('/', 'SpecialOfferController@index')->name('admin.financial.special_offers');
            Route::get('/new', 'SpecialOfferController@create')->name('admin.financial.special_offers.create');
            Route::post('/', 'SpecialOfferController@store')->name('admin.financial.special_offers.store');
            Route::post('/store', 'SpecialOfferController@store');
            Route::get('/{id}/edit', 'SpecialOfferController@edit')->name('admin.financial.special_offers.edit');
            Route::post('/{id}/update', 'SpecialOfferController@update')->name('admin.financial.special_offers.update');
            Route::delete('/{id}/delete', 'SpecialOfferController@destroy')->name('admin.financial.special_offers.delete');
        });

        // Discounts
        Route::group(['prefix' => 'discounts'], function () {
            Route::get('/', 'DiscountController@index')->name('admin.financial.discounts');
            Route::get('/new', 'DiscountController@create')->name('admin.financial.discounts.create');
            Route::post('/', 'DiscountController@store')->name('admin.financial.discounts.store');
            Route::post('/store', 'DiscountController@store');
            Route::get('/{id}/edit', 'DiscountController@edit')->name('admin.financial.discounts.edit');
            Route::post('/{id}/update', 'DiscountController@update')->name('admin.financial.discounts.update');
            Route::delete('/{id}/delete', 'DiscountController@destroy')->name('admin.financial.discounts.delete');
        });

        // Registration Packages
        Route::group(['prefix' => 'registration-packages'], function () {
            Route::get('/', 'RegistrationPackagesController@index')->name('admin.financial.registration-packages');
            Route::get('/new', 'RegistrationPackagesController@create')->name('admin.financial.registration-packages.create');
            Route::post('/', 'RegistrationPackagesController@store')->name('admin.financial.registration-packages.store');
            Route::post('/store', 'RegistrationPackagesController@store');
            Route::get('/settings', 'RegistrationPackagesController@settings')->name('admin.financial.registration-packages.settings');
            Route::post('/settings', 'RegistrationPackagesController@storeSettings')->name('admin.financial.registration-packages.settings.store');
            Route::get('/reports', 'RegistrationPackagesController@reports')->name('admin.financial.registration-packages.reports');
            Route::get('/{id}/edit', 'RegistrationPackagesController@edit')->name('admin.financial.registration-packages.edit');
            Route::post('/{id}/update', 'RegistrationPackagesController@update')->name('admin.financial.registration-packages.update');
            Route::delete('/{id}/delete', 'RegistrationPackagesController@delete')->name('admin.financial.registration-packages.delete');
        });

        // Installments
        Route::group(['prefix' => 'installments'], function () {
            Route::get('/', 'InstallmentsController@index')->name('admin.financial.installments');
            Route::get('/create', 'InstallmentsController@create')->name('admin.financial.installments.create');
            Route::post('/', 'InstallmentsController@store')->name('admin.financial.installments.store');
            Route::post('/store', 'InstallmentsController@store');
            Route::get('/purchases', 'InstallmentsController@purchases')->name('admin.financial.installments.purchases');
            Route::get('/overdue', 'InstallmentsController@overdue')->name('admin.financial.installments.overdue');
            Route::get('/overdue_history', 'InstallmentsController@overdueHistory')->name('admin.financial.installments.overdue_history');
            Route::get('/verification_requests', 'InstallmentsController@verificationRequests')->name('admin.financial.installments.verification_requests');
            Route::get('/verified_users', 'InstallmentsController@verifiedUsers')->name('admin.financial.installments.verified_users');
            Route::get('/settings', 'InstallmentsController@settings')->name('admin.financial.installments.settings');
            Route::post('/settings', 'InstallmentsController@storeSettings')->name('admin.financial.installments.settings.store');
            Route::get('/{id}/edit', 'InstallmentsController@edit')->name('admin.financial.installments.edit');
            Route::post('/{id}/update', 'InstallmentsController@update')->name('admin.financial.installments.update');
            Route::delete('/{id}/delete', 'InstallmentsController@delete')->name('admin.financial.installments.delete');

            // Installment Orders
            Route::get('/orders/{id}/details', 'InstallmentsController@details');
            Route::post('/orders/{id}/approve', 'InstallmentsController@approve');
            Route::get('/orders/{id}/approve', 'InstallmentsController@approve'); // blade uses GET via JS confirm
            Route::post('/orders/{id}/reject', 'InstallmentsController@reject');
            Route::get('/orders/{id}/reject', 'InstallmentsController@reject'); // blade uses GET via JS confirm
            Route::post('/orders/{id}/cancel', 'InstallmentsController@cancel');
            Route::get('/orders/{id}/cancel', 'InstallmentsController@cancel'); // blade uses GET via JS confirm
            Route::post('/orders/{id}/refund', 'InstallmentsController@refund');
            Route::get('/orders/{id}/refund', 'InstallmentsController@refund'); // blade uses GET via JS confirm
            Route::get('/orders/{orderId}/attachments/{attachmentId}/download', 'InstallmentsController@downloadAttachment');
        });

        // Payment Channels
        Route::group(['prefix' => 'payment-channels'], function () {
            Route::get('/', 'PaymentChannelController@index')->name('admin.financial.payment-channels');
            Route::get('/{id}/edit', 'PaymentChannelController@edit')->name('admin.financial.payment-channels.edit');
            Route::post('/{id}/update', 'PaymentChannelController@update')->name('admin.financial.payment-channels.update');
            Route::post('/{id}/toggleStatus', 'PaymentChannelController@toggleStatus')->name('admin.financial.payment-channels.toggleStatus');
        });

        // Currency Settings
        Route::group(['prefix' => 'currency'], function () {
            Route::get('/', 'SettingsController@currencySettings')->name('admin.financial.currency');
            Route::post('/', 'SettingsController@storeCurrency')->name('admin.financial.currency.store');
            Route::get('/{id}/edit', 'SettingsController@editCurrency')->name('admin.financial.currency.edit');
            Route::post('/{id}/update', 'SettingsController@updateCurrency')->name('admin.financial.currency.update');
            Route::delete('/{id}/delete', 'SettingsController@deleteCurrency')->name('admin.financial.currency.delete');
        });

        // Offline Banks
        Route::group(['prefix' => 'offline_banks'], function () {
            Route::get('/get-form', 'SettingsController@financialOfflineBankForm');
            Route::post('/', 'SettingsController@financialOfflineBankStore');
            Route::post('/store', 'SettingsController@financialOfflineBankStore');
            Route::get('/{id}/edit', 'SettingsController@financialOfflineBankEdit');
            Route::post('/{id}/update', 'SettingsController@financialOfflineBankUpdate');
            Route::delete('/{id}/delete', 'SettingsController@financialOfflineBankDelete');
        });

        // User Banks
        Route::group(['prefix' => 'user_banks'], function () {
            Route::get('/get-form', 'SettingsController@financialUserBankForm');
            Route::post('/', 'SettingsController@financialUserBankStore');
            Route::post('/store', 'SettingsController@financialUserBankStore');
            Route::get('/{id}/edit', 'SettingsController@financialUserBankEdit');
            Route::post('/{id}/update', 'SettingsController@financialUserBankUpdate');
            Route::delete('/{id}/delete', 'SettingsController@financialUserBankDelete');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Enrollment
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'enrollments'], function () {
        Route::get('/', 'EnrollmentController@history')->name('admin.enrollments');
        Route::get('/history', 'EnrollmentController@history')->name('admin.enrollments.history');
        Route::get('/add-student-to-class', 'EnrollmentController@addStudentToClass')->name('admin.enrollments.addStudentToClass');
        Route::post('/store', 'EnrollmentController@store')->name('admin.enrollments.store');
        Route::get('/excel', 'EnrollmentController@exportExcel')->name('admin.enrollments.excel');
        Route::post('/{id}/blockAccess', 'EnrollmentController@blockAccess')->name('admin.enrollments.blockAccess');
        Route::get('/{id}/block-access', 'EnrollmentController@blockAccess'); // blade uses this URL with GET
        Route::post('/{id}/enableAccess', 'EnrollmentController@enableAccess')->name('admin.enrollments.enableAccess');
        Route::get('/{id}/enable-access', 'EnrollmentController@enableAccess'); // blade uses this URL with GET
    });

    /*
    |--------------------------------------------------------------------------
    | Certificates
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'certificates'], function () {
        // Quiz Certificates (base route)
        Route::get('/', 'CertificateController@index')->name('admin.certificates');
        Route::get('/excel', 'CertificateController@exportExcel')->name('admin.certificates.excel');

        // Course/Bundle Certificates (WebinarCertificateController)
        Route::get('/course-competition', 'WebinarCertificateController@index')->name('admin.certificates.course-competition');
        Route::get('/course-competition/{id}', 'WebinarCertificateController@show')->name('admin.certificates.course-competition.show');

        // Certificate Settings
        Route::get('/settings', 'CertificateController@settings')->name('admin.certificates.settings');
        Route::post('/settings', 'CertificateController@storeSettings')->name('admin.certificates.settings.store');
        Route::get('/{id}/download', 'CertificateController@CertificatesDownload')->name('admin.certificates.download');

        // Certificate Templates
        Route::group(['prefix' => 'templates'], function () {
            Route::get('/', 'CertificateController@CertificatesTemplatesList')->name('admin.certificates.templates');
            Route::get('/new', 'CertificateController@CertificatesNewTemplate')->name('admin.certificates.templates.create');
            Route::post('/', 'CertificateController@CertificatesTemplateStore')->name('admin.certificates.templates.store');
            Route::post('/store', 'CertificateController@CertificatesTemplateStore');
            Route::post('/{id}', 'CertificateController@CertificatesTemplateStore')->name('admin.certificates.templates.update');
            Route::post('/{id}/update', 'CertificateController@CertificatesTemplateStore');
            Route::get('/{id}/preview', 'CertificateController@CertificatesTemplatePreview')->name('admin.certificates.templates.preview');
            Route::get('/{id}/edit', 'CertificateController@CertificatesTemplatesEdit')->name('admin.certificates.templates.edit');
            Route::delete('/{id}/delete', 'CertificateController@CertificatesTemplatesDelete')->name('admin.certificates.templates.delete');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Supports
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'supports'], function () {
        Route::get('/', 'SupportsController@index')->name('admin.supports');
        Route::get('/create', 'SupportsController@create')->name('admin.supports.create');
        Route::post('/', 'SupportsController@store')->name('admin.supports.store');
        Route::post('/store', 'SupportsController@store');
        Route::get('/{id}/edit', 'SupportsController@edit')->name('admin.supports.edit');
        Route::post('/{id}/update', 'SupportsController@update')->name('admin.supports.update');
        Route::delete('/{id}/delete', 'SupportsController@delete')->name('admin.supports.delete');
        Route::post('/{id}/close', 'SupportsController@conversationClose')->name('admin.supports.close');
        Route::get('/{id}/conversation', 'SupportsController@conversation')->name('admin.supports.conversation');
        Route::post('/{id}/conversation', 'SupportsController@storeConversation')->name('admin.supports.conversation.store');

        // Support Departments
        Route::group(['prefix' => 'departments'], function () {
            Route::get('/', 'SupportDepartmentsController@index')->name('admin.supports.departments');
            Route::get('/create', 'SupportDepartmentsController@create')->name('admin.supports.departments.create');
            Route::post('/', 'SupportDepartmentsController@store')->name('admin.supports.departments.store');
            Route::post('/store', 'SupportDepartmentsController@store');
            Route::get('/{id}/edit', 'SupportDepartmentsController@edit')->name('admin.supports.departments.edit');
            Route::post('/{id}/update', 'SupportDepartmentsController@update')->name('admin.supports.departments.update');
            Route::delete('/{id}/delete', 'SupportDepartmentsController@delete')->name('admin.supports.departments.delete');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', 'NotificationsController@index')->name('admin.notifications');
        Route::get('/posted', 'NotificationsController@posted')->name('admin.notifications.posted');
        Route::get('/send', 'NotificationsController@create')->name('admin.notifications.create');
        Route::post('/', 'NotificationsController@store')->name('admin.notifications.store');
        Route::post('/store', 'NotificationsController@store');
        Route::post('/mark_all_read', 'NotificationsController@markAllRead')->name('admin.notifications.markAllRead');
        Route::get('/{id}/edit', 'NotificationsController@edit')->name('admin.notifications.edit');
        Route::post('/{id}/update', 'NotificationsController@update')->name('admin.notifications.update');
        Route::delete('/{id}/delete', 'NotificationsController@delete')->name('admin.notifications.delete');
        Route::post('/{id}/markAsRead', 'NotificationsController@markAsRead')->name('admin.notifications.markAsRead');

        // Notification Templates
        Route::group(['prefix' => 'templates'], function () {
            Route::get('/', 'NotificationTemplatesController@index')->name('admin.notifications.templates');
            Route::get('/create', 'NotificationTemplatesController@create')->name('admin.notifications.templates.create');
            Route::post('/', 'NotificationTemplatesController@store')->name('admin.notifications.templates.store');
            Route::post('/store', 'NotificationTemplatesController@store');
            Route::get('/{id}/edit', 'NotificationTemplatesController@edit')->name('admin.notifications.templates.edit');
            Route::post('/{id}/update', 'NotificationTemplatesController@update')->name('admin.notifications.templates.update');
            Route::delete('/{id}/delete', 'NotificationTemplatesController@delete')->name('admin.notifications.templates.delete');
        });
    });

    // Noticeboards
    Route::group(['prefix' => 'noticeboards'], function () {
        Route::get('/', 'NoticeboardController@index')->name('admin.noticeboards');
        Route::get('/send', 'NoticeboardController@create')->name('admin.noticeboards.create');
        Route::post('/', 'NoticeboardController@store')->name('admin.noticeboards.store');
        Route::post('/store', 'NoticeboardController@store');
        Route::get('/{id}/edit', 'NoticeboardController@edit')->name('admin.noticeboards.edit');
        Route::post('/{id}/update', 'NoticeboardController@update')->name('admin.noticeboards.update');
        Route::delete('/{id}/delete', 'NoticeboardController@delete')->name('admin.noticeboards.delete');
    });

    // Newsletters
    Route::group(['prefix' => 'newsletters'], function () {
        Route::get('/', 'NewslettersController@index')->name('admin.newsletters');
        Route::get('/send', 'NewslettersController@send')->name('admin.newsletters.send');
        Route::post('/send', 'NewslettersController@sendNewsletter')->name('admin.newsletters.send.post');
        Route::get('/history', 'NewslettersController@history')->name('admin.newsletters.history');
        Route::get('/excel', 'NewslettersController@exportExcel')->name('admin.newsletters.excel');
        Route::delete('/{id}/delete', 'NewslettersController@delete')->name('admin.newsletters.delete');
    });

    // Purchase Notifications
    Route::group(['prefix' => 'purchase_notifications'], function () {
        Route::get('/', 'PurchaseNotificationsController@index')->name('admin.purchase_notifications');
        Route::get('/create', 'PurchaseNotificationsController@create')->name('admin.purchase_notifications.create');
        Route::post('/', 'PurchaseNotificationsController@store')->name('admin.purchase_notifications.store');
        Route::post('/store', 'PurchaseNotificationsController@store');
        Route::get('/searchContents', 'PurchaseNotificationsController@searchContents');
        Route::get('/{id}/edit', 'PurchaseNotificationsController@edit')->name('admin.purchase_notifications.edit');
        Route::post('/{id}/update', 'PurchaseNotificationsController@update')->name('admin.purchase_notifications.update');
        Route::delete('/{id}/delete', 'PurchaseNotificationsController@delete')->name('admin.purchase_notifications.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Contacts
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'contacts'], function () {
        Route::get('/', 'ContactController@index')->name('admin.contacts');
        Route::get('/{id}/reply', 'ContactController@reply')->name('admin.contacts.reply');
        Route::post('/{id}/reply', 'ContactController@storeReply')->name('admin.contacts.reply.store');
        Route::delete('/{id}/delete', 'ContactController@delete')->name('admin.contacts.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Forms
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'forms'], function () {
        Route::get('/', 'FormsController@index')->name('admin.forms');
        Route::get('/create', 'FormsController@create')->name('admin.forms.create');
        Route::post('/', 'FormsController@store')->name('admin.forms.store');
        Route::post('/store', 'FormsController@store');
        Route::get('/{id}/edit', 'FormsController@edit')->name('admin.forms.edit');
        Route::post('/{id}/update', 'FormsController@update')->name('admin.forms.update');
        Route::delete('/{id}/delete', 'FormsController@delete')->name('admin.forms.delete');

        // Form Submissions
        Route::get('/submissions', 'FormSubmissionsController@index')->name('admin.forms.submissions');
        Route::get('/submissions/{id}/show', 'FormSubmissionsController@show');
        Route::post('/submissions/{id}/update', 'FormSubmissionsController@update');
        Route::delete('/submissions/{id}/delete', 'FormSubmissionsController@delete');
    });

    // Form Fields
    Route::group(['prefix' => 'form-fields'], function () {
        Route::post('/', 'FormFieldsController@store');
        Route::get('/{id}/edit', 'FormFieldsController@edit');
        Route::post('/{id}/update', 'FormFieldsController@update');
        Route::delete('/{id}/delete', 'FormFieldsController@delete');
        Route::post('/orders', 'FormFieldsController@orders');
        Route::post('/{id}/orderOptions', 'FormFieldsController@orderOptions');
        Route::delete('/{id}/deleteOption', 'FormFieldsController@deleteOption');
    });

    /*
    |--------------------------------------------------------------------------
    | Forums
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'forums'], function () {
        Route::get('/', 'ForumController@index')->name('admin.forums');
        Route::get('/create', 'ForumController@create')->name('admin.forums.create');
        Route::post('/', 'ForumController@store')->name('admin.forums.store');
        Route::post('/store', 'ForumController@store');
        Route::get('/search', 'ForumController@search')->name('admin.forums.search');
        Route::get('/searchTopics', 'ForumController@searchTopics')->name('admin.forums.searchTopics');
        Route::get('/settings', 'ForumSettingsController@settings')->name('admin.forums.settings');
        Route::post('/settings', 'ForumSettingsController@storeSettings')->name('admin.forums.settings.store');
        Route::get('/{id}/edit', 'ForumController@edit')->name('admin.forums.edit');
        Route::post('/{id}/update', 'ForumController@update')->name('admin.forums.update');
        Route::delete('/{id}/delete', 'ForumController@destroy')->name('admin.forums.delete');
        Route::get('/{id}/setSubForum', 'ForumController@setSubForum')->name('admin.forums.setSubForum');
        // For blade forms posting to /forums/{forum_id}/topics/store
        Route::post('/{forum_id}/topics/store', 'ForumTopicsController@store');

        // Forum Topics
        Route::group(['prefix' => 'topics'], function () {
            Route::get('/', 'ForumTopicsController@index')->name('admin.forums.topics');
            Route::get('/create', 'ForumTopicsController@create')->name('admin.forums.topics.create');
            Route::post('/', 'ForumTopicsController@store')->name('admin.forums.topics.store');
            Route::post('/store', 'ForumTopicsController@store');
            Route::get('/{id}/edit', 'ForumTopicsController@edit')->name('admin.forums.topics.edit');
            Route::post('/{id}/update', 'ForumTopicsController@update')->name('admin.forums.topics.update');
            Route::delete('/{id}/delete', 'ForumTopicsController@delete')->name('admin.forums.topics.delete');
            Route::post('/{id}/closeToggle', 'ForumTopicsController@closeToggle');
            Route::post('/{id}/close', 'ForumTopicsController@close');
            Route::get('/{id}/close', 'ForumTopicsController@close'); // blade uses GET via JS confirm
            Route::post('/{id}/open', 'ForumTopicsController@open');
            Route::get('/{id}/open', 'ForumTopicsController@open'); // blade uses GET via JS confirm
            Route::get('/{id}/posts', 'ForumTopicsController@posts')->name('admin.forums.topics.posts');
            Route::post('/{id}/posts', 'ForumTopicsController@storePost');
            Route::get('/posts/{id}/edit', 'ForumTopicsController@postEdit');
            Route::post('/posts/{id}/update', 'ForumTopicsController@postUpdate');
            Route::post('/posts/{id}/unPin', 'ForumTopicsController@postUnPin');
            Route::post('/posts/{id}/pin', 'ForumTopicsController@postPin');
            Route::delete('/posts/{id}/delete', 'ForumTopicsController@postDelete');
        });
    });

    // Featured Topics
    Route::group(['prefix' => 'featured-topics'], function () {
        Route::get('/', 'FeaturedTopicsController@index')->name('admin.featured-topics');
        Route::get('/create', 'FeaturedTopicsController@create')->name('admin.featured-topics.create');
        Route::post('/', 'FeaturedTopicsController@store')->name('admin.featured-topics.store');
        Route::post('/store', 'FeaturedTopicsController@store');
        Route::get('/{id}/edit', 'FeaturedTopicsController@edit')->name('admin.featured-topics.edit');
        Route::post('/{id}/update', 'FeaturedTopicsController@update')->name('admin.featured-topics.update');
        Route::delete('/{id}/delete', 'FeaturedTopicsController@destroy')->name('admin.featured-topics.delete');
    });

    // Recommended Topics
    Route::group(['prefix' => 'recommended-topics'], function () {
        Route::get('/', 'RecommendedTopicsController@index')->name('admin.recommended-topics');
        Route::get('/create', 'RecommendedTopicsController@create')->name('admin.recommended-topics.create');
        Route::post('/', 'RecommendedTopicsController@store')->name('admin.recommended-topics.store');
        Route::post('/store', 'RecommendedTopicsController@store');
        Route::get('/{id}/edit', 'RecommendedTopicsController@edit')->name('admin.recommended-topics.edit');
        Route::post('/{id}/update', 'RecommendedTopicsController@update')->name('admin.recommended-topics.update');
        Route::delete('/{id}/delete', 'RecommendedTopicsController@destroy')->name('admin.recommended-topics.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Advertising
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'advertising'], function () {
        Route::get('/', 'AdvertisingBannersController@index')->name('admin.advertising');
        Route::group(['prefix' => 'banners'], function () {
            Route::get('/', 'AdvertisingBannersController@index')->name('admin.advertising.banners');
            Route::get('/new', 'AdvertisingBannersController@create')->name('admin.advertising.banners.create');
            Route::post('/', 'AdvertisingBannersController@store')->name('admin.advertising.banners.store');
            Route::post('/store', 'AdvertisingBannersController@store');
            Route::get('/{id}/edit', 'AdvertisingBannersController@edit')->name('admin.advertising.banners.edit');
            Route::post('/{id}/update', 'AdvertisingBannersController@update')->name('admin.advertising.banners.update');
            Route::delete('/{id}/delete', 'AdvertisingBannersController@delete')->name('admin.advertising.banners.delete');
        });
    });

    Route::group(['prefix' => 'advertising_modal'], function () {
        Route::get('/', 'AdvertisingModalController@index')->name('admin.advertising_modal');
        Route::post('/', 'AdvertisingModalController@store')->name('admin.advertising_modal.store');
        Route::get('/preview', 'AdvertisingModalController@preview')->name('admin.advertising_modal.preview');
    });

    Route::group(['prefix' => 'floating_bars'], function () {
        Route::get('/', 'FloatingBarController@index')->name('admin.floating_bars');
        Route::post('/', 'FloatingBarController@store')->name('admin.floating_bars.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Themes & Appearance
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'themes'], function () {
        Route::get('/', 'ThemesController@index')->name('admin.themes');
        Route::get('/create', 'ThemesController@create')->name('admin.themes.create');
        Route::post('/', 'ThemesController@store')->name('admin.themes.store');
        Route::post('/store', 'ThemesController@store');
        Route::get('/getHomeLandingComponents', 'ThemesController@getHomeLandingComponents');
        Route::get('/{id}/edit', 'ThemesController@edit')->name('admin.themes.edit');
        Route::post('/{id}/update', 'ThemesController@update')->name('admin.themes.update');
        Route::delete('/{id}/delete', 'ThemesController@delete')->name('admin.themes.delete');
        Route::post('/{id}/enable', 'ThemesController@enable')->name('admin.themes.enable');
        Route::get('/{id}/enable', 'ThemesController@enable'); // blade uses GET via JS confirm

        // Theme Colors
        Route::group(['prefix' => 'colors'], function () {
            Route::get('/', 'ThemeColorsController@index')->name('admin.themes.colors');
            Route::get('/create', 'ThemeColorsController@create')->name('admin.themes.colors.create');
            Route::post('/', 'ThemeColorsController@store')->name('admin.themes.colors.store');
            Route::post('/store', 'ThemeColorsController@store'); // for blade forms posting to /themes/colors/store
            Route::get('/{id}/edit', 'ThemeColorsController@edit')->name('admin.themes.colors.edit');
            Route::post('/{id}/update', 'ThemeColorsController@update')->name('admin.themes.colors.update');
            Route::delete('/{id}/delete', 'ThemeColorsController@delete')->name('admin.themes.colors.delete');
        });

        // Theme Fonts
        Route::group(['prefix' => 'fonts'], function () {
            Route::get('/', 'ThemeFontsController@index')->name('admin.themes.fonts');
            Route::get('/create', 'ThemeFontsController@create')->name('admin.themes.fonts.create');
            Route::post('/', 'ThemeFontsController@store')->name('admin.themes.fonts.store');
            Route::post('/store', 'ThemeFontsController@store'); // for blade forms posting to /themes/fonts/store
            Route::get('/{id}/edit', 'ThemeFontsController@edit')->name('admin.themes.fonts.edit');
            Route::post('/{id}/update', 'ThemeFontsController@update')->name('admin.themes.fonts.update');
            Route::delete('/{id}/delete', 'ThemeFontsController@delete')->name('admin.themes.fonts.delete');
        });

        // Theme Headers
        Route::group(['prefix' => 'headers'], function () {
            Route::get('/', 'ThemeHeadersController@index')->name('admin.themes.headers');
            Route::get('/{id}/edit', 'ThemeHeadersController@edit')->name('admin.themes.headers.edit');
            Route::post('/{id}/update', 'ThemeHeadersController@update')->name('admin.themes.headers.update');
        });

        // Theme Footers
        Route::group(['prefix' => 'footers'], function () {
            Route::get('/', 'ThemeFootersController@index')->name('admin.themes.footers');
            Route::get('/{id}/edit', 'ThemeFootersController@edit')->name('admin.themes.footers.edit');
            Route::post('/{id}/update', 'ThemeFootersController@update')->name('admin.themes.footers.update');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', 'SettingsController@index')->name('admin.settings');

        // POST routes must be defined BEFORE the catch-all routes
        Route::post('/seo-metas', 'SettingsController@storeSeoMetas')->name('admin.settings.seo-metas');
        Route::post('/seo_metas/store', 'SettingsController@storeSeoMetas');
        Route::get('/socials/{id}/edit', 'SettingsController@editSocials');
        Route::delete('/socials/{id}/delete', 'SettingsController@deleteSocials');
        Route::post('/socials', 'SettingsController@storeSocials');
        Route::post('/socials/store', 'SettingsController@storeSocials');
        Route::post('/notifications-metas', 'SettingsController@notificationsMetas');
        Route::post('/notifications/store', 'SettingsController@notificationsMetas');

        // Reset users login count (DeviceLimitSettings trait)
        Route::get('/reset-users-login-count', 'SettingsController@resetUsersLoginCount');

        // Catch-all GET route for dynamic settings pages
        Route::get('/personalization/{page}', 'SettingsController@personalizationPage')->name('admin.settings.personalization');
        Route::get('/{page}', 'SettingsController@page')->name('admin.settings.page')->where('page', '[a-zA-Z_]+');

        // Catch-all POST route for settings pages - pass {name} to store() method
        Route::post('/{name}', 'SettingsController@store')->name('admin.settings.store')->where('name', '[a-zA-Z_]+');

        // Home Sections
        Route::group(['prefix' => 'home-sections'], function () {
            Route::get('/', 'HomeSectionSettingsController@index')->name('admin.settings.home-sections');
            Route::post('/', 'HomeSectionSettingsController@store');
            Route::post('/store', 'HomeSectionSettingsController@store');
            Route::delete('/{id}/delete', 'HomeSectionSettingsController@delete');
            Route::post('/sort', 'HomeSectionSettingsController@sort');
        });

        // Statistics
        Route::group(['prefix' => 'statistics'], function () {
            Route::get('/', 'StatisticSettingsController@index')->name('admin.settings.statistics');
            Route::post('/', 'StatisticSettingsController@store');
            Route::post('/store', 'StatisticSettingsController@store');
            Route::get('/get-form', 'StatisticSettingsController@getForm');
            Route::post('/item', 'StatisticSettingsController@storeItem');
            Route::post('/item/store', 'StatisticSettingsController@storeItem');
            Route::get('/item/{id}/edit', 'StatisticSettingsController@editItem');
            Route::post('/item/{id}/update', 'StatisticSettingsController@updateItem');
            Route::delete('/item/{id}/delete', 'StatisticSettingsController@deleteItem');
            Route::post('/sort', 'StatisticSettingsController@sort');
        });

        // Mobile App Settings
        Route::get('/mobile-app', 'MobileAppSettingsController@index')->name('admin.settings.mobile-app');
        Route::post('/mobile-app', 'MobileAppSettingsController@store')->name('admin.settings.mobile-app.store');

        // Payment Channels (alias routes - main routes under /financial/payment-channels)
        Route::group(['prefix' => 'payment_channels'], function () {
            Route::get('/', 'PaymentChannelController@index');
            Route::get('/create', 'PaymentChannelController@create');
            Route::post('/store', 'PaymentChannelController@store');
            Route::get('/{id}/edit', 'PaymentChannelController@edit');
            Route::post('/{id}/update', 'PaymentChannelController@update');
            Route::get('/{id}/toggleStatus', 'PaymentChannelController@toggleStatus');
            Route::post('/{id}/toggleStatus', 'PaymentChannelController@toggleStatus');
        });

        // Financial settings under /settings/financial/ (blade templates use this path)
        Route::group(['prefix' => 'financial'], function () {
            // Currency
            Route::group(['prefix' => 'currency'], function () {
                Route::get('/', 'SettingsController@currencySettings');
                Route::post('/', 'SettingsController@storeCurrency');
                Route::get('/{id}/edit', 'SettingsController@editCurrency');
                Route::post('/{id}/update', 'SettingsController@updateCurrency');
                Route::delete('/{id}/delete', 'SettingsController@deleteCurrency');
            });

            // Offline Banks
            Route::group(['prefix' => 'offline_banks'], function () {
                Route::get('/get-form', 'SettingsController@financialOfflineBankForm');
                Route::post('/', 'SettingsController@financialOfflineBankStore');
                Route::post('/store', 'SettingsController@financialOfflineBankStore');
                Route::get('/{id}/edit', 'SettingsController@financialOfflineBankEdit');
                Route::post('/{id}/update', 'SettingsController@financialOfflineBankUpdate');
                Route::delete('/{id}/delete', 'SettingsController@financialOfflineBankDelete');
            });

            // User Banks
            Route::group(['prefix' => 'user_banks'], function () {
                Route::get('/get-form', 'SettingsController@financialUserBankForm');
                Route::post('/', 'SettingsController@financialUserBankStore');
                Route::post('/store', 'SettingsController@financialUserBankStore');
                Route::get('/{id}/edit', 'SettingsController@financialUserBankEdit');
                Route::post('/{id}/update', 'SettingsController@financialUserBankUpdate');
                Route::delete('/{id}/delete', 'SettingsController@financialUserBankDelete');
            });
        });

        // Update App
        Route::group(['prefix' => 'update-app'], function () {
            Route::get('/', 'UpdateController@index')->name('admin.settings.update-app');
            Route::post('/basic', 'UpdateController@basicUpdate')->name('admin.settings.update-app.basic');
            Route::get('/custom-update', 'UpdateController@customUpdate')->name('admin.settings.update-app.custom');
            Route::get('/database', 'UpdateController@databaseUpdate')->name('admin.settings.update-app.database');
        });
    });

    // Additional Pages
    Route::group(['prefix' => 'additional_page'], function () {
        Route::get('/', 'AdditionalPageController@index')->name('admin.additional_page.index')->defaults('name', '404');
        Route::get('/{name}', 'AdditionalPageController@index')->name('admin.additional_page')->where('name', '[a-zA-Z0-9_]+');
        Route::post('/store', 'AdditionalPageController@store')->name('admin.additional_page.store');
        Route::post('/{name}', 'AdditionalPageController@store')->where('name', '[a-zA-Z0-9_]+'); // for blade forms posting to /additional_page/{name}

        // Navbar Links
        Route::group(['prefix' => 'navbar_links'], function () {
            Route::get('/', 'NavbarLinksSettingsController@index')->name('admin.additional_page.navbar_links');
            Route::post('/', 'NavbarLinksSettingsController@store');
            Route::post('/store', 'NavbarLinksSettingsController@store'); // for blade forms posting to /additional_page/navbar_links/store
            Route::get('/{id}/edit', 'NavbarLinksSettingsController@edit');
            Route::post('/{id}/update', 'NavbarLinksSettingsController@update');
            Route::delete('/{id}/delete', 'NavbarLinksSettingsController@delete');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Rewards & Gamification
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'rewards'], function () {
        Route::get('/', 'RewardController@index')->name('admin.rewards');
        Route::get('/items', 'RewardController@create')->name('admin.rewards.items');
        Route::post('/', 'RewardController@store')->name('admin.rewards.store');
        Route::post('/store', 'RewardController@store');
        Route::get('/settings', 'RewardController@settings')->name('admin.rewards.settings');
        Route::post('/settings', 'RewardController@storeSettings')->name('admin.rewards.settings.store');
        Route::get('/{id}/edit', 'RewardController@edit')->name('admin.rewards.edit');
        Route::post('/{id}/update', 'RewardController@update')->name('admin.rewards.update');
        Route::delete('/{id}/delete', 'RewardController@delete')->name('admin.rewards.delete');
    });

    Route::group(['prefix' => 'registration_bonus'], function () {
        Route::get('/', 'RegistrationBonusController@index')->name('admin.registration_bonus');
        Route::get('/history', 'RegistrationBonusController@index')->name('admin.registration_bonus.history');
        Route::get('/excel', 'RegistrationBonusController@exportExcel')->name('admin.registration_bonus.excel');
        Route::get('/settings', 'RegistrationBonusController@settings')->name('admin.registration_bonus.settings');
        Route::post('/settings', 'RegistrationBonusController@storeSettings')->name('admin.registration_bonus.settings.store');
    });

    Route::group(['prefix' => 'gifts'], function () {
        Route::get('/', 'GiftsController@index')->name('admin.gifts');
        Route::get('/excel', 'GiftsController@exportExcel')->name('admin.gifts.excel');
        Route::get('/settings', 'GiftsController@settings')->name('admin.gifts.settings');
        Route::post('/settings', 'GiftsController@storeSettings')->name('admin.gifts.settings.store');
        Route::post('/{id}/sendReminder', 'GiftsController@sendReminder');
        Route::get('/{id}/send_reminder', 'GiftsController@sendReminder'); // blade uses this URL with GET
        Route::post('/{id}/cancel', 'GiftsController@cancel');
        Route::get('/{id}/cancel', 'GiftsController@cancel'); // blade uses GET via JS confirm
    });

    /*
    |--------------------------------------------------------------------------
    | Cashback
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'cashback'], function () {
        Route::get('/', 'CashbackRuleController@index')->name('admin.cashback');
        Route::get('/rules', 'CashbackRuleController@index')->name('admin.cashback.rules');
        Route::get('/rules/new', 'CashbackRuleController@create')->name('admin.cashback.rules.create');
        Route::post('/rules', 'CashbackRuleController@store')->name('admin.cashback.rules.store');
        Route::post('/rules/store', 'CashbackRuleController@store');
        Route::get('/rules/{id}/edit', 'CashbackRuleController@edit')->name('admin.cashback.rules.edit');
        Route::post('/rules/{id}/update', 'CashbackRuleController@update')->name('admin.cashback.rules.update');
        Route::delete('/rules/{id}/delete', 'CashbackRuleController@delete')->name('admin.cashback.rules.delete');
        Route::post('/rules/{id}/statusToggle', 'CashbackRuleController@statusToggle');

        Route::get('/transactions', 'CashbackTransactionsController@index')->name('admin.cashback.transactions');
        Route::get('/transactions/excel', 'CashbackTransactionsController@exportExcel');
        Route::post('/transactions/{id}/refund', 'CashbackTransactionsController@refund');
        Route::get('/history', 'CashbackTransactionsController@history')->name('admin.cashback.history');
        Route::get('/history/excel', 'CashbackTransactionsController@historyExportExcel');
    });

    /*
    |--------------------------------------------------------------------------
    | Abandoned Cart
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'abandoned-cart'], function () {
        Route::get('/', 'AbandonedCartRulesController@index')->name('admin.abandoned-cart');
        Route::get('/settings', 'AbandonedCartController@settings')->name('admin.abandoned-cart.settings');
        Route::post('/settings', 'AbandonedCartController@storeSettings')->name('admin.abandoned-cart.settings.store');

        Route::get('/rules', 'AbandonedCartRulesController@index')->name('admin.abandoned-cart.rules');
        Route::get('/rules/create', 'AbandonedCartRulesController@create')->name('admin.abandoned-cart.rules.create');
        Route::post('/rules', 'AbandonedCartRulesController@store');
        Route::post('/rules/store', 'AbandonedCartRulesController@store');
        Route::get('/rules/{id}/edit', 'AbandonedCartRulesController@edit');
        Route::post('/rules/{id}/update', 'AbandonedCartRulesController@update');
        Route::delete('/rules/{id}/delete', 'AbandonedCartRulesController@delete');

        Route::get('/users-carts', 'AbandonedUsersCartController@index')->name('admin.abandoned-cart.users-carts');
        Route::post('/users-carts/{id}/sendReminder', 'AbandonedUsersCartController@sendReminder');
        Route::get('/users-carts/{id}/viewItems', 'AbandonedUsersCartController@viewItems');
        Route::post('/users-carts/{id}/empty', 'AbandonedUsersCartController@empty');
        Route::delete('/users-carts/{id}/delete', 'AbandonedUsersCartController@deleteById');
    });

    Route::group(['prefix' => 'cart_discount'], function () {
        Route::get('/', 'CartDiscountController@index')->name('admin.cart_discount');
        Route::post('/', 'CartDiscountController@store')->name('admin.cart_discount.store');
        Route::post('/store', 'CartDiscountController@store'); // for blade forms posting to /cart_discount/store
    });

    /*
    |--------------------------------------------------------------------------
    | Roles & Permissions
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', 'RoleController@index')->name('admin.roles');
        Route::get('/create', 'RoleController@create')->name('admin.roles.create');
        Route::post('/', 'RoleController@store')->name('admin.roles.store');
        Route::post('/store', 'RoleController@store');
        Route::get('/{id}/edit', 'RoleController@edit')->name('admin.roles.edit');
        Route::post('/{id}/update', 'RoleController@update')->name('admin.roles.update');
        Route::delete('/{id}/delete', 'RoleController@destroy')->name('admin.roles.delete');
        Route::post('/{id}/permissions', 'RoleController@storePermission');
    });

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'reports'], function () {
        Route::get('/', 'ReportsController@webinarsReports')->name('admin.reports');
        Route::get('/reasons', 'ReportsController@reasons')->name('admin.reports.reasons');
        Route::post('/reasons', 'ReportsController@storeReasons')->name('admin.reports.reasons.store');
        Route::post('/reasons/store', 'ReportsController@storeReasons');
        Route::get('/webinars', 'ReportsController@webinarsReports')->name('admin.reports.webinars');
        Route::get('/forum-topics', 'ForumTopicReportsController@index')->name('admin.reports.forum-topics');
        Route::delete('/forum-topics/{id}/delete', 'ForumTopicReportsController@delete');
        Route::delete('/{id}/delete', 'ReportsController@delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Testimonials
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'testimonials'], function () {
        Route::get('/', 'TestimonialsController@index')->name('admin.testimonials');
        Route::get('/create', 'TestimonialsController@create')->name('admin.testimonials.create');
        Route::post('/', 'TestimonialsController@store')->name('admin.testimonials.store');
        Route::post('/store', 'TestimonialsController@store');
        Route::get('/{id}/edit', 'TestimonialsController@edit')->name('admin.testimonials.edit');
        Route::post('/{id}/update', 'TestimonialsController@update')->name('admin.testimonials.update');
        Route::delete('/{id}/delete', 'TestimonialsController@delete')->name('admin.testimonials.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'pages'], function () {
        Route::get('/', 'PagesController@index')->name('admin.pages');
        Route::get('/create', 'PagesController@create')->name('admin.pages.create');
        Route::post('/', 'PagesController@store')->name('admin.pages.store');
        Route::post('/store', 'PagesController@store');
        Route::get('/{id}/edit', 'PagesController@edit')->name('admin.pages.edit');
        Route::post('/{id}/update', 'PagesController@update')->name('admin.pages.update');
        Route::delete('/{id}/delete', 'PagesController@delete')->name('admin.pages.delete');
        Route::post('/{id}/toggle', 'PagesController@statusTaggle');
    });

    /*
    |--------------------------------------------------------------------------
    | AI Contents
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'ai-contents'], function () {
        Route::get('/', 'AIContentsController@index')->name('admin.ai-contents.index');
        Route::get('/lists', 'AIContentsController@index')->name('admin.ai-contents');
        Route::get('/generate', 'AIContentsController@generate')->name('admin.ai-contents.generate');
        Route::get('/settings', 'AIContentsController@settings')->name('admin.ai-contents.settings');
        Route::post('/settings', 'AIContentsController@storeSettings')->name('admin.ai-contents.settings.store');
        Route::delete('/{id}/delete', 'AIContentsController@delete');

        // AI Templates
        Route::group(['prefix' => 'templates'], function () {
            Route::get('/', 'AIContentTemplatesController@index')->name('admin.ai-contents.templates');
            Route::get('/create', 'AIContentTemplatesController@create')->name('admin.ai-contents.templates.create');
            Route::post('/', 'AIContentTemplatesController@store');
            Route::post('/store', 'AIContentTemplatesController@store');
            Route::get('/{id}/edit', 'AIContentTemplatesController@edit');
            Route::post('/{id}/update', 'AIContentTemplatesController@update');
            Route::delete('/{id}/delete', 'AIContentTemplatesController@delete');
            Route::post('/{id}/statusToggle', 'AIContentTemplatesController@statusToggle');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Product Badges
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'product-badges'], function () {
        Route::get('/', 'ProductBadgeController@index')->name('admin.product-badges');
        Route::get('/create', 'ProductBadgeController@create')->name('admin.product-badges.create');
        Route::post('/', 'ProductBadgeController@store')->name('admin.product-badges.store');
        Route::post('/store', 'ProductBadgeController@store');
        Route::get('/{id}/edit', 'ProductBadgeController@edit')->name('admin.product-badges.edit');
        Route::post('/{id}/update', 'ProductBadgeController@update')->name('admin.product-badges.update');
        Route::delete('/{id}/delete', 'ProductBadgeController@delete')->name('admin.product-badges.delete');
    });

    Route::group(['prefix' => 'product-badge-contents'], function () {
        Route::get('/get-form', 'ProductBadgeContentsController@getForm');
        Route::post('/', 'ProductBadgeContentsController@store');
        Route::post('/store', 'ProductBadgeContentsController@store');
        Route::delete('/{id}/delete', 'ProductBadgeContentsController@delete');
    });

    // Product badge contents (alternate path used by blade templates)
    Route::get('/product-badges/{id}/contents/get-form', 'ProductBadgeContentsController@getForm');

    /*
    |--------------------------------------------------------------------------
    | Related Products
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'relatedProducts'], function () {
        Route::get('/get-form', 'RelatedProductsController@getForm');
        Route::post('/', 'RelatedProductsController@store');
        Route::post('/store', 'RelatedProductsController@store');
        Route::get('/{id}/edit', 'RelatedProductsController@edit');
        Route::post('/{id}/update', 'RelatedProductsController@update');
        Route::delete('/{id}/delete', 'RelatedProductsController@destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Translation
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'translator'], function () {
        Route::get('/', 'TranslatorController@index')->name('admin.translator');
        Route::post('/', 'TranslatorController@translate')->name('admin.translator.store');
        Route::post('/translate', 'TranslatorController@translate')->name('admin.translator.translate');
    });

    /*
    |--------------------------------------------------------------------------
    | Referrals
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'referrals'], function () {
        Route::get('/', 'ReferralController@history')->name('admin.referrals');
        Route::get('/history', 'ReferralController@history')->name('admin.referrals.history');
        Route::get('/users', 'ReferralController@users')->name('admin.referrals.users');
        Route::get('/excel', 'ReferralController@exportExcel')->name('admin.referrals.excel');
    });

    /*
    |--------------------------------------------------------------------------
    | Consultants
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'consultants'], function () {
        Route::get('/', 'ConsultantsController@index')->name('admin.consultants');
        Route::get('/excel', 'ConsultantsController@exportExcel')->name('admin.consultants.excel');
    });

    /*
    |--------------------------------------------------------------------------
    | Appointments
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'appointments'], function () {
        Route::get('/', 'AppointmentsController@index')->name('admin.appointments');
        Route::get('/{id}/join', 'AppointmentsController@join');
        Route::get('/{id}/getReminderDetails', 'AppointmentsController@getReminderDetails');
        Route::post('/{id}/sendReminder', 'AppointmentsController@sendReminder');
        Route::post('/{id}/cancel', 'AppointmentsController@cancel');
        Route::get('/{id}/cancel', 'AppointmentsController@cancel'); // blade uses GET via JS confirm
    });

    /*
    |--------------------------------------------------------------------------
    | Agora History
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'agora_history'], function () {
        Route::get('/', 'AgoraHistoryController@index')->name('admin.agora_history');
        Route::get('/excel', 'AgoraHistoryController@exportExcel');
    });

    /*
    |--------------------------------------------------------------------------
    | Waitlist
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'waitlists'], function () {
        Route::get('/', 'WaitlistController@index')->name('admin.waitlists');
        Route::get('/excel', 'WaitlistController@exportExcel');
        Route::get('/{id}/viewList', 'WaitlistController@viewList');
        Route::post('/{id}/clearList', 'WaitlistController@clearList');
        Route::post('/{id}/disableWaitlist', 'WaitlistController@disableWaitlist');
        Route::get('/{id}/exportUsersList', 'WaitlistController@exportUsersList');
        Route::delete('/{id}/deleteItems', 'WaitlistController@deleteWaitlistItems');
    });

    /*
    |--------------------------------------------------------------------------
    | Content Delete Requests
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'content-delete-requests'], function () {
        Route::get('/', 'ContentDeleteRequestController@index')->name('admin.content-delete-requests');
        Route::post('/{id}/approve', 'ContentDeleteRequestController@approve');
        Route::get('/{id}/approve', 'ContentDeleteRequestController@approve'); // blade uses GET via JS confirm
        Route::post('/{id}/reject', 'ContentDeleteRequestController@reject');
        Route::get('/{id}/reject', 'ContentDeleteRequestController@reject'); // blade uses GET via JS confirm
    });

    /*
    |--------------------------------------------------------------------------
    | Instructor Finder
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'instructor-finder'], function () {
        Route::get('/', 'InstructorFinderController@settings')->name('admin.instructor-finder');
        Route::get('/settings', 'InstructorFinderController@settings')->name('admin.instructor-finder.settings');
        Route::post('/settings', 'InstructorFinderController@storeSettings')->name('admin.instructor-finder.settings.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Region Management
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'regions'], function () {
        Route::get('/', 'RegionController@index')->name('admin.regions')->defaults('pageType', 'countries');
        Route::get('/countries', 'RegionController@index')->name('admin.regions.countries')->defaults('pageType', 'countries');
        Route::get('/provinces', 'RegionController@index')->name('admin.regions.provinces')->defaults('pageType', 'provinces');
        Route::get('/cities', 'RegionController@index')->name('admin.regions.cities')->defaults('pageType', 'cities');
        Route::get('/districts', 'RegionController@index')->name('admin.regions.districts')->defaults('pageType', 'districts');
        Route::get('/create', 'RegionController@create')->name('admin.regions.create');
        Route::post('/', 'RegionController@store')->name('admin.regions.store');
        Route::post('/store', 'RegionController@store'); // for blade forms posting to /regions/store
        Route::get('/{id}/edit', 'RegionController@edit')->name('admin.regions.edit');
        Route::post('/{id}/update', 'RegionController@update')->name('admin.regions.update');
        Route::delete('/{id}/delete', 'RegionController@delete')->name('admin.regions.delete');
        Route::get('/provincesByCountry/{countryId}', 'RegionController@provincesByCountry');
        Route::get('/citiesByProvince/{provinceId}', 'RegionController@citiesByProvince');
    });

    /*
    |--------------------------------------------------------------------------
    | Store (eCommerce)
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'store', 'namespace' => 'Store'], function () {
        // Store index redirects to products
        Route::get('/', 'ProductsController@index')->name('admin.store');
        // Products
        Route::get('/products', 'ProductsController@index')->name('admin.store.products');
        Route::get('/in-house-products', 'ProductsController@inHouseProducts')->name('admin.store.in-house-products');
        Route::get('/products/create', 'ProductsController@create')->name('admin.store.products.create');
        Route::post('/products', 'ProductsController@store')->name('admin.store.products.store');
        Route::get('/products/search', 'ProductsController@search');
        Route::get('/products/getContentItemByLocale', 'ProductsController@getContentItemByLocale');
        Route::get('/products/excel', 'ProductsController@exportExcel');
        Route::get('/products/{id}/edit', 'ProductsController@edit')->name('admin.store.products.edit');
        Route::post('/products/{id}/update', 'ProductsController@update');
        Route::delete('/products/{id}/delete', 'ProductsController@destroy');
        Route::post('/products/{id}/approve', 'ProductsController@approve');
        Route::get('/products/{id}/approve', 'ProductsController@approve'); // blade uses GET via JS confirm
        Route::post('/products/{id}/reject', 'ProductsController@reject');
        Route::get('/products/{id}/reject', 'ProductsController@reject'); // blade uses GET via JS confirm
        Route::post('/products/{id}/unpublish', 'ProductsController@unpublish');
        Route::get('/products/{id}/unpublish', 'ProductsController@unpublish'); // blade uses GET via JS confirm

        // Product Files (for blade form posting to /store/products/files/store)
        Route::post('/products/files/store', 'ProductFileController@store');

        // Store Settings
        Route::get('/settings', 'ProductsController@settings')->name('admin.store.settings');
        Route::post('/settings', 'ProductsController@storeSettings');

        // Orders
        Route::get('/orders', 'OrderController@index')->name('admin.store.orders');
        Route::get('/in-house-orders', 'OrderController@inHouseOrders')->name('admin.store.in-house-orders');
        Route::get('/orders/excel', 'OrderController@exportExcel');
        Route::post('/orders/{id}/refund', 'OrderController@refund');
        Route::get('/orders/{id}/refund', 'OrderController@refund'); // blade uses GET via JS confirm
        Route::get('/orders/{id}/invoice', 'OrderController@invoice');
        Route::get('/orders/{id}/details', 'OrderController@getProductOrder');
        Route::post('/orders/{id}/setTrackingCode', 'OrderController@setTrackingCode');

        // Store Categories
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', 'CategoryController@index')->name('admin.store.categories');
            Route::get('/create', 'CategoryController@create');
            Route::post('/', 'CategoryController@store');
            Route::get('/search', 'CategoryController@search');
            Route::get('/{id}/edit', 'CategoryController@edit');
            Route::post('/{id}/update', 'CategoryController@update');
            Route::delete('/{id}/delete', 'CategoryController@destroy');
            Route::get('/{id}/setSubCategory', 'CategoryController@setSubCategory');
        });

        // Store Filters
        Route::group(['prefix' => 'filters'], function () {
            Route::get('/', 'FilterController@index')->name('admin.store.filters');
            Route::get('/create', 'FilterController@create');
            Route::post('/', 'FilterController@store');
            Route::get('/{id}/edit', 'FilterController@edit');
            Route::post('/{id}/update', 'FilterController@update');
            Route::delete('/{id}/delete', 'FilterController@destroy');
            Route::get('/{id}/setSubFilters', 'FilterController@setSubFilters');
            Route::get('/getByCategoryId/{categoryId}', 'FilterController@getByCategoryId');
        });

        // Specifications
        Route::group(['prefix' => 'specifications'], function () {
            Route::get('/', 'SpecificationController@index')->name('admin.store.specifications');
            Route::get('/create', 'SpecificationController@create');
            Route::post('/', 'SpecificationController@store');
            Route::get('/{id}/edit', 'SpecificationController@edit');
            Route::post('/{id}/update', 'SpecificationController@update');
            Route::delete('/{id}/delete', 'SpecificationController@destroy');
        });

        // Product Specifications
        Route::group(['prefix' => 'product-specifications'], function () {
            Route::get('/{id}', 'ProductSpecificationController@getItem');
            Route::post('/', 'ProductSpecificationController@store');
            Route::post('/{id}/update', 'ProductSpecificationController@update');
            Route::delete('/{id}/delete', 'ProductSpecificationController@destroy');
            Route::get('/search', 'ProductSpecificationController@search');
            Route::get('/getByCategoryId/{categoryId}', 'ProductSpecificationController@getByCategoryId');
        });

        // Product Files
        Route::group(['prefix' => 'product-files'], function () {
            Route::post('/', 'ProductFileController@store');
            Route::get('/{id}/edit', 'ProductFileController@edit');
            Route::post('/{id}/update', 'ProductFileController@update');
            Route::delete('/{id}/delete', 'ProductFileController@destroy');
        });

        // Product FAQ
        Route::group(['prefix' => 'product-faqs'], function () {
            Route::post('/', 'ProductFaqController@store');
            Route::post('/{id}/update', 'ProductFaqController@update');
            Route::delete('/{id}/delete', 'ProductFaqController@destroy');
        });

        // Store Reviews
        Route::group(['prefix' => 'reviews'], function () {
            Route::get('/', 'ReviewsController@index')->name('admin.store.reviews');
            Route::post('/{id}/toggle', 'ReviewsController@toggleStatus');
            Route::get('/{id}/reply', 'ReviewsController@reply');
            Route::post('/{id}/reply', 'ReviewsController@storeReply');
            Route::delete('/{id}/delete', 'ReviewsController@delete');
        });

        // Store Discounts
        Route::group(['prefix' => 'discounts'], function () {
            Route::get('/', 'DiscountController@index')->name('admin.store.discounts');
            Route::get('/create', 'DiscountController@create');
            Route::post('/', 'DiscountController@store');
            Route::get('/{id}/edit', 'DiscountController@edit');
            Route::post('/{id}/update', 'DiscountController@update');
            Route::delete('/{id}/delete', 'DiscountController@destroy');
        });

        // Featured Products
        Route::get('/featured-products', 'ProductFeaturedContentsController@featuredProducts')->name('admin.store.featured-products');

        // Featured Categories
        Route::group(['prefix' => 'featured-categories'], function () {
            Route::get('/', 'ProductFeaturedCategoriesController@index')->name('admin.store.featured-categories');
            Route::post('/', 'ProductFeaturedCategoriesController@store');
            Route::get('/{id}/edit', 'ProductFeaturedCategoriesController@edit');
            Route::post('/{id}/update', 'ProductFeaturedCategoriesController@update');
            Route::delete('/{id}/delete', 'ProductFeaturedCategoriesController@delete');
        });

        // Top Categories
        Route::group(['prefix' => 'top-categories'], function () {
            Route::get('/', 'ProductTopCategoriesController@index')->name('admin.store.top-categories');
            Route::post('/', 'ProductTopCategoriesController@store');
            Route::get('/{id}/edit', 'ProductTopCategoriesController@edit');
            Route::post('/{id}/update', 'ProductTopCategoriesController@update');
            Route::delete('/{id}/delete', 'ProductTopCategoriesController@delete');
        });

        // Sellers
        Route::get('/sellers', 'SellersController@index')->name('admin.store.sellers');

        // Product Filters
        Route::get('/product-filters/getByCategoryId/{categoryId}', 'ProductFilterController@getByCategoryId');

        // Product Comments
        Route::get('/comments', 'CommentsController@productComments')->name('admin.store.comments');
        Route::get('/comments/reports', 'CommentsController@productCommentReports')->name('admin.store.comments.reports');
    });

    /*
    |--------------------------------------------------------------------------
    | Landing Builder
    |--------------------------------------------------------------------------
    */
    Route::group(['prefix' => 'landing-builder'], function () {
        Route::get('/', [\App\Http\Controllers\LandingBuilder\LandingBuilderController::class, 'welcome'])->name('admin.landing-builder');
        Route::get('/main', [\App\Http\Controllers\LandingBuilder\LandingBuilderController::class, 'index'])->name('admin.landing-builder.main');
        Route::get('/all-pages', [\App\Http\Controllers\LandingBuilder\LandingBuilderController::class, 'allLandingPages'])->name('admin.landing-builder.all-pages');
        Route::get('/create', [\App\Http\Controllers\LandingBuilder\LandingBuilderController::class, 'create'])->name('admin.landing-builder.create');
        Route::post('/', [\App\Http\Controllers\LandingBuilder\LandingBuilderController::class, 'store'])->name('admin.landing-builder.store');
        Route::get('/{id}/edit', [\App\Http\Controllers\LandingBuilder\LandingBuilderController::class, 'edit'])->name('admin.landing-builder.edit');
        Route::post('/{id}/update', [\App\Http\Controllers\LandingBuilder\LandingBuilderController::class, 'update'])->name('admin.landing-builder.update');
        Route::delete('/{id}/delete', [\App\Http\Controllers\LandingBuilder\LandingBuilderController::class, 'delete'])->name('admin.landing-builder.delete');
        Route::post('/{id}/duplicate', [\App\Http\Controllers\LandingBuilder\LandingBuilderController::class, 'duplicate'])->name('admin.landing-builder.duplicate');
        Route::post('/{id}/sortComponents', [\App\Http\Controllers\LandingBuilder\LandingBuilderController::class, 'sortComponents'])->name('admin.landing-builder.sortComponents');
        Route::get('/componentPreview/{name}', [\App\Http\Controllers\LandingBuilder\LandingBuilderController::class, 'componentPreview']);

        // Landing Components
        Route::group(['prefix' => '{landingId}/components'], function () {
            Route::post('/add', [\App\Http\Controllers\LandingBuilder\LandingBuilderComponentController::class, 'add']);
            Route::get('/{componentId}/edit', [\App\Http\Controllers\LandingBuilder\LandingBuilderComponentController::class, 'edit']);
            Route::post('/{componentId}/update', [\App\Http\Controllers\LandingBuilder\LandingBuilderComponentController::class, 'update']);
            Route::post('/{componentId}/duplicate', [\App\Http\Controllers\LandingBuilder\LandingBuilderComponentController::class, 'duplicate']);
            Route::post('/{componentId}/clearContent', [\App\Http\Controllers\LandingBuilder\LandingBuilderComponentController::class, 'clearContent']);
            Route::post('/{componentId}/disable', [\App\Http\Controllers\LandingBuilder\LandingBuilderComponentController::class, 'disable']);
            Route::post('/{componentId}/enable', [\App\Http\Controllers\LandingBuilder\LandingBuilderComponentController::class, 'enable']);
            Route::delete('/{componentId}/delete', [\App\Http\Controllers\LandingBuilder\LandingBuilderComponentController::class, 'delete']);
        });
    });

});
