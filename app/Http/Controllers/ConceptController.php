<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\Concept;

class ConceptController extends Controller
{
    public function index()
    {
        $concepts = Concept::all();

        return view('layouts.concept.index', compact('concepts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:concepts,code|max:255',
        ]);

        Concept::create($validated);

        return redirect()->route('concepts.index')->with('success', 'Concepto creado exitosamente');
    }

    public function edit(Concept $concept)
    {
        $concepts = Concept::all();

        return view('layouts.concept.index', compact('concepts', 'concept'));
    }

    public function update(Request $request, Concept $concept)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('concepts', 'code')->ignore($concept->id),
            ],
        ]);

        $concept->update($validated);

        return redirect()->route('concepts.index')->with('success', 'Concepto actualizado exitosamente');
    }

    public function destroy(Concept $concept)
    {
        $concept->delete();

        return redirect()->route('concepts.index')->with('success', 'Concepto eliminado exitosamente');
    }
}
