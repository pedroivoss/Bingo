<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Cartela;

class Festa extends Model
{
    protected $fillable = ['nome', 'data', 'marca_dagua_path', 'coringa_path', 'cabecalho_path', 'rodape_html', 'configs_pdf'];

    protected $casts = [
        'data' => 'date',
        'configs_pdf' => 'array',
    ];

    public function premios(): HasMany
    {
        return $this->hasMany(Premio::class);
    }

    public function cartelas(): HasMany
    {
        return $this->hasMany(Cartela::class);
    }
}
