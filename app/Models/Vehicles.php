<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicles extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'targa',
        'comments',
        'used',
        'doi',
        'dom',
        'username',
        'mne',
        'mnm',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
