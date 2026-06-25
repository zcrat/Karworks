<?php

namespace App\Http\Controllers\zcrat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UnidadSatModel;
use App\Models\CodigoSat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\LOG;
use Illuminate\Support\Facades\Storage;
//eliminar despues de migrar
use App\CondicionesPintura as CondicionesPinturaantes;
use App\EquipoInventario as EquipoInventarioantes;
use App\ExterioresEquipo as ExterioresEquipoantes;
use App\InterioresEquipo as InterioresEquipoantes;
use App\Models\CondicionesPintura;
use App\Models\EquipoInventario;
use App\Models\ExterioresEquipo;
use App\Models\InterioresEquipo;

use App\Models\PresupuestoCarrito;
use App\pCFECarrito;
use App\Models\RecepcionesVehiculares;
use App\Models\RecepcionVehicular;
use App\Models\UsersTaller;
use App\Models\Presupuesto;
use App\presupuestosCFE;
use App\Models\Sucursales;
use App\Models\DetallesGenerales;
use App\Models\Color;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Vehiculo;
use App\Models\Tecnicos;
class SatController extends Controller
{
    public function GetCodeUnidad(Request $request){
        if($request->has('id')){
            $Unidad=UnidadSatModel::find($request->id);
            if(!empty($Unidad)){
                return response()->json(["Code"=>$Unidad->clave]);
            }
            return response()->json(["error"=>'No Se Pudo Obtener Los Datos del Vehiculo'],404);
        }
        return response()->json(["error"=>'No SE Enviaron los datos Necesarios'],422);
    
    }
    public function GetCodeSat(Request $request){
        if($request->has('id')){
            $Sat=CodigoSat::findorfail($request->input("id"));
            $Sat=CodigoSat::find($request->id);
            if(!empty($Sat)){
                return response()->json(["Code"=>$Sat->code]);
            }
            return response()->json(["error"=>'No Se Pudo Obtener Los Datos del Vehiculo'],404);
        }
        return response()->json(["error"=>'No SE Enviaron los datos Necesarios'],422);
    }
    public function migraterecepcionesCFE(Request $request){
        try {
            DB::beginTransaction();
           
            $modulos=[1=>[2,3,4,5,6],2=>[2,3,5,9]];
            $contratosdisel =['2'=>11,'3'=>10,'5'=>12,'9'=>9];
            foreach ($modulos as $modulo => $sucursales) {
                foreach ($sucursales as $sucursal) {
                    $contrato=$modulo == 1 ? Sucursales::findorfail($sucursal)->contratos_id : $contratosdisel[$sucursal]??100; 
                    $zonarecepcion=Sucursales::findorfail($sucursal);
                    $elementostotales = presupuestosCFE::withCount('mensajes')->join('pCFEVehiculos','presupuestosCFE.pCFEVehiculos_id','=','pCFEVehiculos.id')
                        ->join('pCFEGenerales','presupuestosCFE.pCFEGenerales_id','=','pCFEGenerales.id')
                        ->join('users','presupuestosCFE.user_id','=','users.id')
                        ->join('sucursales','users.sucursal_id','=','sucursales.id')
                        ->join('contratos','sucursales.contratos_id','=','contratos.id')
                        ->select(
                            'pCFEGenerales.id as pCFEGenerales_id',
                            'pCFEGenerales.NSolicitud',
                            'pCFEGenerales.OrdenServicio',
                            'pCFEGenerales.KmDeIngreso',
                            'pCFEGenerales.ClienteYRazonSocial',
                            'pCFEGenerales.Mail',
                            'pCFEGenerales.Telefono',
                            'pCFEGenerales.Conductor',
                            'pCFEGenerales.Fecha as FechaIngreso',
                            'pCFEVehiculos.identificador',
                            'pCFEVehiculos.modelo',
                            'pCFEVehiculos.vin',
                            'pCFEVehiculos.placas',
                            'pCFEVehiculos.ano',
                            'pCFEVehiculos.marca',
                            'presupuestosCFE.id',
                            'presupuestosCFE.descripcionMO',
                            'presupuestosCFE.fechaDeVigencia',
                            'presupuestosCFE.observaciones',
                            'presupuestosCFE.user_id',
                            'presupuestosCFE.factura_id',
                            'presupuestosCFE.status',
                            'presupuestosCFE.ubicacion',
                            'presupuestosCFE.tdeentrega as descripciongeneral',
                            'presupuestosCFE.created_at',
                            'presupuestosCFE.updated_at')
                        ->where('presupuestosCFE.id_anio_correspondiente',3)
                        ->where('presupuestosCFE.CFE_id',$sucursal)
                        ->where("presupuestosCFE.modulo",$modulo)
                        ->orderBy('presupuestosCFE.id', 'asc')->get();
                
                    foreach ($elementostotales as $element) {
                        $vehiculo=Vehiculo::where('no_economico',$element->identificador)->first();
                        // $detalles = DetallesGenerales::where('OrdenServicio',$element->OrdenServicio)->orwhere('OrdenServicio',$element->NSolicitud)->orwhere('OrdenSeguimiento',$element->OrdenServicio)->orwhere('OrdenSeguimiento',$element->NSolicitud)->first();
                        $cadena = $element->NSolicitud;
                        if (preg_match('/\d/', $cadena)) {
                            $ultimo_caracter = substr($cadena, -1);
                            while (!ctype_digit($ultimo_caracter)) {
                                $cadena = substr($cadena, 0, -1);
                                $ultimo_caracter = substr($cadena, -1);
                            }
                        }
                        LOG::info('cadena: '.$cadena);
                        $detalles = DetallesGenerales::where('OrdenServicio','LIKE',$cadena.'%')->where('zona_id',$sucursal)->where('contrato_id',$contrato)->first();
                        
                        if(!isset($detalles)){
                            $recepcionVehicular = RecepcionVehicular::where('id_anio_correspondiente',3)->where('sucursal_id',$sucursal)->where('modulo',$modulo)->where('folioNum','LIKE',$cadena.'%')->first();

                            $admintras=UsersTaller::where('nombre','LIKE','%'.$element->ClienteYRazonSocial.'%')->WHERE('tipo_user_taller_id',1)->first();
                            $jefproc=UsersTaller::where('nombre','LIKE','%'.$element->Mail.'%')->WHERE('tipo_user_taller_id',2)->first();
                            $tecnico=Tecnicos::where('nombre','LIKE','%'.($recepcionVehicular ? $recepcionVehicular->tecnico ?? 'Sin Asignar':'Sin Asignar').'%')->first();
                            $trabajador=UsersTaller::where('nombre','LIKE','%'.$element->Conductor.'%')->WHERE('tipo_user_taller_id',3)->first();

                            if (!isset($vehiculo)) {
                                
                                // Buscar o crear una marca utilizando LIKE
                                $marca = Marca::where('nombre', 'LIKE', '%' . $element->marca . '%')->first();
                                if (!$marca) {
                                    $marca = Marca::create(['nombre' => $element->marca]);
                                }

                                // Buscar o crear un modelo utilizando LIKE
                                $modelo = Modelo::where('nombre', 'LIKE', '%' . $element->modelo . '%')
                                                ->where('marca_id', $marca->id)
                                                ->first();
                                if (!$modelo) {
                                    $modelo = Modelo::create(['nombre' => $element->modelo, 'marca_id' => $marca->id]);
                                }

                                // Buscar o crear un color utilizando LIKE
                                $color = Color::where('nombre', 'LIKE', '%' . $element->color . '%')->first();
                                if (!$color) {
                                    $color = Color::create(['nombre' => $element->color]);
                                }
                                
                                $vehiculo = new Vehiculo();
                                $vehiculo->tipo_id = 1;
                                $vehiculo->color_id = $color->id;
                                $vehiculo->marca_id =$marca->id;
                                $vehiculo->modelo_id = $modelo->id;
                                $vehiculo->placas = $element->placas;
                                $vehiculo->anio = $element->ano;
                                $vehiculo->no_economico = $element->identificador;
                                $vehiculo->vim = $element->vin;
                                $vehiculo->save();
                            }
                            if(!$admintras){
                                $admintras = new UsersTaller();
                                $admintras->nombre = $element->ClienteYRazonSocial;
                                $admintras->tipo_user_taller_id = 1;
                                $admintras->save();
                            }
                            if(!$jefproc){
                                $jefproc = new UsersTaller();
                                $jefproc->nombre = $element->Mail;
                                $jefproc->tipo_user_taller_id = 2;
                                $jefproc->save();
                            }
                            if(!$tecnico){
                                $tecnico = new Tecnicos();
                                $tecnico->nombre = $recepcionVehicular ? $recepcionVehicular->tecnico ?? 'Sin Asignar':'Sin Asignar';
                                $tecnico->save();
                            }
                            if(!$trabajador){
                                $trabajador = new UsersTaller();
                                $trabajador->nombre = $element->Conductor;
                                $trabajador->tipo_user_taller_id = 3;
                                $trabajador->save();
                            }
                        
                            $detalles = new DetallesGenerales();
                            $detalles->OrdenServicio = $cadena;
                            $detalles->OrdenSeguimiento = $element->OrdenServicio;
                            $detalles->Ubicacion = strtolower($element->ubicacion) == 'ubicacion' ? strtoupper($zonarecepcion->nombre) : strtoupper($element->ubicacion);
                            $detalles->Fecha_Esperada = $recepcionVehicular ? $recepcionVehicular->fecha_compromiso : $element->FechaIngreso;
                            $detalles->Kilometraje_entrada = $element->KmDeIngreso;
                            $detalles->Gas_entrada = $recepcionVehicular ? $recepcionVehicular->gas_entrada : 0;
                            $detalles->Fecha_entrada = $element->FechaIngreso;
                            $detalles->Kilometraje_salida = $recepcionVehicular ? $recepcionVehicular->km_salida : null;
                            $detalles->Gas_salida = $recepcionVehicular ? $recepcionVehicular->gas_salida : null;
                            $detalles->Fecha_salida =$recepcionVehicular ? $recepcionVehicular->fecha_entrega : null;
                            $detalles->Vehiculo_id = $vehiculo->id;
                            $detalles->Tipo_Vehiculo_Concepto_id = 8;
                            $detalles->User_id = $element->user_id;
                            $detalles->User_update_id = $element->user_id;
                            $detalles->Empresa_id = 4;
                            $detalles->Customer_id = $recepcionVehicular ? $recepcionVehicular->customer_id :400;
                            $detalles->AdministradorTrasporte_id = $admintras->id;
                            $detalles->JefedeProceso_id = $jefproc->id;
                            $detalles->Trabajador_id = $trabajador->id;
                            $detalles->Telefono = $element->Telefono;
                            $detalles->contrato_id =$contrato;
                            $detalles->Indicaciones_cliente = $recepcionVehicular? $recepcionVehicular->indicaciones_del_cliente??'Sin Indicaciones Por Parte Del Cliente' : $element->descripciongeneral ?? 'Sin Indicaciones Por Parte Del Cliente';
                            $detalles->modulo_id =3 ;
                            $detalles->anio = 2025;
                            $detalles->zona_id = $sucursal;
                            $detalles->created_at = $element->created_at;
                            $detalles->updated_at = $element->updated_at;
                            $detalles->save();

                            $recepcion = new RecepcionesVehiculares();
                            $ExterioresEquipo = new ExterioresEquipo();
                            $CondicionesPintura = new CondicionesPintura();
                            $EquipoInventario = new EquipoInventario();
                            $InterioresEquipo = new InterioresEquipo();
                            if(isset($recepcionVehicular)){
                                if(!RecepcionesVehiculares::where('id', $recepcionVehicular->id)->exists()){
                                    LOG::info('Recepcion vehicular existe'. $cadena);
                                    $origen = 'public/carror/'.$recepcionVehicular->carro; // Ruta de la imagen original
                                    $origen2 = 'public/firmas/'.$recepcionVehicular->firma;
                                    $extension = 'png'; 
                                    $fileName = uniqid() . '.' . $extension;
                                    $destino = 'public/carros/'.$fileName;
                                    $destino2 = 'public/firmastaller/'.$fileName;
                                    // Copiar archivo
                                    if (Storage::exists($origen)) {
                                        Storage::copy($origen, $destino);
                                    }
                                    if (Storage::exists($origen2)) {
                                        Storage::copy($origen2, $destino2);
                                    }
                                    $recepcion->id = $recepcionVehicular->id;
                                    $recepcion->DetallesGenerales_id = $detalles->id; 
                                    $recepcion->Notas = $element->descripcionMO;
                                    $recepcion->Tecnico_id = $tecnico->id; ;
                                    $recepcion->Firma = $fileName; 
                                    $recepcion->Carro = $fileName;
                                    $recepcion->created_at = $element->created_at;
                                    $recepcion->updated_at = $element->updated_at;
                                    $recepcion->save();

                                    $ExterioresEquipo2 = ExterioresEquipoantes::where('recepcion_vehicular_id',$recepcionVehicular->id)->first();
                                    $ExterioresEquipo->RecepcionVehicular_id= $recepcion->id;
                                    if(isset($ExterioresEquipo2)){
                                        $data = $ExterioresEquipo2->toArray();
                                        unset($data['id'], $data['recepcion_vehicular_id']);
                                        $ExterioresEquipo->fill($data);
                                    }else{
                                        $ExterioresEquipo2 = ExterioresEquipoantes::first();
                                        $data = collect($ExterioresEquipo2->toArray())->except(['id', 'recepcion_vehicular_id']);
                                        foreach ($data as $key => $value) {
                                            $ExterioresEquipo->$key = 3;
                                        }
                                    }
                                    $ExterioresEquipo->save();

                                    $InterioresEquipo2 = InterioresEquipoantes::where('recepcion_vehicular_id',$recepcionVehicular->id)->first();
                                    $InterioresEquipo->RecepcionVehicular_id= $recepcion->id;
                                    if(isset($InterioresEquipo2)){
                                        $data = $InterioresEquipo2->toArray();
                                        unset($data['id'], $data['recepcion_vehicular_id']);
                                        $InterioresEquipo->fill($data);
                                    }else{
                                        $InterioresEquipo2 = InterioresEquipoantes::first();
                                        $data = collect($InterioresEquipo2->toArray())->except(['id', 'recepcion_vehicular_id']);
                                        foreach ($data as $key => $value) {
                                            $InterioresEquipo->$key = 3;
                                        }
                                    }
                                    $InterioresEquipo->save();

                                    $CondicionesPintura2 = CondicionesPinturaantes::where('recepcion_vehicular_id',$recepcionVehicular->id)->first();
                                    $CondicionesPintura->RecepcionVehicular_id= $recepcion->id;
                                    if(isset($CondicionesPintura2)){
                                        $data = $CondicionesPintura2->toArray();
                                        unset($data['id'], $data['recepcion_vehicular_id']);
                                        $CondicionesPintura->fill($data);
                                    }else{
                                        $CondicionesPintura2 = CondicionesPinturaantes::first();
                                        $data = collect($CondicionesPintura2->toArray())->except(['id', 'recepcion_vehicular_id']);
                                        foreach ($data as $key => $value) {
                                            $CondicionesPintura->$key = 0;
                                        }
                                    }
                                    $CondicionesPintura->save();
                                    
                                    $EquipoInventario2 = EquipoInventarioantes::where('recepcion_vehicular_id',$recepcionVehicular->id)->first();
                                    $EquipoInventario->RecepcionVehicular_id= $recepcion->id;
                                    if(isset($EquipoInventario2)){
                                        $data = $EquipoInventario2->toArray();
                                        unset($data['id'], $data['recepcion_vehicular_id']);
                                        $EquipoInventario->fill($data);
                                    }else{
                                        $EquipoInventario2 = EquipoInventarioantes::first();
                                        $data = collect($EquipoInventario2->toArray())->except(['id', 'recepcion_vehicular_id']);
                                        foreach ($data as $key => $value) {
                                            $EquipoInventario->$key = 0;
                                        }
                                    }
                                    $EquipoInventario->save();
                                }else{
                                    Log::info('Recepcion vehicular existe'. $recepcionVehicular->id);
                                }
                            }else{
                                LOG::info('Recepcion vehicular No existe'. $cadena);
                                $origen = 'public/tiposauto/Vehiculo1.png'; 
                                $origen2 = 'public/firmas/67a2a6d68b68c.png';
                                $extension = 'png'; 
                                $fileName = uniqid() . '.' . $extension;
                                $destino = 'public/carros/'.$fileName;
                                $destino2 = 'public/firmastaller/'.$fileName;
                                // Copiar archivo
                                if (Storage::exists($origen)) {
                                    Storage::copy($origen, $destino);
                                }
                                if (Storage::exists($origen2)) {
                                    Storage::copy($origen2, $destino2);
                                }
                                $recepcion->DetallesGenerales_id = $detalles->id; 
                                $recepcion->Notas = $element->descripcionMO;
                                $recepcion->Tecnico_id = $tecnico->id; ;
                                $recepcion->Firma = $fileName; 
                                $recepcion->Carro = $fileName;
                                $recepcion->created_at = $element->created_at;
                                $recepcion->updated_at = $element->updated_at;
                                $recepcion->save();

                                $ExterioresEquipo->RecepcionVehicular_id= $recepcion->id;
                                $ExterioresEquipo->antena_radio = 3;
                                $ExterioresEquipo->antena_telefono = 3;
                                $ExterioresEquipo->antena_cb = 3;
                                $ExterioresEquipo->estribos = 3;
                                $ExterioresEquipo->espejos_laterales = 3;
                                $ExterioresEquipo->guardafangos = 3;
                                $ExterioresEquipo->parabrisas = 3;
                                $ExterioresEquipo->sistema_alarma = 3;
                                $ExterioresEquipo->limpia_parabrisas = 3;
                                $ExterioresEquipo->luces_exteriores = 3;
                                $ExterioresEquipo->save();


                                $EquipoInventario->RecepcionVehicular_id= $recepcion->id;
                                $EquipoInventario->llanta = 0;
                                $EquipoInventario->cubreruedas = 0;
                                $EquipoInventario->cables_corriente = 0;
                                $EquipoInventario->candado_ruedas = 0;
                                $EquipoInventario->estuche_herramientas = 0;
                                $EquipoInventario->gato = 0;
                                $EquipoInventario->llave_tuercas =0;
                                $EquipoInventario->tarjeta_circulacion = 0;
                                $EquipoInventario->triangulo_seguridad = 0;
                                $EquipoInventario->extinguidor = 0;
                                $EquipoInventario->placas = 0;
                                $EquipoInventario->save();


                                $InterioresEquipo->RecepcionVehicular_id= $recepcion->id;
                                $InterioresEquipo->puerta_interior_frontal = 3;
                                $InterioresEquipo->puerta_interior_trasera = 3;
                                $InterioresEquipo->puerta_delantera_frontal = 3;
                                $InterioresEquipo->puerta_delantera_trasera = 3;
                                $InterioresEquipo->asiento_interior_frontal = 3;
                                $InterioresEquipo->asiento_interior_trasera = 3;
                                $InterioresEquipo->asiento_delantera_frontal = 3;
                                $InterioresEquipo->asiento_delantera_trasera = 3;
                                $InterioresEquipo->consola_central = 3;
                                $InterioresEquipo->claxon = 3;
                                $InterioresEquipo->tablero = 3;
                                $InterioresEquipo->quemacocos = 3;
                                $InterioresEquipo->toldo = 3;
                                $InterioresEquipo->elevadores_eletricos =3;
                                $InterioresEquipo->luces_interiores = 3;
                                $InterioresEquipo->seguros_eletricos = 3;
                                $InterioresEquipo->tapetes = 3;
                                $InterioresEquipo->climatizador = 3;
                                $InterioresEquipo->radio = 3;
                                $InterioresEquipo->espejos_retrovizor =3;
                                $InterioresEquipo->save();

                                $CondicionesPintura->RecepcionVehicular_id= $recepcion->id;
                                $CondicionesPintura->decolorada=0;
                                $CondicionesPintura->emblemas_completos=0;
                                $CondicionesPintura->color_no_igual=0;
                                $CondicionesPintura->logos=0;
                                $CondicionesPintura->exeso_rayones=0;
                                $CondicionesPintura->exeso_rociado=0;
                                $CondicionesPintura->pequenias_grietas=0;
                                $CondicionesPintura->danios_granizado=0;
                                $CondicionesPintura->carroceria_golpes=0;
                                $CondicionesPintura->lluvia_acido=0;
                                $CondicionesPintura->save();
                            }
                            
                        }
                        if(!Presupuesto::where('id',$element->id)->exists() && !Presupuesto::where('Folio',$element->NSolicitud)->exists()){
                            $presupuesto = new Presupuesto();
                            $presupuesto->id =$element->id; 
                            $presupuesto->DetallesGenerales_id =$detalles->id; 
                            $presupuesto->Folio = $element->NSolicitud;
                            $presupuesto->Observaciones = $element->observaciones;
                            $presupuesto->FechaDeVigencia = $element->fechaDeVigencia;
                            $presupuesto->Factura_id = $element->factura_id;
                            $presupuesto->User_update_id = $element->user_id;
                            $presupuesto->Tipo_id = 3;
                            $presupuesto->Status_id = $element->status;
                            $presupuesto->created_at = $element->created_at;
                            $presupuesto->updated_at = $element->updated_at;
                            $presupuesto->save();
                            $carrito=pCFECarrito::where('presupuestoCFE_id', $presupuesto->id)->get(); 
                            foreach ($carrito as $item) {
                                $presupuestos = new PresupuestoCarrito();
                                $presupuestos->id = $item->id;
                                $presupuestos->Presupuesto_id = $item->presupuestoCFE_id;
                                $presupuestos->Concepto_id = $item->pCFEConcepto_id;
                                $presupuestos->Cantidad = $item->cantidad;
                                $presupuestos->Costo = $item->precio;
                                $presupuestos->Venta = $item->precio_v;
                                $presupuestos->User_id = $item->usuario_id;
                                $presupuestos->User_Update_id = $item->usuario_id;
                                $presupuestos->created_at = $item->created_at;
                                $presupuestos->updated_at = $item->updated_at;
                                $presupuestos->save();
                            }
                        }else{
                            LOG::info('Presupuesto existe'. $element->id . ' o '. $element->NSolicitud);
                        }
                    }
                }
            }
            DB::commit();
            return response()->json(['success' => 'Creadas'], 200); 
        } catch (\Exception $e) {
            DB::rollBack();
            log::error('Error al migrar los datos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al migrar los datos: ' . $e->getMessage()], 500);
        }
            
    }
}
