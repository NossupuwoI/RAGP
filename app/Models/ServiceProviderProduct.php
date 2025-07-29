<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceProviderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_provider_id',
        'service_type_id',
        'name',
        'code',
        'price_fixed',
        'price',
        'commission'
    ];

    // Cast les attributs pour que Eloquent les convertisse automatiquement :
    // - 'price' sera manipulé comme un tableau PHP (stocké en JSON en base)
    // - 'price_fixed' sera traité comme un booléen
    protected $casts = [
        'price' => 'array',
        'price_fixed' => 'boolean',
    ];

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }
}
