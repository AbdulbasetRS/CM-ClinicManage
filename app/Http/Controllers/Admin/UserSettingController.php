<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PathHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserSettingsRequest;
use App\Models\UserSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserSettingController extends Controller
{
    /**
     * Display the change password form.
     */
    public function changePassword()
    {
        return view('admin.user-settings.change-password');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => __('admin.current_password_required'),
            'current_password.current_password' => __('admin.current_password_incorrect'),
            'password.required' => __('admin.new_password_required'),
            'password.confirmed' => __('admin.new_password_mismatch'),
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.user-settings.change-password.form')
            ->with('success', __('admin.password_changed_successfully'));
    }

    /**
     * Display the change avatar form.
     */
    public function changeAvatar()
    {
        return view('admin.user-settings.change-avatar');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'avatar.required' => __('admin.avatar_required'),
            'avatar.image' => __('admin.avatar_must_be_image'),
            'avatar.mimes' => __('admin.avatar_mimes_error'),
            'avatar.max' => __('admin.avatar_max_size_error'),
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            if ($user->profile && $user->profile->avatar) {
                PathHelper::deleteUserAvatar($user->id, $user->profile->avatar);
            }

            $filename = PathHelper::storeUserAvatar($user->id, $request->file('avatar'));

            if ($user->profile) {
                $user->profile->avatar = $filename;
            }
        }

        $user->push();

        return redirect()->route('admin.user-settings.change-avatar.form')
            ->with('success', __('admin.avatar_updated_successfully'));
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar(Request $request)
    {
        $user = $request->user();

        if ($user->profile && $user->profile->avatar) {
            PathHelper::deleteUserAvatar($user->id, $user->profile->avatar);

            $user->profile->avatar = null;
            $user->profile->save();

            return redirect()->route('admin.user-settings.change-avatar.form')
                ->with('success', __('admin.avatar_deleted_successfully'));
        }

        return redirect()->route('admin.user-settings.change-avatar.form')
            ->with('error', __('admin.no_avatar_to_delete'));
    }

    /**
     * Display the general settings form.
     */
    public function edit()
    {
        $user = Auth::user();
        // Explicitly query the relationship to ensure we get fresh data
        $settings = $user->userSettings()->first() ?? new UserSettings(['user_id' => $user->id]);

        return view('admin.user-settings.edit', compact('settings'));
    }

    /**
     * Update the general settings.
     */
    public function update(UpdateUserSettingsRequest $request)
    {
        // $validated = $request->validated();

        $user = $request->user();

        $data = $request->only([
            'language',
            'theme',
            'font_size',
            'timezone',
            'date_format',
            'time_format',
            'currency',
        ]);

        // Handle boolean fields (checkboxes)
        $data['notifications_email'] = $request->has('notifications_email');
        $data['notifications_sound'] = $request->has('notifications_sound');
        $data['login_alerts'] = $request->has('login_alerts');

        // Use updateOrCreate to handle both creation and update in one go
        // This ensures we don't have issues with checking existence manually
        $user->userSettings()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return redirect()->route('admin.user-settings.edit')
            ->with('success', __('admin.settings_updated_successfully'));
    }
}
