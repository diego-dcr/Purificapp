<?php

namespace App\Http\Controllers;

use App\Models\Retorno;
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

        $retornos = Retorno::with('user', 'route', 'carboyRetornos')
            ->orderByDesc('timestamp')
            ->get()
            ->map(function (Retorno $retorno) {
                $retorno->carboy_count = $retorno->carboyRetornos->count();

                return $retorno;
            });

        return view('layouts.movement.index', compact('sales', 'retornos'));
    }

    public function showSale(Sale $sale)
    {
        $sale->load('user', 'customer', 'route', 'concept', 'carboySales');

        return view('layouts.movement.show-sale', compact('sale'));
    }

    public function showRetorno(Retorno $retorno)
    {
        $retorno->load('user', 'route', 'carboyRetornos');

        return view('layouts.movement.show-retorno', compact('retorno'));
    }
}
