<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'code', 'name', 'is_active', 'created_at', 'updated_at'
    ];

    public function issueNotes()
    {
        return $this->hasMany(IssueNote::class, 'warehouse');
    }
}
