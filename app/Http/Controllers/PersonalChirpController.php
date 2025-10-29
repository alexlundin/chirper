<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;

class PersonalChirpController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $chirps =  auth()->user()->chirps()->get();
        return view('personal.chirp', compact('chirps'));
    }
}
