<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_order_items';

    protected $fillable = [
        'purchase_order_id',
        'warehouse_id',
        'product_name',
        'product_code',
        'qty',
        'unit_price',
        'sub_total',
        'is_active'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
