# Commented Parts - Clear Summary

## Latest Updates (2026-04-03)
- app/Mixins/Panel/SidebarItems.php:499
	- Articles sidebar block for teachers commented out (includes New Article, My Articles, Comments).
- routes/panel.php:551
	- Panel blog routes commented out (posts, comments, related posts).
- resources/views/design_1/web/users/profile/tabs/about.blade.php:19
	- Profile overview bar: Articles metric card commented out (Meetings restored).
- resources/views/design_1/panel/settings/includes/progress.blade.php:8
	- Additional Information tab commented out from profile settings step navigation.
- resources/views/design_1/web/courses/learning_page/includes/top_header/course_tools.blade.php:193
	- Notes item in Course Tools dropdown commented out.

This file is a simplified map of commented sections with lines.

## Teacher Dashboard Hidden Items
- app/Mixins/Panel/SidebarItems.php:122 My purchases hidden for teacher
- app/Mixins/Panel/SidebarItems.php:135 Favorites hidden for teacher
- app/Mixins/Panel/SidebarItems.php:236 Quizzes section hidden for teacher
- app/Mixins/Panel/SidebarItems.php:270 Certificates section hidden for teacher

## Blade Commented Sections by File

### resources/views/admin/abandoned_cart/rules/create/index.blade.php
- target products (line: 230)

### resources/views/admin/abandoned_cart/rules/lists/index.blade.php
- Stats (line: 16)

### resources/views/admin/abandoned_cart/users_carts/index.blade.php
- Filters (line: 19)
- Stats (line: 16)

### resources/views/admin/additional_pages/contact_us.blade.php
- additional Information (line: 84)

### resources/views/admin/ai_contents/lists/index.blade.php
- Text Generated (line: 197, 216)

### resources/views/admin/ai_contents/templates/create/index.blade.php
- Image Fields (line: 87)
- Prompt (line: 56)
- Text Fields (line: 67)

### resources/views/admin/blog/create.blade.php
- Product Badges (line: 119)

### resources/views/admin/bundles/create.blade.php
- Product Badges (line: 319)
- Related Course (line: 534)

### resources/views/admin/bundles/students.blade.php
- Gift recipient who has not registered yet (line: 225)

### resources/views/admin/cashback/history.blade.php
- Filters (line: 64)
- Lists (line: 148)

### resources/views/admin/cashback/rules/create/includes/target_products.blade.php
- Registration Specific Package (line: 204)
- Subscription Specific Package (line: 181)

### resources/views/admin/cashback/rules/create/index.blade.php
- Basic Information (line: 29)
- Payment (line: 43)
- target products (line: 36)

### resources/views/admin/cashback/rules/lists/filters.blade.php
- Filters (line: 1)

### resources/views/admin/cashback/rules/lists/index.blade.php
- Filters (line: 19)
- Stats (line: 16)

### resources/views/admin/cashback/transactions.blade.php
- Filters (line: 19)
- Lists (line: 102)

### resources/views/admin/certificates/create_template/index.blade.php
- Certificate Container (line: 57)
- Inputs (line: 48)

### resources/views/admin/financial/installments/create/includes/basic_information.blade.php
- Options (line: 81)

### resources/views/admin/financial/installments/create/includes/payment.blade.php
- Installment Steps (line: 33)

### resources/views/admin/financial/installments/create/includes/target_products.blade.php
- Registration Specific Package (line: 204)
- Subscription Specific Package (line: 181)

### resources/views/admin/financial/installments/create/index.blade.php
- Basic Information (line: 29)
- Payment (line: 50)
- target products (line: 36)
- verification (line: 43)

### resources/views/admin/financial/payout/lists.blade.php
- For Modal (line: 179)

### resources/views/admin/forms/create/index.blade.php
- Basic Information (line: 31)
- Form Fields (line: 52)
- Tank You Message (line: 45)
- Welcome Message (line: 38)

### resources/views/admin/forums/topics/posts.blade.php
- Topic Posts (line: 34)

### resources/views/admin/gifts/history.blade.php
- Filters (line: 77)
- Lists (line: 169)

### resources/views/admin/includes/aiContent/generator.blade.php
- Image Fields (line: 103)
- Text Fields (line: 51)
- Text Generated (line: 152, 156)

### resources/views/admin/includes/header/index.blade.php
- Ai (line: 62)
- Curreny (line: 69)
- language (line: 72)
- Notification (line: 75)
- User (line: 80)

### resources/views/admin/includes/sidebar/index.blade.php
- Appearance (line: 67)
- Appointments (line: 46)
- Content (line: 58)
- CRM (line: 55)
- Education (line: 43)
- Financial (line: 61)
- Forum (line: 52)
- Marketing (line: 64)
- Settings (line: 70)
- Users (line: 49)

### resources/views/admin/landing_builder/landings/index.blade.php
- Top Stats (line: 18)

### resources/views/admin/layouts/app.blade.php
- AI Contents (line: 95)

### resources/views/admin/referrals/users.blade.php
- {{ $affiliates->appends(request()->input())->links() }} (line: 143)

### resources/views/admin/registration_bonus/history.blade.php
- <td>{{ !empty($user->bonus_wallet) ? trans('update.'.$user->bonus_wallet) : '-' }}</td> (line: 277)
- <th>{{trans('update.bonus_wallet')}}</th> (line: 233)
- Filters (line: 80)
- Lists (line: 190)

### resources/views/admin/store/products/create/category_and_specification.blade.php
- Related Course (line: 79)
- Related Products (line: 70)

### resources/views/admin/store/products/create/extra_information.blade.php
- Product Badges (line: 60)

### resources/views/admin/theme/create/tabs/includes/landing_component_card.blade.php
- If Disabled (line: 10)

### resources/views/admin/theme/footers/components/footer_1/index.blade.php
- End Col (line: 210, 325)
- End Row (line: 327)
- General Information (line: 3)
- links (line: 214)
- links 2 (line: 251, 288)

### resources/views/admin/theme/headers/components/header_1/index.blade.php
- End Col (line: 81, 150)
- End Row (line: 152)
- General Information (line: 3)
- Navbar Button (line: 110)
- Navbar links (line: 85)

### resources/views/admin/theme/headers/components/header_1/navbar_button.blade.php
- <div class="mt-8 font-12 text-gray-500">{{ trans('empty_means_all_roles') }}</div> (line: 15)

### resources/views/admin/theme/headers/components/header_2/index.blade.php
- End Col (line: 81, 150)
- End Row (line: 152)
- General Information (line: 3)
- Navbar Button (line: 110)
- Navbar links (line: 85)

### resources/views/admin/theme/headers/components/header_2/navbar_button.blade.php
- <div class="mt-8 font-12 text-gray-500">{{ trans('empty_means_all_roles') }}</div> (line: 15)

### resources/views/admin/theme/lists/index.blade.php
- Top Stats (line: 18)

### resources/views/admin/upcoming_courses/create/includes/additional_information.blade.php
- Product Badges (line: 171)

### resources/views/admin/upcoming_courses/create/index.blade.php
- Additional Information (line: 57)
- Basic Information (line: 50)
- Extra Description (line: 74)
- FAQ (line: 71)
- Related Course (line: 63)

### resources/views/admin/webinars/create.blade.php
- Product Badges (line: 540)
- Related Course (line: 760)

### resources/views/admin/webinars/create_includes/accordions/file.blade.php
- Drag and Drop Zone (line: 185)
- Hidden File Input (line: 214)
- Selected File Display (line: 232)

### resources/views/admin/webinars/create_includes/chapter_modal.blade.php
- <input type="hidden" name="ajax[chapter][type]" class="js-chapter-type" value=""> (line: 8)

### resources/views/admin/webinars/forum/answers_lists.blade.php
- Answers Cards (line: 56)
- Question Card (line: 28)

### resources/views/admin/webinars/modals/file.blade.php
- Drag and Drop Zone (line: 90)
- Hidden File Input (line: 119)
- Selected File Display (line: 137)

### resources/views/admin/webinars/students.blade.php
- Gift recipient who has not registered yet (line: 259)

### resources/views/design_1/panel/ai_contents/generator.blade.php
- Image Fields (line: 115)
- Text Fields (line: 62)
- Text Generated (line: 164, 168)

### resources/views/design_1/panel/ai_contents/lists/index.blade.php
- Filters (line: 15)
- List Table (line: 17)
- Pagination (line: 37)

### resources/views/design_1/panel/assignments/histories/index.blade.php
- List Table (line: 24)
- Most Active Assignments (line: 11, 21)
- Pagination (line: 47)
- Stats (line: 8)

### resources/views/design_1/panel/assignments/histories/item_table.blade.php
- Actions (line: 107)
- Attempts (line: 78)
- First Submission (line: 54)
- Grade (line: 83)
- Last Submission (line: 66)
- Purchase Date (line: 42)
- Status (line: 88)
- Student (line: 2)
- Title (line: 19)

### resources/views/design_1/panel/assignments/my_assignments/index.blade.php
- Filters (line: 27)
- List Table (line: 30)
- Pagination (line: 53)
- Pending Assignments (line: 13)
- Top Stats (line: 10)

### resources/views/design_1/panel/assignments/my-courses-assignments/index.blade.php
- List Table (line: 25)
- Most Active Assignments (line: 11, 22)
- Pagination (line: 49)
- Stats (line: 8)

### resources/views/design_1/panel/assignments/my-courses-assignments/item_table.blade.php
- Actions (line: 76)
- Average Grade (line: 30)
- Failed (line: 50)
- Last Submission (line: 55)
- Min Grade (line: 25)
- Passed (line: 45)
- Pending (line: 40)
- Status (line: 67)
- Submissions (line: 35)
- Title (line: 2)

### resources/views/design_1/panel/assignments/students/index.blade.php
- List Table (line: 24)
- Most Active Assignments (line: 11, 21)
- Pagination (line: 46)
- Stats (line: 8)

### resources/views/design_1/panel/assignments/students/item_table.blade.php
- Actions (line: 84)
- Attempts (line: 55)
- First Submission (line: 31)
- Grade (line: 60)
- Last Submission (line: 43)
- Purchase Date (line: 19)
- Status (line: 65)
- Student (line: 2)

### resources/views/design_1/panel/assignments/students/top_stats.blade.php
- Failed Submissions (line: 49)
- Not Submitted (line: 75)
- Passed Submissions (line: 36)
- Pending Review (line: 62)
- Success Rate (line: 88)
- Total Submission (line: 23)

