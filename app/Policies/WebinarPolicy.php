<?php

namespace App\Policies;

use App\Models\Webinar;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WebinarPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\User $user
     * @param \App\Models\Api\Webinar $webinar
     * @return mixed
     */
    public function view(User $user, Webinar $webinar)
    {
        // Allow admin and course creators/teachers full access
        if (!empty($user) and ($user->isAdmin() or $user->id == $webinar->creator_id or $user->organ_id == $webinar->creator_id or $user->id == $webinar->teacher_id)) {
            return true;
        }

        // Check if user has purchased the course
        $hasBought = $webinar->checkUserHasBought($user);
        
        if (!$hasBought) {
            return false;
        }

        // If user has bought, check if it's private
        $isPrivate = $webinar->private;
        
        // If not private or user is creator/admin, allow access
        if (!$isPrivate) {
            return true;
        }

        // Private courses that user has bought - deny access unless they're creator/teacher
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\User $user
     * @param \App\Models\Api\Webinar $webinar
     * @return mixed
     */
    public function update(User $user, Webinar $webinar)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\User $user
     * @param \App\Models\Api\Webinar $webinar
     * @return mixed
     */
    public function delete(User $user, Webinar $webinar)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\User $user
     * @param \App\Models\Api\Webinar $webinar
     * @return mixed
     */
    public function restore(User $user, Webinar $webinar)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\User $user
     * @param \App\Models\Api\Webinar $webinar
     * @return mixed
     */
    public function forceDelete(User $user, Webinar $webinar)
    {
        //
    }
}
