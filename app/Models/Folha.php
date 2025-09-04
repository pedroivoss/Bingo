<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folha extends Model
{
    protected $fillable = ['festa_id', 'primeira_cartela_codigo', 'quantidade_por_folha', 'pdf_path', 'pagina_inicio', 'pagina_fim'];
}
