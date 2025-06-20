<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DeviceController extends Controller
{
    public function index() {
        return Inertia::render('devices/Index');
    }

    public function create() {
        return Inertia::render('devices/Create');
    }

    public function store(Request $request) {

    }
}
