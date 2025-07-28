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
        'service_category_id',
        'name',
        'code',
        'price',
        'commission'
    ];

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}
