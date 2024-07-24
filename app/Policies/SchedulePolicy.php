<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Schedule $schedule)
    {
        return $user->id === $schedule->user_id || $user->hasRole('admin');
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Schedule $schedule)
    {
        return $user->id === $schedule->user_id || $user->hasRole('admin');
    }

    public function delete(User $user, Schedule $schedule)
    {
        return $user->id === $schedule->user_id || $user->hasRole('admin');
    }
}
