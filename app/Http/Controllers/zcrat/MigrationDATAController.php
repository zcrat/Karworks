<?php

namespace App\Http\Controllers\zcrat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\LOG;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CondicionesPintura;
use App\Models\EquipoInventario;
use App\Models\ExterioresEquipo;
use App\Models\InterioresEquipo;
use App\Models\PresupuestoCarrito;
use App\Models\UsersTaller;
use App\Models\Tecnicos;
use App\Models\UnidadSatModel;
use App\Models\CategoriasSat;
use App\Models\Sucursales;
use App\Models\DetallesGenerales;
use App\Models\Color;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Vehiculo;
use App\Models\TipoArchivoPresupuesto;
use App\Models\ArchivosPresupuesto;
use App\Models\RecepcionesVehiculares as NuevaRecepcionesVehiculares;
use App\Models\Presupuesto as NuevosPresupuesto;

use App\Models\CategoriasDisponibles;
use App\Models\TiposDisponibles2 as NuevoTipoVehiculoConceptoDisponible;
use App\Models\TiposVehiculoConcepto;
use App\Models\Conceptos as NuevosConceptos;

use App\Models\RecepcionVehicular as ViejasRecepcionesVehiculares;
use App\CondicionesPintura as CondicionesPinturaantes;
use App\EquipoInventario as EquipoInventarioantes;
use App\ExterioresEquipo as ExterioresEquipoantes;
use App\InterioresEquipo as InterioresEquipoantes;

use App\presupuestos AS presupuestosCfbGenerales;
use App\pCarrito AS CarritoCfbGenerales;
use App\pTipos AS VehiculosConceptoCfbGenerales;
use App\pConceptos AS ConceptoCfbGenerales;

use App\presupuestos2023 AS presupuestosCfbNormales;
use App\pTipos2023 AS VehiculosConceptoCfbNormales;
use App\pConceptos2023 AS ConceptoCfbNormales;
use App\pCarrito2023 AS CarritoCfbNormales;

use App\anexosForaneos AS presupuestosCfbForaneos;
use App\anexosFTipos AS VehiculosConceptoCfbForaneos;
use App\AFConceptos AS ConceptoCfbForaneos;
use App\anexosFCarrito AS CarritoCfbForaneos;


use App\presupuestosCFE;
use App\pCFEGenerales;
use App\pGenerales;
use App\pGenerales2023;
use App\anexosFGenerales;

use App\FotosNuevas;
use App\FotosInstaladas;
use App\FotosViejas;
use App\ReporteAnomalias;
use App\OrdenEntrada;
use App\OrdenServicio;
use App\FacturaPDF;
use App\FacturaXML;
use App\Acuse;

use App\FotosNuevas2;
use App\FotosInstaladas2;
use App\FotosViejas2;
use App\ReporteAnomalias2;
use App\OrdenEntrada2;
use App\OrdenServicio2;
use App\FacturaPDF2;
use App\FacturaXML2;
use App\Acuse2;

use App\FotosNuevas2023;
use App\FotosInstaladas2023;
use App\FotosViejas2023;
use App\OrdenEntrada2023;
use App\ReporteAnomalias2023;
use App\OrdenServicio2023;
use App\FacturaPDF2023;
use App\FacturaXML2023;
use App\Acuse2023;

