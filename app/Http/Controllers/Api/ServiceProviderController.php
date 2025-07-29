<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceProviderController extends Controller
{
    
     // Liste des fournisseurs avec filtres, pagination, tri et recherche.
     
    public function index(Request $request)
    {
        $validated = $request->validate([
            'zone' => 'sometimes|string|max:3',
            'country' => 'sometimes|string|size:2',
            'service_category_id' => 'sometimes|exists:service_categories,id',
            'status' => 'sometimes|boolean',
            'search' => 'sometimes|string|max:100',
            'sort_by' => 'sometimes|in:name,created_at',
            'sort_order' => 'sometimes|in:asc,desc',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ]);

        $query = ServiceProvider::with('serviceCategory');

        if (!empty($validated['zone'])) {
            $query->where('zone', $validated['zone']);
        }

        if (!empty($validated['country'])) {
            $query->where('country', $validated['country']);
        }

        if (!empty($validated['service_category_id'])) {
            $query->where('service_category_id', $validated['service_category_id']);
        }

        if (array_key_exists('status', $validated)) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['search'])) {
            $query->where('name', 'like', '%' . $validated['search'] . '%');
        }

        $sortBy = $validated['sort_by'] ?? 'name';
        $sortOrder = $validated['sort_order'] ?? 'asc';
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $validated['per_page'] ?? 15;

        return response()->json($query->paginate($perPage));
    }

    // create
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'zone' => 'required|string|max:3',
            'country' => 'required|string|size:2',
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
            'code' => 'required|string|unique:service_providers,code|max:100',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $serviceProvider = ServiceProvider::create($validated);

        Log::info('ServiceProvider created', [
            'id' => $serviceProvider->id,
            'data' => $validated
        ]);

        return response()->json($serviceProvider, 201);
    }

    //show
    public function show(ServiceProvider $serviceProvider)
    {
        return response()->json($serviceProvider->load('serviceCategory'));
    }

    // update
    public function update(Request $request, ServiceProvider $serviceProvider)
    {
        $validated = $request->validate([
            'zone' => 'sometimes|required|string|max:3',
            'country' => 'sometimes|required|string|size:2',
            'name' => 'sometimes|required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
            'code' => 'sometimes|required|string|max:100|unique:service_providers,code,' . $serviceProvider->id,
            'status' => 'boolean',
            'service_category_id' => 'sometimes|required|exists:service_categories,id',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $serviceProvider->update($validated);

        Log::info('ServiceProvider updated', [
            'id' => $serviceProvider->id,
            'data' => $validated
        ]);

        return response()->json($serviceProvider);
    }

    // delete
    public function destroy(ServiceProvider $serviceProvider)
    {
        $serviceProvider->delete();

        Log::info('ServiceProvider deleted', [
            'id' => $serviceProvider->id,
        ]);

        return response()->json(null, 204);
    }
}
