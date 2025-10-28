<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index()
    {
         $chirps = Chirp::with('user')
            ->latest()
            ->take(50)  // Limit to 50 most recent chirps
            ->get();

        return view('home', ['chirps' => $chirps]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
             'message' => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('chirps')->where(function ($query) use ($user) {
                   return $query->where('user_id', $user->id); 
                }),
            ],
        ]);

        Chirp::create([
            'message' => $validated['message'],
            'user_id' => null
        ], [
            'message.required' => 'Please write something to chirp!',
            'message.max' => 'Chirps must be 255 characters or less.',
        ]);

        return redirect('/')->with('success', 'Chirp created!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        return view('chirps.edit', compact('chirp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {

        // Validate
        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:255',
            ],
        ]);
    
        // Update
        $chirp->update($validated);
    
        return redirect('/')->with('success', 'Chirp updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        $chirp->delete();
 
        return redirect('/')->with('success', 'Chirp deleted!');
    }
}