use App\anexosFFotosNuevas;
use App\AnexosFFotosInstaladas;
use App\anexosFFotosViejas;
use App\anexosFRA;
use App\anexosFOE;
use App\AnexosFOS;
use App\AnexosFPDF; 
use App\AnexosFXML;
use App\AnexosFAcuses;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MigrationDATAController extends Controller
{

    public function index(Request $request){
        $ruta='migracion';
        $archivos=['conceptos morelia divisionales 2026.xlsx','conceptos morelia gasolina 2026.xlsx'];
        $archivoerror=[];
        foreach ($archivos as $archivo) {
            $filePath = $ruta . '/' . $archivo;
            if (Storage::disk('public')->exists($filePath)) {
                LOG::info('El archivo existe: ' . $filePath);
                try{
                    DB::beginTransaction();
                    $spreadsheet = IOFactory::load(Storage::disk('public')->path($filePath));
                    $sheet = $spreadsheet->getActiveSheet();

                    $data = [];
                    foreach ($sheet->getRowIterator() as $row) {
                        $rowData = [];
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);
                        foreach ($cellIterator as $cell) {
                            $rowData[] = $cell->getValue();
                        }
                        $data[] = $rowData;
                    }
                    if(count($data)<2){
                        return response()->json(['message'=>'El Archivo Esta Vacio'],400);
                    }
                   
                    foreach($data as $index=> $row){
                       log::info('Fila: '. $index);
                       log::info($row); 
                        if($index==0){
                            continue;
                        }
                        $existeconcepto=NuevosConceptos::firstOrCreate([
                            'Categorias_id'=>$row[6],
                            'Tipos_id'=>$row[5],
                            'producto_almacen_id'=>null,
                            'num'=>$row[0],
                            'descripcion'=>$row[1],
                            'p_refaccion'=>$row[3],
                            'p_mo'=>$row[2],
                            'p_total'=>$row[4],
                            'modulo_id'=>$row[9],
                            'contrato_id'=>$row[8],
                            'zona_id'=>$row[10],
                            'anio'=>$row[11],
                            'Categoria_sat_id'=>$row[7],
                            'unidades_sat_id'=>$row[12]   
                        ]);
                    }
                        DB::commit();
                    }catch (\Exception $e) {
                        DB::rollBack();
                        log::error($e);
                        $archivoerror[]=$archivo;
                    }
            } else {
                LOG::info('El archivo no existe: ' . $filePath);
            }
        }
        if(count($archivoerror)>0){
            return response()->json(['message'=>'Error en los siguientes archivos','archivos'=>$archivoerror],500);
        }else{
            return response()->json(['message'=>'Conceptos migrados correctamente'],200);
        }
    }
    public function migraterecepcionesCFBGENERALES(Request $request){
        try {
            DB::beginTransaction();
            $modulo = 4;
            $contrato=8;
            $sucursalnueva=13;

            $categoriasconceptoscorregidas=["0"=>0,"1"=>6,"2"=>7,"3"=>4,"4"=>2,"6"=>3,"7"=>6];
            $sucursales=[2];

            foreach ($sucursales as $sucursal) {
                TiposVehiculoConcepto::query()->update(['id_antes' => 0]);
                $TiposVehiculosAnteriores=VehiculosConceptoCfbGenerales::get();
                foreach ($TiposVehiculosAnteriores as $tipovehiculoanterior) {
                    $NuevoTipoVehiculo = TiposVehiculoConcepto::where('nombre','LIKE',$tipovehiculoanterior->tipo)->first();
                    if(!isset($NuevoTipoVehiculo)){
                        $NuevoTipoVehiculo = new TiposVehiculoConcepto();
                        $NuevoTipoVehiculo->id_antes = $tipovehiculoanterior->id;
                        $NuevoTipoVehiculo->nombre = $tipovehiculoanterior->tipo;
                        $NuevoTipoVehiculo->cilindros = 0;
                        $NuevoTipoVehiculo->save();
                    }
                    if(!NuevoTipoVehiculoConceptoDisponible::where('tipos_vehiculo_concepto_id',$NuevoTipoVehiculo->id)->where('modulo_id',$modulo)->where('zona_id',$sucursalnueva)->where('contrato_id',$contrato)->where('anio',2025)->exists()){
                        $agregartipodisponible=new NuevoTipoVehiculoConceptoDisponible();
                        $agregartipodisponible->tipos_vehiculo_concepto_id = $NuevoTipoVehiculo->id;
                        $agregartipodisponible->modulo_id = $modulo;
                        $agregartipodisponible->zona_id = $sucursalnueva;
                        $agregartipodisponible->contrato_id = $contrato;
                        $agregartipodisponible->anio = 2025;
                        $agregartipodisponible->save();
                    }
                }
                $conceptosanteriores=ConceptoCfbGenerales::get();
                NuevosConceptos::query()->update(['id_antes' => 0]);
                $tipovhc=8;
                foreach ($conceptosanteriores as $conceptoanterior) {
                    $nombreanterior=VehiculosConceptoCfbGenerales::where('id',$conceptoanterior->pTipos_id)->first();
                    $tipovehiculo= TiposVehiculoConcepto::where('nombre','LIKE',$nombreanterior->tipo ?? 'DESCCONOCIDO')->first();

                    $satcateg=CategoriasSat::where('codigo_sat',$conceptoanterior->codigo_sat)->first();
                    $satuni=UnidadSatModel::where('clave',$conceptoanterior->codigo_unidad)->first();

                    $NuevoConcepto = new NuevosConceptos();
                    $NuevoConcepto->id_antes = $conceptoanterior->id;
                    $NuevoConcepto->Categorias_id = $categoriasconceptoscorregidas[$conceptoanterior->pCategorias_id];
                    $NuevoConcepto->Tipos_id =$tipovehiculo?$tipovehiculo->id:000;
                    $NuevoConcepto->num =$conceptoanterior->num;
                    $NuevoConcepto->descripcion =$conceptoanterior->descripcion;
                    $NuevoConcepto->p_refaccion =$conceptoanterior->p_refaccion;
                    $NuevoConcepto->p_mo =$conceptoanterior->p_mo;
                    $NuevoConcepto->p_total =$conceptoanterior->p_total;
                    $NuevoConcepto->modulo_id = $modulo;
                    $NuevoConcepto->zona_id = $sucursalnueva;
                    $NuevoConcepto->contrato_id = $contrato;
                    $NuevoConcepto->anio = 2025;
                    $NuevoConcepto->Categoria_sat_id =$satcateg->id;
                    $NuevoConcepto->unidades_sat_id =$satuni->id;
                    $NuevoConcepto->save();
                    $tipovhc=$NuevoConcepto->Tipos_id;
                }
                $elementostotales = presupuestosCfbGenerales::join('pVehiculos','presupuestos.pVehiculos_id','=','pVehiculos.id')
                    ->join('pGenerales','presupuestos.pGenerales_id','=','pGenerales.id')
                    ->join('empresas','presupuestos.empresa_id','=','empresas.id')
                        ->select(
                            'pGenerales.id as pGenerales_id',
                            'pGenerales.NSolicitud',
                            'pGenerales.OrdenServicio',
                            'pGenerales.KmDeIngreso',
                            'pGenerales.ClienteYRazonSocial',
                            'pGenerales.Mail',
                            'pGenerales.Telefono',
                            'pGenerales.Conductor',
                            'pGenerales.Fecha as FechaIngreso',
                            'pVehiculos.identificador',
                            'pVehiculos.modelo',
                            'pVehiculos.vin',
                            'pVehiculos.placas',
                            'pVehiculos.ano',
                            'pVehiculos.marca',
                            'presupuestos.id',
                            'presupuestos.descripcionMO',
                            'presupuestos.fechaDeVigencia',
                            'presupuestos.observaciones',
                            'presupuestos.user_id',
                            'presupuestos.factura_id',
                            'presupuestos.status',
                            'presupuestos.ubicacion',
                            'presupuestos.tdeentrega as descripciongeneral',
                            'presupuestos.created_at',
                            'presupuestos.updated_at',
                            'presupuestos.empresa_id',
                            )
                        ->orderBy('presupuestos.id', 'asc')->get();
                
                foreach ($elementostotales as $element) {
                    $vehiculo=Vehiculo::where('no_economico',$element->identificador)->first();
                    
                    $cadena = $element->NSolicitud;
                    if (preg_match('/\d/', $cadena)) {
                        $ultimo_caracter = substr($cadena, -1);
                        while (!ctype_digit($ultimo_caracter)) {
                            $cadena = substr($cadena, 0, -1);
                            $ultimo_caracter = substr($cadena, -1);
                        }
                    }
                    LOG::info('cadena: '.$cadena);
                    $detalles = DetallesGenerales::where('OrdenServicio','LIKE',$cadena.'%')->where('zona_id',$sucursalnueva)->where('contrato_id',$contrato)->where('modulo_id',$modulo)->first();
                    
                    if(!isset($detalles)){
                        $recepcionVehicular = ViejasRecepcionesVehiculares::where('sucursal_id',$sucursal)->where('modulo',$modulo)->where('folioNum','LIKE',$cadena.'%')->first();

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
                        $detalles->Ubicacion =$element->ubicacion;
                        $detalles->Fecha_Esperada = $recepcionVehicular ? $recepcionVehicular->fecha_compromiso : $element->FechaIngreso;
                        $detalles->Kilometraje_entrada = $element->KmDeIngreso;
                        $detalles->Gas_entrada = $recepcionVehicular ? $recepcionVehicular->gas_entrada : 0;
                        $detalles->Fecha_entrada = $element->FechaIngreso;
                        $detalles->Kilometraje_salida = $recepcionVehicular ? $recepcionVehicular->km_salida : null;
                        $detalles->Gas_salida = $recepcionVehicular ? $recepcionVehicular->gas_salida : null;
                        $detalles->Fecha_salida =$recepcionVehicular ? $recepcionVehicular->fecha_entrega : null;
                        $detalles->Vehiculo_id = $vehiculo->id;
                        $detalles->Tipo_Vehiculo_Concepto_id = $tipovhc;
                        $detalles->User_id = $element->user_id;
                        $detalles->User_update_id = $element->user_id;
                        $detalles->Empresa_id = $recepcionVehicular ? $recepcionVehicular->empresa_id : $element->empresa_id;
                        $detalles->Customer_id = $recepcionVehicular ? $recepcionVehicular->customer_id : 400;
                        $detalles->AdministradorTrasporte_id = $admintras->id;
                        $detalles->JefedeProceso_id = $jefproc->id;
                        $detalles->Trabajador_id = $trabajador->id;
                        $detalles->Telefono = $element->Telefono;
                        $detalles->contrato_id =$contrato;
                        $detalles->Indicaciones_cliente = $recepcionVehicular? $recepcionVehicular->indicaciones_del_cliente??'Sin Indicaciones Por Parte Del Cliente' : $element->descripciongeneral ?? 'Sin Indicaciones Por Parte Del Cliente';
                        $detalles->modulo_id =$modulo ;
                        $detalles->anio = 2025;
                        $detalles->zona_id = $sucursalnueva;
                        $detalles->created_at = $element->created_at;
                        $detalles->updated_at = $element->updated_at;
                        $detalles->save();

                        $recepcion = new NuevaRecepcionesVehiculares();
                        $ExterioresEquipo = new ExterioresEquipo();
                        $CondicionesPintura = new CondicionesPintura();
                        $EquipoInventario = new EquipoInventario();
                        $InterioresEquipo = new InterioresEquipo();
                        if(isset($recepcionVehicular)){
                            if(!NuevaRecepcionesVehiculares::where('id', $recepcionVehicular->id)->exists()){
                                LOG::info('Recepcion vehicular existe'. $cadena);
                                $origen = public_path('img/carros/'.$recepcionVehicular->carro); // Ruta de la imagen original
                                $origen2 = public_path('img/firmas/'.$recepcionVehicular->firma);
                                $extension = 'png'; 
                                $fileName = uniqid() . '.' . $extension;
                                $destino = public_path('storage/carros/'.$fileName);
                                $destino2 = public_path('storage/firmastaller/'.$fileName);

                                if (file_exists($origen)) {
                                    copy($origen, $destino);
                                }

                                if (file_exists($origen2)) {
                                    copy($origen2, $destino2);
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
                                    $data['tapetes'] <= 0 ? $data['tapetes']=3:$data['tapetes'] =$data['tapetes'];
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
                    if(!NuevosPresupuesto::where('Folio',$element->NSolicitud)->exists()){
                        $presupuesto = new NuevosPresupuesto();
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


                        $carrito=CarritoCfbGenerales::where('presupuesto_id', $element->id)->get(); 
                        foreach ($carrito as $item) {
                            $concepto = NuevosConceptos::where('id_antes',$item->pConcepto_id)->first();

                            $presupuestos = new PresupuestoCarrito();
                            $presupuestos->Presupuesto_id = $presupuesto->id;
                            $presupuestos->Concepto_id = $concepto->id;
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
            DB::commit();
            return response()->json(['success' => 'Creadas'], 200); 
        } catch (\Exception $e) {
            DB::rollBack();
            log::error($e);
            return response()->json(['error' => 'Error al migrar los datos: ' . $e->getMessage()], 500);
        }
            
    }
    public function migraterecepcionesCFB2025(Request $request){
        try {
            DB::beginTransaction();
            $modulo = 4;
            $contrato=8;
            $sucursalnueva=11;

            $categoriasconceptoscorregidas=['1'=>14,'2'=>3,'3'=>2,'4'=>7,'5'=>4,'6'=>9,'7'=>6,'8'=>8,'9'=>10,'10'=>5,'11'=>5,'12'=>6,'13'=>13];
            $sucursales=[2];

            foreach ($sucursales as $sucursal) {
               
                $TiposVehiculosAnteriores=VehiculosConceptoCfbNormales::get();
                TiposVehiculoConcepto::query()->update(['id_antes' => 0]);
                foreach ($TiposVehiculosAnteriores as $tipovehiculoanterior) {
                    
                    $NuevoTipoVehiculo = TiposVehiculoConcepto::where('nombre','LIKE',$tipovehiculoanterior->tipo)->first();
                    if(!isset($NuevoTipoVehiculo)){
                        $NuevoTipoVehiculo = new TiposVehiculoConcepto();
                        $NuevoTipoVehiculo->id_antes = $tipovehiculoanterior->id;
                        $NuevoTipoVehiculo->nombre = $tipovehiculoanterior->tipo;
                        $NuevoTipoVehiculo->cilindros = 0;
                        $NuevoTipoVehiculo->save();
                    }
                    if(!NuevoTipoVehiculoConceptoDisponible::where('tipos_vehiculo_concepto_id',$NuevoTipoVehiculo->id)->where('modulo_id',$modulo)->where('zona_id',$sucursalnueva)->where('contrato_id',$contrato)->where('anio',2025)->exists()){
                        $agregartipodisponible=new NuevoTipoVehiculoConceptoDisponible();
                        $agregartipodisponible->tipos_vehiculo_concepto_id = $NuevoTipoVehiculo->id;
                        $agregartipodisponible->modulo_id = $modulo;
                        $agregartipodisponible->zona_id = $sucursalnueva;
                        $agregartipodisponible->contrato_id = $contrato;
                        $agregartipodisponible->anio = 2025;
                        $agregartipodisponible->save();
                    }
                }
                $conceptosanteriores=ConceptoCfbNormales::get();
                NuevosConceptos::query()->update(['id_antes' => 0]);
                $tipovhc=8;

                foreach ($conceptosanteriores as $conceptoanterior) {
                    $nombreanterior=VehiculosConceptoCfbNormales::where('id',$conceptoanterior->pTipos_id)->first();
                    $tipovehiculo= TiposVehiculoConcepto::where('nombre','LIKE',$nombreanterior->tipo?? 'DESCCONOCIDO')->first();
                    $satcateg=CategoriasSat::where('codigo_sat',$conceptoanterior->codigo_sat)->first();
                    $satuni=UnidadSatModel::where('clave',$conceptoanterior->codigo_unidad)->first();

                    $NuevoConcepto = new NuevosConceptos();
                    $NuevoConcepto->id_antes = $conceptoanterior->id;
                    $NuevoConcepto->Categorias_id = $categoriasconceptoscorregidas[$conceptoanterior->pCategorias_id]??20;
                    $NuevoConcepto->Tipos_id =$tipovehiculo?$tipovehiculo->id:000;
                    $NuevoConcepto->num =$conceptoanterior->num;
                    $NuevoConcepto->descripcion =$conceptoanterior->descripcion;
                    $NuevoConcepto->p_refaccion =$conceptoanterior->p_refaccion;
                    $NuevoConcepto->p_mo =$conceptoanterior->p_mo;
                    $NuevoConcepto->p_total =$conceptoanterior->p_total;
                    $NuevoConcepto->modulo_id = $modulo;
                    $NuevoConcepto->zona_id = $sucursalnueva;
                    $NuevoConcepto->contrato_id = $contrato;
                    $NuevoConcepto->anio = 2025;
                    $NuevoConcepto->Categoria_sat_id =$satcateg->id ?? 272;
                    $NuevoConcepto->unidades_sat_id =$satuni->id ?? 1;
                    $NuevoConcepto->save();
                    $tipovhc=$NuevoConcepto->Tipos_id;
                }
                $elementostotales = presupuestosCfbNormales::join('pVehiculos2023','presupuestos2023.pVehiculos_id','=','pVehiculos2023.id')
                ->join('pGenerales2023','presupuestos2023.pGenerales_id','=','pGenerales2023.id')
                ->join('empresas','presupuestos2023.empresa_id','=','empresas.id')
                        ->select(
                            'pGenerales2023.id as Generales_id',
                            'pGenerales2023.NSolicitud',
                            'pGenerales2023.OrdenServicio',
                            'pGenerales2023.KmDeIngreso',
                            'pGenerales2023.ClienteYRazonSocial',
                            'pGenerales2023.Mail',
                            'pGenerales2023.Telefono',
                            'pGenerales2023.Conductor',
                            'pGenerales2023.Fecha as FechaIngreso',
                            'pVehiculos2023.identificador',
                            'pVehiculos2023.modelo',
                            'pVehiculos2023.vin',
                            'pVehiculos2023.placas',
                            'pVehiculos2023.ano',
                            'pVehiculos2023.marca',
                            'presupuestos2023.id',
                            'presupuestos2023.descripcionMO',
                            'presupuestos2023.fechaDeVigencia',
                            'presupuestos2023.observaciones',
                            'presupuestos2023.user_id',
                            'presupuestos2023.factura_id',
                            'presupuestos2023.status',
                            'presupuestos2023.ubicacion',
                            'presupuestos2023.tdeentrega as descripciongeneral',
                            'presupuestos2023.empresa_id',
                            'presupuestos2023.created_at',
                            'presupuestos2023.updated_at',
                            )->where('presupuestos2023.eco_id',0)
                        ->orderBy('presupuestos2023.id', 'asc')->get();
                
                foreach ($elementostotales as $element) {
                    $vehiculo=Vehiculo::where('no_economico',$element->identificador)->first();
                    
                    $cadena = $element->NSolicitud;
                    if (preg_match('/\d/', $cadena)) {
                        $ultimo_caracter = substr($cadena, -1);
                        while (!ctype_digit($ultimo_caracter)) {
                            $cadena = substr($cadena, 0, -1);
                            $ultimo_caracter = substr($cadena, -1);
                        }
                    }
                    $detalles = DetallesGenerales::where('OrdenServicio','LIKE',$cadena.'%')->where('zona_id',$sucursalnueva)->where('contrato_id',$contrato)->where('modulo_id',$modulo)->first();
                    
                    if(!isset($detalles)){
                        $recepcionVehicular = ViejasRecepcionesVehiculares::where('modulo',5)->where('folioNum','LIKE',$cadena.'%')->first();

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
                        $detalles->Ubicacion =$element->ubicacion??'Sin Ubicacion';
                        $detalles->Fecha_Esperada = $recepcionVehicular ? $recepcionVehicular->fecha_compromiso : $element->FechaIngreso;
                        $detalles->Kilometraje_entrada = $element->KmDeIngreso;
                        $detalles->Gas_entrada = $recepcionVehicular ? $recepcionVehicular->gas_entrada : 0;
                        $detalles->Fecha_entrada = $element->FechaIngreso;
                        $detalles->Kilometraje_salida = $recepcionVehicular ? $recepcionVehicular->km_salida : null;
                        $detalles->Gas_salida = $recepcionVehicular ? $recepcionVehicular->gas_salida : null;
                        $detalles->Fecha_salida =$recepcionVehicular ? $recepcionVehicular->fecha_entrega : null;
                        $detalles->Vehiculo_id = $vehiculo->id;
                        $detalles->Tipo_Vehiculo_Concepto_id = $tipovhc;
                        $detalles->User_id = $element->user_id;
                        $detalles->User_update_id = $element->user_id;
                        $detalles->Empresa_id = $recepcionVehicular ? $recepcionVehicular->empresa_id : $element->empresa_id;
                        $detalles->Customer_id = $recepcionVehicular ? $recepcionVehicular->customer_id : 400;
                        $detalles->AdministradorTrasporte_id = $admintras->id;
                        $detalles->JefedeProceso_id = $jefproc->id;
                        $detalles->Trabajador_id = $trabajador->id;
                        $detalles->Telefono = $element->Telefono;
                        $detalles->contrato_id =$contrato;
                        $detalles->Indicaciones_cliente = $recepcionVehicular? $recepcionVehicular->indicaciones_del_cliente??'Sin Indicaciones Por Parte Del Cliente' : $element->descripciongeneral ?? 'Sin Indicaciones Por Parte Del Cliente';
                        $detalles->modulo_id =$modulo ;
                        $detalles->anio = 2025;
                        $detalles->zona_id = $sucursalnueva;
                        $detalles->created_at = $element->created_at;
                        $detalles->updated_at = $element->updated_at;
                        $detalles->save();

                        $recepcion = new NuevaRecepcionesVehiculares();
                        $ExterioresEquipo = new ExterioresEquipo();
                        $CondicionesPintura = new CondicionesPintura();
                        $EquipoInventario = new EquipoInventario();
                        $InterioresEquipo = new InterioresEquipo();
                        if(isset($recepcionVehicular)){
                            if(!NuevaRecepcionesVehiculares::where('id', $recepcionVehicular->id)->exists()){
                                LOG::info('Recepcion vehicular existe'. $cadena);
                                $origen = public_path('img/carros/'.$recepcionVehicular->carro); // Ruta de la imagen original
                                $origen2 = public_path('img/firmas/'.$recepcionVehicular->firma);
                                $extension = 'png'; 
                                $fileName = uniqid() . '.' . $extension;
                                $destino = public_path('storage/carros/'.$fileName);
                                $destino2 = public_path('storage/firmastaller/'.$fileName);

                                if (file_exists($origen)) {
                                    copy($origen, $destino);
                                }

                                if (file_exists($origen2)) {
                                    copy($origen2, $destino2);
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
                                    $data['tapetes'] <= 0 ? $data['tapetes']=3:$data['tapetes'] =$data['tapetes'];
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
                    if(!NuevosPresupuesto::where('Folio',$element->NSolicitud)->exists()){
                        $presupuesto = new NuevosPresupuesto();
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


                        $carrito=CarritoCfbNormales::where('presupuesto_id', $element->id)->get(); 
                        foreach ($carrito as $item) {
                            $concepto = NuevosConceptos::where('id_antes',$item->pConcepto_id)->first();
                            if(isset($concepto)){
                                $presupuestos = new PresupuestoCarrito();
                                $presupuestos->Presupuesto_id = $presupuesto->id;
                                $presupuestos->Concepto_id = $concepto->id;
                                $presupuestos->Cantidad = $item->cantidad;
                                $presupuestos->Costo = $item->precio;
                                $presupuestos->Venta = $item->precio_v;
                                $presupuestos->User_id = $item->usuario_id;
                                $presupuestos->User_Update_id = $item->usuario_id;
                                $presupuestos->created_at = $item->created_at;
                                $presupuestos->updated_at = $item->updated_at;
                                $presupuestos->save();
                            }

                        }
                    }else{
                        LOG::info('Presupuesto existe'. $element->id . ' o '. $element->NSolicitud);
                    }
                }
            }
            DB::commit();
            return response()->json(['success' => 'Creadas'], 200); 
        } catch (\Exception $e) {
            DB::rollBack();
            log::error($e);
            return response()->json(['error' => 'Error al migrar los datos: ' . $e->getMessage()], 500);
        }
            
    }
    public function migraterecepcionesCFBForaneos(Request $request){
        try {
            DB::beginTransaction();
            $modulo = 4;
            $contrato=8;
            $sucursalnueva=12;

            $categoriasconceptoscorregidas=['1'=>6,'2'=>2,'3'=>14,'4'=>7,'5'=>6,'6'=>5,'7'=>10,'8'=>4,'9'=>3,'10'=>10,'11'=>5,'12'=>8,'13'=>9];
            $sucursales=[2];

            foreach ($sucursales as $sucursal) {
               
                $TiposVehiculosAnteriores=VehiculosConceptoCfbForaneos::get();
                TiposVehiculoConcepto::query()->update(['id_antes' => 0]);
                foreach ($TiposVehiculosAnteriores as $tipovehiculoanterior) {
                    
                    $NuevoTipoVehiculo = TiposVehiculoConcepto::where('nombre','LIKE',$tipovehiculoanterior->tipo)->first();
                    if(!isset($NuevoTipoVehiculo)){
                        $NuevoTipoVehiculo = new TiposVehiculoConcepto();
                        $NuevoTipoVehiculo->id_antes = $tipovehiculoanterior->id;
                        $NuevoTipoVehiculo->nombre = $tipovehiculoanterior->tipo;
                        $NuevoTipoVehiculo->cilindros = 0;
                        $NuevoTipoVehiculo->save();
                    }
                    if(!NuevoTipoVehiculoConceptoDisponible::where('tipos_vehiculo_concepto_id',$NuevoTipoVehiculo->id)->where('modulo_id',$modulo)->where('zona_id',$sucursalnueva)->where('contrato_id',$contrato)->where('anio',2025)->exists()){
                        $agregartipodisponible=new NuevoTipoVehiculoConceptoDisponible();
                        $agregartipodisponible->tipos_vehiculo_concepto_id = $NuevoTipoVehiculo->id;
                        $agregartipodisponible->modulo_id = $modulo;
                        $agregartipodisponible->zona_id = $sucursalnueva;
                        $agregartipodisponible->contrato_id = $contrato;
                        $agregartipodisponible->anio = 2025;
                        $agregartipodisponible->save();
                    }
                }
                $conceptosanteriores=ConceptoCfbForaneos::get();
                NuevosConceptos::query()->update(['id_antes' => 0]);
                $tipovhc=8;

                foreach ($conceptosanteriores as $conceptoanterior) {
                    $nombreanterior=VehiculosConceptoCfbForaneos::where('id',$conceptoanterior->pTipos_id)->first();
                    $tipovehiculo= TiposVehiculoConcepto::where('nombre','LIKE',$nombreanterior->tipo?? 'DESCCONOCIDO')->first();
                    $satcateg=CategoriasSat::where('codigo_sat',$conceptoanterior->codigo_sat)->first();
                    $satuni=UnidadSatModel::where('clave',$conceptoanterior->codigo_unidad)->first();

                    $NuevoConcepto = new NuevosConceptos();
                    $NuevoConcepto->id_antes = $conceptoanterior->id;
                    $NuevoConcepto->Categorias_id = $categoriasconceptoscorregidas[$conceptoanterior->pCategorias_id]??20;
                    $NuevoConcepto->Tipos_id =$tipovehiculo?$tipovehiculo->id:000;
                    $NuevoConcepto->num =$conceptoanterior->num;
                    $NuevoConcepto->descripcion =$conceptoanterior->descripcion;
                    $NuevoConcepto->p_refaccion =$conceptoanterior->p_refaccion;
                    $NuevoConcepto->p_mo =$conceptoanterior->p_mo;
                    $NuevoConcepto->p_total =$conceptoanterior->p_total;
                    $NuevoConcepto->modulo_id = $modulo;
                    $NuevoConcepto->zona_id = $sucursalnueva;
                    $NuevoConcepto->contrato_id = $contrato;
                    $NuevoConcepto->anio = 2025;
                    $NuevoConcepto->Categoria_sat_id =$satcateg->id ?? 272;
                    $NuevoConcepto->unidades_sat_id =$satuni->id ?? 1;
                    $NuevoConcepto->save();
                    $tipovhc=$NuevoConcepto->Tipos_id;
                }
                $elementostotales = presupuestosCfbForaneos::join('anexosFVehiculos','anexosforaneos.pVehiculos_id','=','anexosFVehiculos.id')
                ->join('anexosFGenerales','anexosforaneos.pGenerales_id','=','anexosFGenerales.id')
                ->join('empresas','anexosforaneos.empresa_id','=','empresas.id')
                        ->select(
                            'anexosFGenerales.id as Generales_id',
                            'anexosFGenerales.NSolicitud',
                            'anexosFGenerales.OrdenServicio',
                            'anexosFGenerales.KmDeIngreso',
                            'anexosFGenerales.ClienteYRazonSocial',
                            'anexosFGenerales.Mail',
                            'anexosFGenerales.Telefono',
                            'anexosFGenerales.Conductor',
                            'anexosFGenerales.Fecha as FechaIngreso',
                            'anexosFVehiculos.identificador',
                            'anexosFVehiculos.modelo',
                            'anexosFVehiculos.vin',
                            'anexosFVehiculos.placas',
                            'anexosFVehiculos.ano',
                            'anexosFVehiculos.marca',
                            'anexosforaneos.id',
                            'anexosforaneos.descripcionMO',
                            'anexosforaneos.fechaDeVigencia',
                            'anexosforaneos.observaciones',
                            'anexosforaneos.user_id',
                            'anexosforaneos.factura_id',
                            'anexosforaneos.status',
                            'anexosforaneos.ubicacion',
                            'anexosforaneos.tdeentrega as descripciongeneral',
                            'anexosforaneos.empresa_id',
                            'anexosforaneos.created_at',
                            'anexosforaneos.updated_at',
                            )->where('anexosforaneos.eco_id',0)
                        ->orderBy('anexosforaneos.id', 'asc')->get();
                
                foreach ($elementostotales as $element) {
                    $vehiculo=Vehiculo::where('no_economico',$element->identificador)->first();
                    
                    $cadena = $element->NSolicitud;
                    if (preg_match('/\d/', $cadena)) {
                        $ultimo_caracter = substr($cadena, -1);
                        while (!ctype_digit($ultimo_caracter)) {
                            $cadena = substr($cadena, 0, -1);
                            $ultimo_caracter = substr($cadena, -1);
                        }
                    }
                    $detalles = DetallesGenerales::where('OrdenServicio','LIKE',$cadena.'%')->where('zona_id',$sucursalnueva)->where('contrato_id',$contrato)->where('modulo_id',$modulo)->first();
                    
                    if(!isset($detalles)){
                        //$recepcionVehicular = ViejasRecepcionesVehiculares::where('modulo',5)->where('folioNum','LIKE',$cadena.'%')->first();
                        $recepcionVehicular = NULL;
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
                        $detalles->Ubicacion =$element->ubicacion??'Sin Ubicacion';
                        $detalles->Fecha_Esperada = $recepcionVehicular ? $recepcionVehicular->fecha_compromiso : $element->FechaIngreso;
                        $detalles->Kilometraje_entrada = $element->KmDeIngreso;
                        $detalles->Gas_entrada = $recepcionVehicular ? $recepcionVehicular->gas_entrada : 0;
                        $detalles->Fecha_entrada = $element->FechaIngreso;
                        $detalles->Kilometraje_salida = $recepcionVehicular ? $recepcionVehicular->km_salida : null;
                        $detalles->Gas_salida = $recepcionVehicular ? $recepcionVehicular->gas_salida : null;
                        $detalles->Fecha_salida =$recepcionVehicular ? $recepcionVehicular->fecha_entrega : null;
                        $detalles->Vehiculo_id = $vehiculo->id;
                        $detalles->Tipo_Vehiculo_Concepto_id = $tipovhc;
                        $detalles->User_id = $element->user_id;
                        $detalles->User_update_id = $element->user_id;
                        $detalles->Empresa_id = $recepcionVehicular ? $recepcionVehicular->empresa_id : $element->empresa_id;
                        $detalles->Customer_id = $recepcionVehicular ? $recepcionVehicular->customer_id : 400;
                        $detalles->AdministradorTrasporte_id = $admintras->id;
                        $detalles->JefedeProceso_id = $jefproc->id;
                        $detalles->Trabajador_id = $trabajador->id;
                        $detalles->Telefono = is_numeric($element->Telefono)?$element->Telefono:4430000000;
                        $detalles->contrato_id =$contrato;
                        $detalles->Indicaciones_cliente = $recepcionVehicular? $recepcionVehicular->indicaciones_del_cliente??'Sin Indicaciones Por Parte Del Cliente' : $element->descripciongeneral ?? 'Sin Indicaciones Por Parte Del Cliente';
                        $detalles->modulo_id =$modulo ;
                        $detalles->anio = 2025;
                        $detalles->zona_id = $sucursalnueva;
                        $detalles->created_at = $element->created_at;
                        $detalles->updated_at = $element->updated_at;
                        $detalles->save();

                        $recepcion = new NuevaRecepcionesVehiculares();
                        $ExterioresEquipo = new ExterioresEquipo();
                        $CondicionesPintura = new CondicionesPintura();
                        $EquipoInventario = new EquipoInventario();
                        $InterioresEquipo = new InterioresEquipo();
                        if(isset($recepcionVehicular)){
                            if(!NuevaRecepcionesVehiculares::where('id', $recepcionVehicular->id)->exists()){
                                LOG::info('Recepcion vehicular existe'. $cadena);
                                $origen = public_path('img/carros/'.$recepcionVehicular->carro); // Ruta de la imagen original
                                $origen2 = public_path('img/firmas/'.$recepcionVehicular->firma);
                                $extension = 'png'; 
                                $fileName = uniqid() . '.' . $extension;
                                $destino = public_path('storage/carros/'.$fileName);
                                $destino2 = public_path('storage/firmastaller/'.$fileName);

                                if (file_exists($origen)) {
                                    copy($origen, $destino);
                                }

                                if (file_exists($origen2)) {
                                    copy($origen2, $destino2);
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
                                    $data['tapetes'] <= 0 ? $data['tapetes']=3:$data['tapetes'] =$data['tapetes'];
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
                    if(!NuevosPresupuesto::where('Folio',$element->NSolicitud)->exists()){
                        $presupuesto = new NuevosPresupuesto();
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


                        $carrito=CarritoCfbForaneos::where('presupuesto_id', $element->id)->get(); 
                        foreach ($carrito as $item) {
                            $concepto = NuevosConceptos::where('id_antes',$item->pConcepto_id)->first();
                            if(isset($concepto)){
                                $presupuestos = new PresupuestoCarrito();
                                $presupuestos->Presupuesto_id = $presupuesto->id;
                                $presupuestos->Concepto_id = $concepto->id;
                                $presupuestos->Cantidad = $item->cantidad;
                                $presupuestos->Costo = $item->precio;
                                $presupuestos->Venta = $item->precio_v;
                                $presupuestos->User_id = $item->usuario_id;
                                $presupuestos->User_Update_id = $item->usuario_id;
                                $presupuestos->created_at = $item->created_at;
                                $presupuestos->updated_at = $item->updated_at;
                                $presupuestos->save();
                            }

                        }
                    }else{
                        LOG::info('Presupuesto existe'. $element->id . ' o '. $element->NSolicitud);
                    }
                }
            }
            DB::commit();
            return response()->json(['success' => 'Creadas'], 200); 
        } catch (\Exception $e) {
            DB::rollBack();
            log::error($e);
            return response()->json(['error' => 'Error al migrar los datos: ' . $e->getMessage()], 500);
        }
            
    }
    public function migraterecepcionesECO2025(Request $request){
        try {
            DB::beginTransaction();
            $modulo = 5;
            $contrato=8;
            $sucursalnueva=11;

            $categoriasconceptoscorregidas=['1'=>14,'2'=>3,'3'=>2,'4'=>7,'5'=>4,'6'=>9,'7'=>6,'8'=>8,'9'=>10,'10'=>5,'11'=>5,'12'=>6,'13'=>13];
            $sucursales=[2];

            foreach ($sucursales as $sucursal) {
               
                $TiposVehiculosAnteriores=VehiculosConceptoCfbNormales::get();
                TiposVehiculoConcepto::query()->update(['id_antes' => 0]);
                foreach ($TiposVehiculosAnteriores as $tipovehiculoanterior) {
                    
                    $NuevoTipoVehiculo = TiposVehiculoConcepto::where('nombre','LIKE',$tipovehiculoanterior->tipo)->first();
                    if(!isset($NuevoTipoVehiculo)){
                        $NuevoTipoVehiculo = new TiposVehiculoConcepto();
                        $NuevoTipoVehiculo->id_antes = $tipovehiculoanterior->id;
                        $NuevoTipoVehiculo->nombre = $tipovehiculoanterior->tipo;
                        $NuevoTipoVehiculo->cilindros = 0;
                        $NuevoTipoVehiculo->save();
                    }
                    if(!NuevoTipoVehiculoConceptoDisponible::where('tipos_vehiculo_concepto_id',$NuevoTipoVehiculo->id)->where('modulo_id',$modulo)->where('zona_id',$sucursalnueva)->where('contrato_id',$contrato)->where('anio',2025)->exists()){
                        $agregartipodisponible=new NuevoTipoVehiculoConceptoDisponible();
                        $agregartipodisponible->tipos_vehiculo_concepto_id = $NuevoTipoVehiculo->id;
                        $agregartipodisponible->modulo_id = $modulo;
                        $agregartipodisponible->zona_id = $sucursalnueva;
                        $agregartipodisponible->contrato_id = $contrato;
                        $agregartipodisponible->anio = 2025;
                        $agregartipodisponible->save();
                    }
                }
                $conceptosanteriores=ConceptoCfbNormales::get();
                NuevosConceptos::query()->update(['id_antes' => 0]);
                $tipovhc=8;

                foreach ($conceptosanteriores as $conceptoanterior) {
                    $nombreanterior=VehiculosConceptoCfbNormales::where('id',$conceptoanterior->pTipos_id)->first();
                    $tipovehiculo= TiposVehiculoConcepto::where('nombre','LIKE',$nombreanterior->tipo?? 'DESCCONOCIDO')->first();
                    $satcateg=CategoriasSat::where('codigo_sat',$conceptoanterior->codigo_sat)->first();
                    $satuni=UnidadSatModel::where('clave',$conceptoanterior->codigo_unidad)->first();

                    $NuevoConcepto = new NuevosConceptos();
                    $NuevoConcepto->id_antes = $conceptoanterior->id;
                    $NuevoConcepto->Categorias_id = $categoriasconceptoscorregidas[$conceptoanterior->pCategorias_id]??20;
                    $NuevoConcepto->Tipos_id =$tipovehiculo?$tipovehiculo->id:000;
                    $NuevoConcepto->num =$conceptoanterior->num;
                    $NuevoConcepto->descripcion =$conceptoanterior->descripcion;
                    $NuevoConcepto->p_refaccion =$conceptoanterior->p_refaccion;
                    $NuevoConcepto->p_mo =$conceptoanterior->p_mo;
                    $NuevoConcepto->p_total =$conceptoanterior->p_total;
                    $NuevoConcepto->modulo_id = $modulo;
                    $NuevoConcepto->zona_id = $sucursalnueva;
                    $NuevoConcepto->contrato_id = $contrato;
                    $NuevoConcepto->anio = 2025;
                    $NuevoConcepto->Categoria_sat_id =$satcateg->id ?? 272;
                    $NuevoConcepto->unidades_sat_id =$satuni->id ?? 1;
                    $NuevoConcepto->save();
                    $tipovhc=$NuevoConcepto->Tipos_id;
                }
                $elementostotales = presupuestosCfbNormales::join('pVehiculos2023','presupuestos2023.pVehiculos_id','=','pVehiculos2023.id')
                ->join('pGenerales2023','presupuestos2023.pGenerales_id','=','pGenerales2023.id')
                ->join('empresas','presupuestos2023.empresa_id','=','empresas.id')
                        ->select(
                            'pGenerales2023.id as Generales_id',
                            'pGenerales2023.NSolicitud',
                            'pGenerales2023.OrdenServicio',
                            'pGenerales2023.KmDeIngreso',
                            'pGenerales2023.ClienteYRazonSocial',
                            'pGenerales2023.Mail',
                            'pGenerales2023.Telefono',
                            'pGenerales2023.Conductor',
                            'pGenerales2023.Fecha as FechaIngreso',
                            'pVehiculos2023.identificador',
                            'pVehiculos2023.modelo',
                            'pVehiculos2023.vin',
                            'pVehiculos2023.placas',
                            'pVehiculos2023.ano',
                            'pVehiculos2023.marca',
                            'presupuestos2023.id',
                            'presupuestos2023.descripcionMO',
                            'presupuestos2023.fechaDeVigencia',
                            'presupuestos2023.observaciones',
                            'presupuestos2023.user_id',
                            'presupuestos2023.factura_id',
                            'presupuestos2023.status',
                            'presupuestos2023.ubicacion',
                            'presupuestos2023.tdeentrega as descripciongeneral',
                            'presupuestos2023.empresa_id',
                            'presupuestos2023.created_at',
                            'presupuestos2023.updated_at',
                            )->where('presupuestos2023.eco_id',1)
                        ->orderBy('presupuestos2023.id', 'asc')->get();
                
                foreach ($elementostotales as $element) {
                    $vehiculo=Vehiculo::where('no_economico',$element->identificador)->first();
                    
                    $cadena = $element->NSolicitud;
                    if (preg_match('/\d/', $cadena)) {
                        $ultimo_caracter = substr($cadena, -1);
                        while (!ctype_digit($ultimo_caracter)) {
                            $cadena = substr($cadena, 0, -1);
                            $ultimo_caracter = substr($cadena, -1);
                        }
                    }
                    $detalles = DetallesGenerales::where('OrdenServicio','LIKE',$cadena.'%')->where('zona_id',$sucursalnueva)->where('contrato_id',$contrato)->where('modulo_id',$modulo)->first();
                    
                    if(!isset($detalles)){
                        $recepcionVehicular = ViejasRecepcionesVehiculares::where('modulo',7)->where('folioNum','LIKE',$cadena.'%')->first();

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
                        $detalles->Ubicacion =$element->ubicacion??'Sin Ubicacion';
                        $detalles->Fecha_Esperada = $recepcionVehicular ? $recepcionVehicular->fecha_compromiso : $element->FechaIngreso;
                        $detalles->Kilometraje_entrada = $element->KmDeIngreso;
                        $detalles->Gas_entrada = $recepcionVehicular ? $recepcionVehicular->gas_entrada : 0;
                        $detalles->Fecha_entrada = $element->FechaIngreso;
                        $detalles->Kilometraje_salida = $recepcionVehicular ? $recepcionVehicular->km_salida : null;
                        $detalles->Gas_salida = $recepcionVehicular ? $recepcionVehicular->gas_salida : null;
                        $detalles->Fecha_salida =$recepcionVehicular ? $recepcionVehicular->fecha_entrega : null;
                        $detalles->Vehiculo_id = $vehiculo->id;
                        $detalles->Tipo_Vehiculo_Concepto_id = $tipovhc;
                        $detalles->User_id = $element->user_id;
                        $detalles->User_update_id = $element->user_id;
                        $detalles->Empresa_id = $recepcionVehicular ? $recepcionVehicular->empresa_id : $element->empresa_id;
                        $detalles->Customer_id = $recepcionVehicular ? $recepcionVehicular->customer_id : 400;
                        $detalles->AdministradorTrasporte_id = $admintras->id;
                        $detalles->JefedeProceso_id = $jefproc->id;
                        $detalles->Trabajador_id = $trabajador->id;
                        $detalles->Telefono = $element->Telefono;
                        $detalles->contrato_id =$contrato;
                        $detalles->Indicaciones_cliente = $recepcionVehicular? $recepcionVehicular->indicaciones_del_cliente??'Sin Indicaciones Por Parte Del Cliente' : $element->descripciongeneral ?? 'Sin Indicaciones Por Parte Del Cliente';
                        $detalles->modulo_id =$modulo ;
                        $detalles->anio = 2025;
                        $detalles->zona_id = $sucursalnueva;
                        $detalles->created_at = $element->created_at;
                        $detalles->updated_at = $element->updated_at;
                        $detalles->save();

                        $recepcion = new NuevaRecepcionesVehiculares();
                        $ExterioresEquipo = new ExterioresEquipo();
                        $CondicionesPintura = new CondicionesPintura();
                        $EquipoInventario = new EquipoInventario();
                        $InterioresEquipo = new InterioresEquipo();
                        if(isset($recepcionVehicular)){
                            if(!NuevaRecepcionesVehiculares::where('id', $recepcionVehicular->id)->exists()){
                                LOG::info('Recepcion vehicular existe'. $cadena);
                                $origen = public_path('img/carros/'.$recepcionVehicular->carro); // Ruta de la imagen original
                                $origen2 = public_path('img/firmas/'.$recepcionVehicular->firma);
                                $extension = 'png'; 
                                $fileName = uniqid() . '.' . $extension;
                                $destino = public_path('storage/carros/'.$fileName);
                                $destino2 = public_path('storage/firmastaller/'.$fileName);

                                if (file_exists($origen)) {
                                    copy($origen, $destino);
                                }

                                if (file_exists($origen2)) {
                                    copy($origen2, $destino2);
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
                                    $data['tapetes'] <= 0 ? $data['tapetes']=3:$data['tapetes'] =$data['tapetes'];
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
                    if(!NuevosPresupuesto::where('Folio',$element->NSolicitud)->exists()){
                        $presupuesto = new NuevosPresupuesto();
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


                        $carrito=CarritoCfbNormales::where('presupuesto_id', $element->id)->get(); 
                        foreach ($carrito as $item) {
                            $concepto = NuevosConceptos::where('id_antes',$item->pConcepto_id)->first();
                            if(isset($concepto)){
                                $presupuestos = new PresupuestoCarrito();
                                $presupuestos->Presupuesto_id = $presupuesto->id;
                                $presupuestos->Concepto_id = $concepto->id;
                                $presupuestos->Cantidad = $item->cantidad;
                                $presupuestos->Costo = $item->precio;
                                $presupuestos->Venta = $item->precio_v;
                                $presupuestos->User_id = $item->usuario_id;
                                $presupuestos->User_Update_id = $item->usuario_id;
                                $presupuestos->created_at = $item->created_at;
                                $presupuestos->updated_at = $item->updated_at;
                                $presupuestos->save();
                            }

                        }
                    }else{
                        LOG::info('Presupuesto existe'. $element->id . ' o '. $element->NSolicitud);
                    }
                }
            }
            DB::commit();
            return response()->json(['success' => 'Creadas'], 200); 
        } catch (\Exception $e) {
            DB::rollBack();
            log::error($e);
            return response()->json(['error' => 'Error al migrar los datos: ' . $e->getMessage()], 500);
        }
            
    }
    public function migraterecepcionesECOEDENRED(Request $request){
        try {
            DB::beginTransaction();
            $modulo = 5;
            $contrato=8;
            $sucursalnueva=14;

            $categoriasconceptoscorregidas=['1'=>14,'2'=>3,'3'=>2,'4'=>7,'5'=>4,'6'=>9,'7'=>6,'8'=>8,'9'=>10,'10'=>5,'11'=>5,'12'=>6,'13'=>13];
            $sucursales=[2];

            foreach ($sucursales as $sucursal) {
               
                $TiposVehiculosAnteriores=VehiculosConceptoCfbNormales::get();
                TiposVehiculoConcepto::query()->update(['id_antes' => 0]);
                foreach ($TiposVehiculosAnteriores as $tipovehiculoanterior) {
                    
                    $NuevoTipoVehiculo = TiposVehiculoConcepto::where('nombre','LIKE',$tipovehiculoanterior->tipo)->first();
                    if(!isset($NuevoTipoVehiculo)){
                        $NuevoTipoVehiculo = new TiposVehiculoConcepto();
                        $NuevoTipoVehiculo->id_antes = $tipovehiculoanterior->id;
                        $NuevoTipoVehiculo->nombre = $tipovehiculoanterior->tipo;
                        $NuevoTipoVehiculo->cilindros = 0;
                        $NuevoTipoVehiculo->save();
                    }
                    if(!NuevoTipoVehiculoConceptoDisponible::where('tipos_vehiculo_concepto_id',$NuevoTipoVehiculo->id)->where('modulo_id',$modulo)->where('zona_id',$sucursalnueva)->where('contrato_id',$contrato)->where('anio',2025)->exists()){
                        $agregartipodisponible=new NuevoTipoVehiculoConceptoDisponible();
                        $agregartipodisponible->tipos_vehiculo_concepto_id = $NuevoTipoVehiculo->id;
                        $agregartipodisponible->modulo_id = $modulo;
                        $agregartipodisponible->zona_id = $sucursalnueva;
                        $agregartipodisponible->contrato_id = $contrato;
                        $agregartipodisponible->anio = 2025;
                        $agregartipodisponible->save();
                    }
                }
                $conceptosanteriores=ConceptoCfbNormales::get();
                NuevosConceptos::query()->update(['id_antes' => 0]);
                $tipovhc=8;

                foreach ($conceptosanteriores as $conceptoanterior) {
                    $nombreanterior=VehiculosConceptoCfbNormales::where('id',$conceptoanterior->pTipos_id)->first();
                    $tipovehiculo= TiposVehiculoConcepto::where('nombre','LIKE',$nombreanterior->tipo?? 'DESCCONOCIDO')->first();
                    $satcateg=CategoriasSat::where('codigo_sat',$conceptoanterior->codigo_sat)->first();
                    $satuni=UnidadSatModel::where('clave',$conceptoanterior->codigo_unidad)->first();

                    $NuevoConcepto = new NuevosConceptos();
                    $NuevoConcepto->id_antes = $conceptoanterior->id;
                    $NuevoConcepto->Categorias_id = $categoriasconceptoscorregidas[$conceptoanterior->pCategorias_id]??20;
                    $NuevoConcepto->Tipos_id =$tipovehiculo?$tipovehiculo->id:000;
                    $NuevoConcepto->num =$conceptoanterior->num;
                    $NuevoConcepto->descripcion =$conceptoanterior->descripcion;
                    $NuevoConcepto->p_refaccion =$conceptoanterior->p_refaccion;
                    $NuevoConcepto->p_mo =$conceptoanterior->p_mo;
                    $NuevoConcepto->p_total =$conceptoanterior->p_total;
                    $NuevoConcepto->modulo_id = $modulo;
                    $NuevoConcepto->zona_id = $sucursalnueva;
                    $NuevoConcepto->contrato_id = $contrato;
                    $NuevoConcepto->anio = 2025;
                    $NuevoConcepto->Categoria_sat_id =$satcateg->id ?? 272;
                    $NuevoConcepto->unidades_sat_id =$satuni->id ?? 1;
                    $NuevoConcepto->save();
                    $tipovhc=$NuevoConcepto->Tipos_id;
                }
                $elementostotales = presupuestosCfbNormales::join('pVehiculos2023','presupuestos2023.pVehiculos_id','=','pVehiculos2023.id')
                ->join('pGenerales2023','presupuestos2023.pGenerales_id','=','pGenerales2023.id')
                ->join('empresas','presupuestos2023.empresa_id','=','empresas.id')
                        ->select(
                            'pGenerales2023.id as Generales_id',
                            'pGenerales2023.NSolicitud',
                            'pGenerales2023.OrdenServicio',
                            'pGenerales2023.KmDeIngreso',
                            'pGenerales2023.ClienteYRazonSocial',
                            'pGenerales2023.Mail',
                            'pGenerales2023.Telefono',
                            'pGenerales2023.Conductor',
                            'pGenerales2023.Fecha as FechaIngreso',
                            'pVehiculos2023.identificador',
                            'pVehiculos2023.modelo',
                            'pVehiculos2023.vin',
                            'pVehiculos2023.placas',
                            'pVehiculos2023.ano',
                            'pVehiculos2023.marca',
                            'presupuestos2023.id',
                            'presupuestos2023.descripcionMO',
                            'presupuestos2023.fechaDeVigencia',
                            'presupuestos2023.observaciones',
                            'presupuestos2023.user_id',
                            'presupuestos2023.factura_id',
                            'presupuestos2023.status',
                            'presupuestos2023.ubicacion',
                            'presupuestos2023.tdeentrega as descripciongeneral',
                            'presupuestos2023.empresa_id',
                            'presupuestos2023.created_at',
                            'presupuestos2023.updated_at',
                            )->where('presupuestos2023.eco_id',55)
                        ->orderBy('presupuestos2023.id', 'asc')->get();
                
                foreach ($elementostotales as $element) {
                    $vehiculo=Vehiculo::where('no_economico',$element->identificador)->first();
                    
                    $cadena = $element->NSolicitud;
                    if (preg_match('/\d/', $cadena)) {
                        $ultimo_caracter = substr($cadena, -1);
                        while (!ctype_digit($ultimo_caracter)) {
                            $cadena = substr($cadena, 0, -1);
                            $ultimo_caracter = substr($cadena, -1);
                        }
                    }
                    $detalles = DetallesGenerales::where('OrdenServicio','LIKE',$cadena.'%')->where('zona_id',$sucursalnueva)->where('contrato_id',$contrato)->where('modulo_id',$modulo)->first();
                    
                    if(!isset($detalles)){
                        $recepcionVehicular = ViejasRecepcionesVehiculares::where('modulo',7)->where('folioNum','LIKE',$cadena.'%')->first();

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
                        $detalles->Ubicacion =$element->ubicacion??'Sin Ubicacion';
                        $detalles->Fecha_Esperada = $recepcionVehicular ? $recepcionVehicular->fecha_compromiso : $element->FechaIngreso;
                        $detalles->Kilometraje_entrada = $element->KmDeIngreso;
                        $detalles->Gas_entrada = $recepcionVehicular ? $recepcionVehicular->gas_entrada : 0;
                        $detalles->Fecha_entrada = $element->FechaIngreso;
                        $detalles->Kilometraje_salida = $recepcionVehicular ? $recepcionVehicular->km_salida : null;
                        $detalles->Gas_salida = $recepcionVehicular ? $recepcionVehicular->gas_salida : null;
                        $detalles->Fecha_salida =$recepcionVehicular ? $recepcionVehicular->fecha_entrega : null;
                        $detalles->Vehiculo_id = $vehiculo->id;
                        $detalles->Tipo_Vehiculo_Concepto_id = $tipovhc;
                        $detalles->User_id = $element->user_id;
                        $detalles->User_update_id = $element->user_id;
                        $detalles->Empresa_id = $recepcionVehicular ? $recepcionVehicular->empresa_id : $element->empresa_id;
                        $detalles->Customer_id = $recepcionVehicular ? $recepcionVehicular->customer_id : 400;
                        $detalles->AdministradorTrasporte_id = $admintras->id;
                        $detalles->JefedeProceso_id = $jefproc->id;
                        $detalles->Trabajador_id = $trabajador->id;
                        $detalles->Telefono = $element->Telefono;
                        $detalles->contrato_id =$contrato;
                        $detalles->Indicaciones_cliente = $recepcionVehicular? $recepcionVehicular->indicaciones_del_cliente??'Sin Indicaciones Por Parte Del Cliente' : $element->descripciongeneral ?? 'Sin Indicaciones Por Parte Del Cliente';
                        $detalles->modulo_id =$modulo ;
                        $detalles->anio = 2025;
                        $detalles->zona_id = $sucursalnueva;
                        $detalles->created_at = $element->created_at;
                        $detalles->updated_at = $element->updated_at;
                        $detalles->save();

                        $recepcion = new NuevaRecepcionesVehiculares();
                        $ExterioresEquipo = new ExterioresEquipo();
                        $CondicionesPintura = new CondicionesPintura();
                        $EquipoInventario = new EquipoInventario();
                        $InterioresEquipo = new InterioresEquipo();
                        if(isset($recepcionVehicular)){
                            if(!NuevaRecepcionesVehiculares::where('id', $recepcionVehicular->id)->exists()){
                                LOG::info('Recepcion vehicular existe'. $cadena);
                                $origen = public_path('img/carros/'.$recepcionVehicular->carro); // Ruta de la imagen original
                                $origen2 = public_path('img/firmas/'.$recepcionVehicular->firma);
                                $extension = 'png'; 
                                $fileName = uniqid() . '.' . $extension;
                                $destino = public_path('storage/carros/'.$fileName);
                                $destino2 = public_path('storage/firmastaller/'.$fileName);

                                if (file_exists($origen)) {
                                    copy($origen, $destino);
                                }

                                if (file_exists($origen2)) {
                                    copy($origen2, $destino2);
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
                                    $data['tapetes'] <= 0 ? $data['tapetes']=3:$data['tapetes'] =$data['tapetes'];
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
                    if(!NuevosPresupuesto::where('Folio',$element->NSolicitud)->exists()){
                        $presupuesto = new NuevosPresupuesto();
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


                        $carrito=CarritoCfbNormales::where('presupuesto_id', $element->id)->get(); 
                        foreach ($carrito as $item) {
                            $concepto = NuevosConceptos::where('id_antes',$item->pConcepto_id)->first();
                            if(isset($concepto)){
                                $presupuestos = new PresupuestoCarrito();
                                $presupuestos->Presupuesto_id = $presupuesto->id;
                                $presupuestos->Concepto_id = $concepto->id;
                                $presupuestos->Cantidad = $item->cantidad;
                                $presupuestos->Costo = $item->precio;
                                $presupuestos->Venta = $item->precio_v;
                                $presupuestos->User_id = $item->usuario_id;
                                $presupuestos->User_Update_id = $item->usuario_id;
                                $presupuestos->created_at = $item->created_at;
                                $presupuestos->updated_at = $item->updated_at;
                                $presupuestos->save();
                            }

                        }
                    }else{
                        LOG::info('Presupuesto existe'. $element->id . ' o '. $element->NSolicitud);
                    }
                }
            }
            DB::commit();
            return response()->json(['success' => 'Creadas'], 200); 
        } catch (\Exception $e) {
            DB::rollBack();
            log::error($e);
            return response()->json(['error' => 'Error al migrar los datos: ' . $e->getMessage()], 500);
        }
            
    }
    public function migraterecepcionesECOForaneos(Request $request){
        try {
            DB::beginTransaction();
            $modulo = 5;
            $contrato=8;
            $sucursalnueva=12;

            $categoriasconceptoscorregidas=['1'=>6,'2'=>2,'3'=>14,'4'=>7,'5'=>6,'6'=>5,'7'=>10,'8'=>4,'9'=>3,'10'=>10,'11'=>5,'12'=>8,'13'=>9];
            $sucursales=[2];

            foreach ($sucursales as $sucursal) {
               
                $TiposVehiculosAnteriores=VehiculosConceptoCfbForaneos::get();
                TiposVehiculoConcepto::query()->update(['id_antes' => 0]);
                foreach ($TiposVehiculosAnteriores as $tipovehiculoanterior) {
                    
                    $NuevoTipoVehiculo = TiposVehiculoConcepto::where('nombre','LIKE',$tipovehiculoanterior->tipo)->first();
                    if(!isset($NuevoTipoVehiculo)){
                        $NuevoTipoVehiculo = new TiposVehiculoConcepto();
                        $NuevoTipoVehiculo->id_antes = $tipovehiculoanterior->id;
                        $NuevoTipoVehiculo->nombre = $tipovehiculoanterior->tipo;
                        $NuevoTipoVehiculo->cilindros = 0;
                        $NuevoTipoVehiculo->save();
                    }
                    if(!NuevoTipoVehiculoConceptoDisponible::where('tipos_vehiculo_concepto_id',$NuevoTipoVehiculo->id)->where('modulo_id',$modulo)->where('zona_id',$sucursalnueva)->where('contrato_id',$contrato)->where('anio',2025)->exists()){
                        $agregartipodisponible=new NuevoTipoVehiculoConceptoDisponible();
                        $agregartipodisponible->tipos_vehiculo_concepto_id = $NuevoTipoVehiculo->id;
                        $agregartipodisponible->modulo_id = $modulo;
                        $agregartipodisponible->zona_id = $sucursalnueva;
                        $agregartipodisponible->contrato_id = $contrato;
                        $agregartipodisponible->anio = 2025;
                        $agregartipodisponible->save();
                    }
                }
                $conceptosanteriores=ConceptoCfbForaneos::get();
                NuevosConceptos::query()->update(['id_antes' => 0]);
                $tipovhc=8;

                foreach ($conceptosanteriores as $conceptoanterior) {
                    $nombreanterior=VehiculosConceptoCfbForaneos::where('id',$conceptoanterior->pTipos_id)->first();
                    $tipovehiculo= TiposVehiculoConcepto::where('nombre','LIKE',$nombreanterior->tipo?? 'DESCCONOCIDO')->first();
                    $satcateg=CategoriasSat::where('codigo_sat',$conceptoanterior->codigo_sat)->first();
                    $satuni=UnidadSatModel::where('clave',$conceptoanterior->codigo_unidad)->first();

                    $NuevoConcepto = new NuevosConceptos();
                    $NuevoConcepto->id_antes = $conceptoanterior->id;
                    $NuevoConcepto->Categorias_id = $categoriasconceptoscorregidas[$conceptoanterior->pCategorias_id]??20;
                    $NuevoConcepto->Tipos_id =$tipovehiculo?$tipovehiculo->id:000;
                    $NuevoConcepto->num =$conceptoanterior->num;
                    $NuevoConcepto->descripcion =$conceptoanterior->descripcion;
                    $NuevoConcepto->p_refaccion =$conceptoanterior->p_refaccion;
                    $NuevoConcepto->p_mo =$conceptoanterior->p_mo;
                    $NuevoConcepto->p_total =$conceptoanterior->p_total;
                    $NuevoConcepto->modulo_id = $modulo;
                    $NuevoConcepto->zona_id = $sucursalnueva;
                    $NuevoConcepto->contrato_id = $contrato;
                    $NuevoConcepto->anio = 2025;
                    $NuevoConcepto->Categoria_sat_id =$satcateg->id ?? 272;
                    $NuevoConcepto->unidades_sat_id =$satuni->id ?? 1;
                    $NuevoConcepto->save();
                    $tipovhc=$NuevoConcepto->Tipos_id;
                }
                $elementostotales = presupuestosCfbForaneos::join('anexosFVehiculos','anexosforaneos.pVehiculos_id','=','anexosFVehiculos.id')
                ->join('anexosFGenerales','anexosforaneos.pGenerales_id','=','anexosFGenerales.id')
                ->join('empresas','anexosforaneos.empresa_id','=','empresas.id')
                        ->select(
                            'anexosFGenerales.id as Generales_id',
                            'anexosFGenerales.NSolicitud',
                            'anexosFGenerales.OrdenServicio',
                            'anexosFGenerales.KmDeIngreso',
                            'anexosFGenerales.ClienteYRazonSocial',
                            'anexosFGenerales.Mail',
                            'anexosFGenerales.Telefono',
                            'anexosFGenerales.Conductor',
                            'anexosFGenerales.Fecha as FechaIngreso',
                            'anexosFVehiculos.identificador',
                            'anexosFVehiculos.modelo',
                            'anexosFVehiculos.vin',
                            'anexosFVehiculos.placas',
                            'anexosFVehiculos.ano',
                            'anexosFVehiculos.marca',
                            'anexosforaneos.id',
                            'anexosforaneos.descripcionMO',
                            'anexosforaneos.fechaDeVigencia',
                            'anexosforaneos.observaciones',
                            'anexosforaneos.user_id',
                            'anexosforaneos.factura_id',
                            'anexosforaneos.status',
                            'anexosforaneos.ubicacion',
                            'anexosforaneos.tdeentrega as descripciongeneral',
                            'anexosforaneos.empresa_id',
                            'anexosforaneos.created_at',
                            'anexosforaneos.updated_at',
                            )->where('anexosforaneos.eco_id',1)
                        ->orderBy('anexosforaneos.id', 'asc')->get();
                
                foreach ($elementostotales as $element) {
                    $vehiculo=Vehiculo::where('no_economico',$element->identificador)->first();
                    
                    $cadena = $element->NSolicitud;
                    if (preg_match('/\d/', $cadena)) {
                        $ultimo_caracter = substr($cadena, -1);
                        while (!ctype_digit($ultimo_caracter)) {
                            $cadena = substr($cadena, 0, -1);
                            $ultimo_caracter = substr($cadena, -1);
                        }
                    }
                    $detalles = DetallesGenerales::where('OrdenServicio','LIKE',$cadena.'%')->where('zona_id',$sucursalnueva)->where('contrato_id',$contrato)->where('modulo_id',$modulo)->first();
                    
                    if(!isset($detalles)){
                        //$recepcionVehicular = ViejasRecepcionesVehiculares::where('folioNum','LIKE',$cadena.'%')->first();
                        $recepcionVehicular = NULL;

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
                        $detalles->Ubicacion =$element->ubicacion??'Sin Ubicacion';
                        $detalles->Fecha_Esperada = $recepcionVehicular ? $recepcionVehicular->fecha_compromiso : $element->FechaIngreso;
                        $detalles->Kilometraje_entrada = $element->KmDeIngreso;
                        $detalles->Gas_entrada = $recepcionVehicular ? $recepcionVehicular->gas_entrada : 0;
                        $detalles->Fecha_entrada = $element->FechaIngreso;
                        $detalles->Kilometraje_salida = $recepcionVehicular ? $recepcionVehicular->km_salida : null;
                        $detalles->Gas_salida = $recepcionVehicular ? $recepcionVehicular->gas_salida : null;
                        $detalles->Fecha_salida =$recepcionVehicular ? $recepcionVehicular->fecha_entrega : null;
                        $detalles->Vehiculo_id = $vehiculo->id;
                        $detalles->Tipo_Vehiculo_Concepto_id = $tipovhc;
                        $detalles->User_id = $element->user_id;
                        $detalles->User_update_id = $element->user_id;
                        $detalles->Empresa_id =$recepcionVehicular ? $recepcionVehicular->empresa_id : $element->empresa_id;
                        $detalles->Customer_id = $recepcionVehicular ? $recepcionVehicular->customer_id : 400;
                        $detalles->AdministradorTrasporte_id = $admintras->id;
                        $detalles->JefedeProceso_id = $jefproc->id;
                        $detalles->Trabajador_id = $trabajador->id;
                        $detalles->Telefono = is_numeric($element->Telefono)?$element->Telefono:4430000000;
                        $detalles->contrato_id =$contrato;
                        $detalles->Indicaciones_cliente = $recepcionVehicular? $recepcionVehicular->indicaciones_del_cliente??'Sin Indicaciones Por Parte Del Cliente' : $element->descripciongeneral ?? 'Sin Indicaciones Por Parte Del Cliente';
                        $detalles->modulo_id =$modulo ;
                        $detalles->anio = 2025;
                        $detalles->zona_id = $sucursalnueva;
                        $detalles->created_at = $element->created_at;
                        $detalles->updated_at = $element->updated_at;
                        $detalles->save();

                        $recepcion = new NuevaRecepcionesVehiculares();
                        $ExterioresEquipo = new ExterioresEquipo();
                        $CondicionesPintura = new CondicionesPintura();
                        $EquipoInventario = new EquipoInventario();
                        $InterioresEquipo = new InterioresEquipo();
                        if(isset($recepcionVehicular)){
                            if(!NuevaRecepcionesVehiculares::where('id', $recepcionVehicular->id)->exists()){
                                LOG::info('Recepcion vehicular existe'. $cadena);
                                $origen = public_path('img/carros/'.$recepcionVehicular->carro); // Ruta de la imagen original
                                $origen2 = public_path('img/firmas/'.$recepcionVehicular->firma);
                                $extension = 'png'; 
                                $fileName = uniqid() . '.' . $extension;
                                $destino = public_path('storage/carros/'.$fileName);
                                $destino2 = public_path('storage/firmastaller/'.$fileName);

                                if (file_exists($origen)) {
                                    copy($origen, $destino);
                                }

                                if (file_exists($origen2)) {
                                    copy($origen2, $destino2);
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
                                    $data['tapetes'] <= 0 ? $data['tapetes']=3:$data['tapetes'] =$data['tapetes'];
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
                    if(!NuevosPresupuesto::where('Folio',$element->NSolicitud)->exists()){
                        $presupuesto = new NuevosPresupuesto();
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


                        $carrito=CarritoCfbForaneos::where('presupuesto_id', $element->id)->get(); 
                        foreach ($carrito as $item) {
                            $concepto = NuevosConceptos::where('id_antes',$item->pConcepto_id)->first();
                            if(isset($concepto)){
                                $presupuestos = new PresupuestoCarrito();
                                $presupuestos->Presupuesto_id = $presupuesto->id;
                                $presupuestos->Concepto_id = $concepto->id;
                                $presupuestos->Cantidad = $item->cantidad;
                                $presupuestos->Costo = $item->precio;
                                $presupuestos->Venta = $item->precio_v;
                                $presupuestos->User_id = $item->usuario_id;
                                $presupuestos->User_Update_id = $item->usuario_id;
                                $presupuestos->created_at = $item->created_at;
                                $presupuestos->updated_at = $item->updated_at;
                                $presupuestos->save();
                            }

                        }
                    }else{
                        LOG::info('Presupuesto existe'. $element->id . ' o '. $element->NSolicitud);
                    }
                }
            }
            DB::commit();
            return response()->json(['success' => 'Creadas'], 200); 
        } catch (\Exception $e) {
            DB::rollBack();
            log::error($e);
            return response()->json(['error' => 'Error al migrar los datos: ' . $e->getMessage()], 500);
        }
            
    }
    public function MigrarDocumentos(){
        $tipos=TipoArchivoPresupuesto::get();
        $presupuestos=NuevosPresupuesto::with('detallesGenerales')->get();
        $modales1=[new FotosNuevas(),new FotosInstaladas(),new FotosViejas(),new ReporteAnomalias(),new OrdenEntrada(),new OrdenServicio(),new FacturaPDF(),new FacturaXML(),new Acuse()];
        $modales2=[new FotosNuevas2(),new FotosInstaladas2(),new FotosViejas2(),new ReporteAnomalias2(),new OrdenEntrada2(),new OrdenServicio2(),new FacturaPDF2(),new FacturaXML2(),new Acuse2()];
        $modales3=[new FotosNuevas2023(),new FotosInstaladas2023(),new FotosViejas2023(),new ReporteAnomalias2023(),new OrdenEntrada2023(),new OrdenServicio2023(),new FacturaPDF2023(),new FacturaXML2023(),new Acuse2023()];
        $modales4=[new anexosFFotosNuevas(),new AnexosFFotosInstaladas(),new anexosFFotosViejas(),new anexosFRA(),new anexosFOE(),new AnexosFOS(),new AnexosFPDF(),new AnexosFXML(),new AnexosFAcuses()];

        $carpetasviejas=['fotosnuevas/','fotosinstaladas/','fotosviejas/','reporteanomalias/','ordenentrada/','ordenservicio/','facturapdf/','facturaxml/','acuse/'];
        foreach($presupuestos as $presupuesto){
            $modulo=$presupuesto->detallesGenerales->modulo_id;
            $zona=$presupuesto->detallesGenerales->zona_id;
            if($modulo==3){
                $presupuestodataviejo=presupuestosCFE::where('pCFEGenerales_id',pCFEGenerales::where('NSolicitud',$presupuesto->Folio)->value('id'))->first();
            }else{
                if($zona==13){
                    $presupuestodataviejo=presupuestosCfbGenerales::where('pGenerales_id',pGenerales::where('NSolicitud',$presupuesto->Folio)->value('id'))->first();

                }else if($zona==11 || $zona==14){
                    $presupuestodataviejo=presupuestosCfbNormales::where('pGenerales_id',pGenerales2023::where('NSolicitud',$presupuesto->Folio)->value('id'))->first();

                }else{
                    $presupuestodataviejo=presupuestosCfbForaneos::where('pGenerales_id',anexosFGenerales::where('NSolicitud',$presupuesto->Folio)->value('id'))->first();

                }
            }
            if(!$presupuestodataviejo){
                if($modulo==3){
                    $presupuestodataviejo=presupuestosCFE::where('pCFEGenerales_id',pCFEGenerales::where('NSolicitud',$presupuesto->Folio)->orderbydesc('id')->value('id'))->first();
                }else{
                if($zona==13){
                    $presupuestodataviejo=presupuestosCfbGenerales::where('pGenerales_id',pGenerales::where('NSolicitud',$presupuesto->Folio)->orderbydesc('id')->value('id'))->first();

                }else if($zona==11 || $zona==14){
                    $presupuestodataviejo=presupuestosCfbNormales::where('pGenerales_id',pGenerales2023::where('NSolicitud',$presupuesto->Folio)->orderbydesc('id')->value('id'))->first();

                }else{
                    $presupuestodataviejo=presupuestosCfbForaneos::where('pGenerales_id',anexosFGenerales::where('NSolicitud',$presupuesto->Folio)->orderbydesc('id')->value('id'))->first();

                }}
                if(!$presupuestodataviejo){
                    if($presupuesto->id ==4049){
                        $presupuestodataviejo=presupuestosCFE::where('pCFEGenerales_id',7337)->first();
                    
                    if($presupuestodataviejo){
                        foreach($tipos as $tipo){
                            if($modulo==3){
                                $ModalCorrespondiente=$modales1[$tipo->id-1];
                                $carpetavieja=$carpetasviejas[$tipo->id-1];
                            }else{
                                if($zona==13){
                                    $ModalCorrespondiente=$modales2[$tipo->id-1];
                                    $carpetavieja=$carpetasviejas[$tipo->id-1];
                                }else if($zona==11 || $zona==14){
                                    $ModalCorrespondiente=$modales3[$tipo->id-1];
                                    $carpetavieja=$carpetasviejas[$tipo->id-1];
                                }else{
                                    $ModalCorrespondiente=$modales4[$tipo->id-1];
                                    $carpetavieja=$carpetasviejas[$tipo->id-1];
                                }
                            }
                            if($modulo==3){
                                $archivoviejoexiste=$ModalCorrespondiente::where('presupuestoCFE_id',$presupuestodataviejo->id)->first();
                            }else{
                                $archivoviejoexiste=$ModalCorrespondiente::where('presupuesto_id',$presupuestodataviejo->id)->first();
                            }
                            if(!empty($archivoviejoexiste)){
                                $origen = public_path('documentos/'.$carpetavieja.$archivoviejoexiste->archivo);
                                $destino = public_path('storage/documentos/presupuestos/'.$tipo->Carpeta.'/'.$archivoviejoexiste->archivo);

                                if (file_exists($origen)) {
                                    copy($origen, $destino);
                                }
                                $ArchivoPresupuesto = new ArchivosPresupuesto();
                                $ArchivoPresupuesto->Presupuesto_id = $presupuesto->id;
                                $ArchivoPresupuesto->Tipo_archivo_id = $tipo->id;
                                $ArchivoPresupuesto->Nombre = $archivoviejoexiste->archivo;
                                $ArchivoPresupuesto->save(); 
                            }
                            
                        }
                    }
                    else{
                        log::info('enserio no existe'.$presupuesto->Folio);
                        log::info('modulo'.$modulo);
                        log::info('zona'.$zona);
                        log::info('fecha'.$presupuesto->created_at);
                    }
                    }
                }
            }
        }
        return response()->json(['message'=>'listo']);
    }
    public function ImportConceptos()
    {
        try {
            DB::beginTransaction();
            $creados = 0;
            $files=['conceptos_contrato_24.xlsx','conceptos_contrato_25.xlsx','conceptos_contrato_26.xlsx'];
            foreach($files as $file){
                $pathorigin = Storage::disk('public')->path("pruebas/files_up/{$file}");
                $spreadsheet = IOFactory::load($pathorigin);
                $sheet = $spreadsheet->getActiveSheet();
                $data = [];
                foreach ($sheet->getRowIterator() as $row) {
                    $rowData = [];
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(false);
                    foreach ($cellIterator as $cell) {
                        $rowData[] = $cell->getValue();
                    }
                    if(!empty($rowData[0])){
                        $data[] = $rowData;
                    }
                }
                foreach($data as$index=> $row){
                    if($index==0){
                        continue;
                    }
                    $concepto = new NuevosConceptos();
                    $tipo=TiposVehiculoConcepto::where('nombre','LIKE',$row[1])->first();
                    if(!$tipo){
                        $tipo=TiposVehiculoConcepto::create([
                            'nombre'=>$row[1],
                            'cilindros'=>4
                        ]);
                    }
                    if(!NuevoTipoVehiculoConceptoDisponible::where('tipos_vehiculo_concepto_id',$tipo->id)->where('modulo_id',$row[9])->where('zona_id',$row[11])->where('contrato_id',$row[10])->where('anio',$row[12])->exists()){
                        $agregartipodisponible=new NuevoTipoVehiculoConceptoDisponible();
                        $agregartipodisponible->tipos_vehiculo_concepto_id = $tipo->id;
                        $agregartipodisponible->modulo_id = $row[9];
                        $agregartipodisponible->zona_id = $row[11];
                        $agregartipodisponible->contrato_id = $row[10];
                        $agregartipodisponible->anio = $row[12];
                        $agregartipodisponible->save();
                    }

                    $data = [
                        'anio' => $row[12],
                        'Categoria_sat_id' => $row[7],
                        'unidades_sat_id' => $row[8],
                        'num' => $row[0],
                        'Categorias_id' => $row[6],
                        'Tipos_id' => $tipo->id,
                        'modulo_id' => $row[9],
                        'contrato_id' => $row[10],
                        'zona_id' => $row[11],
                        'p_mo' => $row[3] ?? 0,
                        'p_refaccion' => $row[4] ?? 0,
                        'p_total' => $row[5] ?? 0,
                        'descripcion' => trim($row[2] ?? 'No Especificada'),
                        'g_tiempo' => $row[13] ?? null,
                        'g_kilometros' => $row[14] ?? null,
                    ];
                    $concepto->fill($data);
                    $concepto->save();
                    $creados++;
                }
                // Encabezados
                $headers = [
                    '0NUM','1TIPO','2DESCRIPCION','3MO','4REFACCION','5TOTAL',
                    '6CATEGORIA','7SAT','8UNIDAD','9MODULO','10CONTRATO','11ZONA','12ANIO'
                ];
            }
            DB::commit();
            return response()->json([
                'message' => 'listo',
                'Creados' => $creados
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
