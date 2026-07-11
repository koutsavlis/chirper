<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChirpRequest;
use App\Http\Requests\UpdateChirpRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Chirp;
use Illuminate\Http\Request;

class ChirpController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chirps = auth()->user()
            ->chirps()
            ->latest()
            ->take(50)
            ->get();

        return view('chirps.index', ['chirps' => $chirps]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('chirps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChirpRequest $request)
    {
        // Validate the request
        // $validated = $request->validate([
        //     'message' => 'required|string|max:255',
        // ], [
        //     'message.required' => 'Please write something to chirp!',
        //     'message.max' => 'Chirps must be 255 characters or less.',
        // ]);

        $validated = $request->validated();

        // Use the authenticated user
        auth()->user()->chirps()->create($validated);

        // Redirect back to the feed
        return redirect('/')->with('success', 'Chirp created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        $this->authorize('view', $chirp);

        return view('chirps.show', compact('chirp'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        $this->authorize('update', $chirp);

        // return view('chirps.edit', ['chirp' => $chirp]);
        return view('chirps.edit', compact('chirp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChirpRequest $request, Chirp $chirp)
    {
        $this->authorize('update', $chirp);


        // Validate
        // $validated = $request->validate([
        //     'message' => 'required|string|max:255',
        // ], [
        //     'message.required' => 'Please write something to chirp!',
        //     'message.max' => 'Chirps must be 255 characters or less.',
        // ]);

        $validated = $request->validated();

        // Update
        $chirp->update($validated);

        // Redirect back to the feed
        return redirect('/')->with('success', 'Chirp updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);
        
        $chirp->delete();

        return redirect('/')->with('success', 'Chirp deleted successfully!');
    }
}