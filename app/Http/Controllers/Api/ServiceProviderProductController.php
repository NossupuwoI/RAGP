<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceProviderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceProviderProductController extends Controller
{
    public function index()
    {
        return ServiceProviderProduct::with([
            'serviceProvider',
            'serviceType',
            'serviceCategory'
        ])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_provider_id' => 'required|exists:service_providers,id',
            'service_type_id' => 'required|exists:service_types,id',
            'service_category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string',
            'code' => 'required|string|unique:service_provider_products,code',
            'price' => 'nullable|numeric',
            'commission' => 'nullable|numeric'
        ]);

        $product = ServiceProviderProduct::create($validated);

        Log::info('ServiceProviderProduct created', [
            'id' => $product->id,
            'data' => $validated
        ]);

        return response()->json($product->load(['serviceProvider', 'serviceType', 'serviceCategory']), 201);
    }

    public function show(ServiceProviderProduct $serviceProviderProduct)
    {
        return $serviceProviderProduct->load([
            'serviceProvider',
            'serviceType',
            'serviceCategory'
        ]);
    }

    public function update(Request $request, ServiceProviderProduct $serviceProviderProduct)
    {
        $validated = $request->validate([
            'service_provider_id' => 'sometimes|required|exists:service_providers,id',
            'service_type_id' => 'sometimes|required|exists:service_types,id',
            'service_category_id' => 'sometimes|required|exists:service_categories,id',
            'name' => 'sometimes|required|string',
            'code' => 'sometimes|required|string|unique:service_provider_products,code,' . $serviceProviderProduct->id,
            'price' => 'nullable|numeric',
            'commission' => 'nullable|numeric'
        ]);

        $serviceProviderProduct->update($validated);

        Log::info('ServiceProviderProduct updated', [
            'id' => $serviceProviderProduct->id,
            'data' => $validated
        ]);

        return response()->json($serviceProviderProduct->load(['serviceProvider', 'serviceType', 'serviceCategory']));
    }

    public function destroy(ServiceProviderProduct $serviceProviderProduct)
    {
        $serviceProviderProduct->delete();

        Log::info('ServiceProviderProduct deleted', [
            'id' => $serviceProviderProduct->id
        ]);

        return response()->json(null, 204);
    }
}
