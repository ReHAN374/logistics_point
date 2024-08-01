<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueNoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'issue_note_id',
        'warehouse_id',
        'stock_no',
        'description',
        'unit_of_measure',
        'order_qty',
        'issued_qty',
        'balance_qty',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public function issueNote()
    {
        return $this->belongsTo(IssueNote::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
