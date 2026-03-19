<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contactSettings = getContactPageSettings();
        $selectedContactType = request()->get('type') === 'request_course' ? 'request_course' : 'message';

        $seoSettings = getSeoMetas('contact');
        $pageTitle = !empty($seoSettings['title']) ? $seoSettings['title'] : trans('site.contact_page_title');
        $pageDescription = !empty($seoSettings['description']) ? $seoSettings['description'] : trans('site.contact_page_title');
        $pageRobot = getPageRobot('contact');

        $data = [
            'pageTitle' => $pageTitle,
            'pageDescription' => $pageDescription,
            'pageRobot' => $pageRobot,
            'contactSettings' => $contactSettings,
            'selectedContactType' => $selectedContactType,
        ];

        return view('design_1.web.contactus.index', $data);
    }

    public function store(Request $request)
    {
        $contactType = $request->input('contact_type') === 'request_course' ? 'request_course' : 'message';

        $this->validate($request, [
            'contact_type' => 'required|in:message,request_course',
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|numeric',
            'message' => 'required|string',
            'captcha' => 'required|captcha',
            'university_name' => 'required_if:contact_type,request_course|nullable|string',
            'college_name' => 'required_if:contact_type,request_course|nullable|string',
            'study_field' => 'required_if:contact_type,request_course|nullable|string',
            'course_name' => 'required_if:contact_type,request_course|nullable|string',
            'study_year' => 'required_if:contact_type,request_course|nullable|integer|between:1,5',
            'can_provide_materials' => 'required_if:contact_type,request_course|nullable|in:yes,no',
        ]);

        $data = $request->only([
            'name',
            'email',
            'phone',
            'message',
            'university_name',
            'college_name',
            'study_field',
            'course_name',
            'study_year',
            'can_provide_materials',
        ]);
        $data['contact_type'] = $contactType;
        $data['subject'] = $contactType === 'request_course' ? 'Course Request' : 'Contact Message';

        if ($contactType !== 'request_course') {
            $data['university_name'] = null;
            $data['college_name'] = null;
            $data['study_field'] = null;
            $data['course_name'] = null;
            $data['study_year'] = null;
            $data['can_provide_materials'] = null;
        }

        $data['created_at'] = time();

        Contact::create($data);

        $messageForAdmin = $data['message'];

        if ($contactType === 'request_course') {
            $messageForAdmin .= "\n\nCourse Request Details:";
            $messageForAdmin .= "\nUniversity: " . $data['university_name'];
            $messageForAdmin .= "\nCollege: " . $data['college_name'];
            $messageForAdmin .= "\nField: " . $data['study_field'];
            $messageForAdmin .= "\nRequested Course: " . $data['course_name'];
            $messageForAdmin .= "\nStudy Year: " . $data['study_year'];
            $messageForAdmin .= "\nCan Provide Materials: " . ($data['can_provide_materials'] === 'yes' ? 'Yes' : 'No');
        }

        $notifyOptions = [
            '[c.u.title]' => $data['subject'],
            '[u.name]' => $data['name'],
            '[time.date]' => dateTimeFormat(time(), 'j M Y H:i'),
            '[c.u.message]' => $messageForAdmin,
        ];

        sendNotification('contact_message_submission_for_admin', $notifyOptions, 1);

        sendNotificationToEmail('contact_message_submission', $notifyOptions, $data['email']);

        $toastData = [
            'title' => trans('public.request_success'),
            'msg' => trans('site.contact_store_success'),
            'status' => 'success'
        ];

        $redirectUrl = $contactType === 'request_course' ? '/contact?type=request_course' : '/contact';

        return redirect($redirectUrl)->with(['toast' => $toastData]);
    }
}
