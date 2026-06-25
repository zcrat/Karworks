<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
class ValesAlmacen extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $table = 'vales_almacen';
    protected $fillable = [
        'Detalles_Generales_Id',
        'num',
        'Destino',
        'Tipo_Motor',
        'user_id_created',
        'user_id_deleted',
        'created_at',
        'deleted_at',
        'fecha_entrega',
        'fecha_surtido',
        'status'
    ];
    protected $casts = [
        'fecha_surtido'=>'datetime:Y-m-d H:i:s',
        'fecha_entrega'=>'datetime:Y-m-d H:i:s',
    ];

    public function detallesGenerales()
    {
        return $this->belongsTo(DetallesGenerales::class, 'Detalles_Generales_Id');
    }
    public function Autorizado()
    {
        return $this->belongsTo(User::class, 'user_id_created');
    }
    public function userDeleted()
    {
        return $this->belongsTo(User::class, 'user_id_deleted');
    }
    public function Conceptos()
    {
        return $this->hasMany(ConceptoVale::class, 'Vale_Almacen_id', 'id');
    }
    public function ConceptosPendientes()
    {
        return $this->hasMany(ConceptoVale::class, 'Vale_Almacen_id', 'id')->whereNull('entregado_at');
    }
    public function ConceptosEntregados()
    {
        return $this->hasMany(ConceptoVale::class, 'Vale_Almacen_id', 'id')->whereNotNull('entregado_at');
    }
    protected function serializeDate(\DateTimeInterface $date)
{
    return $date->format('Y-m-d H:i:s');
}
}
