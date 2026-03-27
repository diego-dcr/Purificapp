<?php

namespace App\Http\Controllers;

use App\Models\Input;
use App\Models\Output;

class MovementController extends Controller
{
    public function index()
    {
        $inputs = Input::with('user', 'customer', 'waterjugSales')
            ->orderByDesc('timestamp')
            ->get()
            ->map(function (Input $input) {
                $input->waterjug_count = $input->waterjugSales->count();
                return $input;
            });

        $outputs = Output::with('user', 'route', 'waterjugOutputs')
            ->orderByDesc('timestamp')
            ->get()
            ->map(function (Output $output) {
                $output->waterjug_count = $output->waterjugOutputs->count();
                return $output;
            });

        return view('layouts.movement.index', compact('inputs', 'outputs'));
    }

    public function showInput(Input $input)
    {
        $input->load('user', 'customer', 'route', 'concept', 'waterjugSales');

        return view('layouts.movement.show-input', compact('input'));
    }

    public function showOutput(Output $output)
    {
        $output->load('user', 'route', 'waterjugOutputs');

        return view('layouts.movement.show-output', compact('output'));
    }
}
