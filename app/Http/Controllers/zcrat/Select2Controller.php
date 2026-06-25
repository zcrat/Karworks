<?php

namespace App\Http\Controllers\zcrat;
use App\Http\Controllers\controller;
use Illuminate\Http\Request;
use App\Models\CategoriasDisponibles;
use App\Models\CategoriasConceptos;
use App\Models\Tecnicos;
use App\Models\UsersTaller;
use App\Models\TiposDisponibles2;
use App\Models\TiposVehiculoConcepto;
use App\Models\Empresa;
use App\Models\Customer;
use App\Models\Vehiculo;
use App\Models\DetallesGenerales;
use App\Models\CategoriasSat;
use App\Models\UnidadesSatmodel;
use App\Models\ZonaPerModulo;
use App\Models\Sucursales;
use App\Models\Contratos;
use App\Models\ContratosPerZona;
use App\Models\Presupuesto;
use App\Models\Modulo;
use App\Models\Talleres;
use App\User;
use App\Tareas;
use App\Models\PresupuestosRestringidos;
use Illuminate\Support\Facades\DB;
use App\Models\ProductoAlmacen;
use App\Models\Proveedores;
use App\Models\InventarioAlmacen;
class Select2Controller  extends Controller
{
    public function GetUser(Request $request){
        $term = str_replace(' ', '%', $request->input('search'));
        if(!$request->has('tarea')){
            $users=User::select('id',DB::raw("name as nombre"))->where('name','LIKE','%'.$term.'%')->get();
        }else{
            $tarea = $request->input('tarea');
            $tarea=Tareas::find($tarea);
            $users=User::select('id',DB::raw("name as nombre"))->where('id','!=',$tarea->levanta_id??0)->where('name','LIKE','%'.$term.'%')->get();
        }
        return response()->json($users);
    }
    public function GetTalleres(Request $request){
        $term = str_replace(' ', '%', $request->input('search'));
        $user_id=$request->user()->id;
        $taller_id=$request->user()->taller_id;
        $taller=Talleres::findorfail($taller_id);

        if(in_array($user_id,[1,170,36])){
                $talleres=Talleres::select('id','nombre')->where('nombre','LIKE','%'.$term.'%')->get();
        }else if($taller->externo){
            $talleres=Talleres::select('id','nombre')->where('nombre','LIKE','%'.$term.'%')->where('id',$taller_id)->get();
        }else{
            $talleres=Talleres::select('id','nombre')->where('nombre','LIKE','%'.$term.'%')->where(function($q) use($taller_id){
                    $q->where('id',$taller_id)->orWhere('externo','1');
                })->get();
        }
        return response()->json($talleres);
    }
    public function GetPresupuestos(Request $request){
        $term = str_replace(' ', '%', $request->input('search'));
        $presupuestos=Presupuesto::select('id',DB::raw("Folio as nombre"))->where('Folio','LIKE','%'.$term.'%')->get();
        return response()->json($presupuestos);
    }
    public function GetUsuariosRestringidos(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $users=User::select('id',DB::raw("name as nombre"))->whereIn('id',PresupuestosRestringidos::pluck('user_id'))->where('name','LIKE','%'.$term.'%')->get();
        return response()->json($users);
    }
    public function GetCategoriesSat(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $categorias=CategoriasSat::select('id',DB::raw("CONCAT(codigo_sat,'-',descripcion) as descripcion"))->where('descripcion','LIKE','%'.$term.'%')->orwhere('codigo_sat','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($categorias);
    }
    public function GetUnitsSat(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $unidades=UnidadesSatmodel::select('id',DB::raw("CONCAT(clave,'-',nombre) as nombre"))->where('nombre','LIKE','%'.$term.'%')->orwhere('clave','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($unidades);
    }
    public function GetCategoriesConcepts(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $categorias=CategoriasConceptos::select('id',"nombre")->where('nombre','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($categorias);
    }
    public function GetAvailableTypesConcepts(Request $request){
        if(!$request->has('modulo') || !$request->has('contrato') ||!$request->has('anio') ||!$request->has('zona')){
            return response()->json(['error' => 'No Se Enviaron Los Datos Necesario'], 400);
        }   
        $modulo = $request->input('modulo');
        $contrato = $request->input('contrato');
        $anio = $request->input('anio');
        $zona = $request->input('zona');

        $term = str_replace(' ', '%', $request->input('term'));
        $tiposdisponibles = TiposDisponibles2::where('modulo_id', $modulo)
            ->where('contrato_id', $contrato)
            ->where('anio', $anio)
            ->where('zona_id', $zona)
            ->pluck('tipos_vehiculo_concepto_id');
        $tiposvehiculos =TiposVehiculoConcepto::select('id', 'nombre')
            ->whereIn('id', $tiposdisponibles)
            ->where('nombre', 'like', '%' . $term . '%')
            ->take(15)
            ->get();

        return response()->json($tiposvehiculos);
    }
    public function GetAvailableZonas(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $modulo = $request->input('modulo');
        $anio = $request->input('anio');
         if(!$modulo || !$anio){
            return response()->json([]);
        }
        $zonasID=ZonaPerModulo::where('modulo_id',$modulo)->where('anio',$anio)->pluck('zona_id');
        $zonas=Sucursales::select('id','nombre')->whereIn('id',$zonasID)->where('nombre','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($zonas);
    }
    public function GetAvailableContratos(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $modulo = $request->input('modulo');
        $zona = $request->input('zona');
        $anio = $request->input('anio');
        if(!$modulo || !$zona || !$anio){
            return response()->json([]);
        }
        $contratosID=ContratosPerZona::where('modulo_id',$modulo)->where('zona_id',$zona)->where('anio',$anio)->pluck('contrato_id');
        $contratos=Contratos::select('id','nombre')->whereIn('id',$contratosID)->where('nombre','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($contratos);
    }
    public function GetCompanies(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $empresas=Empresa::select('id','nombre')->where('nombre','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($empresas);
    }
    public function GetCustomers(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $clientes=Customer::select('id','nombre')->where('nombre','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($clientes);
    }
    public function GetVehicles(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $vehiculos=Vehiculo::select('id',DB::raw("CONCAT(no_economico, '-',placas) as nombre"))->where('placas','LIKE','%'.$term.'%')->orwhere('no_economico','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($vehiculos);
    }
    public function GetVehiclesHistory(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $vehiculos=Vehiculo::select('id',DB::raw("CONCAT(no_economico, '-',placas,'-',vim) as label"))->where('placas','LIKE','%'.$term.'%')->orwhere('no_economico','LIKE','%'.$term.'%')->orwhere('vim','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($vehiculos);
    }
    public function GetVehiclesHistoryOrdenes(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));

        $vehiculos=DetallesGenerales::select('id',DB::raw("OrdenServicio as label"))
        ->where('OrdenServicio','LIKE','%'.$term.'%')
        ->where('Vehiculo_id',$request->id??0)
        ->take(15)->get();
        return response()->json($vehiculos);
    }
    public function GetOrdenesServico(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $vehiculos=DetallesGenerales::select('id',DB::raw("OrdenServicio as nombre"))
        ->where('OrdenServicio','LIKE','%'.$term.'%')
        ->take(15)->get();
        return response()->json($vehiculos);
    }
    public function GetUserRepairShop(Request $request){
        if(!$request->has('tipo')){
            return response()->json(['error' => 'Tipo de usuario no especificado'], 400);
        }   
        $tipo = $request->input('tipo');
        $term = str_replace(' ', '%', $request->input('term'));

        if($tipo == '4'){
            $users=Tecnicos::select('id', 'nombre')->where('nombre', 'like', '%' . $term . '%')->take(15)->get();
        } else {
            $users=UsersTaller::select('id', 'nombre')->where('tipo_user_taller_id',$tipo)->where('nombre', 'like', '%' . $term . '%')->take(15)->get();
        }

        return response()->json($users);
    }
    public function GetAvailableCategories(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $presupuesto = $request->input('id');
        if(!$presupuesto){
             $categorias=CategoriasConceptos::select('id',"nombre")->where('nombre','LIKE','%'.$term.'%')->take(15)->get();
            return response()->json($categorias);
        }
        $idscat=CategoriasDisponibles::where('Tipo_id',Presupuesto::where('id',$presupuesto)->value('Tipo_id'))->pluck('categoria_id');
        $categorias=CategoriasConceptos::select('id',"nombre")->whereIn('id',$idscat)->where('nombre','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($categorias);
    }
    public function GetModulos(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $modulos=Modulo::select('id','descripcion')->whereIn('id',[3,4,5,6])->where('descripcion','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($modulos);
    }
    public function GetZonas(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $zonas=Sucursales::select('id','nombre')->where('nombre','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($zonas);
    }
    public function GetContratos(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $contratos=Contratos::select('id','nombre')->where('nombre','LIKE','%'.$term.'%')->take(15)->get();
        return response()->json($contratos);
    }
    public function GetVehiculosConceptos(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $tiposvehiculos =TiposVehiculoConcepto::select('id', 'nombre')
            ->where('nombre', 'like', '%' . $term . '%')
            ->take(15)
            ->get();

        return response()->json($tiposvehiculos);
    }
    public function GetProductosInventario(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $data =ProductoAlmacen::select('id', 'descripcion')
            ->where('descripcion', 'like', '%' . $term . '%')
            ->Orwhere('clave', 'like', '%' . $term . '%')
            ->take(15)
            ->get();

        return response()->json($data);
    }
    public function GetProductosMarcas(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $data =ProductoAlmacen::select('marca as nombre')
            ->where('marca', 'like', '%' . $term . '%')
            ->distinct()
            ->take(15)
            ->get();

        return response()->json($data);
    }
    public function GetProductosProveedores(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $data =Proveedores::select('nombre')
            ->where('nombre', 'like', '%' . $term . '%')
            ->take(15)
            ->get()->map(function($item){
                return [
                    'id'=>$item->nombre,
                    'nombre'=>$item->nombre
                ];
            });
        if($data->isEmpty()){
            $data=collect([[
                'id'=>$request->input('term'),
                'nombre'=>$request->input('term')
            ]]);
        }

        return response()->json($data);
    }
    public function GetNumeroPartesProducto(Request $request){
        $term = str_replace(' ', '%', $request->input('term'));
        $id = $request->input('id');
       $data = InventarioAlmacen::select('num_parte as value', 'num_parte as descripcion')
        ->where('entrada', true)
        ->where('producto_almacen_id', $id)
        ->where('num_parte', 'like', "%{$term}%")
        ->distinct()
        ->take(15)
        ->get();

        return response()->json($data);
    }
    public function ModulosCortana(Request $request){
        $term = $request->input('term');
        $id = $request->user()->id;

       $data = ContratosPerZona::select('id as value', 'descripcion');

       if($term){
        $search="%{$term}%";
        $data=$data->where('descripcion','LIKE', $search);
       }
        $data=$data
        ->orderbydesc('descripcion')
        ->orderbydesc('value')
        ->get();

        return response()->json($data);
    }
}