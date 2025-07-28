<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Import pour les logs

class ServiceTypeController extends Controller
{
    public function index()
    {
        return ServiceType::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $serviceType = ServiceType::create($validated);

        // Log info à la création d'un type de service
        Log::info('ServiceType created', [
            'id' => $serviceType->id,
            'data' => $validated
        ]);

        return response()->json($serviceType, 201);
    }

    public function show(ServiceType $serviceType)
    {
        return $serviceType;
    }

    public function update(Request $request, ServiceType $serviceType)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        $serviceType->update($validated);

        // Log info à la mise à jour d'un type de service
        Log::info('ServiceType updated', [
            'id' => $serviceType->id,
            'data' => $validated
        ]);

        return response()->json($serviceType);
    }

    public function destroy(ServiceType $serviceType)
    {
        $serviceType->delete();

        // Log info à la suppression d'un type de service
        Log::info('ServiceType deleted', [
            'id' => $serviceType->id,
        ]);

        return response()->json(null, 204);
    }
}
