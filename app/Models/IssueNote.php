<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'invoice_id',
        'issue_note_no',
        'customer_name',
        'created_by',
        'issued_by',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'issued_by' => 'array',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Accessor for formatted created_at field
    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->format('d-m-Y H:i:s');
    }

    public function items()
    {
        return $this->hasMany(IssueNoteItem::class);
    }

    public function hasIssuedItems()
    {
        return $this->items()->where('issued_qty', '>', 0)->exists();
    }
}
