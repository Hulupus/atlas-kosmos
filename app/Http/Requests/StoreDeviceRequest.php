<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Only authenticated users can create a new device.
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:devices,name',
            ],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'device_group_id' => ['required', 'integer', 'exists:device_groups,id'],
            'webclient_start_url' => ['nullable', 'url', 'max:2048'],
            'generate_token' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Custom error messages for validation.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The device name is required.',
            'name.unique' => 'This device name is already taken. Please choose a different, globally unique name.',
            'device_group.required' => 'The device group is required.',
            'webclient_start_url.url' => 'The webclient start URL must be a valid URL format.',
        ];
    }
}
