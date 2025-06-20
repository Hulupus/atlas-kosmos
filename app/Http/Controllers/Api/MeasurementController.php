<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMeasurementRequest;
use App\Models\Device;
use App\Models\Measurement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a new measurement data from a device.
     *
     * @param StoreMeasurementRequest $request
     * @return JsonResponse
     */
    public function store(StoreMeasurementRequest $request): JsonResponse
    {
        // The StoreMeasurementRequest has already handled validation and authorization.
        // We know that the device_id in the request belongs to the authenticated user.

        $device = Device::findOrFail($request->device_id);

        // Create the measurement record
        $measurement = $device->measurements()->create($request->validated());

        // Return a successful response
        return response()->json([
            'message' => 'Measurement data received and stored successfully.',
            'data' => $measurement,
        ], 201); // 201 Created status code
    }

    /**
     * Display the specified resource.
     */
    public function show(Measurement $measurement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Measurement $measurement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Measurement $measurement)
    {
        //
    }
}
