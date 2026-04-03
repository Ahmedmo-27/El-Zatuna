# Commented Parts - Actual Summary

## 1) What I changed (Teacher Dashboard)
- app/Mixins/Panel/SidebarItems.php:122 -> My purchases hidden for teachers
- app/Mixins/Panel/SidebarItems.php:135 -> Favorites hidden for teachers
- app/Mixins/Panel/SidebarItems.php:236 -> Quizzes section hidden for teachers
- app/Mixins/Panel/SidebarItems.php:270 -> Certificates section hidden for teachers
- app/Mixins/Panel/SidebarItems.php:499 -> Articles sidebar section hidden for teachers
- routes/panel.php:551 -> Panel Articles (blog) routes disabled
- resources/views/design_1/web/users/profile/tabs/about.blade.php:19 -> Profile overview bar Articles and Meetings cards hidden

## 2) Important commented-out sections (website)
- resources/views/design_1/web/become_instructor/wizard/form.blade.php:54 -> Bank Account Section - Commented Out
- resources/views/design_1/web/become_instructor/wizard/form.blade.php:89 -> Certificate upload - commented out
- resources/views/design_1/web/courses/learning_page/includes/top_header/course_tools.blade.php:22 -> Hidden per request: quizzes and assignments
- resources/views/design_1/web/courses/learning_page/includes/top_header/course_tools.blade.php:83 -> Hidden per request: quizzes, assignments, and certificates
- resources/views/design_1/panel/includes/header.blade.php:13 -> Multi Color (Dark,Light) - Hidden for El Zatuna theme
- resources/views/admin/theme/create/tabs/includes/landing_component_card.blade.php:10 -> If Disabled

## 3) How to restore later
- Teacher menu items: remove !$user->isTeacher() and from the 4 conditions in app/Mixins/Panel/SidebarItems.php
- Blade comments: remove {{-- and --}} around the block in the file/line listed above

## 4) Full long list
- See Documents/commented-parts-report.md for the exhaustive list of all comments.
