<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceGroup;
use App\Models\Measurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DataviewController extends Controller
{

    // TODO PROGRAM IN SAFETY SO THAT THIS SHIT DOESNT CRASH
    // TODO IF device id is not valid, Reroute them to url without please
    public function databaseView(Request $request) {
        $deviceGroupsWithDevices = DeviceGroup::has('devices')->with('devices')->get();

        $user = Auth::user();
        $selectedDeviceId = null;
        $selectedDeviceName = null;
        $selectedDevice = null; // Initialize selectedDevice

        $isValidSelectedDevice = true;

        $deviceIdParam = $request->query('device_id');
        $deviceNameParam = $request->query('device_name');

        // Determine the selected device ID based on either ID or name
        if ($deviceIdParam) {
            $selectedDeviceId = intval($deviceIdParam);
            $selectedDevice = Device::find($deviceIdParam);
        } elseif ($deviceNameParam) {
            $selectedDeviceName = $deviceNameParam;
            $selectedDevice = Device::query()->where('name', $deviceNameParam)->first();
        }


        $selectedMeasurements = collect();
        if ($selectedDevice) {
            $selectedMeasurements = $selectedDevice->measurements;
        }

        // Fetch all devices owned by the user (for the dropdown filter)
        $userDevices = $user->devices()->get(['id', 'name']);

        // TODO Fix reroute when id not fits; implement errors
        // Always render the DataView page, letting the frontend handle the empty state
        return Inertia::render('dataview/DatabaseView', [
            'deviceGroups' => $deviceGroupsWithDevices,
            'measurements' => $selectedMeasurements, // Will be empty if no device selected or found
            'selectedDevice' => $selectedDevice,
            'selectedDeviceId' => $selectedDeviceId, // Will be null if no device selected/found
            'selectedDeviceName' => $selectedDeviceName, // Will be null if no device selected/found
            'selectedDeviceDetails' => $selectedDevice ? $selectedDevice->only('id', 'name', 'location', 'device_group_id') : null,
            // You might add an explicit 'errorMessage' if a device was specified but not found/owned
            'errorMessage' => ($selectedDeviceId && !$selectedDevice) ? 'The selected device was not found or you do not have access to it.' : null,
        ]);
    }
}
