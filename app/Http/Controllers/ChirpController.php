<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use OpenAI\Laravel\Facades\OpenAI;


class ChirpController extends Controller
{
    public function generateAIChirp(): RedirectResponse
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',  // Change to 'gpt-4' if needed
            'messages' => [
                ['role' => 'system', 'content' => 'You are a creative assistant generating short chirps about technology.'],
                ['role' => 'user', 'content' => 'Generate a fun and positive chirp about technology trends.']
            ],
        ]);
    
        $message = trim($response['choices'][0]['message']['content'] ?? 'Generated chirp not available.');
    
        // Save the generated chirp for the authenticated user
        auth()->user()->chirps()->create(['message' => $message]);
    
        return redirect(route('chirps.index'))->with('success', 'AI-generated chirp created successfully.');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Chirps/Index', [
            //
            'chirps' => Chirp::with('user:id,name')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $request->user()->chirps()->create($validated);
 
        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        Gate::authorize('update', $chirp);
 
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $chirp->update($validated);
 
        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        //
        Gate::authorize('delete', $chirp);
 
        $chirp->delete();
 
        return redirect(route('chirps.index'));
    }
}
