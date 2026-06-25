<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Llantas;
use App\Liquidos;
use App\Bandas;
use App\Seguridad;
use App\Filtros;
use App\Escape;
use App\SuspencionDireccion;
use App\AfinacionMotor;
use App\TrenTransmision;
use App\Frenos;
use App\Electrico;
use App\RevisionLucesEspias;
use App\Mangueras;
use App\Models\DetallesGenerales;

class InspeccionTecnicaVehiculo extends Model
{
    protected $table = 'inspeccion_tecnica_vehiculo';
    protected $fillable = [
        'DetallesGenerales_id',
        'id_llantas',
        'id_liquidos',
        'id_bandas',
        'id_seguridad',
        'id_filtros',
        'id_escape',
        'id_suspencion_direccion',
        'id_afinacion_motor',
        'id_tren_transmision',
        'id_frenos',
        'id_electrico',
        'id_revision_luces_espias',
        'id_mangueras',
        'ante_firma',
        'indicaciones_cliente',
        'user_id',
        'firma1',
        'firma2',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function detallesGenerales(){
        return $this->belongsTo(DetallesGenerales::class, 'DetallesGenerales_id');
    }
    public function llantas()
    {
        return $this->belongsTo(Llantas::class, 'id_llantas');
    }

    public function liquidos()
    {
        return $this->belongsTo(Liquidos::class, 'id_liquidos');
    }

    public function bandas()
    {
        return $this->belongsTo(Bandas::class, 'id_bandas');
    }

    public function seguridad()
    {
        return $this->belongsTo(Seguridad::class, 'id_seguridad');
    }

    public function filtros()
    {
        return $this->belongsTo(Filtros::class, 'id_filtros');
    }

    public function escape()
    {
        return $this->belongsTo(Escape::class, 'id_escape');
    }

    public function suspencionDireccion()
    {
        return $this->belongsTo(SuspencionDireccion::class, 'id_suspencion_direccion');
    }

    public function afinacionMotor()
    {
        return $this->belongsTo(AfinacionMotor::class, 'id_afinacion_motor');
    }

    public function trenTransmision()
    {
        return $this->belongsTo(TrenTransmision::class, 'id_tren_transmision');
    }

    public function frenos()
    {
        return $this->belongsTo(Frenos::class, 'id_frenos');
    }

    public function electrico()
    {
        return $this->belongsTo(Electrico::class, 'id_electrico');
    }

    public function revisionLucesEspias()
    {
        return $this->belongsTo(RevisionLucesEspias::class, 'id_revision_luces_espias');
    }

    public function mangueras()
    {
        return $this->belongsTo(Mangueras::class, 'id_mangueras');
    }

}
