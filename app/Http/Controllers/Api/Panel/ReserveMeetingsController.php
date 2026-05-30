<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Api\Controller;
use App\Models\Meeting;
use App\Models\Api\ReserveMeeting;
use App\Models\Session;
use \Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Reserved Meetings",
 *     description="Booked and hosted meeting APIs"
 * )
 */


class ReserveMeetingsController extends Controller
{
    /**
     * List meeting reservations and instructor requests.
     *
     * @OA\Get(
     *     path="/v1/panel/meetings",
     *     summary="List reservations and requests",
     *     tags={"Reserved Meetings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Reservations and requests")
     * )
     */
    public function index(Request $request)
    {

        $data = [
            'reservations' => [
                'count'=>count($this->getReservation()) ,
                'meetings' => $this->getReservation(),
            ],
            'requests' =>[
                'count'=>count( $this->getRequests()) ,
                'meetings'=> $this->getRequests()
            ],
        ];
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), $data);

    }

    /**
     * Show one reserved meeting.
     *
     * @OA\Get(
     *     path="/v1/panel/meetings/{id}",
     *     summary="Show reserved meeting",
     *     tags={"Reserved Meetings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Reserved meeting")
     * )
     */
    public function show(Request $request, $id)
    {
        $user = apiAuth();
        $reserveMeetingsQuery = ReserveMeeting::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)->orWhere(function ($q) use ($user) {

                    $q->whereHas('meeting', function ($qq) use ($user) {
                        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');
                        $qq->whereIn('meeting_id', $meetingIds);
                    });
                });
            })
            ->first();
        if ($reserveMeetingsQuery){
            $reserveMeetingsQuery->isAgora = false;
            $reserveMeetingsQuery->agoraLink = null;
            $session = Session::where("reserve_meeting_id","=",$reserveMeetingsQuery->id)->first();
            if ($session){
                $reserveMeetingsQuery->isAgora = true;
                $reserveMeetingsQuery->agoraLink = route('agora.api.join',$session->id);
            }

        }
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'), [
            'meeting' => $reserveMeetingsQuery
        ]);


    }

    /**
     * List student reservations.
     *
     * @OA\Get(
     *     path="/v1/panel/meetings/reservations",
     *     summary="List my reservations",
     *     tags={"Reserved Meetings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Reservations list")
     * )
     */
    public function reservation(Request $request)
    {


        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),
            $this->getReservation()
        );

    }

    /**
     * List instructor requests.
     *
     * @OA\Get(
     *     path="/v1/panel/meetings/requests",
     *     summary="List my meeting requests",
     *     tags={"Reserved Meetings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Requests list")
     * )
     */
    public function requests(Request $request)
    {
        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),
            $this->getRequests()
        );
    }

    public function getReservation()
    {

        $user = apiAuth();
        $reservedMeetings = ReserveMeeting::where('user_id', $user->id)
            ->whereHas('sale')
            ->whereNotNull('reserved_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($reserveMeeting) {
                return $reserveMeeting->details;
            });

        return $reservedMeetings;
    }

    public function getRequests()
    {
        $user = apiAuth();
        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');
        $reservedMeetings = ReserveMeeting::whereIn('meeting_id', $meetingIds)->whereHas('sale')
            ->orderBy('created_at', 'desc')

            ->get()->map(function ($reserveMeeting) {
                return $reserveMeeting->details;
            });

        return $reservedMeetings;
    }

    /**
     * Finish a reserved meeting.
     *
     * @OA\Post(
     *     path="/v1/panel/meetings/{id}/finish",
     *     summary="Finish reserved meeting",
     *     tags={"Reserved Meetings"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Meeting finished")
     * )
     */
    public function finish($id)
    {
        $user = apiAuth();

        $meetingIds = Meeting::where('creator_id', $user->id)->pluck('id');

        $ReserveMeeting = ReserveMeeting::where('id', $id)
            ->where(function ($query) use ($user, $meetingIds) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('meeting_id', $meetingIds);
            })
            ->first();

        if (!empty($ReserveMeeting)) {
            $ReserveMeeting->update([
                'status' => ReserveMeeting::$finished
            ]);

            $notifyOptions = [
                '[student.name]' => $ReserveMeeting->user->full_name,
                '[instructor.name]' => $ReserveMeeting->meeting->creator->full_name,
                '[time.date]' => $ReserveMeeting->day,
            ];
            sendNotification('meeting_finished', $notifyOptions, $ReserveMeeting->user_id);
            sendNotification('meeting_finished', $notifyOptions, $ReserveMeeting->meeting->creator_id);

            return apiResponse2(1, 'finished',
                trans('api.meeting.finished'));

        }
        abort(404);

    }

}
