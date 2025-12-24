<?php

use Illuminate\Support\Facades\Broadcast;

// Private channel for all admins
Broadcast::channel('admin-channel', function ($user) {
    // Check if the currently authenticated user is an admin
    return $user->type->value === 'admin';
});

// Private channel for specific admin
Broadcast::channel('admin.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id && $user->type->value === 'admin';
});
