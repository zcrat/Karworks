<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CategoriasDisponibles extends Model
{
    use SoftDeletes;
    protected $table = 'categorias_disponibles';
    protected $fillable = [
        'categoria_id',
        'tipo_id',
    ];
}
