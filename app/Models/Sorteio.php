<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sorteio extends Model
{
    protected $fillable = ['festa_id', 'numero', 'letra', 'ordem'];

    public function festa(): BelongsTo
    {
        return $this->belongsTo(Festa::class);
    }
}
