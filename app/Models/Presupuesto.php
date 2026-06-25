<?php

namespace App\Models;
use App\Models\MensajesPresupuesto;
use App\Models\PresupuestoCarrito;
use App\Models\StatusModel;
use App\Models\HojaConcepto;
use App\user;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presupuesto extends Model
{
    use  SoftDeletes;

    protected $table = 'PresupuestosNuevos';

    protected $fillable = ['id',
        'DetallesGenerales_id', 'Observaciones','Mano_Obra_Descripcion','Garantia', 'Folio','FechaDeVigencia', 
        'Factura_id', 'Tipo_id', 'Status_id','User_update_id','Fecha_Pagado','Importe_Pagado'
    ];

    public function detallesGenerales()
    {
        return $this->belongsTo(DetallesGenerales::class, 'DetallesGenerales_id');
    }
    public function user()
    {
        return $this->belongsTo(user::class, 'User_update_id');
    }
    public function mensajes() {
        return $this->hasMany(MensajesPresupuesto::class, 'presupuesto_id');
    }
    public function mensajesNoLeidos()
    {
        return $this->hasMany(MensajesPresupuesto::class, 'presupuesto_id')
                    ->whereNull('read_at');
    }

    public function HojaConceptos() {
        return $this->hasMany(HojaConcepto::class, 'presupuesto_id', 'id')->orderBy('id', 'ASC');
    }
    public function conceptos() {
        return $this->hasMany(PresupuestoCarrito::class, 'Presupuesto_id')->orderBy('id', 'ASC');
    }
    public function pagos() {
        return $this->hasMany(PagosPresupuestos::class, 'presupuesto_id')->orderBy('id', 'ASC');
    }
    public function estatus() {
        return $this->belongsTo(StatusModel::class, 'Status_id')->orderBy('id', 'ASC');
    }
    public function user_restringido() {
        return $this->hasOne(PresupuestosRestringidos::class, 'presupuesto_id');
    }
    public function archivos()
    {
        return $this->hasMany(ArchivosPresupuesto::class, 'Presupuesto_id');
    }
    public function archivossemaforo()
    {
        $tipos = [3,4,7,8,9];

        return $this->hasMany(ArchivosPresupuesto::class, 'Presupuesto_id')
            ->whereIn('Tipo_archivo_id', $tipos)
            ->selectRaw('MIN(id) as id, Tipo_archivo_id, Presupuesto_id') // o el campo que quieras
            ->groupBy('Tipo_archivo_id', 'Presupuesto_id');
    }

}
