<?php

namespace App\Http\Controllers;

use App\Models\Greeting;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $data['guest'] = 'Guest';
        if ($request->has('guest')) {
            $data['guest'] = $request->guest;
        }

        $data['greeting'] = Greeting::latest()->get();
        return Inertia::render("Main/Index", $data);
    }

    public function storeGreeting(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);

        $model = Greeting::create($validated);

        return response()->json(['message' => 'Greeting submitted successfully!'], 201);
    }
    public function storeReservation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'attendance' => 'required|integer|in:1,2,3',
            'guest' => 'required|integer|min:0|max:4',
        ]);

        $model = Reservation::create($validated);

        return response()->json(['message' => 'Reservation submitted successfully!'], 201);
    }
}
