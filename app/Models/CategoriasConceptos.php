<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CategoriasConceptos extends Model
{
    use SoftDeletes;
    protected $table = 'categoriasnuevo';
    protected $fillable = [
        'id',
        'num',
        'nombre',
    ];
}