### resources/views/design_1/panel/blog/comments/index.blade.php
- Filters (line: 20)
- List Table (line: 23)
- Pagination (line: 42)

### resources/views/design_1/panel/blog/posts/create/includes/related_posts.blade.php
- Related Posts (line: 1)

### resources/views/design_1/panel/blog/posts/create/index.blade.php
- Related Posts (line: 114)

### resources/views/design_1/panel/blog/posts/lists/index.blade.php
- Filters (line: 22)
- List Table (line: 25)
- Lists (line: 7)
- Pagination (line: 46)
- Top Stats (line: 4)

### resources/views/design_1/panel/bundles/courses/index.blade.php
- List Table (line: 9)
- Pagination (line: 20)

### resources/views/design_1/panel/bundles/create/includes/accordions/price_plan.blade.php
- <span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 cl... (line: 10)

### resources/views/design_1/panel/bundles/create/includes/bottom_actions.blade.php
- Next (line: 16)
- Previous (line: 5)
- Save as Draft (line: 30)
- Send for Review (line: 42)

### resources/views/design_1/panel/bundles/create/index.blade.php
- Bottom Actions (line: 55)
- Progress (line: 47)
- Steps Inputs (line: 50)

### resources/views/design_1/panel/bundles/create/steps/step_1.blade.php
- Course Description (line: 153)
- Course Summary (line: 139)
- Video (line: 94)

### resources/views/design_1/panel/bundles/create/steps/step_3.blade.php
- Pricing Options (line: 7)
- Pricing Plans (line: 47)
- Pricing Plans Form (line: 68)

### resources/views/design_1/panel/bundles/create/steps/step_4.blade.php
- Courses (line: 8)
- Related Courses (line: 51)

### resources/views/design_1/panel/bundles/my_bundles/index.blade.php
- Lists (line: 12)
- Pagination (line: 23)
- Top Stats (line: 9)

### resources/views/design_1/panel/certificates/lists/course_item_table.blade.php
- Actions (line: 36)
- Generated Certificates (line: 19)
- Last Certificate (line: 24)
- Title (line: 6)

### resources/views/design_1/panel/certificates/lists/index.blade.php
- Filters (line: 48)
- List Table (line: 52)
- Lists (line: 14)
- Pagination (line: 71)
- Recent Student Certificates (line: 11)
- Stats (line: 8)

### resources/views/design_1/panel/certificates/lists/quiz_item_table.blade.php
- Actions (line: 46)
- Generated Certificates (line: 29)
- Last Certificate (line: 34)
- Title (line: 6)

### resources/views/design_1/panel/certificates/my_achievements/course_item_table.blade.php
- Actions (line: 33)
- Generated Certificates (line: 16)
- Last Certificate (line: 21)
- Title (line: 3)

### resources/views/design_1/panel/certificates/my_achievements/index.blade.php
- Filters (line: 47)
- List Table (line: 51)
- Lists (line: 16)
- Pagination (line: 73)
- Recent Student Certificates (line: 13)
- Stats (line: 10)

### resources/views/design_1/panel/certificates/my_achievements/quiz_item_table.blade.php
- Actions (line: 57)
- Average Grade (line: 40)
- Certificate ID (line: 25)
- Last Certificate (line: 45)
- Minimum Grade (line: 30)
- My Grade (line: 35)
- Title (line: 2)

### resources/views/design_1/panel/certificates/students/index.blade.php
- Filters (line: 25)
- List Table (line: 29)
- Lists (line: 14)
- Most Active Courses (line: 11)
- Pagination (line: 50)
- Stats (line: 8)

### resources/views/design_1/panel/certificates/students/item_table.blade.php
- Actions (line: 77)
- Certificate Date (line: 69)
- Certificate ID (line: 15)
- Certificate Reason (line: 20)
- Certificate Type (line: 60)
- Student (line: 2)

### resources/views/design_1/panel/dashboard/instructor/includes/courses_overview.blade.php
- Assignment (line: 89)
- Course Card (line: 43)
- If Empty (line: 103)
- Live Courses (line: 6)
- Quiz (line: 77)
- Text Courses (line: 30)
- Video Courses (line: 18)

### resources/views/design_1/panel/dashboard/instructor/includes/hello_box.blade.php
- If Empty (line: 150)

### resources/views/design_1/panel/dashboard/instructor/includes/open_meetings.blade.php
- Avatar 1 (line: 91)
- Card (line: 21)
- If Empty (line: 80)
- Join To Meeting Modal (line: 77)

### resources/views/design_1/panel/dashboard/instructor/includes/review_student_quizzes.blade.php
- Card (line: 20)
- If Empty (line: 72)

### resources/views/design_1/panel/dashboard/instructor/includes/sales_overview.blade.php
- Chart (line: 64)
- Create a Bundle (line: 102)
- Create a Course (line: 90)
- Create a Product (line: 96)
- If Empty (line: 80)
- Live Courses (line: 6)
- Meeting Settings (line: 108)
- Month Sales (line: 45)
- Stats (line: 43)
- Text Courses (line: 30)
- Total Sales (line: 57)
- Video Courses (line: 18)
- Year Sales (line: 51)

### resources/views/design_1/panel/dashboard/instructor/includes/support_messages.blade.php
- If Empty (line: 75)
- If Have Data (line: 4)
- Open Tickets (line: 8)
- Total Tickets (line: 18)

### resources/views/design_1/panel/dashboard/instructor/includes/top_instructors.blade.php
- Active Instructors (line: 16)
- If Empty (line: 44)
- Total Instructors (line: 6)

### resources/views/design_1/panel/dashboard/instructor/includes/top_students.blade.php
- Active Students (line: 16)
- If Empty (line: 47)
- Total Students (line: 6)

### resources/views/design_1/panel/dashboard/instructor/includes/upcoming_live_sessions.blade.php
- Card (line: 20)
- If Empty (line: 64)

### resources/views/design_1/panel/dashboard/instructor/includes/visitors_statistics.blade.php
- Card (line: 35)
- Chart (line: 17)
- Top Views (line: 30)

### resources/views/design_1/panel/dashboard/instructor/index.blade.php
- Courses Overview (line: 6)
- Current Balance (No different with Student Dashboard) (line: 23)
- Hello Box (line: 3)
- Noticeboard (No different with Student Dashboard) (line: 26)
- Open Meetings (line: 45)
- Organization (line: 49)
- Pending Student Assignments (line: 14)
- Registration Plan (line: 20)
- Review Student Quizzes (line: 42)
- Sales Overview (line: 11)
- Support Messages (line: 29)
- Top Instructors (line: 51)
- Top Students (line: 54)
- Upcoming Live Sessions (line: 39)
- Visitors Statistics (line: 32)

### resources/views/design_1/panel/dashboard/student/includes/courses_overview.blade.php
- Assignment (line: 91)
- Completed Courses (line: 18)
- Course Card (line: 43)
- If Empty (line: 105)
- Open Courses (line: 30)
- Quiz (line: 79)
- Total Courses (line: 6)

### resources/views/design_1/panel/dashboard/student/includes/events_calendar.blade.php
- If Empty (line: 38)
- If Have Upcoming Events (line: 10)

### resources/views/design_1/panel/dashboard/student/includes/learning_activity.blade.php
- Chart (line: 58)
- Continue Learning (line: 61, 110)
- Daily Time (line: 10)
- Have Data & Chart (line: 48)
- Month Time (line: 22)
- No Activity! (line: 87)
- Total Time (line: 34)

### resources/views/design_1/panel/dashboard/student/includes/my_quizzes.blade.php
- If Empty (line: 82)
- If Have Data (line: 1)
- Not Participated (line: 8)
- Pending Review (line: 18)

### resources/views/design_1/panel/dashboard/student/includes/noticeboard.blade.php
- Empty (line: 51)
- User Message (line: 4)

### resources/views/design_1/panel/dashboard/student/includes/open_meetings.blade.php
- Avatar 1 (line: 89)
- Card (line: 21)
- If Empty (line: 78)
- Join To Meeting Modal (line: 75)

### resources/views/design_1/panel/dashboard/student/includes/support_messages.blade.php
- If Empty (line: 82)
- If Have Data (line: 1)
- Open Tickets (line: 9)
- Total Tickets (line: 19)

### resources/views/design_1/panel/dashboard/student/includes/upcoming_live_sessions.blade.php
- Card (line: 20)
- If Empty (line: 61)

### resources/views/design_1/panel/dashboard/student/index.blade.php
- Courses Overview (line: 6)
- Current Balance (line: 22)
- Hello Box (line: 3)
- Learning Activity (line: 14)
- My Assignments (line: 11)
- My Quizzes (line: 31)
- Noticeboard (line: 25)
- Open Meetings (line: 39)
- Subscribe Plan (line: 19)
- Support Messages (line: 28)
- Upcoming Live Sessions (line: 36)

### resources/views/design_1/panel/events/day_events.blade.php
- Events Card (line: 24)

### resources/views/design_1/panel/events/index.blade.php
- Card (line: 36)
- Upcoming Events (line: 29)

### resources/views/design_1/panel/financial/account/index.blade.php
- Charge Account Form (line: 12)
- Offline transactions (line: 15)
- Overview (line: 9)

### resources/views/design_1/panel/financial/account/offline_transactions/index.blade.php
- List Table (line: 1)
- Pagination (line: 31)

### resources/views/design_1/panel/financial/installments/details/index.blade.php
- List Table (line: 18)
- Pagination (line: 149)
- Top State (line: 4)

### resources/views/design_1/panel/financial/installments/lists/grid_card.blade.php
- Actions (line: 28)
- Badges (line: 25)
- Chart (line: 93)

### resources/views/design_1/panel/financial/installments/lists/index.blade.php
- Filters (line: 41)
- List Table (line: 43)
- My Installments (line: 30)
- Overdue Installments (line: 26)
- Pagination (line: 53)
- Top Stats (line: 23)

### resources/views/design_1/panel/financial/payout/index.blade.php
- Filters (line: 29)
- List Table (line: 31)
- Pagination (line: 50)

### resources/views/design_1/panel/financial/payout/table_items.blade.php
- For Modal (line: 36)

### resources/views/design_1/panel/financial/registration_packages/index.blade.php
- Name (line: 10)
- Packages (line: 175)

### resources/views/design_1/panel/financial/sales/index.blade.php
- Filters (line: 22)
- List Table (line: 25)
- Pagination (line: 47)
- Top Stats (line: 9)

