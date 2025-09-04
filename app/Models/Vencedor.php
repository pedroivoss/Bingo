<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vencedor extends Model
{
    protected $fillable = ['festa_id', 'cartela_id', 'premio_id', 'validado_por', 'status'];

    public function cartela(): BelongsTo
    {
        return $this->belongsTo(Cartela::class);
    }

    public function premio(): BelongsTo
    {
        return $this->belongsTo(Premio::class);
    }
}
