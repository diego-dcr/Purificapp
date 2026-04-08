<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

// Models
use App\Models\Concept;

class ConceptController extends Controller
{
    public static function listByType(string $type): Collection
    {
        self::assertValidType($type);

        return Concept::where('type', $type)->orderBy('name')->get();
    }

    public static function conceptExistsRule(string $type): Exists
    {
        self::assertValidType($type);

        return Rule::exists('concepts', 'id')->where('type', $type);
    }

    private static function assertValidType(string $type): void
    {
        if (!in_array($type, [Concept::TYPE_INCOME, Concept::TYPE_EXPENSE], true)) {
            throw new \InvalidArgumentException('Tipo de concepto inválido.');
        }
    }

    public function index()
    {
        $concepts = Concept::orderBy('name')->get();
        $movementTypes = Concept::movementTypes();

        return view('layouts.concept.index', compact('concepts', 'movementTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:concepts,code|max:255',
            'type' => ['required', Rule::in(array_keys(Concept::movementTypes()))],
            'allows_carboy' => 'required|boolean',
        ]);

        Concept::create($validated);

        return redirect()->route('concepts.index')->with('success', 'Concepto creado exitosamente');
    }

    public function edit(Concept $concept)
    {
        $concepts = Concept::orderBy('name')->get();
        $movementTypes = Concept::movementTypes();

        return view('layouts.concept.index', compact('concepts', 'concept', 'movementTypes'));
    }

    public function update(Request $request, Concept $concept)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('concepts', 'code')->ignore($concept->id),
            ],
            'type' => ['required', Rule::in(array_keys(Concept::movementTypes()))],
            'allows_carboy' => 'required|boolean',
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