### resources/views/design_1/panel/financial/subscribes/index.blade.php
- Name (line: 10)
- Packages (line: 93)

### resources/views/design_1/panel/financial/subscribes/plan_card.blade.php
- Popular (line: 10)

### resources/views/design_1/panel/financial/summary/index.blade.php
- Filters (line: 16)
- List Table (line: 19)
- Pagination (line: 38)

### resources/views/design_1/panel/forum/bookmarks/index.blade.php
- Filters (line: 19)
- List Table (line: 21)
- Pagination (line: 40)

### resources/views/design_1/panel/forum/posts/index.blade.php
- Filters (line: 19)
- List Table (line: 22)
- Pagination (line: 40)

### resources/views/design_1/panel/forum/topics/index.blade.php
- Filters (line: 22)
- List Table (line: 25)
- Pagination (line: 44)
- Top Stats (line: 9)

### resources/views/design_1/panel/includes/course_card.blade.php
- Content (line: 63, 142)
- Image (line: 40, 111)

### resources/views/design_1/panel/includes/header.blade.php
- Multi Color (Dark,Light) - Hidden for El Zatuna theme (line: 13)
- Notification (line: 52)

### resources/views/design_1/panel/includes/sidebar.blade.php
- Menu Items (line: 54)

### resources/views/design_1/panel/layouts/panel.blade.php
- AI Contents (line: 84)
- Cart Drawer (line: 89)

### resources/views/design_1/panel/manage/instructors/index.blade.php
- Filters (line: 22)
- List Table (line: 25)
- Pagination (line: 47)
- Top Stats (line: 9)

### resources/views/design_1/panel/manage/students/index.blade.php
- Filters (line: 22)
- List Table (line: 25)
- Pagination (line: 47)
- Top Stats (line: 9)

### resources/views/design_1/panel/marketing/affiliates/index.blade.php
- How it works? (line: 100)
- List Table (line: 123)
- Pagination (line: 150)
- Top Stats (line: 5)
- Your Affiliate Information (line: 61)

### resources/views/design_1/panel/marketing/discounts/create/index.blade.php
- Fixed Amount (line: 121)
- Percentage Inputs (line: 92)

### resources/views/design_1/panel/marketing/discounts/lists/index.blade.php
- Filters (line: 21)
- List Table (line: 24)
- Pagination (line: 49)
- Top Stats (line: 9)

### resources/views/design_1/panel/marketing/promotions/index.blade.php
- List Table (line: 57)
- Pagination (line: 85)

### resources/views/design_1/panel/marketing/registration_bonus/index.blade.php
- List Table (line: 114)
- Pagination (line: 170)
- Top Stats (line: 16)

### resources/views/design_1/panel/marketing/special_offers/index.blade.php
- Lists (line: 13)
- Pagination (line: 42)

### resources/views/design_1/panel/marketing/special_offers/table_items.blade.php
- Actions (line: 27)
- Amount (line: 8)
- Date Range (line: 13)
- Status (line: 18)

### resources/views/design_1/panel/meeting/requests/index.blade.php
- Filters (line: 21)
- List Table (line: 24)
- Pagination (line: 47)
- Top Stats (line: 9)

### resources/views/design_1/panel/meeting/reservation/index.blade.php
- Filters (line: 21)
- List Table (line: 24)
- Pagination (line: 47)
- Top Stats (line: 9)

### resources/views/design_1/panel/noticeboard/create/index.blade.php
- Color (line: 42)
- Course (line: 26, 77)
- Types (line: 58)

### resources/views/design_1/panel/noticeboard/lists/filters.blade.php
- data-option="just_teachers" (line: 55)

### resources/views/design_1/panel/noticeboard/lists/index.blade.php
- Filters (line: 22)
- List Table (line: 25)
- Pagination (line: 50)

### resources/views/design_1/panel/notifications/index.blade.php
- Notifications Lists (line: 21)
- Pagination (line: 29)

### resources/views/design_1/panel/quizzes/create/index.blade.php
- Form (line: 11)

### resources/views/design_1/panel/quizzes/create/questions_list.blade.php
- Questions (line: 28)

### resources/views/design_1/panel/quizzes/create/quiz_form.blade.php
- Locale (line: 3)

### resources/views/design_1/panel/quizzes/holding/overview.blade.php
- Stats (line: 14)

### resources/views/design_1/panel/quizzes/holding/result/index.blade.php
- Header (line: 25)
- Questions Form (line: 39)
- Quiz Content (line: 22)
- Seperator (line: 34)
- Top Info (line: 19)

### resources/views/design_1/panel/quizzes/holding/start/index.blade.php
- Header (line: 26)
- Questions Form (line: 49)
- Quiz Content (line: 23)
- Seperator (line: 44)
- Top Info (line: 20)

### resources/views/design_1/panel/quizzes/holding/status/index.blade.php
- Stats (line: 9)

### resources/views/design_1/panel/quizzes/lists/index.blade.php
- <th>{{ trans('quiz.average') }}</th> (line: 36)
- Filters (line: 21)
- List Table (line: 25)
- Pagination (line: 49)
- Top Stats (line: 9)

### resources/views/design_1/panel/quizzes/my_results/index.blade.php
- Filters (line: 28)
- List Table (line: 32)
- Pagination (line: 54)
- Pending Quizzes (line: 14)
- Top Stats (line: 10)

### resources/views/design_1/panel/quizzes/opens/index.blade.php
- Filters (line: 18)
- List Table (line: 22)
- Pagination (line: 42)

### resources/views/design_1/panel/quizzes/results/index.blade.php
- List Table (line: 24)
- Most Active Assignments (line: 21)
- Pagination (line: 47)
- Pending Review Quizzes (line: 11)
- Stats (line: 8)

### resources/views/design_1/panel/quizzes/results/item_table.blade.php
- Actions (line: 81)
- Attempts (line: 57)
- Date (line: 62)
- Pass Grade (line: 47)
- Quiz (line: 19)
- Status (line: 70)
- Student (line: 2)
- Student Grade (line: 52)
- Total Grade (line: 42)

### resources/views/design_1/panel/rewards/index.blade.php
- Convert your points (line: 14)
- Filters (line: 33)
- Financial Documents (line: 25)
- Lists (line: 36)
- Pagination (line: 54)
- Top Stats (line: 10)

### resources/views/design_1/panel/settings/index.blade.php
- Step Fields (line: 30)

### resources/views/design_1/panel/settings/tabs/about.blade.php
- About (line: 69)
- Education History (line: 5)
- Experiences History (line: 105)
- Files & Attachments (line: 193)
- Items (line: 25, 125, 213)
- Occupuations (line: 163)

### resources/views/design_1/panel/settings/tabs/extra_information.blade.php
- Forms (line: 98)
- Meetings Settings (line: 40)

### resources/views/design_1/panel/settings/tabs/images.blade.php
- Profile Cover (line: 33)
- profile_image (line: 5)
- profile_video (line: 110)
- secondary image (line: 72)
- signature (line: 149)

### resources/views/design_1/panel/store/comments/index.blade.php
- Filters (line: 21)
- List Table (line: 25)
- Pagination (line: 45)
- Top Stats (line: 9)

### resources/views/design_1/panel/store/create_product/includes/bottom_actions.blade.php
- Next (line: 10)
- Previous (line: 5)
- Save as Draft (line: 18)
- Send for Review (line: 30)

### resources/views/design_1/panel/store/create_product/index.blade.php
- Bottom Actions (line: 21)
- Progress (line: 13)
- Steps Inputs (line: 16)

### resources/views/design_1/panel/store/create_product/steps/step_1.blade.php
- Course Description (line: 59)

### resources/views/design_1/panel/store/create_product/steps/step_2.blade.php
- Pricing Options (line: 7)
- Related Courses (line: 180)
- Related Products (line: 137)

### resources/views/design_1/panel/store/create_product/steps/step_3.blade.php
- Files (line: 120)
- Images (line: 26)
- Video (line: 74)

### resources/views/design_1/panel/store/create_product/steps/step_4.blade.php
- FAQ (line: 50)
- Specifications (line: 8)

### resources/views/design_1/panel/store/my_comments/index.blade.php
- Filters (line: 18)
- List Table (line: 21)
- Pagination (line: 40)

### resources/views/design_1/panel/store/my_products/index.blade.php
- List Table (line: 12)
- Pagination (line: 23)
- Top Stats (line: 9)

### resources/views/design_1/panel/store/my_products/product_card/index.blade.php
- Actions Dropdown (positioned outside the link) (line: 70)
- Badges (line: 22)
- Content (line: 27)
- End Stats (line: 44)
- Image (line: 10)
- Price (line: 53)
- Progress & Price (line: 47)
- Stats (line: 42)

### resources/views/design_1/panel/store/my_products/product_card/stats.blade.php
- Availability (line: 22)
- Customers (line: 2)
- Last Purchase (line: 58)
- Sales (line: 12)
- Views (line: 38)
- Waiting Orders (line: 48)

### resources/views/design_1/panel/store/my_purchases/index.blade.php
- Filters (line: 21)
- List Table (line: 24)
- Pagination (line: 50)
- Top Stats (line: 9)

### resources/views/design_1/panel/store/sales/index.blade.php
- Filters (line: 22)
- List Table (line: 25)
- Pagination (line: 49)
- Top Stats (line: 9)

### resources/views/design_1/panel/upcoming_courses/create/includes/bottom_actions.blade.php
- Next (line: 10)
- Previous (line: 5)
- Save as Draft (line: 18)
- Send for Review (line: 30)

### resources/views/design_1/panel/upcoming_courses/create/index.blade.php
- Bottom Actions (line: 25)
- Progress (line: 17)
- Steps Inputs (line: 20)

### resources/views/design_1/panel/upcoming_courses/create/steps/step_1.blade.php
- Course Description (line: 184)
- Course Summary (line: 171)
- course_type (line: 7)
- Video (line: 126)

### resources/views/design_1/panel/upcoming_courses/create/steps/step_2.blade.php
- Course Options (line: 35)

### resources/views/design_1/panel/upcoming_courses/create/steps/step_3.blade.php
- Company Logos (line: 50)
- Learning Materials (line: 75)
- Related Courses (line: 180)
- Requirements (line: 128)

### resources/views/design_1/panel/upcoming_courses/followers/index.blade.php
- Pagination (line: 17)

