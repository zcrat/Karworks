<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\presupuesto;
use Illuminate\Database\Eloquent\SoftDeletes;
class MensajesPresupuesto extends Model
{
    use SoftDeletes;
    protected $table = 'mensajespresupuesto';
    protected $fillable = [
        'id',
        'user_id',
        'presupuesto_id',
        'mensaje',
        'read_at',
    ];
    function usuarios(){
        return $this->belongsTo(User::class, 'user_id','id'); 
    }
    function presupuesto(){
        return $this->belongsTo(Presupuesto::class, 'presupuesto_id','id'); 
    }
}
