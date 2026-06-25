<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoUnidades extends Model
{
    protected $table = 'segumiento_unidades';
    protected $fillable = [
        'orden_id','fecha','tipo_id','user_id'
    ];
}
