<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        return ServiceCategory::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $category = ServiceCategory::create($validated);

        return response()->json($category, 201);
    }

    public function show(ServiceCategory $serviceCategory)
    {
        return $serviceCategory;
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $serviceCategory->update($validated);

        return $serviceCategory;
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        $serviceCategory->delete();

        return response()->json(['message' => 'Supprimé avec succès']);
    }
}
