<?php

namespace App\Http\Controllers\zcrat;
use PhpOffice\PhpSpreadsheet\IOFactory;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Customer as Cliente;
use App\Models\TiposDisponibles2 as NuevoTipoVehiculoConceptoDisponible;
use App\Models\Contratos;
use App\Models\Modulo;
use App\Models\Sucursales;
use App\Models\DetallesGenerales;
use App\Models\Presupuesto;
use App\Models\UnidadSatModel;
use App\Models\MensajesPresupuesto;
use App\Models\CategoriasDisponibles;
use App\Models\Conceptos;
use App\Models\PresupuestoCarrito;
use App\Models\RecepcionesVehiculares;
use App\Models\CondicionesPintura;
use App\Models\EquipoInventario;
use App\Models\ExterioresEquipo;
use App\Models\InterioresEquipo;
use App\Models\UsersTaller;
use App\Models\ArchivosPresupuesto;
use App\Models\TipoArchivoPresupuesto;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Color;
use App\Models\Vehiculo;
use App\Models\Tecnicos;
use App\Models\ContratosPerZona;
use App\Models\PagosPresupuestos;
use App\Models\PresupuestosRestringidos;
use App\Models\HojaConcepto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\log;
use Illuminate\Support\Facades\Storage;
use App\Models\claves;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class PresuspuestosController extends Controller
{
    public $Regimenes = [
        ['id' => '601', 'nombre' => '601 - General de Ley Personas Morales'],
        ['id' => '603', 'nombre' => '603 - Personas Morales con Fines no Lucrativos'],
        ['id' => '605', 'nombre' => '605 - Sueldos y Salarios e Ingresos Asimilados a Salarios'],
        ['id' => '606', 'nombre' => '606 - Arrendamiento'],
        ['id' => '607', 'nombre' => '607 - Régimen de Enajenación o Adquisición de Bienes'],
        ['id' => '608', 'nombre' => '608 - Demas Ingresos'],
        ['id' => '609', 'nombre' => '609 - Consolidación'],
        ['id' => '610', 'nombre' => '610 - Residentes en el Extranjero sin Establecimiento Permanente en México'],
        ['id' => '611', 'nombre' => '611 - Ingresos por Dividendos (Socios y Accionistas)'],
        ['id' => '612', 'nombre' => '612 - Personas Físicas con Actividades Empresariales y Profesionales'],
        ['id' => '614', 'nombre' => '614 - Ingresos por Intereses'],
        ['id' => '615', 'nombre' => '615 - Régimen de los ingresos por obtención de premios'],
        ['id' => '616', 'nombre' => '616 - Sin Obligaciones Fiscales'],
        ['id' => '620', 'nombre' => '620 - Sociedades Cooperativas de Producción que optan por diferir sus ingresos'],
        ['id' => '621', 'nombre' => '621 - Incorporación Fiscal'],
        ['id' => '622', 'nombre' => '622 - Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras'],
        ['id' => '623', 'nombre' => '623 - Opcional para Grupos de Sociedades'],
        ['id' => '624', 'nombre' => '624 - Coordinados'],
        ['id' => '625', 'nombre' => '625 - Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas'],
        ['id' => '626', 'nombre' => '626 - Régimen Simplificado de Confianza'],
        ['id' => '628', 'nombre' => '628 - Hidrocarburos'],
        ['id' => '629', 'nombre' => '629 - De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales'],
        ['id' => '630', 'nombre' => '630 - Enajenación de acciones en bolsa de valores']
    ];
    public function VistaPresupuesto(Request $request){
        $Regimenes= $this->Regimenes;
        $unidades=UnidadSatModel::get();
        $contrato = $request->contrato;
        $modulo = $request->modulo;
        $zona = $request->zona;
        $mod = $modulo;
        $con = $contrato;
        $zon = $zona;
        $anio = $request->anio;
        $empresas=Empresa::select('id','nombre')->get();
        $contrato = Contratos::where('nombre', $contrato)->value('id');
        $modulo = Modulo::where('descripcion', $modulo)->value('id');
        $zona = Sucursales::where('nombre', $zona)->value('id');
        $ids = DetallesGenerales::where('modulo_id',$modulo)->where('zona_id',$zona)->where('contrato_id','=',$contrato)->where('anio','=',$anio)->orderBy('id','desc')->pluck('id');
        $elementostotales = Presupuesto::whereIn('DetallesGenerales_id',$ids)->count();
       return view('zcrat.PresupuestosAdmin',compact('elementostotales','modulo','anio','unidades','empresas','mod','con','zona','zon','contrato'));
        }
    public function VistaPresupuestoEstatus(Request $request){
        $Regimenes= $this->Regimenes;
        $unidades=UnidadSatModel::get();
        $contrato = $request->contrato;
        $modulo = $request->modulo;
        $zona = $request->zona;
        $mod = $modulo;
        $con = $contrato;
        $zon = $zona;
        $estatus = $request->estatus;
        $anio = $request->anio;
        $empresas=Empresa::select('id','nombre')->get();
        $contrato = Contratos::where('nombre', $contrato)->value('id');
        $modulo = Modulo::where('descripcion', $modulo)->value('id');
        $zona = Sucursales::where('nombre', $zona)->value('id');
        $ids = DetallesGenerales::where('modulo_id',$modulo)->where('zona_id',$zona)->where('contrato_id','=',$contrato)->where('anio','=',$anio)->orderBy('id','desc')->pluck('id');
        $elementostotales = Presupuesto::whereIn('DetallesGenerales_id',$ids)->where('Status_id',$estatus)->count();
       return view('zcrat.PresupuestosAdminConEstatus',compact('elementostotales','modulo','estatus','anio','unidades','empresas','mod','con','zona','zon','contrato'));
    }
    public function VistaPresupuesto2(Request $request){
        $Regimenes= $this->Regimenes;
        $unidades=UnidadSatModel::get();
        $contrato = $request->contrato;
        $modulo = $request->modulo;
        $zona = $request->zona;
        $mod = $modulo;
        $con = $contrato;
        $zon = $zona;
        $anio = $request->anio;
        $empresas=Empresa::select('id','nombre')->get();
        $contrato = Contratos::where('nombre', $contrato)->value('id');
        $modulo = Modulo::where('descripcion', $modulo)->value('id');
        $zona = Sucursales::where('nombre', $zona)->value('id');
        $ids = DetallesGenerales::where('modulo_id',$modulo)->where('zona_id',$zona)->where('contrato_id','=',$contrato)->where('anio','=',$anio)->orderBy('id','desc')->pluck('id');
        $elementostotales = Presupuesto::whereIn('DetallesGenerales_id',$ids)->count();
        return view('zcrat.PresupuestosAdmin2',compact('elementostotales','modulo','anio','unidades','empresas','mod','con','zona','zon','contrato'));
    }
    public function VistaRestringida(Request $request){
        if (in_array($request->user()->id, [170,171,1]) || $request->user()->can('ver.todos.presupuestos.restringidos'))  {
            $elementostotales = Presupuesto::count();
            return view('zcrat.PresupuestosRestringidosAdmin',compact('elementostotales'));
        }
        $elementostotales = PresupuestosRestringidos::where('user_id',$request->user()->id??0)->count();
        return view('zcrat.PresupuestosRestringidos',compact('elementostotales'));
    }
    public function AsignarUsuario(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $request->validate([
            'PresupuestoIdRestringido' => ['required','exists:presupuestosnuevos,id'],
            'UsuarioIdRestringido' => ['required','exists:users,id'],
            ], [
            'PresupuestoIdRestringido.required' => 'El campo Presupuesto es obligatorio.',
            'PresupuestoIdRestringido.exists' => 'El Presupuesto seleccionado no existe.',
            'UsuarioIdRestringido.required' => 'El campo Usuario es obligatorio.',
            'UsuarioIdRestringido.exists' => 'El Usuario seleccionado no existe.',
        ]);
        $asignacion = PresupuestosRestringidos::where('presupuesto_id',$request->PresupuestoIdRestringido)->first();
        if($asignacion){
            $asignacion->user_id = $request->UsuarioIdRestringido;
        }else{
            $asignacion = new PresupuestosRestringidos();
            $asignacion->presupuesto_id = $request->PresupuestoIdRestringido;
            $asignacion->user_id = $request->UsuarioIdRestringido;
        }
        $asignacion->save();
        return response()->json(['message' => 'Asignación realizada con éxito.']);
    }
    public function VistaConsultaPresupuesto(Request $request){$elementostotales = Presupuesto::count();
       return view('zcrat.ConsultaPresupuestosTotales',compact('elementostotales'));
    }  
    public function VistaPresupuestotaller(Request $request){
        $Regimenes= $this->Regimenes;
        $unidades=UnidadSatModel::get();
        $contrato = $request->contrato;
        $modulo = $request->modulo;
        $zona = $request->zona;
        $mod = $modulo;
        $con = $contrato;
        $zon = $zona;
        $anio = $request->anio;
        $empresas=Empresa::select('id','nombre')->get();
        $contrato = Contratos::where('nombre', $contrato)->value('id');
        $modulo = Modulo::where('descripcion', $modulo)->value('id');
        $zona = Sucursales::where('nombre', $zona)->value('id');
        $ids = DetallesGenerales::where('modulo_id',$modulo)->where('zona_id',$zona)->where('contrato_id','=',$contrato)->where('anio','=',$anio)->orderBy('id','desc')->pluck('id');
        $elementostotales = Presupuesto::whereIn('DetallesGenerales_id',$ids)->count();
            return view('zcrat.PresupuestoTaller',compact('elementostotales','modulo','anio','unidades','empresas','mod','con','zona','zon','contrato'));
        
    }
    public function ObtenerConsultaPresupuestos(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $estatus = $request->estatus;
        $empresas = $request->empresas;
        $fechamin = $request->fechamin;
        $fechamax = $request->fechamax;
        $elementsperpege = $request->elements ??10 ;
        $page = $request->page;
        $search = $request->search;
        $elements = Presupuesto::withTrashed()
        ->with(['estatus:id,nombre',
        'detallesGenerales:id,OrdenServicio,OrdenSeguimiento,Vehiculo_id,modulo_id,zona_id,contrato_id,anio,Empresa_id',
        'detallesGenerales.contrato:id,nombre',
        'detallesGenerales.zona:id,nombre',
        'detallesGenerales.modulo:id,descripcion', 
        'pagos', 
        'user:id,name'])
        ->select('id',
        'Folio',
        'User_update_id',
        'DetallesGenerales_id',
        'created_at',
        'Status_id',
        'Factura_id',
        'Fecha_Pagado',
        'Importe_Pagado',
        'deleted_at')->orderBy('id',
        'desc')
        ->where(function($query) use ($search, $estatus, $empresas, $fechamin, $fechamax) {
            if ($search) {
                $query->where('Folio', 'like', '%' . $search . '%')
                ->orWhereHas('detallesGenerales', function($q) use ($search) {
                          $q->where('OrdenServicio', 'like', '%' . $search . '%')
                            ->orWhere('OrdenSeguimiento', 'like', '%' . $search . '%')
                            ->orWhereHas('Vehiculo', function($veh) use ($search) {
                                $veh->where('no_economico', 'like', '%' . $search . '%')
                                    ->orWhere('placas', 'like', '%' . $search . '%') 
                                    ->orWhere('vim', 'like', '%' . $search . '%')
                                    ->orWhereHas('marca', function($mar) use ($search) {
                                        $mar->where('nombre', 'like', '%' . $search . '%');
                                    })->orWhereHas('modelo', function($mod) use ($search) {
                                        $mod->where('nombre', 'like', '%' . $search . '%');
                                    });

                            });
                      });
            }
            if ($estatus) {
                $query->where('Status_id', $estatus);
            }
            if ($empresas) {
                $query->whereHas('detallesGenerales', function($q) use ($empresas) {
                    $q->where('Empresa_id', $empresas);
                });
            }
            if ($fechamin && $fechamax) {
                $query->whereBetween(DB::raw('DATE(created_at)'), [$fechamin, $fechamax]);
            } elseif ($fechamin) {
                $query->whereDate('created_at', '>=', $fechamin);
            } elseif ($fechamax) {
                $query->whereDate('created_at', '<=', $fechamax);
            }
        });
        $total= $elements->count();
        $elements = $elements
        ->skip(($page - 1) * ($elementsperpege))
        ->take($elementsperpege)
        ->get()
        ->map(function($item){
            $item->detallesGenerales->modulo_cortana;
            return $item;
        });
        
        return response()->json(['elements' => $elements,'countelements'=> $total]);
    }
    public function ObtenerUtilidadesPresupuestos(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $elements = Presupuesto::with(['detallesGenerales:id,OrdenServicio,Fecha_entrada,modulo_id,zona_id,anio,contrato_id,Vehiculo_id',
        'detallesGenerales.vehiculo:id,no_economico,placas','detallesGenerales.contrato:id,nombre','detallesGenerales.zona:id,nombre','detallesGenerales.modulo:id,descripcion','estatus:id,nombre'])
        ->select('presupuestosnuevos.DetallesGenerales_id','presupuestosnuevos.created_at','presupuestosnuevos.id','presupuestosnuevos.Folio','presupuestosnuevos.Status_id','presupuestosnuevos.Tipo_id',
        DB::raw('ROUND(SUM(carrito.Cantidad * carrito.Venta), 2) as subtotal_Final'),
        DB::raw('ROUND(SUM(carrito.Cantidad * carrito.Venta)*0.16, 2) as iva_Final'),
        DB::raw('ROUND(SUM(carrito.Cantidad * carrito.Venta)*1.16, 2) as total_Final'),
        DB::raw('ROUND(SUM(carrito.Cantidad * carrito.Costo), 2) as subtotal_Costo'),
        DB::raw('ROUND(SUM(carrito.Cantidad * carrito.Costo)*0.16, 2) as iva_Costo'),
        DB::raw('ROUND(SUM(carrito.Cantidad * carrito.Costo)*1.16 , 2)as total_Costo'),
        DB::raw('ROUND((SUM(carrito.Cantidad * carrito.Venta) - SUM(carrito.Cantidad * carrito.Costo)), 2)  as subtotal_Utilidades'),
        DB::raw('ROUND((SUM(carrito.Cantidad * carrito.Venta) - SUM(carrito.Cantidad * carrito.Costo))*0.16, 2)  as iva_Utilidades'),
        DB::raw('ROUND((SUM(carrito.Cantidad * carrito.Venta) - SUM(carrito.Cantidad * carrito.Costo))*1.16, 2)  as total_Utilidades'))
        ->join('Presupuesto_Carrito as carrito','presupuestosnuevos.id','=','carrito.presupuesto_id')
        ->whereNull('carrito.deleted_at')
        ->whereBetween('presupuestosnuevos.Status_id',[4,5])
        ->groupBy('presupuestosnuevos.DetallesGenerales_id',
        'presupuestosnuevos.id',
        'presupuestosnuevos.Folio',
        'presupuestosnuevos.Status_id',
        'presupuestosnuevos.created_at',
        'presupuestosnuevos.Tipo_id')
        ->orderBy('id','desc')->get();
        return response()->json(['elements' => $elements]);
    }

    public function RestaurarPresupuesto(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
       $presupuesto = Presupuesto::withTrashed()->find($request->id);

        if ($presupuesto) {

            
            $presupuesto->restore(); 
            if(Presupuesto::where('DetallesGenerales_id',$presupuesto->DetallesGenerales_id)->count() === 1){
                RecepcionesVehiculares::withTrashed()->where('DetallesGenerales_id',$presupuesto->DetallesGenerales_id)->restore(); 
            }
            }

        return response()->json(['message' => 'Presupuesto Restaurado']);
    }

    public function ObtenerPresupuestos(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $modulo = $request->modulo; // 'cfe, cfb etc'
        $contrato = $request->contrato;  // 'morelia deisel, morelia gasolina etc'
        $zona = $request->zona;
        $anio = $request->anio; 
        $estatus = $request->estatus; // '1,2,3 etc'

        $ids = DetallesGenerales::where('modulo_id',$modulo)->where('zona_id',$zona)->where('contrato_id','=',$contrato)->where('anio','=',$anio)->orderBy('id','desc')->pluck('id');
        $elements = Presupuesto::with(['detallesGenerales','mensajes','pagos'])->whereIn('DetallesGenerales_id',$ids);
        if($estatus != null && $estatus != '6'){
            $elements = $elements->where('Status_id',$estatus);
        }   
        $elements = $elements->orderBy('id','desc')->get();
        return response()->json(['elements' => $elements]);
    }
    public function GetPresupuestosRestringidos(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $user=$request->user()->id;
        $page=$request->currentPage;
        $itemsperpage=$request->itemsPerPage;
        $search=$request->search ;
        $estatus=$request->estatus; 
        $empresa=$request->empresa; 
        $estatusarchivo=$request->estatusarchivo ; 
        $usuario=$request->usuarios ; 
        $fechamin=$request->fechamin ; 
        $fechamax=$request->fechamax ; 
        if($page){
            $elements = Presupuesto::query()->with(['detallesGenerales.User','mensajes','mensajesNoLeidos','pagos','user_restringido.user','archivos','archivossemaforo']);

            if(!in_array($user, [170,171,1]) && !$request->user()->can('ver.todos.presupuestos.restringidos') || $usuario){
                $elements->whereHas('user_restringido', function ($q) use ($user, $usuario,$request) {
                    if (!in_array($user, [170,171,1]) && !$request->user()->can('ver.todos.presupuestos.restringidos')) {
                        $q->where('user_id', $user);
                    } elseif ($usuario) {
                        $q->where('user_id', $usuario);
                    }
                });
            }
            $elements=$elements->where(function($query) use ($search, $estatus, $estatusarchivo, $fechamin, $fechamax,$empresa) {
                if ($search) {
                    $query->where('Folio', 'like', '%' . $search . '%')
                    ->orWhereHas('detallesGenerales', function($q) use ($search) {
                        $q->where('OrdenServicio', 'like', '%' . $search . '%')
                        ->orWhere('OrdenSeguimiento', 'like', '%' . $search . '%')
                        ->orWhereHas('Vehiculo', function($veh) use ($search) {
                            $veh->where('no_economico', 'like', '%' . $search . '%')
                            ->orWhere('placas', 'like', '%' . $search . '%') 
                            ->orWhere('vim', 'like', '%' . $search . '%')
                            ->orWhereHas('marca', function($mar) use ($search) {
                                $mar->where('nombre', 'like', '%' . $search . '%');
                            })->orWhereHas('modelo', function($mod) use ($search) {
                                $mod->where('nombre', 'like', '%' . $search . '%');
                                });
                                
                            });
                        });
                    }
                if ($empresa) {
                    $query->WhereHas('detallesGenerales', function($q) use ($empresa) {
                        $q->where('Empresa_id',  $empresa);
                    });
                }
                if ($estatus) {
                    $query->where('Status_id', $estatus);
                }
                if ($fechamin && $fechamax) {
                    $query->whereBetween(DB::raw('DATE(created_at)'), [$fechamin, $fechamax]);
                } elseif ($fechamin) {
                    $query->whereDate('created_at', '>=', $fechamin);
                } elseif ($fechamax) {
                    $query->whereDate('created_at', '<=', $fechamax);
                }
                if($estatusarchivo){
                    if($estatusarchivo = 1 || 2 ){
                        $query->whereHas('archivossemaforo', function ($q) use ($estatusarchivo) {
                            if($estatusarchivo == 2){
                                $q->groupBy('Presupuesto_id')
                                ->havingRaw('COUNT(*) < 5');
                            }else{
                                $q->groupBy('Presupuesto_id')
                                ->havingRaw('COUNT(*) >= 5');
                            }
                        });
                    }else if($estatusarchivo = 3){
                        $query->whereDoesntHave('archivossemaforo');
                    }
                }
            });
            $total= (clone $elements)->count();
            $elements = $elements
            ->skip(($page - 1) * ($itemsperpage))
            ->take($itemsperpage)
            ->orderBy('PresupuestosNuevos.id','desc')
            ->get()
        ->map(function($item){
            $item->detallesGenerales->modulo_cortana;
            return $item;
        });

            return response()->json(['elements' => $elements,'totalelements'=>$total]);
        }else{
            if (!in_array($user, [170,171,1]) && !$request->user()->can('ver.todos.presupuestos.restringidos')) {
                $ids = PresupuestosRestringidos::where('user_id',$request->user()->id??0)->pluck('presupuesto_id');
            }else{
                $ids = PresupuestosRestringidos::pluck('presupuesto_id');
            }
            $elements = Presupuesto::with(['detallesGenerales.User','mensajes','mensajesNoLeidos','pagos','user_restringido.user','archivos'])->whereIn('id',$ids);
            $elements = $elements->orderBy('id','desc')->get();
            return response()->json(['elements' => $elements]);
        }
    }
    public function ObtenerGeneralData(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $ordservicio = $request->ordservicio; 
        $element = DetallesGenerales::with([
            'Empresa',
            'tipoVehiculo',
            'Customer',
            'AdministradorTrasporte',
            'JefedeProceso',
            'Trabajador',
            'Taller',
            'Vehiculo',])->where('OrdenServicio',$ordservicio)->first();
        return response()->json(['element' => $element]);
    }
    public function GetPagos(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $presupuesto = $request->presupuesto; 
        $elements = PagosPresupuestos::where('presupuesto_id',$presupuesto)->get();
        return response()->json(['elements' => $elements]);
    }
    public function GetConceptos(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $request->validate([
            'presupuesto' => ['required','exists:presupuestosnuevos,id'],
            ], [
            'presupuesto.required' => 'El presupuesto es obligatorio.',
            'presupuesto.exists' => 'El presupuesto seleccionado no es válido.'
        ]);
        $conceptos=PresupuestoCarrito::with('DatosConcepto.TipoVehiculo')->where("Presupuesto_id",$request->presupuesto)->orderBy('id', 'desc')->get();
        $detallesGeneralesId=Presupuesto::where("id",$request->presupuesto)->value('DetallesGenerales_id');
        $detallesGenerales=DetallesGenerales::with('tipoVehiculo')->where("id",$detallesGeneralesId)->first();
        
        $data=[
            'moduloId'=>$detallesGenerales->modulo_id,
            'zonaId'=>$detallesGenerales->zona_id,
            'contratoId'=>$detallesGenerales->contrato_id,
            'anio'=>$detallesGenerales->anio];
        return response()->json(['conceptos' => $conceptos,
        'data'=>$data]);
    }
    public function ObtenerPresupuesto(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $recepcion=Presupuesto::with([
            'detallesGenerales',
            'detallesGenerales.Vehiculo',
            'detallesGenerales.User',
            'detallesGenerales.User_update',
            'detallesGenerales.Empresa',
            'detallesGenerales.Customer',
            'detallesGenerales.AdministradorTrasporte',
            'detallesGenerales.JefedeProceso',
            'detallesGenerales.Trabajador',
            'detallesGenerales.tipoVehiculo',
            'detallesGenerales.Taller',
            'conceptos.DatosConcepto.TipoVehiculo'
            ])
            ->where("id",$request->input('id'))->orderBy('id', 'desc')->first();
        if($recepcion){
            return response()->json([
                'recepcion' => $recepcion
            ]);
        }else{
            return response()->json(['error' => 'La Recepcion Vehicular No Existe'],500);
        }
    }
    public function GetGlobalVar(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $id = $request->id;
        $presupuesto = Presupuesto::with(['detallesGenerales'])->find($id);
        if($presupuesto){
            return response()->json(['contrato' => $presupuesto->detallesGenerales->contrato_id,'zona' => $presupuesto->detallesGenerales->zona_id,'modulo' => $presupuesto->detallesGenerales->modulo_id,'anio' => $presupuesto->detallesGenerales->anio]);
        }else{
            return response()->json(['error' => 'El presupuesto no existe'], 404);
        }
    }
    public function Obtenermensajes(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        if ($request->filled('restringido')) {
            MensajesPresupuesto::where('presupuesto_id', $request->id)
                ->update(['read_at' => Carbon::now()]);
        }

        $messages = MensajesPresupuesto::where('presupuesto_id',$request->id)->get();
        return response()->json(['success' => $messages]);
    }
    public function Create(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $request->validate([
            'PreFol' => ['nullable', 'unique:presupuestosnuevos,Folio'],
            'PreObs' => ['nullable', 'string'],
            'PreOrdSer' => ['nullable', 'string', 'min:5'],
            'PreOrdSeg' => ['required', 'string', 'min:5'],
            'PreOrdOpc' => ['nullable', 'string'],
            'PreUbi' => ['required', 'string'],
            'PreFecEsp' => ['required', 'date'],
            'PreKmEnt' => ['required', 'numeric', 'min:0'],
            'PreGasEnt' => ['required', 'integer', 'between:0,4'],
            // 'PreFecEnt' => ['required', 'date'],
            'PreVeh' => ['required', 'exists:vehiculos,id'],
            'taller_orden_servicio' => ['nullable', 'exists:talleres,id'],
            'PreVehTip' => ['required', 'exists:tipos_vehiculo_concepto,id'],
            'PreEmp' => ['required', 'exists:empresas,id'],
            'PreCli' => ['required', 'exists:customers,id'],
            'PreAdmTra' => ['required', 'exists:users_taller,id'],
            'PreJefPro' => ['required', 'exists:users_taller,id'],
            'PreTra' => ['required', 'exists:users_taller,id'],
            'PreTel' => ['required', 'numeric', 'digits:10'],
            'PreIndCli' => ['nullable', 'string'],
            'contrato' => ['required', 'exists:contratos,id'],
            'modulo' => ['required', 'exists:modulos,id'],
            'anio' => ['required', 'numeric', 'min:2025'],
            'zona' => ['required', 'exists:sucursales,id'],
        ],[
            'PreFol.required' => 'El folio es obligatorio.',
            'PreFol.unique' => 'El folio ya existe en otro presupuesto.',
            'PreObs.string' => 'Las observaciones deben ser un texto válido.',
            'PreOrdSer.required' => 'La Orden de Servicio es obligatoria.',
            'PreOrdSer.min' => 'La Orden de Servicio debe tener exactamente 5 caracteres.',
            'PreOrdSeg.required' => 'La Orden de Seguimiento es obligatoria.',
            'PreOrdSeg.min' => 'La Orden de Seguimiento debe tener exactamente 5 caracteres.',
            'PreUbi.required' => 'La ubicación es obligatoria.',
            'PreFecEsp.required' => 'La fecha esperada es obligatoria.',
            'PreFecEsp.date' => 'La fecha esperada debe ser válida.',
            'PreKmEnt.required' => 'El kilometraje de entrada es obligatorio.',
            'PreKmEnt.numeric' => 'El kilometraje de entrada debe ser un número.',
            'PreKmEnt.min' => 'El kilometraje de entrada debe ser al menos 0.',
            'PreGasEnt.required' => 'El nivel de gasolina de entrada es obligatorio.',
            'PreGasEnt.integer' => 'El nivel de gasolina debe ser valido.',
            'PreGasEnt.between' => 'El nivel de gasolina debe ser valido.',
            'PreFecEnt.required' => 'La fecha de entrada es obligatoria.',
            'PreFecEnt.date' => 'La fecha de entrada debe ser válida.',
            'PreVeh.required' => 'El vehículo es obligatorio.',
            'PreVeh.exists' => 'El vehículo seleccionado no existe.',
            'PreVehTip.required' => 'El tipo de vehículo es obligatorio.',
            'PreVehTip.exists' => 'El tipo de vehículo seleccionado no existe.',
            'PreEmp.required' => 'La empresa es obligatoria.',
            'PreEmp.exists' => 'La empresa seleccionada no existe.',
            'PreCli.required' => 'El cliente es obligatorio.',
            'PreCli.exists' => 'El cliente seleccionado no existe.',
            'PreAdmTra.required' => 'El administrador de transporte es obligatorio.',
            'PreAdmTra.exists' => 'El administrador de transporte seleccionado no existe.',
            'PreJefPro.required' => 'El jefe de proceso es obligatorio.',
            'PreJefPro.exists' => 'El jefe de proceso seleccionado no existe.',
            'PreTra.required' => 'El trabajador es obligatorio.',
            'PreTra.exists' => 'El trabajador seleccionado no existe.',
            'PreTel.required' => 'El teléfono es obligatorio.',
            'PreTel.numeric' => 'El teléfono debe ser un número.',
            'PreTel.digits' => 'El teléfono debe tener exactamente 10 dígitos.',
            'PreIndCli.string' => 'Las indicaciones del cliente deben ser texto válido.',
            'contrato.required' => 'El contrato es obligatorio.',
            'contrato.exists' => 'El contrato seleccionado no existe.',
            'modulo.required' => 'El módulo es obligatorio.',
            'modulo.exists' => 'El módulo seleccionado no existe.',
            'anio.required' => 'El año es obligatorio.',
            'anio.numeric' => 'El año debe ser un número.',
            'anio.min' => 'El año debe ser 2025 o mayor.',
            'zona.required' => 'La zona es obligatoria.',
            'zona.exists' => 'La zona seleccionada no existe.',
            'taller_orden_servicio.exists' => 'El Taller seleccionada no existe.',
        ]);
        try {
            DB::beginTransaction();
            $idcontrato = $request->contrato;
            $idmodulo = $request->modulo;
            $idzona = $request->zona;
            $idanio = $request->anio;
            if(!$request->filled('PreOrdSer')){
                $num = RecepcionesVehiculares::withTrashed()->count();
                $clave=Claves::where('modulo_id',$idmodulo)->where('zona_id',$idzona)->value('clave');
                
                $numeroConCeros = str_pad($num+318, 5, "0", STR_PAD_LEFT);
                $clave = $clave.$numeroConCeros;
            }
            $detalles = DetallesGenerales::where('OrdenServicio',$request->PreOrdSer)->first();
            if(empty($detalles)){
                $user_taller=\Auth::user()->taller_id;
                $tallerid=$request->taller_orden_servicio ?? $user_taller;

                $userid=\Auth::user()->id;
                $detalles = new DetallesGenerales();
                $detalles->OrdenServicio = $request->filled('PreOrdSer')?$request->PreOrdSer:$clave;
                $detalles->OrdenSeguimiento =$request->PreOrdSeg;
                $detalles->Orden =$request->PreOrdOpc;
                $detalles->Ubicacion = $request->PreUbi;
                $detalles->Fecha_Esperada = $request->PreFecEsp;
                $detalles->Kilometraje_entrada = $request->PreKmEnt;
                $detalles->Gas_entrada =$request->PreGasEnt;
                $detalles->Fecha_entrada =Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
                $detalles->Vehiculo_id = $request->PreVeh;
                $detalles->Tipo_Vehiculo_Concepto_id =$request->PreVehTip;
                $detalles->User_id = $userid;
                $detalles->User_update_id = $userid;
                $detalles->Empresa_id = $request->PreEmp;
                $detalles->Customer_id = $request->PreCli;
                $detalles->AdministradorTrasporte_id = $request->PreAdmTra;
                $detalles->JefedeProceso_id = $request->PreJefPro;
                $detalles->Trabajador_id = $request->PreTra;
                $detalles->Telefono = $request->PreTel;
                $detalles->Indicaciones_cliente =$request->PreIndCli;
                $detalles->contrato_id = $request->contrato;
                $detalles->modulo_id = $request->modulo;
                $detalles->anio = $request->anio;
                $detalles->taller_id = $tallerid;
                $detalles->zona_id = $request->zona;
                $detalles->save();

                $origen = 'public/tiposauto/Vehiculo1.png'; // Ruta de la imagen original
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
                $recepcion = new RecepcionesVehiculares();
                $recepcion->DetallesGenerales_id = $detalles->id; // ID válido de DetallesGenerales
                $recepcion->Notas = $request->PreDesMO  ;
                $recepcion->Tecnico_id = 2;// ID válido de un técnico
                $recepcion->Firma = $fileName; // Ruta de la firma
                $recepcion->Carro = $fileName;
                $recepcion->save();

                $ExterioresEquipo = new ExterioresEquipo();
                $CondicionesPintura = new CondicionesPintura();
                $EquipoInventario = new EquipoInventario();
                $InterioresEquipo = new InterioresEquipo();

                $ExterioresEquipo->RecepcionVehicular_id= $recepcion->id;
                $CondicionesPintura->RecepcionVehicular_id= $recepcion->id;
                $EquipoInventario->RecepcionVehicular_id= $recepcion->id;
                $InterioresEquipo->RecepcionVehicular_id= $recepcion->id;

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
                
                $EquipoInventario->llanta = 0;
                $EquipoInventario->cubreruedas = 0;
                $EquipoInventario->cables_corriente = 0;
                $EquipoInventario->candado_ruedas = 0;
                $EquipoInventario->estuche_herramientas = 0;
                $EquipoInventario->gato = 0;
                $EquipoInventario->llave_tuercas = 0;
                $EquipoInventario->tarjeta_circulacion = 0;
                $EquipoInventario->triangulo_seguridad = 0;
                $EquipoInventario->extinguidor = 0;
                $EquipoInventario->placas = 0;
                $EquipoInventario->save();
                
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
                $InterioresEquipo->elevadores_eletricos = 3;
                $InterioresEquipo->luces_interiores = 3;
                $InterioresEquipo->seguros_eletricos = 3;
                $InterioresEquipo->tapetes = 3;
                $InterioresEquipo->climatizador = 3;
                $InterioresEquipo->radio = 3;
                $InterioresEquipo->espejos_retrovizor = 3;
                $InterioresEquipo->save();
                
                $CondicionesPintura->decolorada = 0;
                $CondicionesPintura->emblemas_completos = 0;
                $CondicionesPintura->color_no_igual = 0;
                $CondicionesPintura->logos = 0;
                $CondicionesPintura->exeso_rayones = 0;
                $CondicionesPintura->exeso_rociado = 0;
                $CondicionesPintura->pequenias_grietas = 0;
                $CondicionesPintura->danios_granizado = 0;
                $CondicionesPintura->carroceria_golpes = 0;
                $CondicionesPintura->lluvia_acido = 0;
                $CondicionesPintura->save();
            }
            $tipos=[1=>'P',2=>'C',3=>''];
            $presupuesto = new Presupuesto();
            $presupuesto->DetallesGenerales_id = $detalles->id;
            
            $presupuesto->Folio = $request->filled('PreOrdSer')?$request->filled('PreFol')?$request->PreFol:$request->PreOrdSer.$tipos[$request->PreSer]:$clave.$tipos[$request->PreSer];
            $presupuesto->Observaciones = $request->PreObs ?? 'DE ACUERDO A LO DIFICIL DE LA FALLA PARA SU REPARACION';
            $presupuesto->Mano_Obra_Descripcion = $request->PreDesMO;
            $presupuesto->Garantia = $request->PreGar ?? 'LO ESTIPULADO EN CONTRATO';
            $presupuesto->FechaDeVigencia = now();
            $presupuesto->Factura_id = 0;
            $presupuesto->Tipo_id =  $request->PreSer;
            $presupuesto->Status_id = 0;
            $presupuesto->User_update_id = Auth::user()->id;
            $presupuesto->save();

            if( $request->user()->can('presupuesto restringido automatico')){
                PresupuestosRestringidos::create([
                    'presupuesto_id' => $presupuesto->id,
                    'user_id' => Auth::user()->id
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'Presupuesto Creado Correctamente'], 200); 
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
            return response()->json(['message' => $e->getmessage()], 500); 
        }
    }
    public function Update(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $request->validate([
            'PreOrdSer' => ['required', 'string', 'min:5'],
            'PreFol' => ['required', 'unique:presupuestosnuevos,Folio,' . $request->PreId],
            'PreOrdSeg' => ['required', 'string', 'min:5'],
            'PreOrdOpc' => ['nullable', 'string'],
            'PreUbi' => ['required', 'string'],
            'PreGasEnt' => ['required', 'integer', 'between:0,4'],
            'PreKmEnt' => ['required', 'numeric', 'min:0'],
            'PreFecEsp' => ['required', 'date'],
            'PreAdmTra' => ['required', 'exists:users_taller,id'],
            'PreJefPro' => ['required', 'exists:users_taller,id'],
            'PreTel' => ['required', 'numeric', 'digits:10'],
            'PreTra' => ['required', 'exists:users_taller,id'],
            'PreSer' => ['required', 'in:1,2,3'],
            'PreEmp' => ['required', 'exists:empresas,id'],
            'PreCli' => ['required', 'exists:customers,id'],
            'PreIndCli' => ['nullable', 'string'],
            'PreDesMO' => ['nullable', 'string'],
            'PreGar' => ['required', 'string'],
            'PreObs' => ['required', 'string'],
            'PreVeh' => ['required', 'exists:vehiculos,id'],
            'PreVehTip' => ['required', 'exists:tipos_vehiculo_concepto,id'],
            'PreId' => ['required', 'exists:presupuestosnuevos,id'],
            'PreVehMod' => ['required', 'string'],
            'PreVehVim' => ['required', 'string', 'min:8'],
            'PreVehPla' => ['required', 'string', 'min:7'],
            'PreVehAnio' => ['required', 'numeric', 'digits:4'],
            'PreVehMar' => ['required', 'string']
        ], [
            'PreOrdSer.required' => 'El número de orden de servicio es obligatorio.',
            'PreOrdSer.min' => 'El número de orden de servicio debe tener al menos 5 caracteres.',
            'PreFol.required' => 'El Folio es obligatorio.',
            'PreFol.unique' => 'El Folio ingresado ya existe en otro presupuesto.',
            'PreOrdSeg.required' => 'El número de orden de seguimiento es obligatorio.',
            'PreOrdSeg.min' => 'El número de orden de seguimiento debe tener al menos 5 caracteres.',
            'PreUbi.required' => 'La ubicación es obligatoria.',
            'PreUbi.string' => 'La ubicación debe ser una cadena de texto.',
            'PreGasEnt.required' => 'El gasto de entrega es obligatorio.',
            'PreGasEnt.integer' => 'El gasto de entrega debe ser un número entero.',
            'PreGasEnt.between' => 'El gasto de entrega debe estar entre 0 y 4.',
            'PreFecEnt.required' => 'La fecha de entrega es obligatoria.',
            'PreFecEnt.date' => 'La fecha de entrega debe ser una fecha válida.',
            'PreKmEnt.required' => 'El kilometraje de entrega es obligatorio.',
            'PreKmEnt.numeric' => 'El kilometraje de entrega debe ser un número.',
            'PreKmEnt.min' => 'El kilometraje de entrega no puede ser negativo.',
            'PreFecEsp.required' => 'La fecha esperada es obligatoria.',
            'PreFecEsp.date' => 'La fecha esperada debe ser una fecha válida.',
            'PreAdmTra.required' => 'El administrador del taller es obligatorio.',
            'PreAdmTra.exists' => 'El administrador del taller seleccionado no es válido.',
            'PreJefPro.required' => 'El jefe de producción es obligatorio.',
            'PreJefPro.exists' => 'El jefe de producción seleccionado no es válido.',
            'PreTel.required' => 'El teléfono es obligatorio.',
            'PreTel.numeric' => 'El teléfono debe ser un número.',
            'PreTel.digits' => 'El teléfono debe tener exactamente 10 dígitos.',
            'PreTra.required' => 'El técnico responsable es obligatorio.',
            'PreTra.exists' => 'El técnico responsable seleccionado no es válido.',
            'PreSer.required' => 'El tipo de servicio es obligatorio.',
            'PreSer.in' => 'El tipo de servicio debe ser 1, 2 o 3.',
            'PreEmp.required' => 'La empresa es obligatoria.',
            'PreEmp.exists' => 'La empresa seleccionada no es válida.',
            'PreCli.required' => 'El cliente es obligatorio.',
            'PreCli.exists' => 'El cliente seleccionado no es válido.',
            'PreIndCli.string' => 'El indicativo del cliente debe ser una cadena de texto.',
            'PreDesMO.required' => 'La descripción de mano de obra es obligatoria.',
            'PreDesMO.string' => 'La descripción de mano de obra debe ser una cadena de texto.',
            'PreGar.required' => 'La garantía es obligatoria.',
            'PreGar.string' => 'La garantía debe ser una cadena de texto.',
            'PreObs.required' => 'Las observaciones son obligatorias.',
            'PreObs.string' => 'Las observaciones deben ser una cadena de texto.',
            'PreVeh.required' => 'El vehículo es obligatorio.',
            'PreVeh.exists' => 'El vehículo seleccionado no es válido.',
            'PreVehTip.required' => 'El tipo de vehículo es obligatorio.',
            'PreVehTip.exists' => 'El tipo de vehículo seleccionado no es válido.',
            'PreId.required' => 'El presupuesto es obligatorio.',
            'PreId.exists' => 'El presupuesto seleccionado no está disponible.',
            'PreVehMod.required' => 'El modelo del vehículo es obligatorio.',
            'PreVehMod.string' => 'El modelo del vehículo debe ser una cadena de texto.',
            'PreVehVim.required' => 'El número de identificación del vehículo (VIN) es obligatorio.',
            'PreVehVim.min' => 'El número de identificación del vehículo (VIN) debe tener al menos 8 caracteres.',
            'PreVehPla.required' => 'La placa del vehículo es obligatoria.',
            'PreVehPla.min' => 'La placa del vehículo debe tener al menos 7 caracteres.',
            'PreVehAnio.required' => 'El año del vehículo es obligatorio.',
            'PreVehAnio.numeric' => 'El año del vehículo debe ser un número.',
            'PreVehAnio.digits' => 'El año del vehículo debe tener exactamente 4 dígitos.',
            'PreVehMar.required' => 'La marca del vehículo es obligatoria.',
            'PreVehMar.string' => 'La marca del vehículo debe ser una cadena de texto.'
        ]);
        

        DB::beginTransaction();
        try {
            $Budget=Presupuesto::FindOrFail($request->PreId);
            $Budget->user_update_id=Auth::user()->id;
            $Budget->folio=$request->PreFol;
            $Budget->Tipo_id=$request->PreSer;
            $Budget->Garantia=$request->PreGar;
            $Budget->Observaciones=$request->PreObs;
            $Budget->Mano_Obra_Descripcion=$request->PreDesMO;
            $Budget->save();

            $detalles = DetallesGenerales::findOrFail($Budget->DetallesGenerales_id); // Encuentra el vehículo existente por su ID
            $detalles->OrdenServicio = $request->PreOrdSer;
            $detalles->OrdenSeguimiento =$request->PreOrdSeg;
            $detalles->Orden =$request->PreOrdOpc;
            $detalles->Ubicacion = $request->PreUbi;
            $detalles->Fecha_Esperada = $request->PreFecEsp;
            $detalles->Kilometraje_entrada = $request->PreKmEnt;
            $detalles->Gas_entrada =$request->PreGasEnt;
            $detalles->Vehiculo_id = $request->PreVeh;
            $detalles->Tipo_Vehiculo_Concepto_id =$request->PreVehTip;
            $detalles->User_update_id = \Auth::user()->id;
            $detalles->Empresa_id = $request->PreEmp;
            $detalles->Customer_id = $request->PreCli;
            $detalles->AdministradorTrasporte_id = $request->PreAdmTra;
            $detalles->JefedeProceso_id = $request->PreJefPro;
            $detalles->Trabajador_id = $request->PreTra;
            $detalles->Telefono = $request->PreTel;
            $detalles->Indicaciones_cliente =$request->PreIndCli;
            $detalles->save();

            $marca = Marca::where('nombre', 'LIKE', $request->PreVehMar)->first();
            if (!$marca) {
                $marca = Marca::create(['nombre' => $request->PreVehMar]);
            }
            $modelo = Modelo::where('nombre', 'LIKE',$request->PreVehMod)->where('marca_id', $marca->id)->first();
            if (!$modelo) {
                $modelo = Modelo::create(['nombre' => $request->PreVehMod, 'marca_id' => $marca->id]);
            }
            $vehiculo = Vehiculo::findOrFail($request->PreVeh); // Encuentra el vehículo existente por su ID
            $vehiculo->update([
                'marca_id' => $marca->id,
                'modelo_id' => $modelo->id,
                'anio' => $request->PreVehAnio,
                'vim' => $request->PreVehVim,
                'placas' => $request->PreVehPla,
            ]);
            DB::commit();
            return response()->json(['message' => 'Presupuesto Actualizado Correctamente'], 200); 
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getmessage()], 500); 
        }
    }
    public function CreateMessagePresupuesto(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $request->validate([
            'presupuesto'=>'required','exists:presupuestosnuevos,id',
            'message'=>'required'
        ],[
          'presupuesto.required'=>'EL Mensaje debe ser para un presupuesto',  
          'presupuesto.exists'=>'El Presupuesto Ya no esta disponible',  
          'message.required'=>'EL Mensaje Es Obligatorio',  
        ]);
        DB::beginTransaction();
        try {
            MensajesPresupuesto::create([
                'user_id'=>Auth::user()->id,
                'presupuesto_id'=>$request->presupuesto,
                'mensaje'=>$request->message,
            ]);
            DB::commit();
            return response()->json(['success' => 'Mensaje Creado Correctamente'], 200); 
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getmessage()], 500); 
        }
    }
    public function DeleteMessagePresupuesto(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $request->validate([
            'message'=>'required','exists:mensajespresupuesto,id',
        ],[
          'message.required'=>'EL Mensaje es Obligatorio',  
          'message.exists'=>'El Mensaje Ya no esta disponible',  
        ]);
        try {
            $message = MensajesPresupuesto::findorfail($request->message)->delete();
            return response()->json(['success' => 'Eliminado Correctamente'], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function ChangeStatusPresupuesto(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        if($request->has('id') && $request->has('estatus')){
            $cotizacion = Presupuesto::find($request->id);
            if($cotizacion){
                try{
                    $cotizacion->Status_id = $request->estatus;
                    $cotizacion->save();
                    DB::commit();
                    return response()->json(['success' => 'El Presupuesto se Envio Correctamente'], 200);
                } catch (Exception $e){
                    DB::rollBack();
                    return response()->json(['message' => $e->getmessage()], 500);
                }  
            }
            return response()->json(['message' => 'El Presupuesto No esta Activo'], 500);
        }
        return response()->json(['message' => 'No Se Envio Los Datos Correctamente Para Enviarlo '], 500); 
    }
    public function DeletePresupuesto(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        if($request->has('id')){ 
            try {
                DB::beginTransaction();
                $presupuesto = Presupuesto::findorfail($request->id);
                $presupuesto->User_update_id=Auth::user()->id;
                $presupuesto->save();
                $presupuesto->delete(); 
                DB::commit();
                return response()->json(['success' => 'Eliminado Correctamente'], 200);  
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => $e->getmessage()], 500); 
            }
        }
        else { return response()->json(['message' => 'No Se Envio El Presupuesto'], 500); }
    }
    public function DeletePresupuestoRestringido(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        if($request->has('id')){ 
            try {
                DB::beginTransaction();
                $presupuesto = Presupuesto::findorfail($request->id);
                $presupuesto->User_update_id=Auth::user()->id;
               
                $presupuesto->save();
                $presupuesto->delete();
                
                if(Presupuesto::where('DetallesGenerales_id',$presupuesto->DetallesGenerales_id)->count() === 0){
                    RecepcionesVehiculares::where('DetallesGenerales_id',$presupuesto->DetallesGenerales_id)->delete(); 
                }
                DB::commit();
                return response()->json(['success' => 'Eliminado Correctamente'], 200);  
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => $e->getmessage()], 500); 
            }
        }
        else { return response()->json(['message' => 'No Se Envio El Presupuesto'], 500); }
    }
    public function QuitarPago(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        if($request->has('id')){ 
            try {
                DB::beginTransaction();
                $presupuesto = PagosPresupuestos::findorfail($request->id);
                $presupuesto->delete(); 
                DB::commit();
                return response()->json(['success' => 'Eliminado Correctamente'], 200);  
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => $e->getmessage()], 500); 
            }
        }
        else { return response()->json(['message' => 'No Se Envio El Presupuesto'], 500); }
    }
    public function CatalogoConceptos(Request $request){
       
        if($request->has('id')){
            try {
                $presupuesto=Presupuesto::with('detallesGenerales')->findorfail($request->id);
                
                $detalles=DetallesGenerales::findorfail($presupuesto->DetallesGenerales_id);
                $categoriasdisponibles=CategoriasDisponibles::where('tipo_id',$presupuesto->Tipo_id)->pluck('categoria_id');
                $conceptos = Conceptos::with('TipoVehiculo')->Where('Tipos_id',$detalles->Tipo_Vehiculo_Concepto_id)->where('num','!=','FC')->Where('modulo_id',$detalles->modulo_id)->where('contrato_id',$detalles->contrato_id)->where('zona_id',$detalles->zona_id)->where('anio',$detalles->anio)->whereIn('Categorias_id',$categoriasdisponibles)->get();
                $conceptos2 = Conceptos::with('TipoVehiculo')->where('num','=','FC')->Where('modulo_id',$detalles->modulo_id)->where('contrato_id',$detalles->contrato_id)->where('zona_id',$detalles->zona_id)->where('anio',$detalles->anio)->whereIn('Categorias_id',$categoriasdisponibles)->orderby('Tipos_id')->get();
                //$conceptos2 = Conceptos::with('TipoVehiculo')->where('num','=','FC')->Where('modulo_id',$detalles->modulo_id)->where('anio',$detalles->anio)->whereIn('Categorias_id',$categoriasdisponibles)->orderby('Tipos_id')->get();
                $conceptosCombinados = $conceptos->merge($conceptos2);
                $tiposVehiculo = $conceptosCombinados->pluck('TipoVehiculo')
                    ->unique('id') // Aquí aseguras que sean únicos por ID
                    ->map(function ($tipo) {
                        if($tipo)
                        {return [
                            'id' => $tipo->id,
                            'nombre' => $tipo->nombre,
                        ];}else{
                            return [
                                'id'=>0,
                                'nombre'=>'SIN TIPO AFILIADO'          
                            ];
                        }
                    })
                    ->values();
                return response()->json([
                    'conceptos' => $conceptosCombinados,
                    'tipos' => $tiposVehiculo,
                ]);
            } catch (\Exception $e) {
                log::info($e);
                return response()->json(['message'=>$e->getmessage()],500);
            }
           
        }
       return response()->json(['message'=>'No Se Envia El Presupuesto'],500);
        
    }
    public function AgregarConceptoslast(Request $request){
        $productos = $request->productos;
        $idPresupuesto = $request->Presupuesto_id;
        $idsProductos = array_column($productos, 'id');
        $productosExistentes = PresupuestoCarrito::where('Presupuesto_id', $idPresupuesto)->pluck('Concepto_id')->toArray();
        $nuevosProductos = array_filter($productos, function ($producto) use ($productosExistentes) {
            return !in_array($producto['id'], $productosExistentes);
        });
        $productosRepetidos = array_filter($productos, function ($producto) use ($productosExistentes) {
            return in_array($producto['id'], $productosExistentes);
        });
        $presuspuesto = Presupuesto::findorfail($idPresupuesto);
        $orden=DetallesGenerales::findorfail($presupuesto->DetallesGenerales_id);

        foreach ($nuevosProductos as $producto) {
            try {
                $venta = Conceptos::findorfail($producto['id']);
                $dias=$venta->g_tiempo;
                $kilometraje=$venta->g_kilometraje;
                $garantia=false;
                if($dias || $kilometraje){
                    $ultimovez=DB::table('Presupuesto_Carrito as lc')
                    ->join('PresupuestosNuevos p','p.id','=','lc.Presupuesto_id')
                    ->join('DetallesGenerales d','d.id','=','p.DetallesGenerales_id')
                    ->where('d.Vehiculo_id',$orden->Vehiculo_id)
                    ->whereNotNull('d.Fecha_salida')
                    ->select('d.Fecha_salida,d.Kilometraje_salida,lc.garantia,lc.dictamen')
                    ->orderbydesc('lc.id')
                    ->first();
                    if(!$ultimovez->garantia || ($ultimovez->garantia && $ultimovez->dictamen)){
                        $kaplica=true;
                        $taplica=true;
                        if($kilometraje){
                            $diff=$orden->Kilometraje_entrada  - $ultimovez->Kilometraje_salida;
                            $kaplica=$kilometraje <= $diff;    
                        }
                        if($dias){
                            try{
                                $diff = (new DateTime($orden->Fecha_entrada))->diff(new DateTime($ultimovez->Fecha_entrada));
                                $taplica=$dias <= $diff->days;    
                            }catch(\Exception $e){
                                log::error($e);
                                $taplica=false;
                            }
                        }
                        $garantia=$taplica && $kaplica;
                    }
                }
                PresupuestoCarrito::create([
                    'Presupuesto_id' => $idPresupuesto,
                    'Concepto_id' => $producto['id'],
                    'Cantidad' => $producto['cantidad'],
                    'Costo' => $producto['precio'],
                    'Venta' => $venta->p_total,
                    'garantia'=>$garantia,
                    'User_id' => Auth::user()->id,
                    'User_Update_id' => Auth::user()->id,
                ]);
            } catch (\Exception $e) {
                log::error($e);
            }
        }
        $nombresRepetidos = Conceptos::whereIn('id', array_column($productosRepetidos, 'id'))->pluck('descripcion')->toArray();
        return response()->json(['existen' => $nombresRepetidos]);
    }
