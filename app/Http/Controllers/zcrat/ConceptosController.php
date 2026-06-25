<?php

namespace App\Http\Controllers\zcrat;
use App\Http\Controllers\controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Conceptos;
use App\Models\PresupuestoCarrito;

class ConceptosController extends Controller
{
    public function CreateGlobal(Request $request)
    {
        // This method can be used to create a new concept
        $request->validate([
            'anio' => ['required', 'integer', 'min:2000', 'max:2100'],
            'ConCatSat' => ['required', 'exists:categorias_sat,id'],
            'ConUniSat' => ['required', 'exists:unidades,id'],
            'ConCod' => ['required', 'string', 'max:50'],
            'ConCat' => ['required', 'exists:categoriasnuevo,id'],
            'ConTipVeh' => ['required', 'exists:tipos_vehiculo_concepto,id'],
            'modulo' => ['required', 'exists:modulos,id'],
            'contrato' => ['required', 'exists:contratos,id'],
            'zona' => ['required', 'exists:sucursales,id'],
            'ConPreMO' => ['required', 'numeric', 'min:0'],
            'ConPreRef' => ['required', 'numeric', 'min:0'],
            'ConDes' => ['required','string','max:5000'],
        ]);
        try{
            DB::beginTransaction();
            $concepto = new Conceptos();
            $data = [
                'anio' => $request->input('anio'),
                'Categoria_sat_id' => $request->input('ConCatSat'),
                'unidades_sat_id' => $request->input('ConUniSat'),
                'num' => $request->input('ConCod'),
                'Categorias_id' => $request->input('ConCat'),
                'Tipos_id' => $request->input('ConTipVeh'),
                'modulo_id' => $request->input('modulo'),
                'contrato_id' => $request->input('contrato'),
                'zona_id' => $request->input('zona'),
                'p_mo' => $request->input('ConPreMO'),
                'p_refaccion' => $request->input('ConPreRef'),
                'p_total' => $request->input('ConPreMO') + $request->input('ConPreRef'),
                'descripcion' => $request->input('ConDes'),
            ];
            $concepto->fill($data);
            $concepto->save();
            DB::commit();
            return response()->json(['message' => 'Concept created successfully', 'concept' => $concepto], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message'=>$e->getmessage()]);
        }
    }
    public function Update(Request $request)
    {
        // This method can be used to create a new concept
        $request->validate([
            'anio' => ['required', 'integer', 'min:2000', 'max:2100'],
            'ConCatSat' => ['required', 'exists:categorias_sat,id'],
            'ConUniSat' => ['required', 'exists:unidades,id'],
            'ConCod' => ['required', 'string', 'max:50'],
            'ConCat' => ['required', 'exists:categoriasnuevo,id'],
            'ConTipVeh' => ['required', 'exists:tipos_vehiculo_concepto,id'],
            'modulo' => ['required', 'exists:modulos,id'],
            'contrato' => ['required', 'exists:contratos,id'],
            'zona' => ['required', 'exists:sucursales,id'],
            'ConPreMO' => ['required', 'numeric', 'min:0'],
            'ConPreRef' => ['required', 'numeric', 'min:0'],
            'ConDes' => ['required','string','max:5000'],
            'ConId' => ['required', 'exists:conceptosnuevos,id'],
        ]);

        try{
            DB::beginTransaction();
            $concepto = Conceptos::findorfail($request->ConId);
            $data = [
                'anio' => $request->input('anio'),
                'Categoria_sat_id' => $request->input('ConCatSat'),
                'unidades_sat_id' => $request->input('ConUniSat'),
                'num' => $request->input('ConCod'),
                'Categorias_id' => $request->input('ConCat'),
                'Tipos_id' => $request->input('ConTipVeh'),
                'modulo_id' => $request->input('modulo'),
                'contrato_id' => $request->input('contrato'),
                'zona_id' => $request->input('zona'),
                'p_mo' => $request->input('ConPreMO'),
                'p_refaccion' => $request->input('ConPreRef'),
                'p_total' => $request->input('ConPreMO') + $request->input('ConPreRef'),
                'descripcion' => $request->input('ConDes'),
                
            ];
            $concepto->Update($data);
            DB::commit();
            return response()->json(['message' => 'Concept Update successfully', 'concept' => $concepto], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['message'=>$e->getmessage()]);
        }
    }
    public function GetDataElement(Request $request)
    {
        // This method can be used to get a single concept by ID
        $request->validate([
            'id' => ['required', 'exists:conceptosnuevos,id'],
        ]);

       $concepto = Conceptos::with([
    'CategoriaSat', 'UnidadSat', 'Categoria', 'TipoVehiculo', 'modulo', 'contrato', 'zona'
    ])->findOrFail($request->input('id'));

    $conceptoTransformado = [
        'ConPreId' => $concepto->id,
        'anio' => $concepto->anio,
        'ConPreCatSat' => $concepto->Categoria_sat_id,
        'ConPreCatSatClave' =>  $concepto->CategoriaSat ? $concepto->CategoriaSat->codigo_sat : null,
        'ConPreCatSatName' => $concepto->CategoriaSat ? $concepto->CategoriaSat->descripcion : null,
        'ConPreUniSat' => $concepto->unidades_sat_id,
        'ConPreUniSatName' => $concepto->UnidadSat ? $concepto->UnidadSat->nombre : null,
        'ConPreUniSatCode' => $concepto->UnidadSat ? $concepto->UnidadSat->clave : null,
        'ConPreCod' => $concepto->num,
        'ConPreCat' => $concepto->Categorias_id,
        'ConPreCatName' => $concepto->Categoria ? $concepto->Categoria->nombre : null,
        'ConPreTip' => $concepto->Tipos_id,
        'ConPreTipName' => $concepto->TipoVehiculo ? $concepto->TipoVehiculo->nombre : null,
        'modulo' => $concepto->modulo_id,
        'moduloName' => $concepto->modulo ? $concepto->modulo->descripcion : null,
        'contrato' => $concepto->contrato_id,
        'contratoName' => $concepto->contrato ? $concepto->contrato->nombre : null,
        'zona' => $concepto->zona_id,
        'zonaName' => $concepto->zona ? $concepto->zona->nombre : null,
        'ConPreMO' => $concepto->p_mo,
        'ConPreRef' => $concepto->p_refaccion,
        'ConPrePreTot' => $concepto->p_total,
        'ConPreDes' => $concepto->descripcion,
    ];

        return response()->json(['element'=>$conceptoTransformado]);
    }
    public function create(Request $request)
    {
        // This method can be used to create a new concept
        $request->validate([
            'anio' => ['required', 'integer', 'min:2000', 'max:2100'],
            'ConPreCatSat' => ['required', 'exists:categorias_sat,id'],
            'ConPreUniSat' => ['required', 'exists:unidades,id'],
            'ConPreCod' => ['required', 'string', 'max:50'],
            'ConPreCat' => ['required', 'exists:categoriasnuevo,id'],
            'ConPreTip' => ['required', 'exists:tipos_vehiculo_concepto,id'],
            'modulo' => ['required', 'exists:modulos,id'],
            'contrato' => ['required', 'exists:contratos,id'],
            'zona' => ['required', 'exists:sucursales,id'],
            'ConPrePreMan' => ['required', 'numeric', 'min:0'],
            'ConPrePreRef' => ['required', 'numeric', 'min:0'],
            'ConPreDes' => ['required','string','max:5000'],
        ]);

        $concepto = new Conceptos();
        $data = [
            'anio' => $request->input('anio'),
            'Categoria_sat_id' => $request->input('ConPreCatSat'),
            'unidades_sat_id' => $request->input('ConPreUniSat'),
            'num' => $request->input('ConPreCod'),
            'Categorias_id' => $request->input('ConPreCat'),
            'Tipos_id' => $request->input('ConPreTip'),
            'modulo_id' => $request->input('modulo'),
            'contrato_id' => $request->input('contrato'),
            'zona_id' => $request->input('zona'),
            'p_mo' => $request->input('ConPrePreMan'),
            'p_refaccion' => $request->input('ConPrePreRef'),
            'p_total' => $request->input('ConPrePreMan') + $request->input('ConPrePreRef'),
            'descripcion' => $request->input('ConPreDes'),
        ];
        $concepto->fill($data);
        $concepto->save();

        return response()->json(['message' => 'Concept created successfully', 'concept' => $concepto], 201);
    }

    public function Delete(Request $request)
    {
        // This method can be used to delete a concept
        $request->validate([
            'id' => ['required', 'exists:conceptosnuevos,id'],
        ]);

        $carrito = PresupuestoCarrito::where("Concepto_id",$request->id)->get();
        
        if($carrito->isNotEmpty()){
            return response()->json(['message' => 'El Concepto Esta Agregado A un Presupuesto'], 500);
        }
        //eliminar concepto del carrito eliminados

        $concepto = Conceptos::findOrFail($request->input('id'));
        $concepto->delete();

        return response()->json(['message' => 'Eliminado Correctamente'], 200);
    }

    public function GetAllElement (Request $request)
    {

        $conceptos = Conceptos::with(['CategoriaSat', 'UnidadSat', 'Categoria', 'TipoVehiculo', 'modulo', 'contrato', 'zona'])
            ->orderBy('anio', 'desc');
        
        if ($request->filled('anio')) {
            $conceptos->where('anio', $request->input('anio'));
        }
        if ($request->filled('modulo')) {
            $conceptos->where('modulo_id', $request->input('modulo'));
        }
        if ($request->filled('contrato')) {
            $conceptos->where('contrato_id', $request->input('contrato'));
        }
        if ($request->filled('zona')) {
            $conceptos->where('zona_id', $request->input('zona'));
        }
        if ($request->filled('categoria')) {
            $conceptos->where('Categorias_id', $request->input('categoria'));
        }
        if ($request->filled('search')) {
            $conceptos->where('descripcion', 'LIKE', '%' . $request->input('search') . '%');
        }
        if($request->filled('tipo')){
            $conceptos->where('Tipos_id', $request->input('Tipos_id'));
        }
        if($request->filled('catsat')){
            $conceptos->where('Categoria_sat_id', $request->input('Categoria_sat_id'));
        }
        if($request->filled('unisat')){
            $conceptos->where('unidades_sat_id', $request->input('unidades_sat_id'));
        }
        $page = $request->input('page', 1);
        $limit = $request->input('itemsperpage', 30);
        $total= $conceptos->count();
        $conceptos = $conceptos->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();
        $conceptos=$conceptos->map(function($concepto) {
            return [
                'ConPreId' => $concepto->id,
                'anio' => $concepto->anio,
                'ConPreCatSat' => $concepto->Categoria_sat_id,
                'ConPreCatSatName' => $concepto->CategoriaSat ? $concepto->CategoriaSat->descripcion : null,
                'ConPreUniSat' => $concepto->unidades_sat_id,
                'ConPreUniSatName' => $concepto->UnidadSat ? $concepto->UnidadSat->nombre : null,
                'ConPreUniSatCode' => $concepto->UnidadSat ? $concepto->UnidadSat->clave : null,
                'ConPreCod' => $concepto->num,
                'ConPreCat' => $concepto->Categorias_id,
                'ConPreCatName' => $concepto->Categoria ? $concepto->Categoria->nombre : null,
                'ConPreTip' => $concepto->Tipos_id,
                'ConPreTipName' => $concepto->TipoVehiculo ? $concepto->TipoVehiculo->nombre : null,
                'modulo' => $concepto->modulo_id,
                'moduloName' => $concepto->modulo ? $concepto->modulo->descripcion : null,
                'contrato' => $concepto->contrato_id,
                'contratoName' => $concepto->contrato ? $concepto->contrato->nombre : null,
                'zona' => $concepto->zona_id,
                'zonaName' => $concepto->zona ? $concepto->zona->nombre : null,
                'ConPrePreMan' => $concepto->p_mo,
                'ConPrePreRef' => $concepto->p_refaccion,
                'ConPrePreTot' => $concepto->p_total,
                'ConPreDes' => $concepto->descripcion,
                'ConPreTotal' => $concepto->p_total,
                'id' => $concepto->id,
            ];
        }); 

        return response()->json([
            'Conceptos' => $conceptos,
            'total' => $total,
        ]);
    }
      public function CatalogoConceptos2(Request $request){
       
        if($request->has('id')){

            try {

                $page = $request->page??1;
                $perPage = $request->itemsPerPage??10;
                $skip = ($page - 1) * $perPage;

                $presupuesto=Presupuesto::with('detallesGenerales')->findorfail($request->id);
                $detalles=DetallesGenerales::findorfail($presupuesto->DetallesGenerales_id);
                $categoriasdisponibles=CategoriasDisponibles::where('tipo_id',$presupuesto->Tipo_id)->pluck('categoria_id');

                $query = Conceptos::with('TipoVehiculo')
                    ->where(function ($q) use ($detalles) {
                        $q->where(function ($q2) use ($detalles) {
                            $q2->where('Tipos_id', $detalles->Tipo_Vehiculo_Concepto_id)
                                ->where('num', '!=', 'FC');
                        })
                        ->orWhere(function ($q2) {
                            $q2->where('num', 'FC');
                        });
                    })
                    ->where('modulo_id', $detalles->modulo_id)
                    ->where('contrato_id', $detalles->contrato_id)
                    ->where('zona_id', $detalles->zona_id)
                    ->where('anio', $detalles->anio)
                    ->whereIn('Categorias_id', $categoriasdisponibles)
                    ->whereNotIn('id', PresupuestoCarrito::where("Presupuesto_id", $request->id)->pluck('Concepto_id'));

                
                $queryTipos = clone $query;
                    $tiposIds = $queryTipos->pluck('Tipos_id');
                    $tipos = TiposVehiculoConcepto::whereIn('id', $tiposIds)->pluck('nombre', 'id');
                
                if ($request->has('search')) {
                    $query->where(function ($q) use ($request) {
                        $q->Where('descripcion', 'like', '%' . $request->search . '%');
                    });
                }
                if ($request->has('tipo')) {
                    $query->where('Tipos_id', $request->tipo);
                }
                if ($request->has('categoria')) {
                    $query->where('Categorias_id', $request->categoria);
                }
                $total = $query->count();
                $elements = $query->skip($skip)->take($perPage)->get();
                return response()->json([
                    'elements' => $elements,
                    'tipos' => $tipos,
                    'tipo_pre' => $detalles->Tipo_Vehiculo_Concepto_id,
                    'total' => $total,
                ]);
            } catch (\Exception $e) {
                log::info($e);
                return response()->json([$e->getmessage()],500);
            }
           
        }
       return response()->json(['message'=>'No Se Envia El Presupuesto'],500);
        
    }
}
