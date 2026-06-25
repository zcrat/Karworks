<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriasSat extends Model
{
    protected $table = 'categorias_sat';
    protected $fillable = [
        'codigo_sat',
        'descripcion',
    ];
}
