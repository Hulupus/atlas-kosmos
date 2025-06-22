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
        $selectedDeviceId = null;
        $selectedDevice = null;

        // Fetch all devices owned by the user (for the dropdown filter)
        $deviceGroupsWithDevices = DeviceGroup::has('devices')->with('devices')->get();

        $deviceIdParam = $request->query('device_id');

        if ($deviceIdParam) {
            $selectedDeviceId = intval($deviceIdParam);
            $selectedDevice = Device::find($deviceIdParam);
        }

        $selectedMeasurements = collect();
        if ($selectedDevice) {
            $selectedMeasurements = $selectedDevice->measurements;
        } else if ($request->query()) {
            // Redirect to route if anything non-valid is in url
            return redirect()
                ->route('measurements')
                ->with('error', 'Das gesuchte Gerät konnte nicht gefunden werden!');
        }

        // Always render the DataView page, letting the frontend handle the empty state
        return Inertia::render('dataview/DatabaseView', [
            'deviceGroups' => $deviceGroupsWithDevices,
            'measurements' => $selectedMeasurements,
            'selectedDevice' => $selectedDevice,
            'selectedDeviceId' => $selectedDeviceId,
        ]);
    }

    public function graphView(Request $request) {
        $selectedDeviceId = null;
        $selectedDevice = null;

        // Fetch all devices owned by the user (for the dropdown filter)
        $deviceGroupsWithDevices = DeviceGroup::has('devices')->with('devices')->get();

        $deviceIdParam = $request->query('device_id');

        if ($deviceIdParam) {
            $selectedDeviceId = intval($deviceIdParam);
            $selectedDevice = Device::find($deviceIdParam);
        }

        $selectedMeasurements = collect();
        if ($selectedDevice) {
            $selectedMeasurements = $selectedDevice->measurements;
        } else if ($request->query()) {
            // Redirect to route if anything non-valid is in url
            return redirect()
                ->route('graphs')
                ->with('error', 'Das gesuchte Gerät konnte nicht gefunden werden!');
        }

        // Always render the DataView page, letting the frontend handle the empty state
        return Inertia::render('dataview/GraphView', [
            'deviceGroups' => $deviceGroupsWithDevices,
            'measurements' => $selectedMeasurements,
            'selectedDevice' => $selectedDevice,
            'selectedDeviceId' => $selectedDeviceId,
        ]);
    }
}
