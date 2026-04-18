<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Quiz;
use App\Models\Session;
use App\Models\TextLesson;
use App\Models\Translation\WebinarChapterTranslation;
use App\Models\Webinar;
use App\Models\WebinarAssignment;
use App\Models\WebinarChapter;
use App\Models\WebinarChapterItem;
use App\Services\R2StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ChapterController extends Controller
{
    protected function recalculateWebinarChapterPricing(Webinar $webinar): void
    {
        $chapters = WebinarChapter::query()
            ->where('webinar_id', $webinar->id)
            ->where('status', WebinarChapter::$chapterActive)
            ->orderByRaw('COALESCE(`order`, 999999) ASC')
            ->orderBy('id')
            ->get();

        $totalPrice = 0.0;

        foreach ($chapters as $index => $chapter) {
            $price = 0.0; // first active section is free

            if ($index !== 0) {
                $minutes = $chapter->duration;

                if (is_null($minutes)) {
                    $chapter->loadMissing(['files', 'sessions', 'textLessons']);
                    $minutes = (int) $chapter->getDuration();
                }

                $calculated = function_exists('calculateChapterPriceByDurationMinutes')
                    ? calculateChapterPriceByDurationMinutes((int) $minutes)
                    : null;

                $price = !is_null($calculated) ? (float) $calculated : 0.0;
            }

            if ((float) $chapter->price !== $price) {
                $chapter->update(['price' => $price]);
            }

            $totalPrice += $price;
        }

        $webinar->update(['price' => $totalPrice]);
    }

    public function getForm(Request $request)
    {
        $html = (string)view()->make("design_1.panel.webinars.create.modals.chapter");

        return response()->json([
            'code' => 200,
            'html' => $html
        ]);
    }

    public function getChapter(Request $request, $id)
    {
        $user = auth()->user();

        $chapter = WebinarChapter::where('id', $id)->first();

        if (!empty($chapter)) {
            $webinar = Webinar::query()->find($chapter->webinar_id);

            if ($chapter->user_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {

                $locale = $request->get('locale', app()->getLocale());

                foreach ($chapter->translatedAttributes as $attribute) {
                    try {
                        $chapter->$attribute = $chapter->translate(mb_strtolower($locale))->$attribute;
                    } catch (\Exception $e) {
                        $chapter->$attribute = null;
                    }
                }

                $data = [
                    'chapter' => $chapter
                ];

                return response()->json($data, 200);
            }
        }

        abort(403);
    }

    public function getAllByWebinarId($webinar_id)
    {
        $user = auth()->user();

        $webinar = Webinar::find($webinar_id);

        if (!empty($webinar) and $webinar->canAccess($user)) {

            $chapters = $webinar->chapters->where('status', WebinarChapter::$chapterActive);

            $data = [
                'chapters' => [],
            ];

            if (!empty($chapters) and count($chapters)) {
                // for translate send on array of data

                foreach ($chapters as $chapter) {
                    $data['chapters'][] = [
                        'user_id' => $chapter->user_id,
                        'webinar_id' => $chapter->webinar_id,
                        'id' => $chapter->id,
                        'order' => $chapter->order,
                        'status' => $chapter->status,
                        'title' => $chapter->title,
                        'type' => $chapter->type,
                        'price' => isset($chapter->price) ? (float) $chapter->price : 0,
                        'created_at' => $chapter->created_at,
                    ];
                }
            }

            return response()->json($data, 200);
        }

        abort(403);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $data = $request->get('ajax')['chapter'];

        $validator = Validator::make($data, [
            'webinar_id' => 'required',
            //'type' => 'required|' . Rule::in(WebinarChapter::$chapterTypes),
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {
            $status = (!empty($data['status']) and $data['status'] == 'on') ? WebinarChapter::$chapterActive : WebinarChapter::$chapterInactive;

            $chapter = WebinarChapter::create([
                'user_id' => $user->id,
                'webinar_id' => $webinar->id,
                //'type' => $data['type'],
                'status' => $status,
                'check_all_contents_pass' => (!empty($data['check_all_contents_pass']) and $data['check_all_contents_pass'] == 'on'),
                // duration is derived from files; keep null here
                'duration' => null,
                // Price is auto-calculated based on duration after save.
                'price' => 0,
                'created_at' => time(),
            ]);

            if (!empty($chapter)) {
                $locale = $request->get("locale", getDefaultLocale());

                WebinarChapterTranslation::updateOrCreate([
                    'webinar_chapter_id' => $chapter->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                ]);

                $this->recalculateWebinarDuration($webinar);
                $this->recalculateWebinarChapterPricing($webinar);
            }

            return response()->json([
                'code' => 200,
            ], 200);
        }

        abort(403);
    }

    public function edit(Request $request, $id)
    {
        $user = auth()->user();
        $chapter = WebinarChapter::where('id', $id)->first();

        $webinar = $chapter->webinar;

        if ($chapter->user_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {
            $data = [
                'title' => $chapter->title,
                'chapter' => $chapter,
            ];

            $html = (string)view()->make("design_1.panel.webinars.create.modals.chapter", $data);

            return response()->json([
                'code' => 200,
                'html' => $html
            ]);
        }

        abort(404);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $data = $request->get('ajax')['chapter'];

        $validator = Validator::make($data, [
            'webinar_id' => 'required',
            //'type' => 'required|' . Rule::in(WebinarChapter::$chapterTypes),
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }


        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {

            $chapter = WebinarChapter::where('id', $id)
                ->where(function ($query) use ($user, $webinar) {
                    $query->where('user_id', $user->id);
                    $query->orWhere('webinar_id', $webinar->id);
                })
                ->first();

            if (!empty($chapter)) {
                $locale = $request->get("locale", getDefaultLocale());
                $status = (!empty($data['status']) and $data['status'] == 'on') ? WebinarChapter::$chapterActive : WebinarChapter::$chapterInactive;

                $chapter->update([
                    'status' => $status,
                    // keep existing sequence-content toggle behavior
                    'check_all_contents_pass' => (!empty($data['check_all_contents_pass']) and $data['check_all_contents_pass'] == 'on'),
                ]);

                WebinarChapterTranslation::updateOrCreate([
                    'webinar_chapter_id' => $chapter->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                ]);

                $this->recalculateWebinarDuration($webinar);
                $this->recalculateWebinarChapterPricing($webinar);

                return response()->json([
                    'code' => 200
                ], 200);
            }
        }

        abort(403);
    }

    public function destroy($id)
    {
        $user = auth()->user();

        $chapter = WebinarChapter::where('id', $id)->first();

        if (!empty($chapter)) {

            $webinar = Webinar::query()->find($chapter->webinar_id);

            if ($chapter->user_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {

                $webinarId = $chapter->webinar_id;
                $chapter->delete();

                if (!empty($webinarId)) {
                    if ($webinar = Webinar::find($webinarId)) {
                        $this->recalculateWebinarDuration($webinar);
                        $this->recalculateWebinarChapterPricing($webinar);
                    }
                }

                return response()->json([
                    'code' => 200
                ], 200);
            }
        }

        abort(403);
    }

    public function change(Request $request)
    {
        $user = auth()->user();
        $data = $request->get('ajax');

        $validator = Validator::make($data, [
            'item_id' => 'required',
            'item_type' => 'required',
            'chapter_id' => 'required',
            'webinar_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $item = null;

        $webinar = Webinar::find($data['webinar_id']);

        if (!empty($webinar) and $webinar->canAccess($user)) {

            switch ($data['item_type']) {
                case WebinarChapterItem::$chapterSession:
                    $item = Session::where('id', $data['item_id'])
                        ->where('webinar_id', $data['webinar_id'])
                        ->first();
                    break;

                case WebinarChapterItem::$chapterFile:
                    $item = File::where('id', $data['item_id'])
                        ->where('webinar_id', $data['webinar_id'])
                        ->first();
                    break;

                case WebinarChapterItem::$chapterTextLesson:
                    $item = TextLesson::where('id', $data['item_id'])
                        ->where('webinar_id', $data['webinar_id'])
                        ->first();
                    break;

                case WebinarChapterItem::$chapterQuiz:
                    $item = Quiz::where('id', $data['item_id'])
                        ->where('webinar_id', $data['webinar_id'])
                        ->first();
                    break;

                case WebinarChapterItem::$chapterAssignment:
                    $item = WebinarAssignment::where('id', $data['item_id'])
                        ->where('webinar_id', $data['webinar_id'])
                        ->first();
                    break;
            }
        }

        if (!empty($item)) {
            $payload = [
                'chapter_id' => !empty($data['chapter_id']) ? $data['chapter_id'] : null,
            ];

            if ($item instanceof File
                && $data['item_type'] === WebinarChapterItem::$chapterFile
                && $item->storage === 'r2'
                && !empty($data['chapter_id'])
                && (int) $data['chapter_id'] !== (int) $item->chapter_id
                && !empty($item->file)
            ) {
                $moveResult = (new R2StorageService())->moveCourseFileToSection(
                    $item->file,
                    (int) $webinar->id,
                    (int) $data['chapter_id']
                );
                if (!empty($moveResult['status']) && !empty($moveResult['path'])) {
                    $payload['file'] = $moveResult['path'];
                }
            }

            $item->update($payload);

            WebinarChapterItem::where('item_id', $item->id)
                ->where('type', $data['item_type'])
                ->delete();

            if (!empty($data['chapter_id'])) {
                WebinarChapterItem::makeItem($user->id, $data['chapter_id'], $item->id, $data['item_type']);
            }
        }

        return response()->json([
            'code' => 200
        ], 200);
    }

    protected function recalculateWebinarDuration(Webinar $webinar): void
    {
        $totalMinutes = $webinar->chapters()
            ->where('status', WebinarChapter::$chapterActive)
            ->sum('duration');

        $webinar->update([
            'duration' => (int) $totalMinutes,
        ]);
    }
}
