<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'language' => ['nullable', 'string', 'in:ar,en'],
            'theme' => ['nullable', 'string', 'in:light,dark,system'],
            'font_size' => ['nullable', 'string', 'in:small,medium,large'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'date_format' => ['nullable', 'string'],
            'time_format' => ['nullable', 'string', 'in:24h,12h'],
            'currency' => ['nullable', 'string', 'in:EGP,USD,SAR,EUR'],
            'notifications_email' => ['nullable', 'boolean'],
            'notifications_sound' => ['nullable', 'boolean'],
            'login_alerts' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'language' => __('admin.language'),
            'theme' => __('admin.theme'),
            'font_size' => __('admin.font_size'),
            'timezone' => __('admin.timezone'),
            'date_format' => __('admin.date_format'),
            'time_format' => __('admin.time_format'),
            'currency' => __('admin.currency'),
            'notifications_email' => __('admin.notifications_email'),
            'notifications_sound' => __('admin.notifications_sound'),
            'login_alerts' => __('admin.login_alerts'),
        ];
    }
}
