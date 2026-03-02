<?php

namespace App\Http\Controllers;

use App\Models\Greeting;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\App;

class MainController extends Controller
{
    public function index(Request $request)
    {
        // Set locale dari request atau dari localStorage
        $locale = $request->get('locale', session('locale', 'en'));
        App::setLocale($locale);
        session(['locale' => $locale]);
        
        $data['guest'] = 'Guest';
        if ($request->has('guest')) {
            $data['guest'] = $request->guest;
        }
        
        $data['code'] = '28';
        if ($request->has('code')) {
            $data['code'] = $request->code;
        }

        $data['greeting'] = Greeting::latest()->get();
        
        // Tambahkan data terjemahan dan locale
        $data['translations'] = [
            'id' => require resource_path('lang/id/messages.php'),
            'en' => require resource_path('lang/en/messages.php'),
        ];
        $data['locale'] = $locale;
        
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
