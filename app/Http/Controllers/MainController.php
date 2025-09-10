<?php

namespace App\Http\Controllers;

use App\Models\Greeting;
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
            'attendance' => 'required|integer|in:0,1,2',
        ]);

        $model = Greeting::create($validated);

        return response()->json(['message' => 'Greeting submitted successfully!'], 201);
    }
}
