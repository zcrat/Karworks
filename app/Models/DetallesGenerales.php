<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Vehiculo;
use App\Models\Empresa;
use App\Models\Customer;
use App\Models\Contratos;
use App\Models\Modulo;
use App\Models\Sucursales;
use App\Models\Presupuesto;
use App\User;
use App\Models\UsersTaller;
use App\Models\JefeDeProceso;
use App\Models\Trabajador;
use App\Models\RecepcionesVehiculares;
use App\Models\TiposVehiculoConcepto;
use App\Models\ArchivoSalida;
use App\Models\SeguimientoUnidades;
use App\Models\Talleres;
use App\Models\ContratosPerZona;


class DetallesGenerales extends Model
{
    use SoftDeletes;

    protected $table = 'DetallesGenerales';
    protected $with=['Vehiculo','Empresa'];
    protected $fillable = [
        'OrdenServicio','Orden','OrdenSeguimiento', 'Ubicacion', 'Fecha_Esperada',
        'Kilometraje_entrada', 'Gas_entrada', 'Fecha_entrada',
        'Kilometraje_salida', 'Gas_salida', 'Fecha_salida','Diagnostico','PedidoHecho','PedidoEntregado','Vehiculo_id','Tipo_Vehiculo_Concepto_id',
        'User_id','User_update_id', 'Empresa_id', 'Customer_id', 'AdministradorTrasporte_id',
        'JefedeProceso_id', 'Trabajador_id', 'Telefono','contacto','contacto_tel', 'contrato_id','taller_id','has_subcontrato',
        'modulo_id', 'anio', 'zona_id','Indicaciones_cliente'
    ];

    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class, 'DetallesGenerales_id');
    }
    
    public function recepcionesVehiculares()
    {
        return $this->hasMany(RecepcionesVehiculares::class, 'DetallesGenerales_id');
    }
    public function RecepcionVehicular()
    {
        return $this->belongsto(RecepcionesVehiculares::class, 'id','DetallesGenerales_id');
    }
    public function Vehiculo()
    {
        return $this->belongsto(Vehiculo::class, 'Vehiculo_id');
    }
    public function User()
    {
        return $this->belongsto(User::class, 'User_id');
    }
    public function User_update()
    {
        return $this->belongsto(User::class, 'User_update_id');
    }
    public function Empresa()
    {
        return $this->belongsto(Empresa::class, 'Empresa_id');
    }
    public function Customer()
    {
        return $this->belongsto(Customer::class, 'Customer_id');
    }
    public function AdministradorTrasporte()
    {
        return $this->belongsto(UsersTaller::class, 'AdministradorTrasporte_id');
    }
    public function JefedeProceso()
    {
        return $this->belongsto(UsersTaller::class, 'JefedeProceso_id');
    }
    public function Trabajador()
    {
        return $this->belongsto(UsersTaller::class, 'Trabajador_id');
    }
    public function contrato()
    {
        return $this->belongsto(Contratos::class, 'contrato_id');
    }
public function modulo_cortana()
{
    return $this->hasOne(ContratosPerZona::class, 'contrato_id', 'contrato_id')
        ->where('modulo_id', $this->modulo_id)
        ->where('anio', $this->anio)
        ->where('zona_id', $this->zona_id);
}
    public function modulo()
    {
        return $this->belongsto(Modulo::class, 'modulo_id');
    }
    public function zona()
    {
        return $this->belongsto(Sucursales::class, 'zona_id');
    }
    public function tipoVehiculo()
    {
        return $this->belongsto(TiposVehiculoConcepto::class, 'Tipo_Vehiculo_Concepto_id');
    }
    public function ArchivosSalida()
    {
        return $this->hasMany(ArchivoSalida::class, 'Detalles_Generales_Id');
    }

    public function DateDiagnosticoInicio()
    {
        return $this->belongsto(SeguimientoUnidades::class, 'id','orden_id')->where('tipo_id',1);
    }
    public function DateDiagnosticoTerminado()
    {
        return $this->belongsto(SeguimientoUnidades::class, 'id','orden_id')->where('tipo_id',2);
    }
    public function DateTerminado()
    {
        return $this->belongsto(SeguimientoUnidades::class, 'id','orden_id')->where('tipo_id',3);
    }
    public function Taller()
    {
        return $this->belongsto(Talleres::class,'taller_id');
    }
    public function DateVerificado()
    {
        return $this->belongsto(SeguimientoUnidades::class, 'id','orden_id')->where('tipo_id',4);
    }
    public function DateEntregado()
    {
        return $this->belongsto(SeguimientoUnidades::class, 'id','orden_id')->where('tipo_id',5);
    }
    public function Vales()
    {
        return $this->hasMany(ValesAlmacen::class, 'Detalles_Generales_Id');
    }
    public function ValesNoEntregados()
    {
        return $this->hasMany(ValesAlmacen::class, 'Detalles_Generales_Id')->whereNull('fecha_entrega');
    }
    public function ValesNoConfirmados()
    {
        return $this->hasMany(ValesAlmacen::class, 'Detalles_Generales_Id')->whereNotNull('fecha_entrega')->whereNull('fecha_surtido');
    }
    public function ValesPendientes()
    {
        return $this->hasMany(ValesAlmacen::class, 'Detalles_Generales_Id')->whereNotNull('fecha_surtido')->whereHas('ConceptosPendientes');
    }
    public function ValesTerminados()
    {
        return $this->hasMany(ValesAlmacen::class, 'Detalles_Generales_Id')->whereNotNull('fecha_surtido')->whereDoesntHave('ConceptosPendientes');
    }
}
