<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\sendContactReply;
use App\Mail\SendNotifications;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $this->authorize('admin_contacts_lists');

        $contacts = Contact::orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => trans('admin/pages/users.contacts_lists'),
            'contacts' => $contacts
        ];

        return view('admin.contacts.lists', $data);
    }

    public function courseRequests()
    {
        $this->authorize('admin_contacts_lists');

        $contacts = Contact::query()
            ->where(function ($query) {
                $query->where('contact_type', 'request_course')
                    ->orWhere('subject', 'Course Request');
            })
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = [
            'pageTitle' => 'Course Requests',
            'contacts' => $contacts,
            'isCourseRequestsPage' => true,
        ];

        return view('admin.contacts.lists', $data);
    }

    public function reply($id)
    {
        $this->authorize('admin_contacts_reply');

        $contact = Contact::findOrFail($id);

        $data = [
            'pageTitle' => trans('admin/main.reply'),
            'contact' => $contact
        ];

        return view('admin.contacts.reply', $data);
    }

    public function storeReply(Request $request, $id)
    {
        $this->authorize('admin_contacts_reply');

        $this->validate($request, [
            'reply' => 'required'
        ]);

        $reply = $request->get('reply');

        $contact = Contact::findOrFail($id);
        $contact->reply = $reply;
        $contact->status = 'replied';
        $contact->save();

        if (!empty($contact->email)) {
            \Mail::to($contact->email)->send(new sendContactReply($contact));
        }

        if ($this->shouldRedirectToCourseRequests($request, $contact)) {
            return redirect(getAdminPanelUrl() . '/contacts/course-requests');
        }

        return redirect(getAdminPanelUrl() . '/contacts');
    }

    public function delete(Request $request, $id)
    {
        $this->authorize('admin_contacts_delete');

        $contact = Contact::findOrFail($id);

        $redirectToCourseRequests = $this->shouldRedirectToCourseRequests($request, $contact);

        $contact->delete();

        if ($redirectToCourseRequests) {
            return redirect(getAdminPanelUrl() . '/contacts/course-requests');
        }

        return redirect(getAdminPanelUrl().'/contacts');
    }

    private function shouldRedirectToCourseRequests(Request $request, Contact $contact): bool
    {
        if ($request->get('return_to') === 'course_requests') {
            return true;
        }

        return ($contact->contact_type ?? 'message') === 'request_course'
            || ($contact->subject ?? null) === 'Course Request';
    }
}
