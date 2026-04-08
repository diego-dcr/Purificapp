<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\Income;
use App\Models\Sale;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index()
    {
        $automaticIncomes = Sale::with('concept', 'customer', 'user')
            ->orderByDesc('timestamp')
            ->paginate(100, ['*'], 'automatic_page');

        $systemIncomes = Income::with('concept')
            ->orderByDesc('timestamp')
            ->paginate(100, ['*'], 'system_page');

        $concepts = ConceptController::listByType(Concept::TYPE_INCOME);

        $totalAutomaticIncome = (float) Sale::query()->sum('cost');
        $totalSystemIncome = (float) Income::query()->sum('amount');
        $totalIncome = $totalAutomaticIncome + $totalSystemIncome;

        $automaticIncomesByConcept = Sale::query()
            ->leftJoin('concepts', 'concepts.id', '=', 'sales.concept_id')
            ->selectRaw("COALESCE(concepts.name, 'Sin concepto') as concept_name")
            ->selectRaw('SUM(sales.cost) as total')
            ->groupBy('concept_name')
            ->orderByDesc('total')
            ->pluck('total', 'concept_name');

        $systemIncomesByConcept = Income::query()
            ->leftJoin('concepts', 'concepts.id', '=', 'incomes.concept_id')
            ->selectRaw("COALESCE(concepts.name, 'Sin concepto') as concept_name")
            ->selectRaw('SUM(incomes.amount) as total')
            ->groupBy('concept_name')
            ->orderByDesc('total')
            ->pluck('total', 'concept_name');

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

        Income::create($validated);

        return redirect()->route('incomes.index')->with('success', 'Ingreso agregado exitosamente');
    }

    public function edit(Income $systemIncome)
    {
        $automaticIncomes = Sale::with('concept', 'customer', 'user')
            ->orderByDesc('timestamp')
            ->paginate(100, ['*'], 'automatic_page');

        $systemIncomes = Income::with('concept')
            ->orderByDesc('timestamp')
            ->paginate(100, ['*'], 'system_page');

        $concepts = ConceptController::listByType(Concept::TYPE_INCOME);

        $totalAutomaticIncome = (float) Sale::query()->sum('cost');
        $totalSystemIncome = (float) Income::query()->sum('amount');
        $totalIncome = $totalAutomaticIncome + $totalSystemIncome;

        $automaticIncomesByConcept = Sale::query()
            ->leftJoin('concepts', 'concepts.id', '=', 'sales.concept_id')
            ->selectRaw("COALESCE(concepts.name, 'Sin concepto') as concept_name")
            ->selectRaw('SUM(sales.cost) as total')
            ->groupBy('concept_name')
            ->orderByDesc('total')
            ->pluck('total', 'concept_name');

        $systemIncomesByConcept = Income::query()
            ->leftJoin('concepts', 'concepts.id', '=', 'incomes.concept_id')
            ->selectRaw("COALESCE(concepts.name, 'Sin concepto') as concept_name")
            ->selectRaw('SUM(incomes.amount) as total')
            ->groupBy('concept_name')
            ->orderByDesc('total')
            ->pluck('total', 'concept_name');

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

    public function update(Request $request, Income $systemIncome)
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

    public function destroy(Income $systemIncome)
    {
        $systemIncome->delete();

        return redirect()->route('incomes.index')->with('success', 'Ingreso eliminado exitosamente');
    }
}
