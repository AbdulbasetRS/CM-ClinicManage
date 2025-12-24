<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // User fields
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => $this->type === 'patient' ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'mobile_number' => 'required|string|max:20|unique:users,mobile_number',
            'national_id' => 'nullable|string|max:20|unique:users,national_id',
            'nationality' => 'nullable|string|max:255',
            'passport_number' => 'nullable|string|max:255|unique:users,passport_number',
            'status' => 'required|string',
            'type' => 'required|string',
            'can_login' => 'required|boolean',
            'status_details' => 'nullable|string|max:1000',

            // Profile fields
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'whatapp_number' => 'nullable|string|max:20',
            'telegram_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'title' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'blood_type' => 'nullable|string',
            'chronic_diseases' => 'nullable|string|max:2000',
            'allergies' => 'nullable|string|max:2000',
            'note' => 'nullable|string|max:2000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function userData(): array
    {
        return $this->only([
            'username',
            'email',
            'mobile_number',
            'national_id',
            'nationality',
            'passport_number',
            'status',
            'type',
            'can_login',
            'status_details',
        ]);
    }

    public function profileData(): array
    {
        return $this->only([
            'first_name',
            'middle_name',
            'last_name',
            'whatapp_number',
            'telegram_number',
            'date_of_birth',
            'gender',
            'title',
            'address',
            'blood_type',
            'chronic_diseases',
            'allergies',
            'note',
        ]);
    }
}
