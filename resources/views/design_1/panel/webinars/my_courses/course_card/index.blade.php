@php
    $nextSession = $course->nextSession();
@endphp

@include('design_1.panel.includes.course_card', [
    'item' => $course,
    'course' => $course,
    'badgesView' => 'design_1.panel.webinars.my_courses.course_card.badges',
    'statsView' => 'design_1.panel.webinars.my_courses.course_card.stats',
    'progressView' => 'design_1.panel.webinars.my_courses.course_card.progress_and_chart',
    'footerRightView' => 'design_1.panel.webinars.my_courses.course_card.price',
    'actionsView' => 'design_1.panel.webinars.my_courses.course_card.actions_dropdown',
    'actionsOutsideLink' => true,
    'wrapInLink' => true,
    'isInvitedCoursesPage' => $isInvitedCoursesPage ?? null,
])
