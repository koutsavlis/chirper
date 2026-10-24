<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChirpRequest;
use App\Models\Chirp;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChirpController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the public feed of all chirps.
     */
    public function index()
    {
        $chirps = Chirp::query()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('chirps.index', ['chirps' => $chirps]);
    }

    /**
     * Show the form for creating a new chirp.
     */
    public function create()
    {
        return view('chirps.create');
    }

    /**
     * Store a newly created chirp.
     */
    public function store(ChirpRequest $request)
    {
        auth()->user()->chirps()->create($request->validated());

        return redirect()->route('dashboard')->with('success', 'Chirp created successfully!');
    }

    /**
     * Display the specified chirp. Chirps are public.
     */
    public function show(Chirp $chirp)
    {
        return view('chirps.show', compact('chirp'));
    }

    /**
     * Show the form for editing the specified chirp.
     */
    public function edit(Chirp $chirp)
    {
        $this->authorize('update', $chirp);

        return view('chirps.edit', compact('chirp'));
    }

    /**
     * Update the specified chirp.
     */
    public function update(ChirpRequest $request, Chirp $chirp)
    {
        $this->authorize('update', $chirp);

        $chirp->update($request->validated());

        return redirect()->route('dashboard')->with('success', 'Chirp updated successfully!');
    }

    /**
     * Remove the specified chirp.
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        return redirect()->route('dashboard')->with('success', 'Chirp deleted successfully!');
    }
}
