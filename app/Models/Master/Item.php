<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    public function uom()
    {
        return $this->hasOne(Uom::class, 'id', 'uom_id');
    }
    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }
    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }
}
