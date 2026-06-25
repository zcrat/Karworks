<?php

namespace App\Http\Controllers\zcrat;
use App\Http\Controllers\controller;
use Illuminate\Support\Facades\LOG;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\ProductoAlmacen;
use App\Models\Proveedores;
use App\Models\InventarioAlmacen;
use App\Models\ImagenesMovimientos;

class AlmacenController extends Controller
{
    public function InventarioVista(Request $request){
        return view('zcrat.AlmacenInventario');
    }
    public function MovimientosVista(Request $request){
        return view('zcrat.AlmacenMovimientos');
    }
    public function ReadInventario(Request $request){
        
        $user=$request->user();
        if(!$user->can('ver.taller.1') && !$user->can('ver.taller.2')){
            return response()->json(['message'=>'no puedes estar aqui'],500);
        }
        $taller=$user->can('ver.taller.1') && $user->can('ver.taller.2') ? ($request->taller == '' ? '%' : $request->taller) : ($user->can('ver.taller.1') ? 1 : 2) ; 

        $page = $request->input('currentPage', 1);
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $search = $request->input('search', '');
        $estatus = $request->input('estatus', '');
        $tipo = $request->input('tipo', '');

        try{
            $sub = DB::table('inventario_almacen')
                ->select(
                    'producto_almacen_id',
                    DB::raw('SUM(CASE WHEN tipo = 2  THEN cantidad ELSE 0 END) as inicio'),
                    DB::raw('SUM(CASE WHEN tipo = 1  THEN cantidad ELSE 0 END) as entradas'),
                    DB::raw('SUM(CASE WHEN tipo = 0  THEN cantidad ELSE 0 END) as salidas')
                )
                ->whereNull('deleted_at')
                ->where('taller_id','LIKE',$taller)
                ->groupBy('producto_almacen_id');
            $ultimaEntradaId = DB::table('inventario_almacen')
                ->select(
                    'producto_almacen_id',
                    DB::raw('MAX(id) as ultimo_id')
                )
                ->whereNull('deleted_at')
                ->where('tipo','!=', 0)
                ->where('taller_id','LIKE', $taller)
                ->groupBy('producto_almacen_id');

            $ultimaEntrada = DB::table('inventario_almacen as ia')
                            ->joinSub($ultimaEntradaId, 'u', function ($join) {
                                $join->on('ia.id', '=', 'u.ultimo_id');
                            })
                            ->leftJoin('proveedores as p', 'p.id', '=', 'ia.proveedor_id')
                            ->select(
                                'ia.producto_almacen_id',
                                'ia.created_at as ultima_fecha',
                                'ia.precio as ultimo_precio',
                                'ia.proveedor_id as idproveedor',
                                'p.nombre as proveedor'
                            );
            $elements = ProductoAlmacen::query()
                ->leftJoinSub($sub, 'inv', function ($join) {
                    $join->on('inv.producto_almacen_id', '=', 'productos_almacen.id');
                })
                ->leftJoinSub($ultimaEntrada, 'ue', function ($join) {
                    $join->on('ue.producto_almacen_id', '=', 'productos_almacen.id');
                })
                ->select(
                    'productos_almacen.*',
                    DB::raw('COALESCE(inv.inicio,0) as inicio'),
                    DB::raw('COALESCE(inv.entradas,0) as entradas'),
                    DB::raw('COALESCE(inv.salidas,0) as salidas'),
                    DB::raw("COALESCE(
                        DATE_FORMAT(ue.ultima_fecha, '%Y-%m-%d %H:%i'),
                        'Sin registro aún'
                    ) as ultima_fecha"),
                    DB::raw('COALESCE(ue.ultimo_precio, 0) as ultimo_precio'),
                    DB::raw("COALESCE(ue.proveedor, 'Sin registro aún') as proveedor")
                );

            if ($search) {
                $elements->where(function($query) use ($search){
                    $query->where('productos_almacen.descripcion', 'LIKE', '%' . $search . '%')
                    ->Orwhere('productos_almacen.clave', 'LIKE', '%' . $search . '%')
                    ->Orwhere('productos_almacen.marca', 'LIKE', '%' . $search . '%');

                });
            }
            if ($tipo) {
                $elements->where('productos_almacen.tipo',$tipo);
            }

            if ($estatus) {
                if ($estatus == 1) {
                    $elements->whereRaw('(COALESCE(inv.entradas,0) + (COALESCE(inv.inicio,0) - COALESCE(inv.salidas,0)) >= cantidad_max)');
                } elseif ($estatus == 2) {
                    $elements->whereRaw('(COALESCE(inv.entradas,0) + (COALESCE(inv.inicio,0) - COALESCE(inv.salidas,0)) < cantidad_max)')
                            ->whereRaw('(COALESCE(inv.entradas,0) + (COALESCE(inv.inicio,0) - COALESCE(inv.salidas,0)) > cantidad_min)');
                } else {
                    $elements->whereRaw('(COALESCE(inv.entradas,0) + (COALESCE(inv.inicio,0) - COALESCE(inv.salidas,0)) <= cantidad_min)');
                }
            }

            $elements = $elements->orderbydesc('id')->paginate($itemsPerPage, ['*'], 'page', $page);
            $totalelements=$elements->total();
            $elements = $elements->map(function ($item) {
                $inventario = ($item->entradas ?? 0) + ($item->inicio ?? 0) - ($item->salidas ?? 0);
                return [
                    'id'          => $item->id,
                    'descripcion' => $item->descripcion,
                    'tipo'        => $item->tipo == 1 ? 'Refaccion' : 'Herramienta',
                    'codigo'      => $item->clave,
                    'inicio'      => $this->formatNumber($item->inicio ?? 0),
                    'entradas'    => $this->formatNumber($item->entradas ?? 0),
                    'salidas'     => $this->formatNumber($item->salidas ?? 0),
                    'inventario'  => $this->formatNumber($inventario),
                    'fecha'       => $item->ultima_fecha,
                    'precio'      => $this->formatNumber($item->ultimo_precio),
                    'iva'         => $this->formatNumber($item->ultimo_precio * 0.16),
                    'final'       => $this->formatNumber($item->ultimo_precio * 1.16),
                    'precio2'     => $this->formatNumber($item->ultimo_precio * $inventario),
                    'iva2'        => $this->formatNumber($item->ultimo_precio * $inventario * 0.16),
                    'final2'      => $this->formatNumber($item->ultimo_precio * $inventario* 1.16),
                    'proveedor'   => $item->proveedor,
                    'marca'   => $item->marca,
                    'estatus'     => $inventario >= $item->cantidad_max
                                        ? 1
                                        : ($inventario > $item->cantidad_min ? 2 : 3)
                ];
            });
            return response()->json(compact('elements','totalelements'));
        }catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()],500);
            
        }
    }
    public function ReadMovimientos(Request $request){
        $user=$request->user();
        if(!$user->can('ver.taller.1') && !$user->can('ver.taller.2')){
            return response()->json(['message'=>'no puedes estar aqui'],500);
        }
        $taller=$user->can('ver.taller.1') && $user->can('ver.taller.2') ? ($request->taller == '' ? '%' : $request->taller ?? '%') : ($user->can('ver.taller.1') ? 1 : 2) ; 

        $page = $request->input('currentPage', 1);
        $itemsPerPage = $request->input('itemsPerPage', 10);
        $search = $request->input('search', '');
        $producto = $request->input('producto', '');
        $fecha1 = $request->input('fecha1', '');
        $fecha2 = $request->input('fecha2', '');
        $tipo = $request->input('tipo', '');
        $tipoproducto = $request->input('tipoproducto', '');
        try{

            $elements=InventarioAlmacen::with(['producto','proveedor','orden','taller'])->where('taller_id','LIKE',$taller);

             if($tipoproducto != ''){
                $elements=$elements->whereHas('producto', function($query) use($tipoproducto){
                    $query->where('tipo',$tipoproducto);
                });
            }else{
                 $elements=$elements->whereHas('producto');

             }
            if($search){
                $elements=$elements->where(function($query) use ($search){
                    $query->whereHas('orden',function ($q) use ($search){
                        $q->where('OrdenServicio', 'like', '%' . $search . '%')
                            ->orWhere('OrdenSeguimiento', 'like', '%' . $search . '%')
                            ->orWhereHas('Vehiculo', function($veh) use ($search) {
                                $veh->where('no_economico', 'like', '%' . $search . '%')
                                ->orWhere('placas', 'like', '%' . $search . '%') 
                                ->orWhere('vim', 'like', '%' . $search . '%');
                                // ->orWhereHas('marca', function($mar) use ($search) {
                                //     $mar->where('nombre', 'like', '%' . $search . '%');
                                // })->orWhereHas('modelo', function($mod) use ($search) {
                                //     $mod->where('nombre', 'like', '%' . $search . '%');
                                //     });
                                });
                    });
                    $query->OrWhereHas('proveedor',function ($q) use ($search){
                         $q->where('nombre', 'like', '%' . $search . '%');
                    });
                    
                });
            }
            if($tipo != ''){
                $elements=$elements->where('tipo',$tipo);
            }
            if($producto != ''){
                $elements=$elements->where('producto_almacen_id',$producto);
            }
            if ($fecha1 && $fecha1) {
                    $query->whereBetween(DB::raw('DATE(created_at)'), [$fecha1, $fecha2]);
            } elseif($fecha1 != ''){
                $elements=$elements->where('created_at','>=',$fecha1);
            }else if($fecha2 != ''){
                $elements=$elements->where('created_at','<=',$fecha2);
            }
            $elements=$elements->orderbydesc('id')->paginate($itemsPerPage,['*'],'page',$page);
            
            $totalelements=$elements->total();
            $elements=$elements->map(function ($item) {
                return 
                [
                    'id'=>$item->id,
                    'parte'=>$item->producto->clave,
                    'descripcion'=>$item->producto->descripcion,
                    'motivo'=>$item->descripcion,
                    'taller'=>$item->taller->nombre,
                    'cantidad' => $item->cantidad,
                    'tipo'=> $item->tipo == 1 ? 'Entrada' :( $item->tipo == 2 ? 'Compra' : 'Salida'),
                    'precio' => $item->precio * $item->cantidad,
                    'proveedor' => $item->proveedor ? $item->proveedor->nombre : '',
                    'orden' => $item->tipo == 0 ? ($item->orden ? $item->orden->OrdenServicio : 'No Se Registro') : '',
                    'vehiculo' => $item->tipo == 0 ? ($item->producto->tipo == 1 ?
                    ( $item->orden ? ($item->orden->Vehiculo->no_economico .'-'.$item->orden->Vehiculo->placas.'-'.$item->orden->Vehiculo->vim ) :  'No Se Registro')
                    : $item->descripcion ?? 'No Se Registro El Motivo') : '',
                    'fecha'=>$item->created_at,
                ];
            });
            return response()->json(compact('elements','totalelements'));
        }catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()],500);
            
        }
    }
    public function Entrada(Request $request){
        try {
            DB::beginTransaction();
            $user=$request->user();
            if(!$user->can('ver.taller.1') && !$user->can('ver.taller.2')){
                return response()->json(['message'=>'no puedes estar aqui'],500);
            }
            $taller=$user->can('ver.taller.1') && $user->can('ver.taller.2') ? ($request->taller) : ($user->can('ver.taller.1') ? 1 : 2) ;

            
            $producto=$request->producto;
            $cantidad=$request->cantidad;
            $precio=$request->precio;
            $proveedor=$request->proveedor;
            $id=$request->id_entrada;
            if(empty($id)){
                if(!$producto || !$cantidad || !$precio || !$proveedor || !$taller){
                    throw new \Exception('Los Datos Estan Incompletos');
                }
                if(!ProductoAlmacen::where('id',$producto)->exists()){
                    throw new \Exception('El Producto No Existe');
                }
                $proveedor = Proveedores::firstOrCreate([
                    'nombre' => $proveedor
                ]);
                $mov=InventarioAlmacen::create([
                    'cantidad'=>$cantidad,
                    'producto_almacen_id'=>$producto,
                    'tipo'=>1,
                    'descripcion'=>'',
                    'proveedor_id'=>$proveedor->id,
                    'precio'=>round($precio / $cantidad, 2),
                    'talle_id'=>$taller
                ]);
            }else{
                $mov=InventarioAlmacen::find($id);
                if(!$mov){
                    throw new \Exception('El Movimiento No Existe');
                }
                if(!$request->user()->can('editar.entradas')){
                    throw new \Exception('No Tienes Permiso de Editar Entradas');
                }
                if(!$cantidad || !$precio){
                    throw new \Exception('Los Datos Estan Incompletos');
                }
                $mov->cantidad=$cantidad;
                $mov->precio=round($precio / $cantidad, 2);
                $mov->save();

                $existenciasproducto=InventarioAlmacen::where('producto_almacen_id',$mov->producto_almacen_id)->where('taller_id',$mov->taller_id)->get();
                $existencia=0;
                foreach($existenciasproducto as $exis){
                    if($exis->tipo != 0){
                        $existencia+=$exis->cantidad;
                    }else{
                        $existencia-=$exis->cantidad;
                        
                    }
                }
                if($existencia < 0){
                    throw new \Exception('No hay suficiente Exitencia del Producto En El Almacen Para Reducir La Cantidad');
                }
            }
            $paths = [];
                foreach ($request->file('fotos', []) as $foto) {
                    $path=Storage::disk('public')->put('almacen\entradas', $foto);
                    $filename = basename($path);
                    $paths[] = $filename;
                }
            foreach ($paths as $path){
                ImagenesMovimientos::create([
                    'foto'=>$path,
                    'movimiento_almacen_id'=>$mov->id
                ]);
            }
            DB::commit();
            return response()->json(['message' => 'Movimiento Creado Correctamente']);

        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['message' => $th->getMessage()],500);
        }
    }
    public function Salida(Request $request){
        try {
            $user=$request->user();
            if(!$user->can('ver.taller.1') && !$user->can('ver.taller.2')){
                return response()->json(['message'=>'no puedes estar aqui'],500);
            }
            $taller=$user->can('ver.taller.1') && $user->can('ver.taller.2') ? ($request->taller) : ($user->can('ver.taller.1') ? 1 : 2) ;
            DB::beginTransaction();
            $producto=$request->producto;
            $cantidad=$request->cantidad;
            $orden_id=$request->orden_id;
            $motivo_salida=$request->motivo_salida;
            
            if(!$producto || !$cantidad || !$taller){
                throw new \Exception('Los Datos Estan Incompletos');
            }
            $pro=ProductoAlmacen::where('id',$producto)->first();
            if(!$pro){
                throw new \Exception('El Producto No Existe');
            }
            if($pro->tipo == 1){
                if(!$orden_id){
                    throw new \Exception('Para La Salida De Una Refaccion es necesario la orden de destino');
                }
            }else{
                if(!$motivo_salida){
                    throw new \Exception('Para La Salida De Una Herramienta es necesario el motivo de la salida');
                }
            }

            $existenciasproducto=InventarioAlmacen::where('producto_almacen_id',$producto)->where('taller_id',$taller)->get();
            $existencia=0;
            foreach($existenciasproducto as $exis){
                if($exis->tipo != 0){
                    $existencia+=$exis->cantidad;
                }else{
                    $existencia-=$exis->cantidad;
                    
                }
            }
            if($existencia < $cantidad){
                throw new \Exception('No hay suficiente Exitencia del Producto En El Almacen Con Este Numero De Parte');
            }
            InventarioAlmacen::create([
                'cantidad'=>$cantidad,
                'descripcion'=>$motivo_salida??'',
                'producto_almacen_id'=>$producto,
                'tipo' => 0,
                'orden_id'=>$orden_id,
                'taller_id'=>$taller
            ]);
            DB::commit();
            return response()->json(['message' => 'Movimiento Creado Correctamente']);

        } catch (\Throwable $th) {
            DB::rollback();
             return response()->json(['message' => $th->getMessage()],500);
        }
    }
    public function CrearOrEditar(Request $request){
         try {
            DB::beginTransaction();

            $user=$request->user();
            if(!$user->can('ver.taller.1') && !$user->can('ver.taller.2')){
                return response()->json(['message'=>'no puedes estar aqui'],500);
            }
            $taller=$user->can('ver.taller.1') && $user->can('ver.taller.2') ? ($request->taller) : ($user->can('ver.taller.1') ? 1 : 2) ; 

            $proveedor=$request->proveedor;
            $cantidad_inicio=empty($request->cantidad_inicio) ? 0 : $request->cantidad_inicio;
            $precio_producto=$request->precio_producto;
            
            $producto=$request->id;
            $descripcion_producto=$request->descripcion_producto;
            $cantidad_minima=$request->cantidad_minima;
            $cantidad_optima=$request->cantidad_optima;
            $codigo_producto=$request->codigo_producto;
            $tipo_producto=$request->tipo_producto;
            $marca_producto=$request->marca_producto;

            if(!empty($producto)){
                if(empty($descripcion_producto) || empty($cantidad_minima) || empty($cantidad_optima) || empty($codigo_producto) || empty($tipo_producto) || empty($marca_producto)){
                    throw new \Exception('Los Datos Estan Incompletos');
                }
                if(!ProductoAlmacen::where('id',$producto)->exists()){
                    throw new \Exception('El Producto No Existe');
                }
                if(ProductoAlmacen::where('clave',$codigo_producto)->where('id','!=',$producto)->exists()){
                    throw new \Exception('La Clave Esta Asociada A Otro Producto En El Almacen');
                }else if(ProductoAlmacen::onlyTrashed()->where('clave',$codigo_producto)->exists()){
                    throw new \Exception('La Clave Esta Asociada A Otro Producto En El Almacen Que Esta Eliminado, Notificar A Soporte');
                }   
               ProductoAlmacen::where('id', $producto)->update([
                    'proveedor_id' => 1,
                    'clave' => $codigo_producto,
                    'tipo' => $tipo_producto,
                    'marca'=>$marca_producto,  
                    'descripcion' => $descripcion_producto,
                    'cantidad_min' => $cantidad_minima,
                    'cantidad_max' => $cantidad_optima,
                ]);

            }else{
                if(empty($descripcion_producto) || empty($cantidad_minima) || empty($cantidad_optima) || empty($codigo_producto)  || empty($tipo_producto) || empty($marca_producto) || $cantidad_inicio <= 0 || empty($precio_producto) || empty($proveedor) || empty($taller)){
                    throw new \Exception('Los Datos Estan Incompletos 2');
                }else if(ProductoAlmacen::where('clave',$codigo_producto)->exists()){
                    throw new \Exception('La Clave Esta Asociada A Otro Producto En El Almacen');
                }else if(ProductoAlmacen::onlyTrashed()->where('clave',$codigo_producto)->exists()){
                    throw new \Exception('La Clave Esta Asociada A Otro Producto En El Almacen Que Esta Eliminado, Notificar A Soporte');
                }
                $producto=ProductoAlmacen::create([
                    'proveedor_id'=>1,
                    'clave'=>$codigo_producto, 
                    'descripcion'=>$descripcion_producto, 
                    'cantidad_min'=>$cantidad_minima, 
                    'cantidad_max'=>$cantidad_optima,
                    'tipo'=>$tipo_producto,  
                    'marca'=>$marca_producto,  
                ]);
                $proveedor = Proveedores::firstOrCreate([
                    'nombre' => $proveedor
                ]);
                $mov=InventarioAlmacen::create([
                    'cantidad'=>$cantidad_inicio,
                    'producto_almacen_id'=>$producto->id,
                    'descripcion'=>'',
                    'tipo'=>2,
                    'taller_id'=>$taller,
                    'precio'=>round($precio_producto / $cantidad_inicio, 2),
                    'proveedor_id'=>$proveedor->id,
                ]);
                $paths = [];
                foreach ($request->file('fotos', []) as $foto) {
                    $path=Storage::disk('public')->put('almacen\entradas', $foto);
                    $filename = basename($path);
                    $paths[] = $filename;
                }
                foreach ($paths as $path){
                    ImagenesMovimientos::create([
                        'foto'=>$path,
                        'movimiento_almacen_id'=>$mov->id
                    ]);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Guardado Correctamente']);
        } catch (\Throwable $th) {
            DB::rollback();
             return response()->json(['message' => $th->getMessage()],500);
        }
    }
    public function ReadEntrada(Request $request){
         try {
            $id=$request->id;
            $movimiento=InventarioAlmacen::with('Imagenes')->find($id);
            if(!$movimiento){
                throw new \Exception('El Movimiento No Existe');
            }else if($movimiento->tipo == 0){
                throw new \Exception('El Movimiento No Es Valido');
            }
            $data=[
                'cantidad'=>$movimiento->cantidad,
                'precio'=>round($movimiento->precio * $movimiento->cantidad, 2),
                'id'=>$movimiento->id,
                'imagenes'=>$movimiento->Imagenes
            ];
            return response()->json(compact(
                'data',
            ));
        } catch (\Throwable $th) {
             return response()->json(['message' => $th->getMessage()],500);
        }
    }
    public function ReadProducto(Request $request){
         try {
            $id=$request->id;
            $producto=ProductoAlmacen::find($id);
            if(!$producto){
                throw new \Exception('El Producto No Existe');
            }
            $descripcion_producto=$producto->descripcion;
            $cantidad_minima=$producto->cantidad_min;
            $cantidad_optima=$producto->cantidad_max;
            $tipo_producto=$producto->tipo;
            $codigo_producto=$producto->clave;
            $marca_producto=$producto->marca;
            return response()->json(compact(
                'descripcion_producto',
                'cantidad_minima',
                'cantidad_optima',
                'tipo_producto',
                'codigo_producto',
                'marca_producto'
            ));
        } catch (\Throwable $th) {
             return response()->json(['message' => $th->getMessage()],500);
        }
    }
    public function DeleteProducto(Request $request){
         try {
            $id=$request->id;
            $producto=ProductoAlmacen::find($id);
            if(!$producto){
                throw new \Exception('El Producto No Existe');
            }
            $useraaut=[1,170];
            if(!in_array($request->user()->id,$useraaut)){
                throw new \Exception('No Tienes Permiso de Eliminarlo');
            }
            $producto->delete();
            return response()->json(['message'=>'Eliminado Correctamente']);
        } catch (\Throwable $th) {
             return response()->json(['message' => $th->getMessage()],500);
        }
    }
    public function DeleteFoto(Request $request){
        try {
            $id=$request->id;
            $imagen=ImagenesMovimientos::find($id);
            if(!$imagen){
                throw new \Exception('El movimiento No Existe');
            }
            $useraaut=[1,170];
            if(!in_array($request->user()->id,$useraaut)){
                throw new \Exception('No Tienes Permiso de Eliminarlo');
            }
            $id=$imagen->movimiento_almacen_id;
            if (Storage::disk('public')->exists('almacen/entradas/'.$imagen->foto)) {
                Storage::disk('public')->delete('almacen/entradas/'.$imagen->foto);
            }
            $imagen->delete();
            return response()->json(['message'=>'Eliminado Correctamente','id'=>$id]);
        } catch (\Throwable $th) {
             return response()->json(['message' => $th->getMessage()],500);
        }
    }
    public function DeleteMovimiento(Request $request){
        try {
            $id=$request->id;
            $movimiento=InventarioAlmacen::find($id);
            if(!$movimiento){
                throw new \Exception('El movimiento No Existe');
            }
            $useraaut=[1,170];
            if(!in_array($request->user()->id,$useraaut)){
                throw new \Exception('No Tienes Permiso de Eliminarlo');
            }
            if($movimiento->tipo != 0){
                $existenciasproducto=InventarioAlmacen::where('producto_almacen_id',$movimiento->producto_almacen_id)->where('taller_id', $movimiento->taller_id)->get();
                $existencia=0;
                foreach($existenciasproducto as $exis){
                    if($exis->tipo != 0){
                        $existencia+=$exis->cantidad;
                    }else{
                        $existencia-=$exis->cantidad;
                    }
                }
                if(($existencia - $movimiento->cantidad) < 0){
                    throw new \Exception('No hay suficiente Exitencia del Producto En El Almacen del Taller Para Eliminar La Entrada');
                }
            }
            $movimiento->delete();
            return response()->json(['message'=>'Eliminado Correctamente']);
        } catch (\Throwable $th) {
             return response()->json(['message' => $th->getMessage()],500);
        }
    }
    private function formatNumber($valor) {
        // Normaliza nulos
        $valor = $valor ?? 0;

        // Si es entero exacto
        if (fmod($valor, 1) == 0) {
            return (int) $valor;
        }

        // Si tiene decimales, fuerza dos dígitos
        return number_format($valor, 2, '.', '');
    }
}
