<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicesItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'warehouse_id',
        'invoice_id',
        'product_name',
        'product_code',
        'qty',
        'unit_price',
        'value',
        'sub_total',
        'is_active',
        'created_at',
        'updated_at',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
