<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Products extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'barcode',
        'comments',
        'amount',
        'usedamount',
        'doi',        
        'dom',
        'mne',
        'mnm',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
