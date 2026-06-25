<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallesPrefacturas extends Model
{
    use SoftDeletes;
    protected $table = 'detalles_prefacturas';
    protected $fillable = [
        'presupuesto_id',
        'prefactura_id'
    ];
}
