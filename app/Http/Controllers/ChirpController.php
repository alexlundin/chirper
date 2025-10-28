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
            ],
        ]);

        auth()->user()->chirps()->create($validated);

        return redirect('/')->with('success', 'Your chirp has been posted!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        if ($chirp->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('chirps.edit', compact('chirp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {

         if ($chirp->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

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
        $this->authorize('delete', $chirp);

        $chirp->delete();
 
        return redirect('/')->with('success', 'Chirp deleted!');
    }
}
