<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index()
    {
        return view('admin.brands.index');
    }

    public function show(Request $request)
    {
        $brand = Brand::findOrFail($request->id) ;
        return Response()->json(compact('brand'));
    }

    public function get()
    {
        $brands = Brand::orderBy('name', 'asc')->get();
        return Response()->json(compact('brands'));
    }

    public function store(Request $request)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);
            $brand = Brand::create($validated);
            return response()->json([
                'message' => 'Brand created successfully.',
                'data' => $brand,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function update(Request $request, Brand $brand)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $brand->update($validated);
            return response()->json([
                'message' => 'Brand updated successfully.',
                'data' => $brand,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function destroy(Brand $brand)
    {
        try
        {
            $brand->delete();
            return response()->json([
                'message' => 'Brand deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }
}
