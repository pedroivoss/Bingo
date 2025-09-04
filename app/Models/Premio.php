<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Premio extends Model
{
    protected $fillable = ['festa_id', 'titulo', 'ordem', 'descricao'];

    public function festa(): BelongsTo
    {
        return $this->belongsTo(Festa::class);
    }
}
