<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Archivos_Retrasos_RRVV extends Model
{
    use SoftDeletes;
    protected $table ='archivos_retrasos_rrvv';
    protected $fillable =[
        'RecepcionV_id','archivo'
    ];
}
