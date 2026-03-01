<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Admin;
use App\Notifications\AppNotification;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user)
    {
        // Find admins/staff who have the 'user.read' permission
        $admins = Admin::all()->filter(function($admin) {
            return $admin->hasRole('Super Admin') || $admin->hasPermissionTo('user.read');
        });

        foreach ($admins as $admin) {
            $admin->notify(new AppNotification(
                __('New User Registered'),
                __(':name has just created a new account.', ['name' => $user->name]),
                'info',
                route('spark-admin.users.index') // Assuming this route exists
            ));
        }
    }
}
