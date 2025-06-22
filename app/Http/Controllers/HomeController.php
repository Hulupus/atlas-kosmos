<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Measurement;
// use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        // Count total devices
        $totalDevices = Device::all()->count();

        // Count total measurements for all devices
        $totalMeasurements = Measurement::all()->count();

        // Fetch some recent devices
        $recentDevices = Device::query()->orderBy('created_at', 'desc')->limit(4)->get(['id', 'name', 'location', 'created_at']);

        return Inertia::render('Home', [
            'totalDevices' => $totalDevices,
            'totalMeasurements' => $totalMeasurements,
            'recentDevices' => $recentDevices,
        ]);
    }
}
