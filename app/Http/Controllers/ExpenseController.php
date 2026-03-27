<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $concepts = Concept::orderBy('name')->get();
        $expenses = Expense::with('concept')->orderByDesc('timestamp')->get();
        $totalExpense = $expenses->sum('amount');

        return view('layouts.finance.expense.index', compact('concepts', 'expenses', 'totalExpense'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'concept_id' => 'required|exists:concepts,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'timestamp' => 'nullable|date',
        ]);

        $validated['timestamp'] = $validated['timestamp'] ?? now();

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Egreso registrado exitosamente');
    }

    public function edit(Expense $expense)
    {
        $concepts = Concept::orderBy('name')->get();
        $expenses = Expense::with('concept')->orderByDesc('timestamp')->get();
        $totalExpense = $expenses->sum('amount');

        return view('layouts.finance.expense.index', compact('concepts', 'expenses', 'totalExpense', 'expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'concept_id' => 'required|exists:concepts,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'timestamp' => 'nullable|date',
        ]);

        $validated['timestamp'] = $validated['timestamp'] ?? now();

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Egreso actualizado exitosamente');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Egreso eliminado exitosamente');
    }
}
