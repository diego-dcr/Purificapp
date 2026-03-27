<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\Customer;
use App\Models\Route;

class CustomerController extends Controller
{
    public function index()
    {
        $routes = Route::all();
        $customers = Customer::with('route')->get();

        return view('layouts.customer.index', compact('routes', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'barcode' => 'required|string|unique:customers,barcode|max:255',
            'number' => 'required|string|unique:customers,number|max:255',
            'name' => 'required|string|max:255',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Cliente creado exitosamente');
    }

    public function edit(Customer $customer)
    {
        $routes = Route::all();
        $customers = Customer::with('route')->get();
        return view('layouts.customer.index', compact('routes', 'customers', 'customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,id',
            'barcode' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('customers', 'barcode')->ignore($customer->id),
            ],
            'number' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('customers', 'number')->ignore($customer->id),
            ],
            'name' => 'required|string|max:255',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Cliente eliminado exitosamente');
    }
}
