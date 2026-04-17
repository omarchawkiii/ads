<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Customer;

class ProfileController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $customer = null;

        if ($user->role == 1) {
            return view('admin.profile.index', compact('user'));
        }

        if ($user->advertiser_type === 'direct') {
            $customer = Customer::where('user_id', $user->id)->first();
        }

        return view('advertiser.profile.index', compact('user', 'customer'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'last_name'        => 'nullable|string|max:255',
            'username'         => 'nullable|string|max:255',
            'email'            => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'customer_name'       => 'nullable|string|max:255',
            'customer_brand_name' => 'nullable|string|max:255',
            'customer_address'    => 'nullable|string|max:255',
            'customer_address_2'  => 'nullable|string|max:255',
            'customer_city'       => 'nullable|string|max:100',
            'customer_state'      => 'nullable|string|max:100',
            'customer_postcode'   => 'nullable|string|max:20',
            'customer_country'    => 'nullable|string|max:100',
            'customer_email'      => 'nullable|email|max:255',
            'customer_phone'      => 'nullable|string|max:30',
            'customer_pic_name'   => 'nullable|string|max:255',
        ]);

        $user->update([
            'name'      => $validated['name'],
            'last_name' => $validated['last_name'] ?? null,
            'username'  => $validated['username'] ?? null,
            'email'     => $validated['email'],
        ]);

        if ($user->advertiser_type === 'direct') {
            Customer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name'       => $request->customer_name ?? $user->name,
                    'brand_name' => $request->customer_brand_name,
                    'address'    => $request->customer_address,
                    'address_2'  => $request->customer_address_2,
                    'city'       => $request->customer_city,
                    'state'      => $request->customer_state,
                    'postcode'   => $request->customer_postcode,
                    'country'    => $request->customer_country,
                    'email'      => $request->customer_email,
                    'phone'      => $request->customer_phone,
                    'pic_name'   => $request->customer_pic_name,
                ]
            );
        }

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
