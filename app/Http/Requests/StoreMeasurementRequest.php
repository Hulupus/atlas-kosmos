<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\Device; // Import Device model
use Illuminate\Validation\ValidationException; // Import ValidationException

class StoreMeasurementRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     * This method is crucial for converting device_name to device_id.
     * It runs before the validation rules are applied.
     */
    protected function prepareForValidation(): void
    {
        $deviceName = $this->input('device_name');
        $userId = Auth::id(); // Get the ID of the authenticated user

        if ($deviceName && $userId) {
            // Attempt to find the device by name and ensure it belongs to the authenticated user
            $device = Device::where('name', $deviceName)
                ->where('user_id', $userId)
                ->first();

            if ($device) {
                // If the device is found and owned by the user, merge its ID into the request data
                $this->merge([
                    'device_id' => $device->id,
                ]);
            } else {
                // If the device is not found or not owned by the user,
                // throw a validation exception immediately.
                // This prevents the 'exists' rule from trying to validate a non-existent ID
                // or a device name that doesn't resolve to a valid owned device.
                throw ValidationException::withMessages([
                    'device_name' => ['The provided device name is invalid or does not belong to your account.'],
                ]);
            }
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // The prepareForValidation method already handles the device ownership check
        // by throwing an exception if the device name doesn't resolve correctly.
        // So, if we reach here, and there's an authenticated user, we can proceed.
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // We now expect 'device_name' in the input
            'device_name' => ['required', 'string', 'max:255'],
            // device_id is merged into the request by prepareForValidation,
            // and we still need to validate its presence and existence
            'device_id' => ['required', 'integer', 'exists:devices,id'], // This validates the ID after prepareForValidation has merged it

            'metric_name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'numeric'],
            'unit' => ['nullable', 'string', 'max:50'],
            'measured_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
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
            'device_name.required' => 'The device name is required.',
            'device_name.string' => 'The device name must be a string.',
            'device_id.required' => 'Internal error: Device ID not found from name.', // Should not happen with prepareForValidation
            'device_id.exists' => 'Internal error: Device ID does not exist after name resolution.', // Should not happen with prepareForValidation
            'metric_name.required' => 'The metric name is required.',
            'value.required' => 'The measurement value is required.',
            'value.numeric' => 'The measurement value must be a number.',
        ];
    }
}
