<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone', 'country', 'name', 'logo', 'code', 'status'
    ];

    public function products()
    {
        return $this->hasMany(ServiceProviderProduct::class);
    }
}