### resources/views/design_1/panel/upcoming_courses/followings/grid_card.blade.php
- Chart (line: 59)

### resources/views/design_1/panel/upcoming_courses/followings/index.blade.php
- Pagination (line: 19)

### resources/views/design_1/panel/upcoming_courses/my_courses/grid_card.blade.php
- Actions (line: 9)
- Badges (line: 6)
- Chart (line: 76)

### resources/views/design_1/panel/upcoming_courses/my_courses/index.blade.php
- Pagination (line: 22)
- Top Stats (line: 9)

### resources/views/design_1/panel/webinars/comments/index.blade.php
- Filters (line: 21)
- List Table (line: 24)
- Pagination (line: 44)
- Top Stats (line: 9)

### resources/views/design_1/panel/webinars/course_statistics/includes/avg_stats.blade.php
- Assignments (line: 38)
- Average Rating (line: 2)
- Forum Messages (line: 56)
- Quizzes (line: 20)

### resources/views/design_1/panel/webinars/course_statistics/includes/learning_activity.blade.php
- Chart (line: 49)
- Have Data & Chart (line: 46)
- Month Time (line: 19)
- Total Time (line: 7)
- Year Time (line: 31)

### resources/views/design_1/panel/webinars/course_statistics/includes/sales.blade.php
- Chart (line: 49)
- Have Data & Chart (line: 46)
- Month Sales (line: 19)
- Totla Sales (line: 7)
- Year sales (line: 31)

### resources/views/design_1/panel/webinars/course_statistics/includes/students_progress.blade.php
- Chart (line: 47)
- Daily Time (line: 7)
- Have Data & Chart (line: 44)
- Month Time (line: 19)
- Total Time (line: 31)

### resources/views/design_1/panel/webinars/course_statistics/includes/top_summary.blade.php
- Average Student Progress (line: 124)
- Chapters (line: 17)
- Comments (line: 39)
- Course Performance (line: 74)
- Lessons (line: 28)
- Pending Assignments (line: 50)
- Pending Quizzes (line: 61)
- Sales (line: 79)
- Students (line: 6)
- Visits (line: 94)
- Watch Time (line: 109)

### resources/views/design_1/panel/webinars/course_statistics/includes/visitors.blade.php
- Chart (line: 48)
- Have Data & Chart (line: 45)
- month Time (line: 19)
- total Time (line: 7)
- year Time (line: 31)

### resources/views/design_1/panel/webinars/course_statistics/index.blade.php
- .\ Pie Charts (line: 64)
- Avg Stats (line: 14)
- Course Progress (line: 30)
- Course Students (line: 88)
- Learning Activity (line: 67)
- Pie Charts (line: 17)
- Quiz Status (line: 41)
- Sales (line: 77)
- Student Assignments (line: 52)
- Student User Roles (line: 19)
- Students Progress (line: 72)
- Top Summary (line: 11)
- Visitors (line: 82)

### resources/views/design_1/panel/webinars/course_statistics/students/index.blade.php
- Filters (line: 9)
- List Table (line: 13)
- Pagination (line: 34)

### resources/views/design_1/panel/webinars/course_statistics/students/item_table.blade.php
- Certificates (line: 42)
- Enrollment Date (line: 47)
- Learning Activity (line: 24)
- Passed Assignments (line: 37)
- Passed Quizzes (line: 32)
- Progress (line: 15)
- Student (line: 2)

### resources/views/design_1/panel/webinars/create/includes/accordions/file.blade.php
- Current / uploaded file display (same as after first upload) (line: 170)
- Drag and Drop Zone (line: 208)
- Hidden File Input (line: 244)

### resources/views/design_1/panel/webinars/create/includes/accordions/price_plan.blade.php
- <span class="move-icon mr-8 cursor-pointer d-flex text-gray-500"><x-iconsax-lin-arrow-3 cl... (line: 10)

### resources/views/design_1/panel/webinars/create/includes/accordions/quiz.blade.php
- Form (line: 41)

### resources/views/design_1/panel/webinars/create/includes/bottom_actions.blade.php
- Next (line: 16)
- Previous (line: 5)
- Save & Preview (line: 27)
- Save as Draft (line: 24)
- Send for Review (line: 39)

### resources/views/design_1/panel/webinars/create/includes/chapter_contents.blade.php
- Add Content Dropdown (line: 21)

### resources/views/design_1/panel/webinars/create/index.blade.php
- Bottom Actions (line: 65)
- Progress (line: 50)
- Steps Inputs (line: 53)

### resources/views/design_1/panel/webinars/create/modals/chapter.blade.php
- <input type="hidden" name="ajax[chapter][type]" class="js-chapter-type" value=""> (line: 15)

### resources/views/design_1/panel/webinars/create/steps/step_1.blade.php
- Course Description (line: 215)
- Course Icon (line: 129)
- Course Summary (line: 202)
- Course type is fixed to "course" in the new flow (line: 7)
- Video (line: 157)

### resources/views/design_1/panel/webinars/create/steps/step_2.blade.php
- Course Options (line: 13)
- data-option="just_teachers" (line: 138)

### resources/views/design_1/panel/webinars/create/steps/step_3.blade.php
- Chapter Items (line: 182)
- Hidden Forms for New Content (line: 186)

### resources/views/design_1/panel/webinars/create/steps/step_4.blade.php
- Related Courses (line: 50)

### resources/views/design_1/panel/webinars/create/steps/step_5.blade.php
- Company Logos (line: 163)
- Learning Materials (line: 57)
- Requirements (line: 111)

### resources/views/design_1/panel/webinars/favorites/index.blade.php
- Pagination (line: 19)

### resources/views/design_1/panel/webinars/my_comments/index.blade.php
- Filters (line: 18)
- List Table (line: 21)
- Pagination (line: 40)

### resources/views/design_1/panel/webinars/my_courses/course_card/progress_and_chart.blade.php
- if not capacity (line: 62)
- if webinar and is finish (line: 35)

### resources/views/design_1/panel/webinars/my_courses/course_card/stats.blade.php
- Av. Learning (line: 42)
- Hrs. Activity (line: 32)
- Lessons (line: 52)
- Sales (line: 12)
- Students (line: 2)
- Views (line: 22)

### resources/views/design_1/panel/webinars/my_courses/grid_card.blade.php
- Actions (line: 21)
- Badges (line: 18)
- Chart (line: 112)

### resources/views/design_1/panel/webinars/my_courses/grid_card/grid_card_progress_chart.blade.php
- if not capacity (line: 51)
- if webinar and is finish (line: 24)

### resources/views/design_1/panel/webinars/my_courses/grid_card/index.blade.php
- Actions (line: 21)
- Badges (line: 18)
- Chart (line: 112)

### resources/views/design_1/panel/webinars/my_courses/index.blade.php
- List Table (line: 16)
- Pagination (line: 27)
- Top Stats (line: 10)
- Upcoming Live Sessions (line: 13)

### resources/views/design_1/panel/webinars/my_purchases/index.blade.php
- List Table (line: 16)
- Pagination (line: 27)
- Top Stats (line: 10)
- Upcoming Live Sessions (line: 13)

### resources/views/design_1/panel/webinars/my_purchases/item_card/stats.blade.php
- Assignments (line: 57)
- Courses (line: 46)
- Duration (line: 34)
- Enroll Date (line: 24)
- Hrs. Activity (line: 14)
- Lessons (line: 69)
- Type (line: 2)

### resources/views/design_1/panel/webinars/organization_classes/grid_card.blade.php
- @include("design_1.panel.webinars.my_courses.grid_card.grid_card_actions") (line: 23)
- Actions (line: 22)
- Badges (line: 19)

### resources/views/design_1/panel/webinars/organization_classes/index.blade.php
- List Table (line: 26)
- Pagination (line: 37)

### resources/views/design_1/panel/webinars/personal_notes/index.blade.php
- List Table (line: 15)
- Pagination (line: 39)

### resources/views/design_1/web/auth/theme_1/login/index.blade.php
- Source (line: 11)

### resources/views/design_1/web/auth/theme_1/register/index.blade.php
- Role (line: 24)

### resources/views/design_1/web/become_instructor/packages/current_package.blade.php
- Courses (line: 32)
- Live Courses Capacity (line: 54)
- Meeting Hours (line: 43)
- Organization Instructors (line: 77)
- Organization Students (line: 88)
- products (line: 65)

### resources/views/design_1/web/become_instructor/wizard/form.blade.php
- Areas of Expertise: searchable input + "Add different subject" (shared partial) (line: 30)
- Bank Account Section - Commented Out (line: 54)
- Certificate upload - commented out to allow form submission without filling (line: 89)
- Documents Section (line: 51)
- Role Selection (line: 5)

### resources/views/design_1/web/blog/lists/includes/left_filters.blade.php
- Instructor (line: 100)
- More Options (line: 26)
- Prices Filters (line: 62)

### resources/views/design_1/web/blog/lists/index.blade.php
- Blog Lists (line: 50)
- Featured Categories (line: 32)
- Featured Posts (line: 35)
- Header (line: 27, 29)
- Left Filters (line: 45)
- Top Authors (line: 67)

### resources/views/design_1/web/blog/show/includes/comments.blade.php
- Comments Lists (line: 11)
- Form (line: 5)

### resources/views/design_1/web/blog/show/index.blade.php
- Author Info (line: 34)
- Comments (line: 40)
- Fixed Bottom (line: 47)
- Header (line: 21)
- Post content (line: 29)
- Short Description (line: 24)
- Suggested Post (line: 37)

### resources/views/design_1/web/bundles/lists/includes/left_filters.blade.php
- Instructor (line: 99)
- More Options (line: 28)
- Prices Filters (line: 61)
- Types (line: 6)

### resources/views/design_1/web/bundles/lists/index.blade.php
- Courses Lists (line: 59)
- Header (line: 20)
- Left Filters (line: 54)
- Pagination (line: 66)
- Seo Content (line: 73)
- Top Filters (line: 50)

### resources/views/design_1/web/bundles/show/includes/hero.blade.php
- Badges (line: 18)
- Lectures (line: 47)
- Rate (line: 33)
- Students (line: 40)
- Top Seller (line: 20)

### resources/views/design_1/web/bundles/show/includes/right_side.blade.php
- Cashback (line: 72)
- Enroll Form (line: 28)
- organization (line: 67)
- Price (line: 25)
- Send as Gift (line: 79)
- Specifications (line: 61)
- tags (line: 83)
- teacher (line: 64)
- Thumbnail (line: 6)

