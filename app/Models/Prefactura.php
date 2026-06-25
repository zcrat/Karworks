<?php

namespace App\Models;
use App\Models\Modulos;
use App\Models\Contratos;
use App\Models\Empresa;
use App\Models\Sucursales;
use App\user;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prefactura extends Model
{
    use  SoftDeletes;

    protected $table = 'prefacturas';

    protected $fillable = [
        'facturada',
        'factura_id',
        'anio',
        'modulo_id',
        'zona_id',
        'contrato_id',
        'empresa_id',
        'user_id',
        'fpago',
        'moneda',
        'mpago',
        'tipo_comprobante',
        'tipo_impuesto_local',
        'uso_cfdi',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function cliente()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
    public function zona()
    {
        return $this->belongsTo(Sucursales::class, 'zona_id');
    }
    public function contrato()
    {
        return $this->belongsTo(Contratos::class, 'contrato_id');
    }
    public function modulo()
    {
        return $this->belongsTo(Modulos::class, 'modulo_id');
    }
  
}
