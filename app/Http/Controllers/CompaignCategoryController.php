<?php

namespace App\Http\Controllers;

use App\Models\CompaignCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompaignCategoryController extends Controller
{
    public function index()
    {
        return view('admin.compaign_categories.index');
    }

    public function show(Request $request)
    {
        $compaign_category = CompaignCategory::findOrFail($request->id) ;
        return Response()->json(compact('compaign_category'));
    }

    public function get()
    {
        $compaign_categories = CompaignCategory::orderBy('name', 'asc')->get();
        return Response()->json(compact('compaign_categories'));
    }

    public function store(Request $request)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);
            $compaign_category = CompaignCategory::create($validated);
            return response()->json([
                'message' => 'Compaign Category created successfully.',
                'data' => $compaign_category,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function update(Request $request, CompaignCategory $compaign_category)
    {
        try
        {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
            ]);

            $compaign_category->update($validated);
            return response()->json([
                'message' => 'Compaign Category updated successfully.',
                'data' => $compaign_category,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }

    public function destroy(CompaignCategory $compaign_category)
    {
        try
        {
            $compaign_category->delete();
            return response()->json([
                'message' => 'Compaign Category deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }
}
