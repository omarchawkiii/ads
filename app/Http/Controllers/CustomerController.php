<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('advertiser.customers.index');
    }

    public function get()
    {

        $customers = Customer::where('user_id', auth()->id())->orderBy('name')->get();
        return response()->json(compact('customers'));
    }

    public function show(Request $request)
    {
        $customer = Customer::where('user_id', auth()->id())->findOrFail($request->id);
        return response()->json(compact('customer'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email'   => 'nullable|email',
            'phone'   => 'nullable|string|max:30',
        ]);

        $validated['user_id'] = auth()->id();

        $customer = Customer::create($validated);

        return response()->json([
            'message' => 'Customer created successfully',
            'data' => $customer
        ], 201);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email'   => 'nullable|email',
            'phone'   => 'nullable|string|max:30',
        ]);

        $customer->update($validated);

        return response()->json([
            'message' => 'Customer updated successfully',
            'data' => $customer
        ]);
    }

    public function destroy(Customer $customer)
    {
        if ($customer->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully'
        ]);
    }
}

