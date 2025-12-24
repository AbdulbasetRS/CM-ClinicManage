<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\NewUserCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserObserver
{
    public function creating(User $user)
    {
        $user->slug = Str::slug($user->username).'-'.Str::random(6);
        $user->created_by = Auth::id();
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $admins = User::where('type', 'admin')
            ->where('status', 'active')
            ->where('can_login', true)
            ->where('id', '!=', auth()->id())
            ->get();
        foreach ($admins as $admin) {
            $notification = new NewUserCreated($user, $admin->id);
            $admin->notify($notification);
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
