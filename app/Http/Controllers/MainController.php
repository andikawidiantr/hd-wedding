<?php

namespace App\Http\Controllers;

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

        return Inertia::render("Main/Index", $data);
    }
}