### resources/views/design_1/web/bundles/show/includes/rightSide/price.blade.php
- Price (line: 24)
- Price Plans (line: 1)

### resources/views/design_1/web/bundles/show/index.blade.php
- ./ Ads Bannaer (line: 31)
- Ads Bannaer (line: 29)
- Contant and Tabs (line: 25)
- Hero (line: 21)
- Right Side (line: 34)
- Sidebar ads Banner (line: 38)
- Special Offer (line: 16)

### resources/views/design_1/web/bundles/show/tabs/about.blade.php
- About course (line: 18)
- About Instructor (line: 68)
- course FAQ (line: 29)
- Installments (line: 3, 145)
- Related Courses (line: 119)

### resources/views/design_1/web/bundles/show/tabs/comments.blade.php
- Comments Lists (line: 10)
- Form (line: 4)

### resources/views/design_1/web/bundles/show/tabs/reviews.blade.php
- Rate (line: 13)
- Review form (line: 16)
- Review Lists (line: 24)

### resources/views/design_1/web/cart/overview/index.blade.php
- CashBack (line: 22)
- Coupon (line: 45)
- Items (line: 19, 35)
- Right Side (line: 49)
- Summary (line: 52)

### resources/views/design_1/web/cart/payment/channels/jazzCash.blade.php
- ... (line: 16)

### resources/views/design_1/web/cart/payment/index.blade.php
- Alert (line: 84)
- CashBack (line: 29)
- Items (line: 26)
- Right Side (line: 119)
- Summary (line: 122)

### resources/views/design_1/web/certificate_validation/status.blade.php
- Achieve Date (line: 30)
- Certificate ID (line: 12)
- Course (line: 36)
- Student (line: 24)
- Type (line: 18)

### resources/views/design_1/web/contactus/our_info.blade.php
- Contact Numbers (line: 6)
- Email (line: 30)

### resources/views/design_1/web/courses/agora/index.blade.php
- Noticeboards (line: 30)
- Page Content (line: 17)
- Sidebar (line: 26)
- Stream (line: 20)
- Top Header (line: 14)

### resources/views/design_1/web/courses/agora/sidebar/index.blade.php
- Tabs (line: 43)
- User & Progress (line: 10)

### resources/views/design_1/web/courses/agora/stream.blade.php
- Brush Size Selector (line: 121)
- Color & Size Indicators (line: 96)
- Color Palette (line: 107)
- Controls (line: 193)
- Drawing Tools (line: 50)
- End Session (line: 223)
- Laser Pointer (line: 89)
- Page Navigation (line: 140)
- Shape Tools (line: 66)
- Text Tool (line: 82)
- Timer (line: 178)
- Toolbar (line: 46)
- Whiteboard Container (line: 19)
- Zoom Controls (line: 155)

### resources/views/design_1/web/courses/components/cards/rows/row_card_1.blade.php
- Image (line: 3)

### resources/views/design_1/web/courses/free_contents/text_lesson.blade.php
- Attachments (line: 69)
- Content (line: 53)
- End Container (line: 156)
- End Row (line: 155)
- Header (line: 18)
- Right Side (line: 67)
- Text Lessons (line: 117)

### resources/views/design_1/web/courses/learning_page/includes/contents/assignment.blade.php
- About Assignment (line: 13)
- Attachments (line: 17)
- Instructor Rate (line: 20)
- Left (line: 34)
- messages (line: 57)
- Right (line: 55)
- Send Assignment Form (line: 50)
- Status (line: 36)
- Top Stats (line: 10)
- Top Status (line: 7)

### resources/views/design_1/web/courses/learning_page/includes/contents/assignment/top_stats.blade.php
- Deadline (line: 3)
- Pass Grade (line: 61)
- Submission Times (line: 27)
- Your Grade (line: 46)

### resources/views/design_1/web/courses/learning_page/includes/contents/assignment_instructor.blade.php
- Average Grade (line: 56)
- Failed Students (line: 34)
- Participated Students (line: 12)
- Passed Students (line: 23)
- Waiting Students (line: 45)

### resources/views/design_1/web/courses/learning_page/includes/contents/file.blade.php
- Footer Actions And Desc (line: 109)
- Local upload videos can use direct path (line: 100)
- R2 videos use JavaScript to get proxied URL (line: 90)

### resources/views/design_1/web/courses/learning_page/includes/contents/forum/answers.blade.php
- Question Details (line: 19)

### resources/views/design_1/web/courses/learning_page/includes/contents/forum/includes/answer_card.blade.php
- Content (line: 40)
- User Info (line: 9)

### resources/views/design_1/web/courses/learning_page/includes/contents/forum/includes/forum_top_stats.blade.php
- Active Users (line: 41)
- Open Questions (line: 28)
- Resolved Questions (line: 15)
- Total Questions (line: 2)

### resources/views/design_1/web/courses/learning_page/includes/contents/forum/index.blade.php
- Card (line: 27)
- Have a Question? (line: 5)
- Top Stats (line: 2)

### resources/views/design_1/web/courses/learning_page/includes/contents/quiz.blade.php
- Footer Actions And Desc (line: 12)
- Not Participated (line: 3)

### resources/views/design_1/web/courses/learning_page/includes/contents/quiz/not_participated.blade.php
- Attempts (line: 109)
- Average Grade (line: 64)
- Failed Students (line: 40)
- Participated Students (line: 18)
- Pass Mark (line: 98)
- Passed Students (line: 29)
- Questions (line: 87)
- Quiz Time (line: 76)
- Status (line: 120)
- Waiting Students (line: 51)

### resources/views/design_1/web/courses/learning_page/includes/contents/quiz/result_status.blade.php
- Attempts (line: 49)
- Pass Mark (line: 27)
- Status (line: 60)
- Your Grade (line: 38)

### resources/views/design_1/web/courses/learning_page/includes/contents/session.blade.php
- Footer Actions And Desc (line: 15)
- session_not_started (line: 9)

### resources/views/design_1/web/courses/learning_page/includes/contents/text_lesson.blade.php
- Footer Actions And Desc (line: 6)

### resources/views/design_1/web/courses/learning_page/includes/sidebar/index.blade.php
- Course Expire Alert (line: 51)
- Course Start Alert (line: 63)
- Course Tools (line: 3)
- Tabs (line: 72)
- User & Progress (line: 17)

### resources/views/design_1/web/courses/learning_page/includes/top_header.blade.php
- Course Tools (line: 14)
- Notification (line: 21)

### resources/views/design_1/web/courses/learning_page/includes/top_header/course_tools.blade.php
- Hidden per request: quizzes and assignments (line: 22)
- Hidden per request: quizzes, assignments, and certificates (line: 83, 158)
- Hidden per request: notes (line: 193)

### resources/views/design_1/web/courses/learning_page/index.blade.php
- Noticeboards (line: 47)
- Page Content (line: 39)
- Sidebar (line: 43)
- Top Header (line: 36)

### resources/views/design_1/web/courses/lists/classes.blade.php
- Courses Lists (line: 64)
- Header (line: 25)
- Left Filters (line: 59)
- Pagination (line: 75)
- Seo Content (line: 82)
- Top Filters (line: 55)

### resources/views/design_1/web/courses/lists/includes/left_filters.blade.php
- Category Filters And Options (line: 33)
- Instructor (line: 97)
- Prices Filters (line: 59)
- Rating (line: 123)

### resources/views/design_1/web/courses/lists/reward_courses.blade.php
- Courses Lists (line: 63)
- Header (line: 23)
- Left Filters (line: 58)
- Pagination (line: 74)
- Seo Content (line: 81)
- Top Filters (line: 54)

### resources/views/design_1/web/courses/lists/with_category.blade.php
- Courses Lists (line: 62)
- Featured Courses (line: 49)
- Header (line: 22)
- Left Filters (line: 57)
- Pagination (line: 73)
- Seo Content (line: 80)
- Top Filters (line: 53)

### resources/views/design_1/web/courses/show/includes/hero.blade.php
- Badges (line: 20)
- Featured (line: 22)
- Lectures (line: 63)
- Rate (line: 49)
- Students (line: 56)
- Top Seller (line: 30)

### resources/views/design_1/web/courses/show/includes/right_side.blade.php
- Cashback (line: 138)
- Course Specifications (line: 120)
- Enroll Form (line: 29)
- Invited (line: 131)
- organization (line: 126)
- Price (line: 26)
- Send as Gift (line: 145)
- tags (line: 148)
- teacher (line: 123)
- This course includes (line: 42)
- Thumbnail (line: 6)

### resources/views/design_1/web/courses/show/includes/rightSide/price.blade.php
- Price (line: 32)
- Price Plans (line: 1)

### resources/views/design_1/web/courses/show/index.blade.php
- ./ Ads Bannaer (line: 35)
- Ads Bannaer (line: 33)
- Bottom Fixed (line: 47)
- Contant and Tabs (line: 28)
- Hero (line: 24)
- Right Side (line: 38)
- Sidebar ads Banner (line: 42)
- Special Offer (line: 19)

### resources/views/design_1/web/courses/show/tabs/about.blade.php
- About course (line: 46)
- About Instructor (line: 174)
- course FAQ (line: 114)
- course Prerequisites (line: 151)
- Installments (line: 7, 322)
- Recent Reviews (line: 241)
- Related Courses (line: 296)
- Requirements (line: 57)
- What will you learn (line: 23)

### resources/views/design_1/web/courses/show/tabs/comments.blade.php
- Comments Lists (line: 10)
- Form (line: 4)

### resources/views/design_1/web/courses/show/tabs/content.blade.php
- Chapters (line: 24)
- Files (line: 41)
- Sessions (line: 32)
- TextLessons (line: 50)

### resources/views/design_1/web/courses/show/tabs/reviews.blade.php
- Rate (line: 13)
- Review form (line: 16)
- Review Lists (line: 24)

### resources/views/design_1/web/emails/layout.blade.php
- <link rel="stylesheet" type="text/css" href="{{ url('/css/email.css') }}"> (line: 6)

### resources/views/design_1/web/forms/layout.blade.php
- Content (line: 55)
- Header (line: 23)

### resources/views/design_1/web/forms/pages/fields.blade.php
- Inputs (line: 21)

### resources/views/design_1/web/forums/homepage/includes/forums.blade.php
- Foreach Sub Forums Or Self (line: 17)

