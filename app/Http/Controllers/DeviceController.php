<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeviceRequest;
use App\Models\Device;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DeviceController extends Controller
{
    public function index() {
        return Inertia::render('devices/Index');
    }

    public function create() {
        return Inertia::render('devices/Create');
    }

    public function store(StoreDeviceRequest $request) {
        $user = Auth::user(); // Get the authenticated user

        // Get all validated data, excluding the 'generate_token' flag
        // The 'except' method ensures 'generate_token' is not included in the device's fillable data.
        $deviceData = $request->except('generate_token');

        // Assign the user_id to the device immediately upon creation via this UI.
        // This makes the device owned by the creating user from the start.
        $deviceData['user_id'] = $user->id;

        // Create the device in the database.
        $device = Device::create($deviceData);

        // Initialize variables for the response
        $responseMessage = 'Device created successfully!';
        $deviceToken = null; // This will hold the plain text token if generated

        // Check if the user opted to generate a token via the checkbox
        if ($request->boolean('generate_token')) {
            // Optional: Revoke any *existing* tokens for this user that share the same device name.
            // This is good practice to prevent multiple active tokens for the same named device.
            $user->tokens()->where('name', 'device-'.$device->name)->delete();

            // Generate a new Sanctum API token for the user.
            // The token is named using the device's name for easy identification.
            // ['device-access'] defines the abilities (scopes) this token will have.
            // .plainTextToken gives you the raw token string, available only at creation.
            $deviceToken = $user->createToken('device-' . $device->name, ['device-access'])->plainTextToken;
            $responseMessage = 'Device created and API Token generated successfully!';
        }

        // Redirect the user back to the device index page.
        // Flash the success message, the generated token (if any), and the device name to the session.
        return redirect()->route('devices.index')->with([
            'success' => $responseMessage,
            'deviceToken' => $deviceToken, // Will be null if the checkbox was unchecked
            'deviceName' => $device->name,
        ]);
    }

    /**
     * Generate a new API token for a specific device.
     * This method also handles associating the device with the current user if it's unassigned.
     *
     * @param Request $request The incoming request.
     * @param Device $device The device instance (route model binding).
     * @return RedirectResponse
     */
    public function generateToken(Request $request, Device $device)
    {
        $user = Auth::user(); // Get the authenticated user

        // --- Authorization and Association Logic ---
        $message = ''; // Initialize message variable

        if ($device->user_id === null) {
            // Device is currently unassigned.
            // Associate this device with the authenticated user (claiming it).
            $device->user_id = $user->id;
            $device->save();
            $message = 'Device successfully associated and API Token generated!';
        } elseif ($device->user_id !== $user->id) {
            // Device is assigned to a different user.
            // This prevents a user from claiming a device already owned by someone else.
            abort(403, 'Unauthorized: This device is already assigned to another account.');
        } else {
            // Device is already assigned to the current user.
            $message = 'New API Token generated for your device!';
        }
        // --- End Authorization and Association Logic ---

        // Optional: Revoke existing tokens for this device name if you want only one active token per device name
        // This ensures that only the most recently generated token for a given device name is valid.
        // It's good practice for security and manageability.
        $user->tokens()->where('name', 'device-'.$device->name)->delete();

        // Generate a new Sanctum API token for the user.
        // The token is named using the device's name for easy identification in the personal_access_tokens table.
        // ['device-access'] defines the abilities (scopes) granted to this token.
        // ->plainTextToken returns the raw token string which is only available right after creation.
        $token = $user->createToken('device-' . $device->name, ['device-access'])->plainTextToken;

        // Redirect back to the devices index page.
        // The success message, plain text token, and device name are flashed to the session
        // so they can be displayed to the user immediately on the front-end.
        return redirect()->route('devices.index')->with([
            'success' => $message,
            'deviceToken' => $token,
            'deviceName' => $device->name,
        ]);
    }
}
