<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Presupuesto;
use App\Models\Conceptos;
class PresupuestoCarrito extends Model
{
    use  SoftDeletes;

    protected $table = 'Presupuesto_Carrito';
    protected $primaryKey = 'id';
    
    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'id',
        'Presupuesto_id',
        'Concepto_id',
        'Cantidad',
        'Costo',
        'Venta',
        'garantia',
        'dictamen',
        'User_id',
        'User_Update_id',
        'created_at',
        'updated_at',
    ];
    
    protected $guarded = ['deleted_at'];
    
    protected $casts = [
        'Costo' => 'double',
        'Venta' => 'double',
        'garantia' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class, 'Presupuesto_id');
    }

    public function DatosConcepto()
    {
        return $this->belongsTo(Conceptos::class, 'Concepto_id');
    }
}