### resources/views/design_1/web/forums/homepage/index.blade.php
- Featured Topics (line: 22)
- Forums (line: 27)
- Hero (line: 13)
- Recommended Topics (line: 32)
- Revolver (line: 19)
- Stats (line: 16)

### resources/views/design_1/web/forums/posts/includes/post_card.blade.php
- Attachments (line: 113)
- Avatar (line: 19)
- Edit (line: 157)
- Likes (line: 190)
- Pin & Unpin (line: 147)
- Post Description (line: 107)
- Replay (line: 137)
- Report (line: 176)

### resources/views/design_1/web/forums/posts/index.blade.php
- Hero (line: 13)
- Topic (line: 16)
- Topic Posts (line: 19)

### resources/views/design_1/web/forums/search/index.blade.php
- Hero & Cover (line: 10)
- Lists (line: 27)
- Right (line: 53)
- Search Drawer (line: 70)

### resources/views/design_1/web/forums/topics/create/index.blade.php
- Hero & Cover (line: 12)

### resources/views/design_1/web/forums/topics/lists/index.blade.php
- Hero & Cover (line: 10)
- Lists (line: 14)
- Right (line: 40)
- Search Drawer (line: 56)

### resources/views/design_1/web/includes/advertise_modal/modal.blade.php
- Countdown (line: 39)
- Description (line: 33)
- Icon (line: 18)
- Progress (line: 11)
- Title (line: 28)

### resources/views/design_1/web/includes/occupations_input.blade.php
- Shared searchable occupations/subjects input: search + "Add different subject", same UX on... (line: 1)

### resources/views/design_1/web/installments/includes/card.blade.php
- Installment Details (line: 2)
- Other Step (line: 89)
- Payment Steps (line: 71)
- Right Card (Payment Details) (line: 49)
- Upfront (line: 74)

### resources/views/design_1/web/installments/plans/index.blade.php
- Installment Overview (line: 18)

### resources/views/design_1/web/installments/plans/overview.blade.php
- duration (line: 59)
- installments (line: 37)
- total_amount (line: 48)
- upfront_amount (line: 26)

### resources/views/design_1/web/installments/verify/index.blade.php
- Installment Form (line: 24)
- Installment Overview (line: 19)

### resources/views/design_1/web/installments/verify/overview.blade.php
- duration (line: 64)
- installments (line: 42)
- total_amount (line: 53)
- upfront_amount (line: 31)

### resources/views/design_1/web/installments/verify/verify_form.blade.php
- Attachments (line: 32)
- Banner (line: 15)
- Installment Terms & Rules (line: 76)
- Verify Section (line: 7)
- Video (line: 22)

### resources/views/design_1/web/instructor_discounts/cards.blade.php
- $instructorDiscounts (line: 5)

### resources/views/design_1/web/instructor_finder/lists/index.blade.php
- Featured Instructors (line: 30)
- Filters (line: 73)
- Instructors Card (line: 45)
- Left Side (line: 67)
- Location (line: 76)
- Map (line: 35)
- Others (line: 79)
- Top Filters (line: 40)
- top hero (line: 15)
- Top Mentors (line: 70)

### resources/views/design_1/web/instructor_finder/lists/instructor_card.blade.php
- Courses (line: 54)
- Member Since (line: 43)
- Total Meetings (line: 65)
- Tutoring Hours (line: 76)

### resources/views/design_1/web/instructor_finder/lists/left_side/filters.blade.php
- Filter Instructors (line: 3)
- Rating (line: 73)

### resources/views/design_1/web/instructor_finder/lists/left_side/other.blade.php
- Days (line: 98)
- Time Range (line: 130)

### resources/views/design_1/web/instructor_finder/lists/map.blade.php
- <img src="/assets/design_1/img/map/pin_large.svg" class="marker" width="40" height="40"> (line: 11)

### resources/views/design_1/web/instructors/components/cards/grids/grid_card_1.blade.php
- Rate (line: 15)

### resources/views/design_1/web/instructors/includes/left_filters.blade.php
- Other Options (line: 70)
- Rating (line: 35)
- Types (line: 6)

### resources/views/design_1/web/instructors/index.blade.php
- Header (line: 20, 22)
- Instructors Lists (line: 40)
- Left Filters (line: 35)
- Top Filters (line: 31)
- Top Instructors (line: 25)

### resources/views/design_1/web/layouts/app.blade.php
- Cart Drawer (line: 91)
- Content (line: 80)

### resources/views/design_1/web/organizations/includes/left_filters.blade.php
- Meeting Options (line: 73)
- Organization (line: 115)
- Other Options (line: 94)
- Rating (line: 35)
- Types (line: 6)

### resources/views/design_1/web/organizations/index.blade.php
- Header (line: 19, 21)
- Left Filters (line: 34)
- organizations Lists (line: 39)
- Top Filters (line: 30)
- Top organizations (line: 24)

### resources/views/design_1/web/pages/index.blade.php
- Content (line: 20)
- Header (line: 16)

### resources/views/design_1/web/products/files/index.blade.php
- Files (line: 44)
- Rate (line: 19)

### resources/views/design_1/web/products/lists/includes/left_filters.blade.php
- Instructor (line: 135)
- More Options (line: 66)
- Prices Filters (line: 97)
- Types (line: 44)

### resources/views/design_1/web/products/lists/index.blade.php
- Courses Lists (line: 70)
- Featured Categories (line: 95)
- Featured Products (line: 57)
- Header (line: 25)
- Left Filters (line: 65)
- Pagination (line: 77)
- Seo Content (line: 84)
- Top Categories (line: 52)
- Top Filters (line: 61)

### resources/views/design_1/web/products/show/includes/main_info.blade.php
- Actions (line: 182)
- Badges (line: 32)
- Breadcrumb (line: 13)
- Price (line: 128)
- Quantity (line: 156)
- Rate (line: 51)
- Special Offer (line: 10)
- Specifications (line: 105)
- Summary (line: 87)

### resources/views/design_1/web/products/show/includes/promotions.blade.php
- Gift Card (line: 15)

### resources/views/design_1/web/products/show/index.blade.php
- ./ Ads Bannaer (line: 196)
- Ads Bannaer (line: 194)
- Images (line: 32)
- Installments (line: 17, 129)
- Main Info (line: 37)
- Promotions Banners (line: 43)
- Related Courses (line: 168)
- Related Products (line: 142)
- Tabs (line: 50)

### resources/views/design_1/web/products/show/tabs/about.blade.php
- FAQ (line: 10)

### resources/views/design_1/web/products/show/tabs/comments.blade.php
- Comments Lists (line: 10)
- Form (line: 4)

### resources/views/design_1/web/products/show/tabs/files.blade.php
- Files (line: 20)

### resources/views/design_1/web/products/show/tabs/reviews.blade.php
- Rate (line: 13)
- Review form (line: 16)
- Review Lists (line: 25)

### resources/views/design_1/web/search/index.blade.php
- Blog Posts Section (line: 108)
- Bundles Section (line: 75)
- Courses Section (line: 64)
- Products Section (line: 86)
- Upcoming Courses Section (line: 97)
- Users Section (line: 119)

### resources/views/design_1/web/theme/footers/footer_1/index.blade.php
- Newsletter (line: 27)

### resources/views/design_1/web/theme/headers/header_1/includes/auth_user_info.blade.php
- End User (line: 37)
- User (line: 13)

### resources/views/design_1/web/theme/headers/header_1/index.blade.php
- Main (line: 9)
- Top Nav (line: 4)

### resources/views/design_1/web/theme/headers/header_1/main.blade.php
- Category (line: 25)
- Links (line: 30)
- Logo (line: 12)
- Right Button (line: 41)

### resources/views/design_1/web/theme/headers/header_1/top_nav.blade.php
- Cart (line: 59)
- Currency (line: 56)
- Email (line: 15)
- Language (line: 53)
- Multi Color (Dark,Light) (line: 23)
- Phone (line: 7)
- Search (line: 36)

### resources/views/design_1/web/theme/headers/header_2/includes/auth_user_info.blade.php
- End User (line: 37)
- User (line: 13)

### resources/views/design_1/web/theme/headers/header_2/index.blade.php
- Main (line: 9)
- Top Nav (line: 4)

### resources/views/design_1/web/theme/headers/header_2/main.blade.php
- Category (line: 24)
- Links (line: 29)
- Logo (line: 11)
- Right Button (line: 40)

### resources/views/design_1/web/theme/headers/header_2/top_nav.blade.php
- Buttons (line: 62)
- Cart (line: 54)
- Currency (line: 51)
- Email (line: 15)
- Language (line: 48)
- Local/Cart/Currency (line: 45)
- Multi Color (Dark,Light) (line: 23)
- Phone (line: 7)
- Search (line: 34)

### resources/views/design_1/web/theme/headers/mobile/includes/auth_user_drawer.blade.php
- End User (line: 28)

### resources/views/design_1/web/theme/headers/mobile/includes/main_menu_drawer.blade.php
- Cart (line: 31)
- Close (line: 38)
- Currency (line: 20)
- First Section (line: 8)
- Language (line: 11)
- Second Section (line: 44)
- Tabs (line: 73)

### resources/views/design_1/web/theme/headers/mobile/index.blade.php
- Cart (line: 52)
- Currency (line: 41)
- Drawers (line: 106)
- First Section (line: 10)
- Language (line: 32)
- Link Or User (line: 59)
- Multi Color (Dark,Light) (line: 20)
- Second Section (line: 29)
- Third Section (line: 88)

### resources/views/design_1/web/upcoming_courses/lists/includes/left_filters.blade.php
- Instructor (line: 136)
- More Options (line: 65)
- Prices Filters (line: 98)
- Types (line: 43)

### resources/views/design_1/web/upcoming_courses/lists/index.blade.php
- Courses Lists (line: 59)
- Header (line: 20)
- Left Filters (line: 54)
- Pagination (line: 66)
- Seo Content (line: 73)
- Top Filters (line: 50)

### resources/views/design_1/web/upcoming_courses/show/includes/hero.blade.php
- Badges (line: 18)
- Created By (line: 40)
- Featured (line: 20)
- Lectures (line: 47)
- Top Seller (line: 26)

### resources/views/design_1/web/upcoming_courses/show/includes/right_side.blade.php
- Course Specifications (line: 114)
- organization (line: 120)
- tags (line: 125)
- teacher (line: 117)
- This course includes (line: 40)
- Thumbnail (line: 6)

