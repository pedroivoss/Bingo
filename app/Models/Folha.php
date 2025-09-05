<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folha extends Model
{
    protected $fillable = ['festa_id', 'nome_arquivo', 'quantidade_por_arquivo', 'pdf_path', 'primeira_cartela_codigo', 'ultima_cartela_codigo'];
}
