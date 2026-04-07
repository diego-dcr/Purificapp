<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\Sale;
use App\Models\SystemIncome;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        $automaticIncomes = Sale::with('concept', 'customer', 'user')
            ->orderByDesc('timestamp')
            ->get();

        $systemIncomes = SystemIncome::with('concept')
            ->orderByDesc('timestamp')
            ->get();

        $concepts = ConceptController::listByType(Concept::TYPE_INCOME);

        $totalAutomaticIncome = $automaticIncomes->sum('cost');
        $totalSystemIncome = $systemIncomes->sum('amount');
        $totalIncome = $totalAutomaticIncome + $totalSystemIncome;

        $automaticIncomesByConcept = $automaticIncomes
            ->groupBy(fn ($income) => $income->concept?->name ?? 'Sin concepto')
            ->map(fn ($items) => $items->sum('cost'))
            ->sortDesc();

        $systemIncomesByConcept = $systemIncomes
            ->groupBy(fn ($income) => $income->concept?->name ?? 'Sin concepto')
            ->map(fn ($items) => $items->sum('amount'))
            ->sortDesc();

        return view('layouts.finance.income.index', compact(
            'automaticIncomes',
            'systemIncomes',
            'concepts',
            'totalAutomaticIncome',
            'totalSystemIncome',
            'totalIncome',
            'automaticIncomesByConcept',
            'systemIncomesByConcept',
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'concept_id' => [
                'required',
                ConceptController::conceptExistsRule(Concept::TYPE_INCOME),
            ],
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'timestamp' => 'nullable|date',
        ]);

        $validated['timestamp'] = $validated['timestamp'] ?? now();

        SystemIncome::create($validated);

        return redirect()->route('incomes.index')->with('success', 'Ingreso agregado exitosamente');
    }

    public function edit(SystemIncome $systemIncome)
    {
        $automaticIncomes = Sale::with('concept', 'customer', 'user')
            ->orderByDesc('timestamp')
            ->get();

        $systemIncomes = SystemIncome::with('concept')
            ->orderByDesc('timestamp')
            ->get();

        $concepts = ConceptController::listByType(Concept::TYPE_INCOME);

        $totalAutomaticIncome = $automaticIncomes->sum('cost');
        $totalSystemIncome = $systemIncomes->sum('amount');
        $totalIncome = $totalAutomaticIncome + $totalSystemIncome;

        $automaticIncomesByConcept = $automaticIncomes
            ->groupBy(fn ($income) => $income->concept?->name ?? 'Sin concepto')
            ->map(fn ($items) => $items->sum('cost'))
            ->sortDesc();

        $systemIncomesByConcept = $systemIncomes
            ->groupBy(fn ($income) => $income->concept?->name ?? 'Sin concepto')
            ->map(fn ($items) => $items->sum('amount'))
            ->sortDesc();

        return view('layouts.finance.income.index', compact(
            'automaticIncomes',
            'systemIncomes',
            'concepts',
            'totalAutomaticIncome',
            'totalSystemIncome',
            'totalIncome',
            'automaticIncomesByConcept',
            'systemIncomesByConcept',
            'systemIncome',
        ));
    }

    public function update(Request $request, SystemIncome $systemIncome)
    {
        $validated = $request->validate([
            'concept_id' => [
                'required',
                ConceptController::conceptExistsRule(Concept::TYPE_INCOME),
            ],
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'timestamp' => 'nullable|date',
        ]);

        $validated['timestamp'] = $validated['timestamp'] ?? now();

        $systemIncome->update($validated);

        return redirect()->route('incomes.index')->with('success', 'Ingreso actualizado exitosamente');
    }

    public function destroy(SystemIncome $systemIncome)
    {
        $systemIncome->delete();

        return redirect()->route('incomes.index')->with('success', 'Ingreso eliminado exitosamente');
    }
}