### resources/views/design_1/web/upcoming_courses/show/index.blade.php
- ./ Ads Bannaer (line: 27)
- Ads Bannaer (line: 25)
- Bottom Fixed (line: 40)
- Contant and Tabs (line: 21)
- Hero (line: 17)
- Right Side (line: 30)
- Sidebar ads Banner (line: 34)

### resources/views/design_1/web/upcoming_courses/show/tabs/about.blade.php
- About course (line: 49)
- About Instructor (line: 156)
- course FAQ (line: 117)
- Related Courses (line: 206)
- Requirements (line: 60)
- What will you learn (line: 26)

### resources/views/design_1/web/upcoming_courses/show/tabs/comments.blade.php
- Comments Lists (line: 10)
- Form (line: 4)

### resources/views/design_1/web/users/meeting/includes/stats.blade.php
- Group Meeting Charge (line: 66)
- Hourly Charge (line: 18)
- In Person Charge (line: 42)

### resources/views/design_1/web/users/meeting/steps/step_1.blade.php
- Stats (line: 17)

### resources/views/design_1/web/users/meeting/steps/step_2.blade.php
- Selected Time (line: 20)
- Stats (line: 17)

### resources/views/design_1/web/users/profile/includes/left_side.blade.php
- Socials (line: 55)

### resources/views/design_1/web/users/profile/tabs/about.blade.php
- Match course page "About This Course" structure exactly (line: 33)

### resources/views/design_1/web/users/profile/tabs/reserveMeeting/top_stats.blade.php
- Group Meeting Charge (line: 63)
- Hourly Charge (line: 15)
- In Person Charge (line: 39)
- Nearest Time (line: 3)

### resources/views/landingBuilder/admin/atoms/addable-file-input.blade.php
- Main Row (line: 47)

### resources/views/landingBuilder/admin/atoms/addable-text-input.blade.php
- Main Row (line: 38)

### resources/views/landingBuilder/admin/components/includes/sidebar.blade.php
- all assigned components (line: 46)

### resources/views/landingBuilder/admin/components/manage/banner_2_items_per_row/index.blade.php
- End Col (line: 35, 64)
- End Row (line: 66)
- General Information (line: 3)
- Instructors (line: 39)

### resources/views/landingBuilder/admin/components/manage/banner_3_items_per_row/index.blade.php
- End Col (line: 35, 64)
- End Row (line: 66)
- General Information (line: 3)
- Instructors (line: 39)

### resources/views/landingBuilder/admin/components/manage/banner_4_items_per_row/index.blade.php
- End Col (line: 35, 64)
- End Row (line: 66)
- General Information (line: 3)
- Instructors (line: 39)

### resources/views/landingBuilder/admin/components/manage/banner_full_width/index.blade.php
- End Col (line: 44, 73)
- End Row (line: 75)
- General Information (line: 3)
- Instructors (line: 48)

### resources/views/landingBuilder/admin/components/manage/banners_grid_3_in_different_sizes/index.blade.php
- End Col (line: 32, 44)
- End Row (line: 46)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/best_rated_courses/index.blade.php
- End Col (line: 34, 71)
- End Row (line: 73)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/best_selling_courses/index.blade.php
- End Col (line: 34, 71)
- End Row (line: 73)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/big_call_to_action_cards_2x/index.blade.php
- End Col (line: 155, 246)
- End Row (line: 248)
- General Information (line: 3)
- Main Content (line: 24)
- Title (line: 58, 159)

### resources/views/landingBuilder/admin/components/manage/blog/index.blade.php
- End Col (line: 35, 80)
- End Row (line: 82)
- General Information (line: 3)
- Main Content (line: 39)

### resources/views/landingBuilder/admin/components/manage/boxed_cta_full_width/index.blade.php
- End Col (line: 122, 151)
- End Row (line: 153)
- General Information (line: 3)
- Instructors (line: 126)
- Main Content (line: 45)

### resources/views/landingBuilder/admin/components/manage/center_text/index.blade.php
- End Col (line: 61)
- End Row (line: 65)
- General Information (line: 3)
- Main Content (line: 24)

### resources/views/landingBuilder/admin/components/manage/company_logos/index.blade.php
- Company logos (line: 69)
- End Col (line: 65, 85)
- End Row (line: 87)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/course_bundles/index.blade.php
- End Col (line: 56, 120)
- End Row (line: 122)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/cta_and_information_hybrid/index.blade.php
- End Col (line: 99, 129)
- End Row (line: 131)
- General Information (line: 3)
- Information (line: 103)
- Main Content (line: 45)

### resources/views/landingBuilder/admin/components/manage/cta_card_8_columns/index.blade.php
- Buttons (line: 94)
- End Col (line: 90, 114)
- End Row (line: 116)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/cta_section_full_width/index.blade.php
- Additional Information (line: 116)
- End Col (line: 112, 196)
- End Row (line: 198)
- General Information (line: 3)
- Title (line: 34)

### resources/views/landingBuilder/admin/components/manage/custom_courses_grid/index.blade.php
- End Col (line: 100, 131)
- Featured Courses (line: 103)
- General Information (line: 3)
- Main Content (line: 35)

### resources/views/landingBuilder/admin/components/manage/discounted_courses/index.blade.php
- End Col (line: 34, 110)
- End Row (line: 112)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/faq_6_col/index.blade.php
- End Col (line: 97, 125)
- End Row (line: 127)
- FAQ Items (line: 101)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/featured_courses/index.blade.php
- End Col (line: 76, 103)
- End Row (line: 105)
- Featured Courses (line: 79)
- General Information (line: 3)
- Main Content (line: 43)

### resources/views/landingBuilder/admin/components/manage/features_4x/index.blade.php
- End Col (line: 74, 103)
- End Row (line: 105)
- Features (line: 78)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/footer_1/index.blade.php
- End Col (line: 187, 295)
- End Row (line: 297)
- General Information (line: 3)
- links (line: 191)
- links 2 (line: 228, 265)

### resources/views/landingBuilder/admin/components/manage/free_courses/index.blade.php
- End Col (line: 45, 124)
- End Row (line: 126)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/full_width_bar_cta/index.blade.php
- End Col (line: 35, 80)
- End Row (line: 82)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/full_width_hero/index.blade.php
- End Col (line: 146, 229)
- End Row (line: 230)
- General Information (line: 3)
- Image Content (line: 162)
- Main Content (line: 89)
- Students Widget (line: 149)
- Upper Call to Action (line: 45)

### resources/views/landingBuilder/admin/components/manage/full_width_image_and_video_cta/index.blade.php
- End Col (line: 83, 166)
- End Row (line: 168)
- General Information (line: 3)
- Image Content (line: 121)
- Statistics (line: 87)

### resources/views/landingBuilder/admin/components/manage/hybrid_information_section_2_images_check_items_text/index.blade.php
- Checked Items (line: 76)
- End Col (line: 72, 116)
- End Row (line: 118)
- General Information (line: 3)
- Image Content (line: 89)

### resources/views/landingBuilder/admin/components/manage/hybrid_information_section_2_images_text/index.blade.php
- End Col (line: 73, 143)
- End Row (line: 145)
- General Information (line: 3)
- Image Content (line: 116)
- Statistics (line: 77)

### resources/views/landingBuilder/admin/components/manage/hybrid_information_section_2_images_text_2/index.blade.php
- End Col (line: 72, 142)
- End Row (line: 144)
- General Information (line: 3)
- Image Content (line: 115)
- Statistics (line: 76)

### resources/views/landingBuilder/admin/components/manage/hybrid_information_section_3_images_text/index.blade.php
- End Col (line: 81, 163)
- End Row (line: 165)
- General Information (line: 3)
- Image Content (line: 124)
- Statistics (line: 85)

### resources/views/landingBuilder/admin/components/manage/hybrid_information_section_4_images_text/index.blade.php
- End Col (line: 73, 166)
- End Row (line: 168)
- General Information (line: 3)
- Image Content (line: 116)
- Statistics (line: 77)

### resources/views/landingBuilder/admin/components/manage/hybrid_information_section_full_width/index.blade.php
- End Col (line: 72, 142)
- End Row (line: 144)
- General Information (line: 3)
- Image Content (line: 115)
- Statistics (line: 76)

### resources/views/landingBuilder/admin/components/manage/image_information_cards_3x/index.blade.php
- End Col (line: 57, 86)
- End Row (line: 88)
- General Information (line: 3)
- Links (line: 61)
- Main Content (line: 23)

### resources/views/landingBuilder/admin/components/manage/information_card_full_width/index.blade.php
- End Col (line: 103, 134)
- End Row (line: 136)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/information_card_full_width_2/index.blade.php
- End Col (line: 87, 108)
- End Row (line: 110)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/information_cards/index.blade.php
- End Col (line: 24, 53)
- End Row (line: 55)
- General Information (line: 3)
- Information Cards (line: 28)

### resources/views/landingBuilder/admin/components/manage/instructors/index.blade.php
- End Col (line: 116, 150)
- End Row (line: 152)
- General Information (line: 3)
- Instructors (line: 120)

### resources/views/landingBuilder/admin/components/manage/linked_images_3x/index.blade.php
- End Col (line: 66, 95)
- End Row (line: 97)
- General Information (line: 3)
- Links (line: 70)
- Main Content (line: 32)

### resources/views/landingBuilder/admin/components/manage/links_and_images_6_items_per_row/index.blade.php
- End Col (line: 57, 86)
- End Row (line: 88)
- General Information (line: 3)
- Links (line: 61)
- Main Content (line: 23)

### resources/views/landingBuilder/admin/components/manage/links_and_titles_slider_1_row/index.blade.php
- End Col (line: 56, 84)
- End Row (line: 86)
- General Information (line: 3)
- Title Items (line: 60)

### resources/views/landingBuilder/admin/components/manage/links_and_titles_slider_2_rows/index.blade.php
- End Col (line: 45, 73)
- End Row (line: 75)
- General Information (line: 3)
- Title Items (line: 49)

### resources/views/landingBuilder/admin/components/manage/meeting_booking_list/index.blade.php
- End Col (line: 130, 164)
- End Row (line: 166)
- Features (line: 134)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/newest_courses/index.blade.php
- End Col (line: 34, 117)
- End Row (line: 119)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/organizations/index.blade.php
- End Col (line: 75, 109)
- End Row (line: 111)
- General Information (line: 3)
- Organizations (line: 79)

