<?php

namespace App\Http\Controllers;

use App\Models\Output;
use App\Models\Sale;

class MovementController extends Controller
{
    public function index()
    {
        $sales = Sale::with('user', 'customer', 'carboySales')
            ->orderByDesc('timestamp')
            ->get()
            ->map(function (Sale $sale) {
                $sale->carboy_count = $sale->carboySales->count();

                return $sale;
            });

        $outputs = Output::with('user', 'route', 'carboyOutputs')
            ->orderByDesc('timestamp')
            ->get()
            ->map(function (Output $output) {
                $output->carboy_count = $output->carboyOutputs->count();

                return $output;
            });

        return view('layouts.movement.index', compact('sales', 'outputs'));
    }

    public function showSale(Sale $sale)
    {
        $sale->load('user', 'customer', 'route', 'concept', 'carboySales');

        return view('layouts.movement.show-sale', compact('sale'));
    }

    public function showOutput(Output $output)
    {
        $output->load('user', 'route', 'carboyOutputs');

        return view('layouts.movement.show-output', compact('output'));
    }
}
