<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;
    use HasUuids;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'geo_location',
        'address_1',
        'address_2',
        'town',
        'county',
        'postcode',
        'state_code',
        'country_code',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'warehouse_stock', 'warehouse_uuid', 'product_uuid', 'uuid', 'uuid')
            ->withPivot(['quantity', 'threshold'])
            ->withTimestamps();
    }

    public function warehouseStock(): HasMany
    {
        return $this->hasMany(WarehouseStock::class, 'warehouse_uuid', 'uuid');
    }
}
