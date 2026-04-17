<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->advertiser_type === 'direct') {
            return redirect()->route('advertiser.profile.index');
        }

        $customers = Customer::where('user_id', $user->id)
            ->withCount('dcpCreatives')
            ->orderBy('name')
            ->get();

        $totalClients    = $customers->count();
        $activeCampaigns = \App\Models\Compaign::where('user_id', $user->id)
            ->where('status', 2)
            ->count();
        $outstandingAmount = \App\Models\Invoice::whereHas('compaign', fn($q) => $q->where('user_id', $user->id))
            ->whereNotIn('status', ['paid'])
            ->sum('total_ttc');

        return view('advertiser.customers.index', compact('customers', 'totalClients', 'activeCampaigns', 'outstandingAmount'));
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
        if (auth()->user()->advertiser_type === 'direct') {
            $existing = Customer::where('user_id', auth()->id())->count();
            if ($existing >= 1) {
                return response()->json(['message' => 'Direct advertisers can only have one customer.'], 422);
            }
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'address'    => 'nullable|string|max:255',
            'address_2'  => 'nullable|string|max:255',
            'city'       => 'nullable|string|max:100',
            'state'      => 'nullable|string|max:100',
            'postcode'   => 'nullable|string|max:20',
            'country'    => 'nullable|string|max:100',
            'email'      => 'nullable|email',
            'phone'      => 'nullable|string|max:30',
            'pic_name'   => 'nullable|string|max:255',
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
            'name'       => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'address'    => 'nullable|string|max:255',
            'address_2'  => 'nullable|string|max:255',
            'city'       => 'nullable|string|max:100',
            'state'      => 'nullable|string|max:100',
            'postcode'   => 'nullable|string|max:20',
            'country'    => 'nullable|string|max:100',
            'email'      => 'nullable|email',
            'phone'      => 'nullable|string|max:30',
            'pic_name'   => 'nullable|string|max:255',
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
