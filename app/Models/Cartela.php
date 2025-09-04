<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Vencedor;

class Cartela extends Model
{
    protected $fillable = ['festa_id', 'codigo', 'numeros', 'hash_integridade'];

    protected $casts = [
        'numeros' => 'array',
    ];

    public function festa(): BelongsTo
    {
        return $this->belongsTo(Festa::class);
    }

    public function vencedores(): HasMany
    {
        return $this->hasMany(Vencedor::class);
    }
}
