<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'warehouse_id', 'product_code', 'product_name', 'product_unit', 'product_unit_price', 'stock_available', 'created_at', 'updated_at'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
