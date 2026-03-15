<?php

namespace App\Models;

use App\Models\Traits\SequenceContent;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class WebinarChapter extends Model implements TranslatableContract
{
    use Translatable;
    use SequenceContent;

    protected $table = 'webinar_chapters';
    public $timestamps = false;
    protected $dateFormat = 'U';
    protected $guarded = ['id'];

    static $chapterFile = 'file';
    static $chapterSession = 'session';
    static $chapterTextLesson = 'text_lesson';

    static $chapterActive = 'active';
    static $chapterInactive = 'inactive';

    static $chapterTypes = ['file', 'session', 'text_lesson'];

    static $chapterStatus = ['active', 'inactive'];

    public $translatedAttributes = ['title'];

    public function getTitleAttribute()
    {
        return getTranslateAttributeValue($this, 'title');
    }


    public function sessions()
    {
        return $this->hasMany('App\Models\Session', 'chapter_id', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\File', 'chapter_id', 'id');
    }

    public function textLessons()
    {
        return $this->hasMany('App\Models\TextLesson', 'chapter_id', 'id');
    }

    public function assignments()
    {
        return $this->hasMany('App\Models\WebinarAssignment', 'chapter_id', 'id');
    }

    public function quizzes()
    {
        return $this->hasMany('App\Models\Quiz', 'chapter_id', 'id');
    }

    public function chapterItems()
    {
        return $this->hasMany('App\Models\WebinarChapterItem', 'chapter_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\Webinar', 'webinar_id', 'id');
    }

    /**
     * Whether this chapter is the first section (by order) of the course — free for everyone.
     */
    public function isFirstSection(): bool
    {
        $first = static::query()
            ->where('webinar_id', $this->webinar_id)
            ->where('status', self::$chapterActive)
            ->orderByRaw('COALESCE(`order`, 999999) ASC')
            ->orderBy('id')
            ->first();

        return $first && (int) $first->id === (int) $this->id;
    }

    /**
     * Check if user has purchased this section (chapter).
     */
    public function checkUserHasBought($user = null): bool
    {
        if (empty($user) && auth()->check()) {
            $user = auth()->user();
        }
        if (empty($user)) {
            $user = function_exists('apiAuth') ? apiAuth() : null;
        }
        if (empty($user)) {
            return false;
        }

        return \App\Models\Sale::query()
            ->where('buyer_id', $user->id)
            ->where('chapter_id', $this->id)
            ->where('type', \App\Models\Sale::$chapter)
            ->whereNull('refund_at')
            ->where('access_to_purchased_item', true)
            ->exists();
    }

    /**
     * Get the sale record if user purchased this chapter.
     */
    public function getSaleItem($user = null)
    {
        if (empty($user) && auth()->check()) {
            $user = auth()->user();
        }
        if (empty($user)) {
            $user = function_exists('apiAuth') ? apiAuth() : null;
        }
        if (empty($user)) {
            return null;
        }

        return \App\Models\Sale::query()
            ->where('buyer_id', $user->id)
            ->where('chapter_id', $this->id)
            ->where('type', \App\Models\Sale::$chapter)
            ->whereNull('refund_at')
            ->where('access_to_purchased_item', true)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function getDuration()
    {
        // If a manual duration is set on the chapter, prefer that.
        if (!is_null($this->duration)) {
            return (int) $this->duration;
        }

        $time = 0;
        $time += $this->sessions->sum('duration');
        $time += $this->textLessons->sum('study_time');

        return $time;
    }


    public function getTopicsCount($withQuiz = false)
    {
        $count = 0;

        $count += $this->files->where('status', 'active')->count();
        $count += $this->sessions->where('status', 'active')->count();
        $count += $this->textLessons->where('status', 'active')->count();
        $count += $this->assignments->where('status', 'active')->count();

        if ($withQuiz) {
            $count += $this->quizzes->where('status', 'active')->count();
        }

        return $count;
    }
}
