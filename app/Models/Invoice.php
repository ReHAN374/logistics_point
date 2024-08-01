<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'sales_user',
        'invoice_no',
        'invoice_date',
        'customer_name',
        'customer_address',
        'vat_no',
        'vat_amount',
        'grand_total',
        'printed_at',
        'is_active',
        'created_at',
        'updated_at',
    ];

    public function warehouses()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoicesItem::class);
    }

    public function issueNotes()
    {
        return $this->hasMany(IssueNote::class);
    }

    public function hasIssuedItems()
    {
        return $this->issueNotes()->whereHas('items', function ($query) {
            $query->where('issued_qty', '>', 0);
        })->exists();
    }
}
