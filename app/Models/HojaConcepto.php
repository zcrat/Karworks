<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Presupuesto;
class HojaConcepto extends Model
{
    protected $table = 'hojaconceptosbeta';
    protected $fillable = [
        'presupuesto_id',
        'fecha',
        'remplazo',
        'reparar',
        'clave',
        'descripcion',
        'partes',
        'manoobra',
        'subcontratados',
        'otros',
        'venta',
        'iva',
        'porcentaje_utilidad'
    ];
    protected $casts = [
        'reparar' => 'decimal:2',
        'remplazo' => 'decimal:2',
    ];
    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class, 'presupuesto_id');
    }
}
