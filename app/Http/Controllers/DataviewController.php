<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DataviewController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();
        $selectedDeviceId = null;
        $selectedDeviceName = null;
        $selectedDevice = null; // Initialize selectedDevice

        $deviceIdParam = $request->query('device_id');
        $deviceNameParam = $request->query('device_name');

        // TODO FIX THIS THING PLEAAAAAAAAAS
        if ($deviceIdParam == null && $deviceNameParam == null) {
            return Inertia::render('dataview/Index');
        }
        dd($deviceIdParam);

        // Determine the selected device ID based on either ID or name
        if ($deviceIdParam) {
            $selectedDeviceId = intval($deviceIdParam);
        } elseif ($deviceNameParam) {
            $deviceByName = $user->devices()->where('name', $deviceNameParam)->first(['id', 'name']);
            if ($deviceByName) {
                $selectedDeviceId = $deviceByName->id;
                $selectedDeviceName = $deviceByName->name;
            }
        }

        // Fetch all devices owned by the user (for the dropdown filter)
        $userDevices = $user->devices()->get(['id', 'name']);

        $measurements = collect(); // Initialize measurements as an empty collection

        // Check if a device was successfully identified and is owned by the user
        if ($selectedDeviceId) {
            $selectedDevice = $user->devices()->find($selectedDeviceId);

            if ($selectedDevice) {
                // If a valid and owned device is found, fetch its measurements
                $measurements = Measurement::query()
                    ->where('device_id', $selectedDevice->id)
                    ->with(['device.deviceGroup'])
                    ->orderBy('measured_at', 'desc')
                    ->limit(50)
                    ->get();

                if (is_null($selectedDeviceName)) {
                    $selectedDeviceName = $selectedDevice->name;
                }
            }
        }

        // Always render the DataView page, letting the frontend handle the empty state
        return Inertia::render('Measurements/DataView', [
            'measurements' => $measurements, // Will be empty if no device selected or found
            'userDevices' => $userDevices,
            'selectedDeviceId' => $selectedDeviceId, // Will be null if no device selected/found
            'selectedDeviceName' => $selectedDeviceName, // Will be null if no device selected/found
            'selectedDeviceDetails' => $selectedDevice ? $selectedDevice->only('id', 'name', 'location', 'device_group_id') : null,
            // You might add an explicit 'errorMessage' if a device was specified but not found/owned
            'errorMessage' => ($selectedDeviceId && !$selectedDevice) ? 'The selected device was not found or you do not have access to it.' : null,
        ]);
    }
}
