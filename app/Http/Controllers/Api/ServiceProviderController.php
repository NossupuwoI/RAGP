<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceProviderController extends Controller
{
    public function index()
    {
        return ServiceProvider::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'zone' => 'required|string',
            'country' => 'required|string',
            'name' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
            'code' => 'required|string|unique:service_providers,code',
            'status' => 'boolean'
        ]);

        // Upload du logo si présent
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $serviceProvider = ServiceProvider::create($validated);

        Log::info('ServiceProvider created', [
            'id' => $serviceProvider->id,
            'data' => $validated
        ]);

        return response()->json($serviceProvider, 201);
    }

    public function show(ServiceProvider $serviceProvider)
    {
        return $serviceProvider;
    }

    public function update(Request $request, ServiceProvider $serviceProvider)
    {
        $validated = $request->validate([
            'zone' => 'sometimes|required|string',
            'country' => 'sometimes|required|string',
            'name' => 'sometimes|required|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
            'code' => 'sometimes|required|string|unique:service_providers,code,' . $serviceProvider->id,
            'status' => 'boolean'
        ]);

        // Upload du logo si présent
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $serviceProvider->update($validated);

        Log::info('ServiceProvider updated', [
            'id' => $serviceProvider->id,
            'data' => $validated
        ]);

        return response()->json($serviceProvider);
    }

    public function destroy(ServiceProvider $serviceProvider)
    {
        $serviceProvider->delete();

        Log::info('ServiceProvider deleted', [
            'id' => $serviceProvider->id,
        ]);

        return response()->json(null, 204);
    }
}
