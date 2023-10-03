<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'PIVid',
        'requesterid',
        'PIVname',
        'employee',
        'PIVtype',
        'quantity',
        'tempered',
        'returned',
        'delflag',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