public function AgregarConceptos(Request $request)
{
    $productos = $request->productos;
    $idPresupuesto = $request->Presupuesto_id;

    $productosExistentes = PresupuestoCarrito::where('Presupuesto_id', $idPresupuesto)
        ->pluck('Concepto_id')
        ->toArray();

    $nuevosProductos = array_filter($productos, function ($producto) use ($productosExistentes) {
        return !in_array($producto['id'], $productosExistentes);
    });

    $productosRepetidos = array_filter($productos, function ($producto) use ($productosExistentes) {
        return in_array($producto['id'], $productosExistentes);
    });

    $presupuesto = Presupuesto::findOrFail($idPresupuesto);
    $orden = DetallesGenerales::findOrFail($presupuesto->DetallesGenerales_id);

    foreach ($nuevosProductos as $producto) {
        try {
            $venta = Conceptos::findOrFail($producto['id']);

            $meses = $venta->g_tiempo;
            $kilometraje = $venta->g_kilometros;
            $garantia = false;
            Log::info($meses);
            Log::info($kilometraje);

            if ($meses || $kilometraje) {
                log::info($producto['id']);
                log::info($orden->Vehiculo_id);
                $ultimovez = DB::table('Presupuesto_Carrito')
                    ->join('PresupuestosNuevos as p', 'p.id', '=', 'Presupuesto_Carrito.Presupuesto_id')
                    ->join('DetallesGenerales as d', 'd.id', '=', 'p.DetallesGenerales_id')
                    ->where('d.Vehiculo_id', $orden->Vehiculo_id)
                    ->where('Presupuesto_Carrito.Concepto_id', $producto['id'])
                    ->whereNotNull('d.Fecha_salida')
                    ->whereNull('Presupuesto_Carrito.deleted_at')
                    ->select(
                        'd.Fecha_salida as Fecha_salida',
                        'd.Kilometraje_salida as Kilometraje_salida',
                        'Presupuesto_Carrito.garantia as garantia',
                        'Presupuesto_Carrito.dictamen as dictamen'
                    )
                    ->orderByDesc('Presupuesto_Carrito.id')
                    ->first();
                    Log::info((array)$ultimovez);
                if (
                    $ultimovez && (
                    $ultimovez->garantia==0 || ($ultimovez->garantia==1 && is_null($ultimovez->dictamen)))
                ) {
                    $kaplica = true;
                    $taplica = true;

                    if ($kilometraje) {
                        $kmRecorridos = $orden->Kilometraje_entrada - $ultimovez->Kilometraje_salida;
                        $kaplica = $kmRecorridos <= $kilometraje;
                    }

                    if ($meses) {
                        $fechalimite = Carbon::parse($ultimovez->Fecha_salida)->addMonths($meses);
                        $fechaactual = Carbon::parse($orden->Fecha_entrada);
                        $taplica = $fechaactual->lessThanOrEqualTo($fechalimite);
                    }
                    $garantia = $taplica && $kaplica;
                }
            }

            PresupuestoCarrito::create([
                'Presupuesto_id' => $idPresupuesto,
                'Concepto_id' => $producto['id'],
                'Cantidad' => $producto['cantidad'],
                'Costo' => $producto['precio'],
                'Venta' => $venta->p_total,
                'garantia' => $garantia,
                'User_id' => Auth::user()->id,
                'User_Update_id' => Auth::user()->id,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    $nombresRepetidos = Conceptos::whereIn('id', array_column($productosRepetidos, 'id'))
        ->pluck('descripcion')
        ->toArray();

    return response()->json(['existen' => $nombresRepetidos]);
}
    public function QuitarConcepto(Request $request){
       if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        $concepto = $request->conceptoid;

            try {  
                DB::beginTransaction();
                $concepto = PresupuestoCarrito::findorfail($concepto);
                $concepto->User_Update_id=Auth::user()->id;
                $concepto->save();
                $concepto->delete();
                DB::commit();
                return response()->json(['message' => 'concepto eliminado del presupuesto correctamente'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getmessage()],500);
        }
        
    }
    public function UpdateConceptos(Request $request){
       
        $productos = $request->productos;
        if(empty($productos)){
            return response()->json(['message' => 'No Hay Nada Que Actualizar']);
        }
        foreach ($productos as $producto) {
            try {
                $cambio = false;
                $concepto = PresupuestoCarrito::findOrFail($producto['id']);
                $hasgarantiavalid= $concepto->garantia && !$concepto->dictamen;
                if($hasgarantiavalid) continue;
                if ($concepto->cantidad !== $producto['Cantidad']) {
                    $concepto->cantidad = $producto['Cantidad'];
                    $cambio = true;
                }
                if ($concepto->Costo !== $producto['Costo']) {
                    $concepto->Costo = $producto['Costo'];
                    $cambio = true;
                }
                if ($concepto->Venta !== $producto['Venta']) {
                    $concepto->Venta = $producto['Venta'];
                    $cambio = true;
                }
                if ($cambio) {

                    $concepto->User_Update_id = Auth::user()->id;
                    $concepto->save();
                }
        } catch (\Exception $e) {
            log::info($e->getmessage());
        }
        }
        return response()->json(['message' => 'Listado de Conceptos Actualizados']);
    }
    public function SendFile(Request $request){
        $request->validate([
            'file' => 'required|file|max:102400', // Máximo 10MB
            'tipo' => 'required|exists:tipos_archivos_presupuestos,id',
            'presupuesto' => 'required|exists:presupuestosnuevos,id'
        ], [
            'file.required' => 'El archivo es obligatorio.',
            'file.file' => 'El archivo debe ser un archivo válido.',
            'file.max' => 'El archivo no debe superar los 100MB.',
            'tipo.required' => 'El tipo de archivo es obligatorio.',
            'tipo.exists' => 'El tipo de archivo seleccionado no es válido.',
            'presupuesto.required' => 'El presupuesto es obligatorio.',
            'presupuesto.exists' => 'El presupuesto seleccionado no es válido.'
        ]);

        $archivo = $request->file('file');
        $tipo=$request->tipo;
        $presupuesto=$request->presupuesto;

        $TipoArchivo=TipoArchivoPresupuesto::find($tipo);
        $ruta='/public/documentos/presupuestos/'.$TipoArchivo->Carpeta;
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        try{
            DB::beginTransaction();
            $archivo->storeAs($ruta, $nombreArchivo);
            $ArchivoPresupuesto = ArchivosPresupuesto::where('Presupuesto_id',$presupuesto)->where('Tipo_archivo_id',$tipo)->first();
            if($ArchivoPresupuesto){
                $archivoExistente = $ruta . '/' . $ArchivoPresupuesto->Nombre;
                if (Storage::exists($archivoExistente)) {
                    Storage::delete($archivoExistente);
                }
            }else{
                $ArchivoPresupuesto = new ArchivosPresupuesto();
                $ArchivoPresupuesto->Presupuesto_id = $presupuesto;
            }
            $ArchivoPresupuesto->Tipo_archivo_id = $tipo;
            $ArchivoPresupuesto->Nombre = $nombreArchivo;
            $ArchivoPresupuesto->save();  

            DB::commit();
            return response()->json(['message' => 'Se Guardo Correctamente el archivo'], 200);
        } catch (Exception $e){
            if (Storage::exists($ruta.'/'.$nombreArchivo)) {
                Storage::delete($ruta.'/'.$nombreArchivo);
            }
            DB::rollBack();
            return response()->json(['message' => 'Ocurrió un error al guardar el archivo'], 500);
        }
    }
    public function SendFiles(Request $request){
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:102400', // Cada archivo máximo 100MB
            'tipo' => 'required|exists:tipos_archivos_presupuestos,id',
            'presupuesto' => 'required|exists:presupuestosnuevos,id'
        ], [
            'files.required' => 'Debes subir al menos un archivo.',
            'files.array' => 'Los archivos deben enviarse como una lista.',
            'files.*.file' => 'Cada elemento debe ser un archivo válido.',
            'files.*.max' => 'Cada archivo no debe superar los 100MB.',
            'tipo.required' => 'El tipo de archivo es obligatorio.',
            'tipo.exists' => 'El tipo de archivo seleccionado no es válido.',
            'presupuesto.required' => 'El presupuesto es obligatorio.',
            'presupuesto.exists' => 'El presupuesto seleccionado no es válido.'
        ]);

        $archivos = $request->file('files');
        $tipo=$request->tipo;
        $presupuesto=$request->presupuesto;

        $TipoArchivo=TipoArchivoPresupuesto::find($tipo);
        $ruta='/public/documentos/presupuestos/'.$TipoArchivo->Carpeta;
        try{
            DB::beginTransaction();
            foreach ($archivos as $archivo) {
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $archivo->storeAs($ruta, $nombreArchivo);
                $ArchivoPresupuesto = new ArchivosPresupuesto();
                $ArchivoPresupuesto->Presupuesto_id = $presupuesto;
                $ArchivoPresupuesto->Tipo_archivo_id = $tipo;
                $ArchivoPresupuesto->Nombre = $nombreArchivo;
                $ArchivoPresupuesto->save();
            }
            DB::commit();
            return response()->json(['message' => 'Se Guardo Correctamente el archivo'], 200);
        } catch (Exception $e){
            DB::rollBack();
            return response()->json(['message' => 'Ocurrió un error al guardar el archivo'], 500);
        }
    }
    function deletefile(Request $request){
        $request->validate([
            'archivo' => 'required|exists:archivos_presupuesto,id',
            'presupuesto' => 'required|exists:presupuestosnuevos,id'
        ], [
            'archivo.required' => 'El tipo de archivo es obligatorio.',
            'archivo.exists' => 'El tipo de archivo seleccionado no es válido.',
            'presupuesto.required' => 'El presupuesto es obligatorio.',
            'presupuesto.exists' => 'El presupuesto seleccionado no es válido.'
        ]);
        $archivo=$request->archivo;
        $presupuesto=$request->presupuesto;
        $ArchivoPresupuesto = ArchivosPresupuesto::where('Presupuesto_id',$presupuesto)->where('id',$archivo)->first();
        if($ArchivoPresupuesto){
            $TipoArchivo=TipoArchivoPresupuesto::find($ArchivoPresupuesto->Tipo_archivo_id);
            $ruta='/documentos/presupuestos/'.$TipoArchivo->Carpeta;
            $archivoExistente = $ruta . '/' . $ArchivoPresupuesto->Nombre; 
            if (Storage::exists('/public'.$archivoExistente)) {
                Storage::delete('/public'.$archivoExistente);
            }
            $ArchivoPresupuesto->delete();
            return response()->json(['message' => 'El Archivo Se Elimino Correctamente'], 200);
        }
        return response()->json(['message' => 'No Se Encontro El Archivo En Este Presupuesto Para Eliminar'], 500);
    }
    public function GetFile(Request $request){
        $request->validate([
            'tipo' => 'required|exists:tipos_archivos_presupuestos,id',
            'presupuesto' => 'required|exists:presupuestosnuevos,id'
        ], [
            'tipo.required' => 'El tipo de archivo es obligatorio.',
            'tipo.exists' => 'El tipo de archivo seleccionado no es válido.',
            'presupuesto.required' => 'El presupuesto es obligatorio.',
            'presupuesto.exists' => 'El presupuesto seleccionado no es válido.'
        ]);
        $tipo=$request->tipo;
        $presupuesto=$request->presupuesto;
        $ArchivoPresupuesto = ArchivosPresupuesto::where('Presupuesto_id',$presupuesto)->where('Tipo_archivo_id',$tipo)->first();
        if($ArchivoPresupuesto){
            
            $TipoArchivo=TipoArchivoPresupuesto::find($tipo);
            $ruta='/documentos/presupuestos/'.$TipoArchivo->Carpeta;
            $archivoExistente = $ruta . '/' . $ArchivoPresupuesto->Nombre; 
            if (Storage::exists('/public'.$archivoExistente)) {
                return response()->json(['message' => 'Se Guardo Correctamente el archivo','url'=>'/storage'.$archivoExistente], 200);
            }
            return response()->json(['message' => 'El Archivo Ya No Existe o Esta Corrompido'.$archivoExistente], 500);
        }
        return response()->json(['message' => 'No Se Han Subido Archivos'], 500);
    }
    public function GetFiles(Request $request){
        $request->validate([
            'tipo' => 'required|exists:tipos_archivos_presupuestos,id',
            'presupuesto' => 'required|exists:presupuestosnuevos,id'
        ], [
            'tipo.required' => 'El tipo de archivo es obligatorio.',
            'tipo.exists' => 'El tipo de archivo seleccionado no es válido.',
            'presupuesto.required' => 'El presupuesto es obligatorio.',
            'presupuesto.exists' => 'El presupuesto seleccionado no es válido.'
        ]);
        $tipo=$request->tipo;
        $presupuesto=$request->presupuesto;
        $ArchivosPresupuesto = ArchivosPresupuesto::where('Presupuesto_id',$presupuesto)->where('Tipo_archivo_id',$tipo)->get();
        $archivoExistente = []; 
        if($ArchivosPresupuesto){
            $TipoArchivo=TipoArchivoPresupuesto::find($tipo);
            $ruta='documentos/presupuestos/'.$TipoArchivo->Carpeta;
            foreach($ArchivosPresupuesto as $ArchivoPresupuesto){
                if (Storage::exists('/public/'.$ruta . '/' . $ArchivoPresupuesto->Nombre)) {
                    $archivoExistente[] =['id'=>$ArchivoPresupuesto->id,'ruta_completa'=>Storage::url($ruta.'/' . $ArchivoPresupuesto->Nombre)]; 
                }
            }
        }
        return response()->json(['archivos' => $archivoExistente], 200 );
    }
    public function PDFCOSTO(Request $request,$id){

        $presupuesto = Presupuesto::with([
            'detallesGenerales',
            'detallesGenerales.Vehiculo',
            'detallesGenerales.User',
            'detallesGenerales.User_update',
            'detallesGenerales.Empresa',
            'detallesGenerales.Customer',
            'detallesGenerales.AdministradorTrasporte',
            'detallesGenerales.JefedeProceso',
            'detallesGenerales.Trabajador',
            'detallesGenerales.tipoVehiculo',
            'detallesGenerales.modulo.FacturaEmisor',
            'conceptos.DatosConcepto.Categoria'
            ])->where("id",$request->id)->first();
            return \View::make('pdf.presupuestoCosto', compact('presupuesto'))->render();    
    }
    public function PDFVENTA(Request $request,$id){

        $presupuesto = Presupuesto::with([
            'detallesGenerales',
            'detallesGenerales.Vehiculo',
            'detallesGenerales.User',
            'detallesGenerales.User_update',
            'detallesGenerales.Empresa',
            'detallesGenerales.Customer',
            'detallesGenerales.AdministradorTrasporte',
            'detallesGenerales.JefedeProceso',
            'detallesGenerales.Trabajador',
            'detallesGenerales.tipoVehiculo',
            'detallesGenerales.modulo.FacturaEmisor',
            'conceptos.DatosConcepto.Categoria'
            ])->where("id",$request->id)->first();
            return \View::make('pdf.presupuestoVenta', compact('presupuesto'))->render();    
    }
    public function PDFACUSE(Request $request,$id){

        $presupuesto = Presupuesto::with([
            'detallesGenerales',
            'detallesGenerales.Vehiculo',
            'detallesGenerales.User',
            'detallesGenerales.User_update',
            'detallesGenerales.Empresa',
            'detallesGenerales.Customer',
            'detallesGenerales.AdministradorTrasporte',
            'detallesGenerales.JefedeProceso',
            'detallesGenerales.Trabajador',
            'detallesGenerales.tipoVehiculo',
            'detallesGenerales.modulo.FacturaEmisor',
            'conceptos.DatosConcepto.Categoria'
            ])->where("id",$request->id)->first()
            ;
            return \View::make('pdf.presupuestoAcuseZcrat', compact('presupuesto'))->render();    
    }
    public function HojaConceptos(Request $request,$id){

        $presupuesto = Presupuesto::with([
            'detallesGenerales:Vehiculo_id,OrdenServicio,OrdenSeguimiento,Empresa_id,zona_id,modulo_id,id,fecha_entrada,Fecha_salida,kilometraje_entrada',
            'detallesGenerales.Vehiculo',
            'detallesGenerales.Vehiculo.color:id,nombre',
            'detallesGenerales.Vehiculo.marca:id,nombre',
        'detallesGenerales.Vehiculo.modelo:id,nombre',
            'detallesGenerales.Empresa:id,nombre',
            'detallesGenerales.modulo.FacturaEmisor',
            'detallesGenerales.zona:id,nombre',
            'detallesGenerales.DateEntregado',
            ])->select('id','DetallesGenerales_id','Folio')->where("id",$request->id)->first();

            // if(!$presupuesto){
            //     return response()->json(['message' => 'Presupuesto no encontrado'], 404);
            // }
            // if(!$presupuesto->detallesGenerales){
            //     return response()->json(['message' => 'Detalles Generales no encontrados'], 404);
            // }
            // if(!$presupuesto->detallesGenerales->Vehiculo){
            //     return response()->json(['message' => 'Vehículo no encontrado'], 404);
            // }
            // if(!$presupuesto->detallesGenerales->Vehiculo->color){
            //     return response()->json(['message' => 'Color del vehículo no encontrado'], 404);
            // }
            // if(!$presupuesto->detallesGenerales->Vehiculo->marca){
            //     return response()->json(['message' => 'Marca del vehículo no encontrada'], 404);
            // }
            // if(!$presupuesto->detallesGenerales->Vehiculo->modelo){
            //     return response()->json(['message' => 'Modelo del vehículo no encontrado'], 404);
            // }
            // if(!$presupuesto->detallesGenerales->Empresa){
            //     return response()->json(['message' => 'Empresa no encontrada'], 404);
            // }
            // if(!$presupuesto->detallesGenerales->modulo){
            //     return response()->json(['message' => 'Módulo no encontrado'], 404);
            // }
            // if(!$presupuesto->detallesGenerales->modulo->FacturaEmisor){
            //     return response()->json(['message' => 'Emisor de factura no encontrado'], 404);
            // }
            // if(!$presupuesto->detallesGenerales->zona){
            //     return response()->json(['message' => 'Zona no encontrada'], 404);
            // }
        
        $tecnico=RecepcionesVehiculares::with('tecnico')->where('DetallesGenerales_id',$presupuesto->detallesGenerales->id)->orderbydesc('id')->first();
        $presupuesto->Tecnico= $tecnico ? ($tecnico->tecnico ?  ($tecnico->tecnico->nombre == 'Sin Asignar' ? '':$tecnico->tecnico->nombre) : ''  ): '';
        $conceptos=HojaConcepto::where('presupuesto_id',$request->id)->get();
            return \View::make('pdf.HojaConcepto', compact('presupuesto','conceptos'))->render();    
    }
    public function DiagnosticoTecnicoLLena(Request $request){

       $detallesGenerales = DetallesGenerales::with([
        'Vehiculo',
        'Vehiculo.color:id,nombre',
        'Vehiculo.marca:id,nombre',
        'Vehiculo.modelo:id,nombre',
        'Empresa:id,nombre',
        'modulo.FacturaEmisor',
        'zona:id,nombre',
        'presupuestos', 
        'RecepcionVehicular.tecnico',
        'Vales.Conceptos'
        ])->where("id",$request->id)->first();
        $datos=(object)[
            'logo'=>$detallesGenerales->modulo->FacturaEmisor->logotipo_emisor,
            'fecha_entrada'=>$detallesGenerales->fecha_entrada,
            'fecha_salida'=>$detallesGenerales->Fecha_salida,
            'kilometraje_entrada'=>$detallesGenerales->kilometraje_entrada,
            'orden_servicio'=>$detallesGenerales->OrdenServicio,
            'orden_seguimiento'=>$detallesGenerales->OrdenSeguimiento,
            'empresa'=>$detallesGenerales->Empresa->nombre,
            'indicaciones_cliente'=>$detallesGenerales->indicaciones_cliente,
            'zona'=>$detallesGenerales->zona->nombre,
            'vehiculo'=>(object)[
                'marca'=>$detallesGenerales->Vehiculo->marca->nombre,
                'modelo'=>$detallesGenerales->Vehiculo->modelo->nombre,
                'color'=>$detallesGenerales->Vehiculo->color->nombre,
                'anio'=>$detallesGenerales->Vehiculo->anio,
                'placas'=>$detallesGenerales->Vehiculo->placas,
                'vim'=>$detallesGenerales->Vehiculo->vim,
                'no_economico'=>$detallesGenerales->Vehiculo->no_economico
            ],
            'presupuestos'=>$detallesGenerales->presupuestos->count(),
            'tecnico'=> $detallesGenerales->RecepcionVehicular ? ($detallesGenerales->RecepcionVehicular->tecnico ?  ($detallesGenerales->RecepcionVehicular->tecnico->nombre == 'Sin Asignar' ? '':$detallesGenerales->RecepcionVehicular->tecnico->nombre) : ''  ): '',
            'conceptos'=>$detallesGenerales->Vales->flatMap(function ($vale) {
                return $vale->Conceptos->map(function ($concepto){
                    return (object)[
                        'descripcion'=>$concepto->Descripcion,
                        'cantidad'=>$concepto->Cantidad,
                    ];
                });
            }),
        ];
        return \View::make('pdf.DiagnosticoTecnicoLLeno', compact('datos'))->render();    
    }
    public function DiagnosticoTecnico(Request $request,$id){
        $presupuesto = Presupuesto::with([
            'detallesGenerales:Vehiculo_id,OrdenServicio,Empresa_id,zona_id,modulo_id,id,fecha_entrada,kilometraje_entrada,Gas_entrada,indicaciones_cliente',                
            'detallesGenerales.Vehiculo',
            'detallesGenerales.Vehiculo.color:id,nombre',
            'detallesGenerales.Vehiculo.marca:id,nombre',
            'detallesGenerales.Vehiculo.modelo:id,nombre',
            'detallesGenerales.Empresa:id,nombre',
            'detallesGenerales.modulo.FacturaEmisor',
            'detallesGenerales.zona:id,nombre',
            ])->select('id','DetallesGenerales_id','Folio')->where("id",$request->id)->first();
        
        $tecnico=RecepcionesVehiculares::with('tecnico')->where('DetallesGenerales_id',$presupuesto->detallesGenerales->id)->orderbydesc('id')->first();
        
        $presupuesto->Tecnico= $tecnico ? ($tecnico->tecnico ?  ($tecnico->tecnico->nombre == 'Sin Asignar' ? '':$tecnico->tecnico->nombre) : ''  ): '';
        
        return \View::make('pdf.DiagnosticoTecnico', compact('presupuesto'))->render();    
    }
    public function UpdatePago(Request $request){

        $request->validate([
            'id'=> ['required','exists:PresupuestosNuevos,id'],
            'Importe'=>['nullable','numeric', 'min:0'],
            'Fecha'=>['nullable','date'],
            'descripcion'=>['required','string'],
            'nombre'=>['nullable','string'],
        ]);
        $importe=$request->Importe;
        $descripcion=$request->descripcion??'General';
        $nombre=$request->nombre??'Sin Especificar';
        $fecha=$request->filled('Fecha') ? $request->Fecha: Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
        try{
            
            PagosPresupuestos::Create([
                'importe'=>$importe,
                'nombre'=>$nombre,
                'fecha'=>$fecha,
                'descripcion'=> $descripcion,
                'presupuesto_id'=>$request->id
            ]);
            return response()->json(['message'=>'Presupuesto Pagado Correctamente','importe'=>$importe,'fecha'=>$fecha,'descripcion'=> $descripcion]);
        }catch(\Exception $e){
            return response()->json(['mesasage'=>$e->getmessage()]);

        }

    }
    public function Import1Presupuestos(Request $request){
        $request->validate([
            'PreResOrdSer' => ['nullable', 'string', 'min:5'],
            'PreResFol' => ['nullable', 'string', 'min:5'],
            'PreResUbi' => ['required', 'string'],
            'PreResGasEnt' => ['required', 'integer', 'between:0,4'],
            'PreResKmEnt' => ['required', 'numeric', 'min:0'],
            'PreResFecEsp' => ['required', 'date'],
            'PreResAdmTra' => ['required', 'exists:users_taller,id'],
            'PreResTel' => ['required', 'numeric', 'digits:10'],
            'PreResSer' => ['required', 'in:1,2,3'],
            'PreResEmp' => ['required', 'exists:empresas,id'],
            'PreResCli' => ['required', 'exists:customers,id'],
            'PreResIndCli' => ['nullable', 'string'],
            'PreResDesMO' => ['nullable', 'string'],
            'PreResVeh' => ['required', 'exists:vehiculos,id'],
            'ModuloCortana' => ['required', 'exists:contratos_modulo,id'],
        ],[
            'PreResFol.required' => 'El folio es obligatorio.',
            'PreResFol.unique' => 'El folio ya existe en otro presupuesto.',
            'PreResObs.string' => 'Las observaciones deben ser un texto válido.',
            'PreResOrdSer.required' => 'La Orden de Servicio es obligatoria.',
            'PreResOrdSer.min' => 'La Orden de Servicio debe tener exactamente 5 caracteres.',
            'PreResOrdSeg.required' => 'La Orden de Seguimiento es obligatoria.',
            'PreResOrdSeg.min' => 'La Orden de Seguimiento debe tener exactamente 5 caracteres.',
            'PreResUbi.required' => 'La ubicación es obligatoria.',
            'PreResFecEsp.required' => 'La fecha esperada es obligatoria.',
            'PreResFecEsp.date' => 'La fecha esperada debe ser válida.',
            'PreResKmEnt.required' => 'El kilometraje de entrada es obligatorio.',
            'PreResKmEnt.numeric' => 'El kilometraje de entrada debe ser un número.',
            'PreResKmEnt.min' => 'El kilometraje de entrada debe ser al menos 0.',
            'PreResGasEnt.required' => 'El nivel de gasolina de entrada es obligatorio.',
            'PreResGasEnt.integer' => 'El nivel de gasolina debe ser valido.',
            'PreResGasEnt.between' => 'El nivel de gasolina debe ser valido.',
            'PreResFecEnt.required' => 'La fecha de entrada es obligatoria.',
            'PreResFecEnt.date' => 'La fecha de entrada debe ser válida.',
            'PreResVeh.required' => 'El vehículo es obligatorio.',
            'PreResVeh.exists' => 'El vehículo seleccionado no existe.',
            'PreResVehTip.required' => 'El tipo de vehículo es obligatorio.',
            'PreResVehTip.exists' => 'El tipo de vehículo seleccionado no existe.',
            'PreResEmp.required' => 'La empresa es obligatoria.',
            'PreResEmp.exists' => 'La empresa seleccionada no existe.',
            'PreResCli.required' => 'El cliente es obligatorio.',
            'PreResCli.exists' => 'El cliente seleccionado no existe.',
            'PreResAdmTra.required' => 'El administrador de transporte es obligatorio.',
            'PreResAdmTra.exists' => 'El administrador de transporte seleccionado no existe.',
            'PreResJefPro.required' => 'El jefe de proceso es obligatorio.',
            'PreResJefPro.exists' => 'El jefe de proceso seleccionado no existe.',
            'PreResTra.required' => 'El trabajador es obligatorio.',
            'PreResTra.exists' => 'El trabajador seleccionado no existe.',
            'PreResTel.required' => 'El teléfono es obligatorio.',
            'PreResTel.numeric' => 'El teléfono debe ser un número.',
            'PreResTel.digits' => 'El teléfono debe tener exactamente 10 dígitos.',
            'PreResIndCli.string' => 'Las indicaciones del cliente deben ser texto válido.',
            'contrato.required' => 'El contrato es obligatorio.',
            'contrato.exists' => 'El contrato seleccionado no existe.',
            'modulo.required' => 'El módulo es obligatorio.',
            'modulo.exists' => 'El módulo seleccionado no existe.',
            'anio.required' => 'El año es obligatorio.',
            'anio.numeric' => 'El año debe ser un número.',
            'anio.min' => 'El año debe ser 2025 o mayor.',
            'zona.required' => 'La zona es obligatoria.',
            'zona.exists' => 'La zona seleccionada no existe.',
        ]);
        try{
            DB::beginTransaction();

            $moodulo=ContratosPerZona::find($request->ModuloCortana);

            [$modulo,$zona,$contrato,$anio] = [$moodulo->modulo_id,$moodulo->zona_id,$moodulo->contrato_id,$moodulo->anio];
            if($modulo==0 || $zona==0 || $contrato==0){
                throw new \Exception('Modulo Invalido');
            }
            if(!$request->filled('PreResOrdSer')){
                $num = RecepcionesVehiculares::withTrashed()->count();
                $clave=Claves::where('modulo_id',$modulo)->where('zona_id',$zona)->value('clave');
                $numeroConCeros = str_pad($num+318, 5, "0", STR_PAD_LEFT);
                $clave = $clave.$numeroConCeros;
            }else{
                $clave=$request->PreResOrdSer;
            }
            $detalles = DetallesGenerales::where('OrdenServicio',$clave)->first();
            if(empty($detalles)){
                $jefproc=UsersTaller::where('nombre','LIKE','%ODILON RODRIGUEZ%')->first();
                $tecnico=Tecnicos::where('nombre','LIKE','%Sin Asignar%')->first();
                $trabajador=UsersTaller::where('nombre','LIKE','%VARIOS%')->first();
            
                $tipovhc=NuevoTipoVehiculoConceptoDisponible::where('modulo_id',$modulo)->where('zona_id',$zona)->where('contrato_id',$contrato)->where('anio',2025)->value('tipos_vehiculo_concepto_id')??4;
                $userid=\Auth::user()->id;
                $tallerid=\Auth::user()->taller_id ?? 3;
                $detalles = new DetallesGenerales();
                $detalles->taller_id = $tallerid;
                $detalles->OrdenServicio = $clave;
                $detalles->OrdenSeguimiento = $clave;
                $detalles->Ubicacion =$request->PreResUbi;
                $detalles->Fecha_Esperada =  $request->PreResFecEsp;
                $detalles->Kilometraje_entrada = $request->PreResKmEnt;
                $detalles->Gas_entrada  = $request->PreResGasEnt;
                $detalles->Fecha_entrada = Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
                $detalles->Kilometraje_salida =  null;
                $detalles->Gas_salida = null;
                $detalles->Fecha_salida =  null;
                $detalles->Vehiculo_id = $request->PreResVeh;
                $detalles->Tipo_Vehiculo_Concepto_id = $tipovhc;
                $detalles->User_id = $userid;
                $detalles->User_update_id = $userid;
                $detalles->Empresa_id =  $request->PreResEmp;
                $detalles->Customer_id = $request->PreResCli;
                $detalles->AdministradorTrasporte_id = $request->PreResAdmTra;
                $detalles->JefedeProceso_id = $jefproc->id;
                $detalles->Trabajador_id = $trabajador->id;
                $detalles->Telefono = $request->PreResTel;
                $detalles->contrato_id =$contrato;
                $detalles->Indicaciones_cliente =$request->PreResIndCli??'Sin Indicaciones Por Parte Del Cliente';
                $detalles->modulo_id =$modulo;
                $detalles->anio = $anio;
                $detalles->zona_id = $zona;
                $detalles->save();

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
                $recepcion = new RecepcionesVehiculares();
                $ExterioresEquipo = new ExterioresEquipo();
                $CondicionesPintura = new CondicionesPintura();
                $EquipoInventario = new EquipoInventario();
                $InterioresEquipo = new InterioresEquipo();

                $recepcion->DetallesGenerales_id = $detalles->id; 
                $recepcion->Notas =$request->PreResDesMO??'';
                $recepcion->Tecnico_id = $tecnico->id;
                $recepcion->Firma = $fileName; 
                $recepcion->Carro = $fileName;
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
            $tipos=[1=>'P',2=>'C',3=>''];
            $folio =$request->filled('PreFol')
                    ? $request->PreResFol
                : $clave . $tipos[$request->PreResSer];

            $index = 1;
            while (Presupuesto::where('Folio', $folio)->exists()) {
                $folio = $folio . '-' . $index;
                $index++;
            }
            $presupuesto = new Presupuesto();
            $presupuesto->DetallesGenerales_id =$detalles->id; 
            $presupuesto->Folio = $folio;
            $presupuesto->Observaciones = 'DE ACUERDO A LO DIFICIL DE LA FALLA PARA SU REPARACION';
            $presupuesto->Mano_Obra_Descripcion =  $request->PreResDesMO ??'' ;
            $presupuesto->FechaDeVigencia = Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
            $presupuesto->Factura_id = 0;
            $presupuesto->User_update_id = 1;
            $presupuesto->Tipo_id =  $request->PreResSer;
            $presupuesto->Status_id = 1;
            $presupuesto->save();

            $restringido = new PresupuestosRestringidos();
            $restringido->Presupuesto_id = $presupuesto->id;
            $restringido->User_id = Auth::user()->id;
            $restringido->save();
            DB::commit();
        return response()->json(['message'=>'Se Creo Correctamente']);
                   
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getmessage()],500);
        }
    }
    public function ImportPresupuestos(Request $request){
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:102400', // Máximo 10MB
        ], [
            'file.required' => 'El archivo es obligatorio.',
            'file.file' => 'El archivo debe ser un archivo válido.',
            'file.max' => 'El archivo no debe superar los 100MB.',
            'file.mimes' => 'El archivo debe ser un Excel'
        ]);
        try{
        DB::beginTransaction();
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
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
        $encabezadosExcel = array_slice($data[0], 0, 18);
        $encabezadosEsperados = [
            'orden','folio', 'seguimiento', 'ubicacion', 'gasolina', 'kilometraje',
            'fecha esperada', 'administrador', 'jefe proceso', 'trabajador',
            'telefono', 'servicio', 'empresa', 'cliente', 'indicaciones',
            'mano de obra', 'economico', 'modulo'
        ];
        $resultado = $this->compararEncabezadosConOrden($encabezadosExcel, $encabezadosEsperados);
        log::info($resultado);  
        if (!$resultado['coinciden']) {
            return response()->json(['message'=>'El Archivo No Tiene El Formato Esperado'],400);
        }

        $filasnoimportadas=[];
        foreach($data as$index=> $row){
            if($index==0){
                continue;
            }
            if (empty(array_filter($row, function($v){return !is_null($v);}))) {
                continue;
            }
            if (count($row) < 18 ) {
                $filasnoimportadas[] = $index + 1;
                continue;
            }
            $valor = trim((string)$row[17]);
           [$modulo,$zona,$contrato,$anio] = $this->GetCorrectoModul($valor);

            if(empty($row[0])){
                $num = RecepcionesVehiculares::withTrashed()->count();
                $clave=Claves::where('modulo_id',$modulo)->where('zona_id',$zona)->value('clave');
                $numeroConCeros = str_pad($num+318, 5, "0", STR_PAD_LEFT);
                $clave = $clave.$numeroConCeros;
            }else{
                $clave=$row[0];
            }
            $detalles = DetallesGenerales::where('OrdenServicio',$clave)->first();
            if(empty($detalles)){
                $vehiculo=Vehiculo::where('no_economico',$row[16])->first();
                if (!isset($vehiculo)) {
                    $marca = Marca::where('nombre','Desconocida')->first();
                    if (!$marca) {
                        $marca = Marca::create(['nombre' =>'Desconocida']);
                    }

                    // Buscar o crear un modelo utilizando LIKE
                    $modelo = Modelo::where('nombre', 'Desconocido')
                                    ->where('marca_id', $marca->id)
                                    ->first();
                    if (!$modelo) {
                        $modelo = Modelo::create(['nombre' => 'Desconocido', 'marca_id' => $marca->id]);
                    }

                    // Buscar o crear un color utilizando LIKE
                    $color = Color::where('nombre', 'LIKE','NEGRO')->first();
                    if (!$color) {
                        $color = Color::create(['nombre' => 'NEGRO']);
                    }
                    
                    $vehiculo = new Vehiculo();
                    $vehiculo->tipo_id = 1;
                    $vehiculo->color_id = $color->id;
                    $vehiculo->marca_id =$marca->id;
                    $vehiculo->modelo_id = $modelo->id;
                    $vehiculo->placas = 'XXXXXX';
                    $vehiculo->anio = '0000';
                    $vehiculo->no_economico = $row[16];
                    $vehiculo->vim = 'XXXXXXXXXXXXXXXX';
                    $vehiculo->save();
                }
                $admintras=UsersTaller::where('nombre','LIKE',$row[7])->WHERE('tipo_user_taller_id',1)->first();
                $jefproc=UsersTaller::where('nombre','LIKE',$row[8])->WHERE('tipo_user_taller_id',2)->first();
                $tecnico=Tecnicos::where('nombre','LIKE','%Sin Asignar%')->first();
                $trabajador=UsersTaller::where('nombre','LIKE',$row[9])->WHERE('tipo_user_taller_id',3)->first();
                if(!$admintras){
                    $admintras = new UsersTaller();
                    $admintras->nombre = $row[7];
                    $admintras->tipo_user_taller_id = 1;
                    $admintras->save();
                }
                if(!$jefproc){
                    $jefproc = new UsersTaller();
                    $jefproc->nombre = $row[8];
                    $jefproc->tipo_user_taller_id = 2;
                    $jefproc->save();
                }
                if(!$tecnico){
                    $tecnico = new Tecnicos();
                    $tecnico->nombre ='Sin Asignar';
                    $tecnico->save();
                }
                if(!$trabajador){
                    $trabajador = new UsersTaller();
                    $trabajador->nombre = $row[9];
                    $trabajador->tipo_user_taller_id = 3;
                    $trabajador->save();
                }
                $empresa = Empresa::firstOrCreate(
                    ['nombre' => $row[12]], // criterio de búsqueda
                    [
                        'rfc'         => 'XXXXXXXXXXXX',
                        'logo'        => 'XXXXXXXXXXXX',
                        'email'       => 'XXXXXXXXXXXX',
                        'direccion'   => 'XXXXXXXXXXXX',
                        'tel_negocio' => 'XXXXXXXXXXXX',
                        'regimen'     =>  601,
                    ]
                );
                $cliente = Cliente::firstOrCreate(
                    ['nombre' => $row[13]],
                    ['empresa_id' => $empresa->id]
                );
                $fecha = strtotime($row[6]) ? Carbon::parse($row[6]) : Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
            
                $tipovhc=NuevoTipoVehiculoConceptoDisponible::where('modulo_id',$modulo)->where('zona_id',$zona)->where('contrato_id',$contrato)->where('anio',2025)->value('tipos_vehiculo_concepto_id')??4;
                $detalles = new DetallesGenerales();
                $tallerid=\Auth::user()->taller_id ?? 3;
                $detalles->OrdenServicio = $clave;
                $detalles->taller_id = 3;
                $detalles->OrdenSeguimiento = $row[2]??$clave;
                $detalles->Ubicacion =$row[3];
                $detalles->Fecha_Esperada =  $fecha;
                $detalles->Kilometraje_entrada = is_numeric($row[5]) ? $row[5] : 0;
                $detalles->Gas_entrada  = is_numeric($row[4]) ? $row[4] : 0;
                $detalles->Fecha_entrada = Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
                $detalles->Kilometraje_salida =  null;
                $detalles->Gas_salida = null;
                $detalles->Fecha_salida =  null;
                $detalles->Vehiculo_id = $vehiculo->id;
                $detalles->Tipo_Vehiculo_Concepto_id = $tipovhc;
                $detalles->User_id = Auth::user()->id;
                $detalles->User_update_id = 1;
                $detalles->Empresa_id =  $empresa->id;
                $detalles->Customer_id = $cliente->id;
                $detalles->AdministradorTrasporte_id = $admintras->id;
                $detalles->JefedeProceso_id = $jefproc->id;
                $detalles->Trabajador_id = $trabajador->id;
                $detalles->Telefono = is_numeric($row[10]) ? $row[10]:1234567890;
                $detalles->contrato_id =$contrato;
                $detalles->Indicaciones_cliente =$row[14]??'Sin Indicaciones Por Parte Del Cliente';
                $detalles->modulo_id =$modulo;
                $detalles->anio = $anio;
                $detalles->zona_id = $zona;
                $detalles->save();

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
                $recepcion = new RecepcionesVehiculares();
                $ExterioresEquipo = new ExterioresEquipo();
                $CondicionesPintura = new CondicionesPintura();
                $EquipoInventario = new EquipoInventario();
                $InterioresEquipo = new InterioresEquipo();

                $recepcion->DetallesGenerales_id = $detalles->id; 
                $recepcion->Notas =$row[15]??'';
                $recepcion->Tecnico_id = $tecnico->id; ;
                $recepcion->Firma = $fileName; 
                $recepcion->Carro = $fileName;
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
            $folio=empty($row[1])?$clave:$row[1];
            if(Presupuesto::where('Folio',$folio)->exists()){
                $filasnoimportadas[]=$index+1;
                continue;
            }
            $presupuesto = new Presupuesto();
            $presupuesto->DetallesGenerales_id =$detalles->id; 
            $presupuesto->Folio = $folio;
            $presupuesto->Observaciones = 'DE ACUERDO A LO DIFICIL DE LA FALLA PARA SU REPARACION';
            $presupuesto->Mano_Obra_Descripcion =  $row[15] ??'' ;
            $presupuesto->FechaDeVigencia = Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
            $presupuesto->Factura_id = 0;
            $presupuesto->User_update_id = 1;
            $presupuesto->Tipo_id =  is_numeric($row[11])?$row[11]:1234567890;
            $presupuesto->Status_id = 1;
            $presupuesto->save();
        }
        DB::commit();
        return response()->json(['message'=>'Se Importaron Correctamente','filasnoimportadas'=>$filasnoimportadas]);
                   
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getmessage()],500);
        }
    }

    private function GetCorrectoModul($valor){
        $anio=2025;
        switch($valor){
                case '1':
                    $modulo=3;
                    $zona=5;
                    $contrato=12;
                break;
                case '2':
                    $modulo=3;
                    $zona=5;
                    $contrato=5;
                break;
                case '3':
                    $modulo=3;
                    $zona=2;
                    $contrato=11;
                break;
                case '4':
                    $modulo=3;
                    $zona=2;
                    $contrato=2;
                break;
                case '5':
                    $modulo=3;
                    $zona=9;
                    $contrato=9;
                break;
                case '6':
                    $modulo=3;
                    $zona=4;
                    $contrato=4;
                break;
                case '7':
                    $modulo=3;
                    $zona=3;
                    $contrato=10;
                break;
                case '8':
                    $modulo=3;
                    $zona=3;
                    $contrato=3;
                break;
                case '9':
                    $modulo=3;
                    $zona=6;
                    $contrato=7;
                break;
                case '10':
                    $modulo=4;
                    $zona=13;
                    $contrato=8;
                break;
                case '11':
                    $modulo=4;
                    $zona=11;
                    $contrato=8;
                break;
                case '12':
                    $modulo=4;
                    $zona=12;
                    $contrato=8;
                break;
                case '13':
                    $modulo=6;
                    $zona=10;
                    $contrato=8;
                    $anio=2026;
                break;
                case '14':
                    $modulo=5;
                    $zona=14;
                    $contrato=8;
                break;
                case '15':
                    $modulo=5;
                    $zona=11;
                    $contrato=8;
                break;
                case '16':
                    $modulo=5;
                    $zona=12;
                    $contrato=8;
                break;
                case '17':
                    $modulo=6;
                    $zona=10;
                    $contrato=16;
                    $anio=2026;
                break;
                case '18':
                    $modulo=6;
                    $zona=10;
                    $contrato=18;
                    $anio=2026;
                break;
                case '19':
                    $modulo=6;
                    $zona=10;
                    $contrato=19;
                    $anio=2026;
                break;
                case '20':
                    $modulo=6;
                    $zona=10;
                    $contrato=15;
                    $anio=2026;
                break;
                case '21':
                    $modulo=6;
                    $zona=10;
                    $contrato=17;
                    $anio=2026;
                break;
                case '22':
                    $modulo=6;
                    $zona=10;
                    $contrato=20;
                    $anio=2026;
                break;
                case '23':
                    $modulo=6;
                    $zona=10;
                    $contrato=21;
                    $anio=2026;
                break;
                case '24':
                    $modulo=5;
                    $zona=2;
                    $contrato=13;
                    $anio=2026;
                break;
                case '25':
                    $modulo=5;
                    $zona=6;
                    $contrato=14;
                    $anio=2026;
                break;
                case '26':
                    $modulo=3;
                    $zona=4;
                    $contrato=22;
                    $anio=2026;
                break;
                case '27':
                    $modulo=5;
                    $zona=2;
                    $contrato=23;
                    $anio=2026;
                break;
                case '28':
                    $modulo=5;
                    $zona=2;
                    $contrato=24;
                    $anio=2026;
                break;
                case '29':
                    $modulo=3;
                    $zona=2;
                    $contrato=25;
                    $anio=2026;
                break;
                case '30':
                    $modulo=5;
                    $zona=2;
                    $contrato=26;
                    $anio=2026;
                break;
                default:
                    $modulo=0;
                    $zona=0;
                    $contrato=0;
                break;
            }
            return [$modulo,$zona,$contrato,$anio];
    }
    private function compararEncabezadosConOrden(array $reales, array $esperados): array
    {
       $normalizar = function($v) {
            return strtolower(trim($v));
        };

        $realesNorm = array_map($normalizar, $reales);
        $esperadosNorm = array_map($normalizar, $esperados);

        $faltantes = array_diff($esperadosNorm, $realesNorm);
        $extras = array_diff($realesNorm, $esperadosNorm);

        $ordenCorrecto = $realesNorm === $esperadosNorm;

        return [
            'faltantes' => array_values($faltantes),
            'extras' => array_values($extras),
            'orden_correcto' => $ordenCorrecto,
            'coinciden' => empty($faltantes) && empty($extras) && $ordenCorrecto,
        ];
    }
    public function downloadFile(Request $request){
        $request->validate([
            'id' => 'required|exists:archivos_presupuesto,id',
        ], [
            'id.required' => 'El tipo de archivo es obligatorio.',
            'id.exists' => 'El tipo de archivo seleccionado no es válido.',
        ]);
        $ArchivoPresupuesto = ArchivosPresupuesto::where('id',$request->id)->first();
        if($ArchivoPresupuesto){
            $TipoArchivo=TipoArchivoPresupuesto::find($ArchivoPresupuesto->Tipo_archivo_id);
            $ruta='/documentos/presupuestos/'.$TipoArchivo->Carpeta;
            $archivoExistente = $ruta . '/' . $ArchivoPresupuesto->Nombre; 
            if (Storage::exists('/public'.$archivoExistente)) {
                $path = public_path('storage/'.$ruta . '/' . $ArchivoPresupuesto->Nombre);
                $nombreLimpio = str_replace('"', '', $ArchivoPresupuesto->Nombre);
                return response()->download($path, $ArchivoPresupuesto->Nombre, [
                                    'Content-Type' => mime_content_type($path),
                                    'Content-Disposition' => "attachment; filename={$nombreLimpio}"
                                    ]);
            }
        }
        return response()->json(['message' => 'El Archivo Ya No Existe o Esta Corrompido'.$archivoExistente], 500);

    }
    public function downloadFiles(Request $request){
        $request->validate([
            'tipo' => 'required|exists:tipos_archivos_presupuestos,id',
            'presupuesto' => 'required|exists:presupuestosnuevos,id'
        ], [
            'tipo.required' => 'El tipo de archivo es obligatorio.',
            'tipo.exists' => 'El tipo de archivo seleccionado no es válido.',
            'presupuesto.required' => 'El presupuesto es obligatorio.',
            'presupuesto.exists' => 'El presupuesto seleccionado no es válido.'
        ]);
        $tipo=$request->tipo;
        $presupuesto=$request->presupuesto;
        $ArchivoPresupuesto = ArchivosPresupuesto::where('Presupuesto_id',$presupuesto)->where('Tipo_archivo_id',$tipo)->get();
        if($ArchivoPresupuesto){
            
            $TipoArchivo=TipoArchivoPresupuesto::find($tipo);
            $ruta='/documentos/presupuestos/'.$TipoArchivo->Carpeta;
            $zip= new \ZipArchive();
            $zipFileName= 'Presupuesto_'.$presupuesto.'_Tipo_'.$tipo.'_'.uniqid().'.zip';
            $zipFilePath= storage_path('app/public/'.$zipFileName);
            if($zip->open($zipFilePath,\ZipArchive::CREATE)===true){
                foreach ($ArchivoPresupuesto as $archivo) {
                    $archivoExistente = $ruta . '/' . $archivo->Nombre;
                    if (Storage::exists('/public'.$archivoExistente)) {
                        $contenido = Storage::get('/public'.$archivoExistente);
                        $zip->addFromString($archivo->Nombre, $contenido);
                    }
                }
                $zip->close();
                return response()->download($zipFilePath, $zipFileName, [
                    'Content-Type' => 'application/zip',
                    'Content-Disposition' => "attachment; filename={$zipFileName}"
                ])->deleteFileAfterSend(true);
            }
        }
        return response()->json(['message' => 'No Se Han Subido Archivos'], 500);
    }
    public function ToggleGarantia(Request $request){
         $request->validate([
            'id' => 'required|exists:Presupuesto_Carrito,id',
            'dictamen' => 'nullable|string'
        ]);

        $registro=PresupuestoCarrito::find($request->id);

        if(!$registro->garantia){
            return response()->json(['message' => 'No Tiene Garantia'],500);
        }
        if(!$registro->dictamen && !$request->dictamen ){
            return response()->json(['message' => 'El Dictamen Es Requerido'],500);
        }

        $registro->dictamen = $request->dictamen;
        $registro->save();
        return response()->json(['message' => $request->dictamen ? 'Garantia Anulada' : 'Garantia Restaurada']);
    }
}
