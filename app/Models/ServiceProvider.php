<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_category_id',
        'zone',
        'country',
        'name',
        'logo',
        'code',
        'status'
    ];

    public function products()
    {
        return $this->hasMany(ServiceProviderProduct::class);
    }

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}
