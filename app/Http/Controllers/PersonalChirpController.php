<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalChirpController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $chirps = Auth::user()
            ->chirps()
            ->get();

        return view('personal.chirp', compact('chirps'));
    }
}
