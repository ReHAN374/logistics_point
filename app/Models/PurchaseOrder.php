<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';

    protected $fillable = [
        'user_id',
        'purchase_order_no',
        'purchase_date',
        'supplier_name',
        'supplier_address',
        'grand_total',
        'is_active'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'purchase_date'
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
