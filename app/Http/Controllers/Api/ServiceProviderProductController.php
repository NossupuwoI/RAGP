<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceProviderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceProviderProductController extends Controller
{
    // Liste avec filtres optionnels
    public function index(Request $request)
    {
        $request->validate([
            'zone' => 'nullable|string|max:3',
            'country' => 'nullable|string|size:2',
            'service_type_id' => 'nullable|integer|exists:service_types,id',
        ]);

        $query = ServiceProviderProduct::with(['serviceProvider', 'serviceType']);

        if ($request->filled('zone')) {
            $query->whereHas('serviceProvider', function ($q) use ($request) {
                $q->where('zone', $request->zone);
            });
        }

        if ($request->filled('country')) {
            $query->whereHas('serviceProvider', function ($q) use ($request) {
                $q->where('country', $request->country);
            });
        }

        if ($request->filled('service_type_id')) {
            $query->where('service_type_id', $request->service_type_id);
        }

        return response()->json($query->get());
    }


    // Création
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_provider_id' => 'required|exists:service_providers,id',
            'service_type_id' => 'required|exists:service_types,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:service_provider_products,code',
            'price_fixed' => 'required|boolean',
            'price' => 'required|array',
            'price.min' => 'nullable|numeric',
            'price.max' => 'nullable|numeric',
            'price.amount' => 'nullable|numeric',
            'commission' => 'nullable|numeric|min:0',
        ]);

        // Optionnel : validations personnalisées sur le price
        if ($validated['price_fixed'] === false) {
            if (!isset($validated['price']['min']) || !isset($validated['price']['max'])) {
                return response()->json([
                    'message' => 'Le prix minimum et maximum doivent être renseignés si le prix n’est pas fixe.'
                ], 422);
            }
            if ($validated['price']['min'] > $validated['price']['max']) {
                return response()->json([
                    'message' => 'Le prix minimum ne peut pas être supérieur au prix maximum.'
                ], 422);
            }
        } elseif ($validated['price_fixed'] === true && !isset($validated['price']['amount'])) {
            return response()->json([
                'message' => 'Le prix fixe doit avoir un montant défini.'
            ], 422);
        }

        $validated['price'] = json_encode($validated['price']);

        $product = ServiceProviderProduct::create($validated);

        Log::info('ServiceProviderProduct created', ['id' => $product->id, 'data' => $validated]);

        return response()->json($product->load(['serviceProvider', 'serviceType']), 201);
    }

    // Détail
    public function show(ServiceProviderProduct $serviceProviderProduct)
    {
        return response()->json($serviceProviderProduct->load(['serviceProvider', 'serviceType']));
    }

    // Mise à jour partielle ou totale
    public function update(Request $request, ServiceProviderProduct $serviceProviderProduct)
    {
        $validated = $request->validate([
            'service_provider_id' => 'sometimes|required|exists:service_providers,id',
            'service_type_id' => 'sometimes|required|exists:service_types,id',
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:255|unique:service_provider_products,code,' . $serviceProviderProduct->id,
            'price_fixed' => 'sometimes|required|boolean',
            'price' => 'sometimes|required|array',
            'price.min' => 'nullable|numeric',
            'price.max' => 'nullable|numeric',
            'price.amount' => 'nullable|numeric',
            'commission' => 'nullable|numeric|min:0',
        ]);

        if (isset($validated['price'])) {
            // Même validation personnalisée que pour store
            if (($validated['price_fixed'] ?? $serviceProviderProduct->price_fixed) === false) {
                if (!isset($validated['price']['min']) || !isset($validated['price']['max'])) {
                    return response()->json([
                        'message' => 'Le prix minimum et maximum doivent être renseignés si le prix n’est pas fixe.'
                    ], 422);
                }
                if ($validated['price']['min'] > $validated['price']['max']) {
                    return response()->json([
                        'message' => 'Le prix minimum ne peut pas être supérieur au prix maximum.'
                    ], 422);
                }
            } elseif (($validated['price_fixed'] ?? $serviceProviderProduct->price_fixed) === true && !isset($validated['price']['amount'])) {
                return response()->json([
                    'message' => 'Le prix fixe doit avoir un montant défini.'
                ], 422);
            }

            $validated['price'] = json_encode($validated['price']);
        }

        $serviceProviderProduct->update($validated);

        Log::info('ServiceProviderProduct updated', ['id' => $serviceProviderProduct->id, 'data' => $validated]);

        return response()->json($serviceProviderProduct->load(['serviceProvider', 'serviceType']));
    }

    // Suppression
    public function destroy(ServiceProviderProduct $serviceProviderProduct)
    {
        $serviceProviderProduct->delete();

        Log::info('ServiceProviderProduct deleted', ['id' => $serviceProviderProduct->id]);

        return response()->json(null, 204);
    }
}
