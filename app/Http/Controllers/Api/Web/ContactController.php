<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Api\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public function store(Request $request)
    {
        $contactType = $request->input('contact_type') === 'request_course' ? 'request_course' : 'message';

        validateParam($request->all(), [
            'contact_type' => 'nullable|in:message,request_course',
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|numeric',
            'message' => 'required|string',
            'university_name' => 'required_if:contact_type,request_course|nullable|string',
            'college_name' => 'required_if:contact_type,request_course|nullable|string',
            'study_field' => 'required_if:contact_type,request_course|nullable|string',
            'course_name' => 'required_if:contact_type,request_course|nullable|string',
            'study_year' => 'required_if:contact_type,request_course|nullable|integer|between:1,5',
            'can_provide_materials' => 'required_if:contact_type,request_course|nullable|in:yes,no',
            // 'captcha' => 'required|captcha',
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

        $notifyOptions = [
            '[c.u.title]' => $data['subject'],
            '[u.name]' => $data['name']
        ];
        sendNotification('new_contact_message', $notifyOptions, 1);

        return apiResponse2(1, 'stored', 'user sent message successfully.');
        //return back()->with(['msg' => trans('site.contact_store_success')]);
    }
}