### resources/views/landingBuilder/admin/components/manage/single_instructor_hero/index.blade.php
- Companies Widget (line: 216)
- End Col (line: 153, 261)
- General Information (line: 3)
- Image Content (line: 189)
- Main Content (line: 78)
- Students Widget (line: 156)
- Upper Call to Action (line: 34)

### resources/views/landingBuilder/admin/components/manage/single_video_section/index.blade.php
- End Col (line: 78, 117)
- End Row (line: 119)
- General Information (line: 3)
- Video Content (line: 81)

### resources/views/landingBuilder/admin/components/manage/sliding_testimonials_2_rows/index.blade.php
- End Col (line: 88, 122)
- End Row (line: 124)
- Features (line: 92)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/statistics/index.blade.php
- End Col (line: 45, 71)
- End Row (line: 72)
- General Information (line: 3)
- Statistics (line: 48)

### resources/views/landingBuilder/admin/components/manage/store_products/index.blade.php
- End Col (line: 34, 79)
- End Row (line: 81)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/subscription_plans/index.blade.php
- End Col (line: 77, 111)
- End Row (line: 113)
- Features (line: 81)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/trending_categories/index.blade.php
- End Col (line: 57, 91)
- End Row (line: 93)
- General Information (line: 3)
- Information Cards (line: 61)
- Main Content (line: 34)

### resources/views/landingBuilder/admin/components/manage/two_columns_hero/index.blade.php
- General Information (line: 3)
- Image Content (line: 172)
- Main Content (line: 79)
- Students Widget (line: 138)
- Upper Call to Action (line: 35)

### resources/views/landingBuilder/admin/components/manage/two_sided_information_images_and_cards/index.blade.php
- End Col (line: 80, 109)
- End Row (line: 111)
- General Information (line: 3)
- Links (line: 84)
- Main Content (line: 45)

### resources/views/landingBuilder/admin/components/manage/upcoming_courses/index.blade.php
- End Col (line: 34, 79)
- End Row (line: 81)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/vertical_spacer/index.blade.php
- End Col (line: 28)
- End Row (line: 31)
- General Information (line: 3)

### resources/views/landingBuilder/admin/components/manage/video_and_image_slider_full_width/index.blade.php
- End Col (line: 67, 96)
- End Row (line: 98)
- General Information (line: 3)
- Links (line: 71)

### resources/views/landingBuilder/admin/includes/sidebar.blade.php
- all landings (line: 51)
- New Landing (line: 31)

### resources/views/landingBuilder/admin/pages/create/tabs/basic_information.blade.php
- Assigned Pages (line: 72)
- General Information (line: 5)

### resources/views/landingBuilder/admin/pages/create/tabs/components.blade.php
- All Generated Components (line: 3)
- Assigned Components (line: 31)
- Lists (line: 22, 56)
- Loading (line: 39)

### resources/views/landingBuilder/admin/pages/create/tabs/includes/landing_component_card.blade.php
- If Disabled (line: 10)

### resources/views/landingBuilder/front/components/banners_grid_3_in_different_sizes/index.blade.php
- cta_section (line: 108)

### resources/views/landingBuilder/front/components/best_rated_courses/index.blade.php
- Newest Courses (line: 46)

### resources/views/landingBuilder/front/components/best_selling_courses/index.blade.php
- Newest Courses (line: 45)

### resources/views/landingBuilder/front/components/blog/index.blade.php
- List (line: 34)
- Primary Button (line: 56)

### resources/views/landingBuilder/front/components/boxed_cta_full_width/index.blade.php
- Links (line: 68)
- Primary Button (line: 85)
- Secondary Button (line: 98)

### resources/views/landingBuilder/front/components/company_logos/index.blade.php
- Button (line: 31)

### resources/views/landingBuilder/front/components/course_bundles/index.blade.php
- Bundles (line: 74)
- Button (line: 56)
- Statistic (line: 40)

### resources/views/landingBuilder/front/components/cta_and_information_hybrid/index.blade.php
- Primary Button (line: 34)

### resources/views/landingBuilder/front/components/cta_card_8_columns/index.blade.php
- En Col (line: 73)
- End Row (line: 74)
- Primary Button (line: 49)
- Secondary Button (line: 60)

### resources/views/landingBuilder/front/components/cta_section_full_width/index.blade.php
- floating_images (line: 98)
- Primary Button (line: 73)
- Secondary Button (line: 84)
- side_images (line: 113)

### resources/views/landingBuilder/front/components/custom_courses_grid/index.blade.php
- floating_images (line: 68)
- List (line: 33)
- Primary Button (line: 53)

### resources/views/landingBuilder/front/components/faq_6_col/index.blade.php
- Button (line: 29)

### resources/views/landingBuilder/front/components/features_4x/index.blade.php
- Button (line: 39)
- Checked Items (line: 29)

### resources/views/landingBuilder/front/components/full_width_bar_cta/index.blade.php
- Button (line: 33)

### resources/views/landingBuilder/front/components/full_width_hero/index.blade.php
- Checked Items (line: 120)
- Description (line: 82)
- Image/Video (line: 132)
- Overlay Image 1 (line: 146)
- Overlay Image 2 (line: 153)
- Primary Button (line: 92)
- Secondary Button (line: 105)
- Title (line: 59)
- Upper Call to Action (line: 30)

### resources/views/landingBuilder/front/components/full_width_image_and_video_cta/index.blade.php
- Button (line: 40)
- Images (line: 87)
- Statistics (line: 52)

### resources/views/landingBuilder/front/components/hybrid_information_section_2_images_check_items_text/index.blade.php
- Button (line: 60)
- Checked Items (line: 50)

### resources/views/landingBuilder/front/components/hybrid_information_section_2_images_text/index.blade.php
- Button (line: 32)
- Image (line: 89)
- statistic (line: 43)

### resources/views/landingBuilder/front/components/hybrid_information_section_2_images_text_2/index.blade.php
- Button (line: 32)
- Image (line: 89)
- statistic (line: 43)

### resources/views/landingBuilder/front/components/hybrid_information_section_3_images_text/index.blade.php
- Button (line: 32)
- Image (line: 89)
- statistic (line: 43)

### resources/views/landingBuilder/front/components/hybrid_information_section_4_images_text/index.blade.php
- Button (line: 32)
- Image (line: 89)
- statistic (line: 43)

### resources/views/landingBuilder/front/components/hybrid_information_section_full_width/index.blade.php
- Button (line: 31)
- Image (line: 88)
- Statistics (line: 42)

### resources/views/landingBuilder/front/components/information_card_full_width/index.blade.php
- Button (line: 61)
- Checked Items (line: 47)

### resources/views/landingBuilder/front/components/information_card_full_width_2/index.blade.php
- Button (line: 44)
- Checked Items (line: 35)

### resources/views/landingBuilder/front/components/instructors/index.blade.php
- CTA (line: 52)
- List (line: 32)
- Primary Button (line: 78)

### resources/views/landingBuilder/front/components/meeting_booking_list/index.blade.php
- action (line: 121)
- CTA (line: 138)
- Earliest Available Time (line: 109)
- Hourly Rate (line: 112)
- Instructors (line: 39)
- Total Meetings (line: 103)
- Tutoring Hours (line: 106)
- Weekly Hours (line: 100)

### resources/views/landingBuilder/front/components/newest_courses/index.blade.php
- CTA (line: 41)
- Newest Courses (line: 35)

### resources/views/landingBuilder/front/components/organizations/index.blade.php
- List (line: 33)
- Primary Button (line: 63)

### resources/views/landingBuilder/front/components/single_instructor_hero/index.blade.php
- Buttons (line: 114)
- Primary Button (line: 117)
- Secondary Button (line: 128)
- Seperator (line: 30)
- Students (line: 141)
- Students Widget (line: 142)
- Title (line: 59, 102)
- Upper Call to Action (line: 35)
- Welcome (line: 19)

### resources/views/landingBuilder/front/components/single_video_section/index.blade.php
- back_image (line: 54, 61)

### resources/views/landingBuilder/front/components/store_products/index.blade.php
- Button (line: 38)

### resources/views/landingBuilder/front/components/subscription_plans/index.blade.php
- Button (line: 38)
- Checked Items (line: 28)
- floating_image (line: 49)

### resources/views/landingBuilder/front/components/subscription_plans/plan_card.blade.php
- Popular (line: 10)

### resources/views/landingBuilder/front/components/trending_categories/index.blade.php
- Trending Categories (line: 34)

### resources/views/landingBuilder/front/components/two_columns_hero/index.blade.php
- Primary Button (line: 85)
- Secondary Button (line: 96)
- Students Widget (line: 110)
- Title (line: 40)
- Upper Call to Action (line: 17)

### resources/views/landingBuilder/front/landing/index.blade.php
- Jquery (line: 36)
- Prerequisite Scripts (line: 32)
- Prerequisite Styles (line: 5)

### resources/views/vendor/laravel-filemanager/index.blade.php
- <link rel="stylesheet" href="{{ asset('/vendor/laravel-filemanager/css/lfm.css') }}"> (line: 25)
- <script src="{{ asset('vendor/laravel-filemanager/js/script.js') }}"></script> (line: 276)
- Use the line below instead of the above if you need to cache the css. (line: 24)
- Use the line below instead of the above if you need to cache the script. (line: 275)

### resources/views/vendor/pagination/admin_default.blade.php
- "Three Dots" Separator (line: 18)
- Array Of Links (line: 23)
- Next Page Link (line: 35)
- Pagination Elements (line: 16)
- Previous Page Link (line: 5)

### resources/views/vendor/pagination/bootstrap-4.blade.php
- "Three Dots" Separator (line: 17)
- Array Of Links (line: 22)
- Next Page Link (line: 34)
- Pagination Elements (line: 15)
- Previous Page Link (line: 4)

### resources/views/vendor/pagination/default.blade.php
- "Three Dots" Separator (line: 17)
- Array Of Links (line: 22)
- Next Page Link (line: 34)
- Pagination Elements (line: 15)
- Previous Page Link (line: 4)

### resources/views/vendor/pagination/design_1.blade.php
- "Three Dots" Separator (line: 41)
- Next Page Link (line: 60)
- Pagination Elements (line: 28)
- Previous Page Link (line: 13)

### resources/views/vendor/pagination/panel.blade.php
- <li><span class="d-flex align-items-center justify-content-center">...</span></li> (line: 61)

