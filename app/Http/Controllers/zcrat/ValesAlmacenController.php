<?php

namespace App\Http\Controllers\zcrat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ValesAlmacen;
use App\Models\ConceptoVale;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class ValesAlmacenController extends Controller
{
    Public function GetElemetsPerOrdenServicio(Request $request){
        $elements = ValesAlmacen::where('Detalles_Generales_Id',$request->id)->get();
        return response()->json(compact('elements'));
    }
    Public function GetConceptosPerVale(Request $request){
        $vale= ValesAlmacen::findorfail($request->id);
        $motor=$vale->Tipo_Motor;
        $destino=$vale->Destino;
        $tipo=$vale->status; 
        $elements = ConceptoVale::where('Vale_Almacen_id',$request->id)->get()->map(function($item){
            return[
                'id'=>$item->id,
                'cantidad'=>$item->Cantidad,
                'descripcion'=>$item->Descripcion
            ];
        });
        return response()->json(compact('elements','motor','destino','tipo'));
    }
    Public function CreatedViewPdf(Request $request){
        $element = ValesAlmacen::with([
            'detallesGenerales:Vehiculo_id,OrdenServicio,OrdenSeguimiento,Empresa_id,zona_id,modulo_id,id,fecha_entrada,Fecha_salida,kilometraje_entrada',
            'detallesGenerales.Vehiculo',
            'detallesGenerales.Vehiculo.color:id,nombre',
            'detallesGenerales.Vehiculo.marca:id,nombre',
            'detallesGenerales.Vehiculo.modelo:id,nombre',
            'detallesGenerales.Empresa:id,nombre',
            'detallesGenerales.modulo.FacturaEmisor',
            'detallesGenerales.zona:id,nombre',
            'detallesGenerales.RecepcionVehicular.tecnico',
            'Autorizado',
            'Conceptos'])->where('id',$request->id)->first();
        
        return \View::make('pdf.ValeAlmacen', compact('element'))->render();   
    }
    Public function Create(Request $request){
        try{
            DB::beginTransaction();
            if($request->filled('ValAlm_Tip') && !$request->filled('ValAlm_Des')){
                throw new \Exception('no se especifico el destinatario');
            }
            $status=$request->filled('ValAlm_Tip') ? $request->ValAlm_Tip: 0;
            $destino=$request->filled('ValAlm_Tip') ? $request->ValAlm_Des:'ALMACEN';

            $num = ValesAlmacen::withTrashed()->where('Detalles_Generales_Id',$request->ValAlm_DetGen)->count();
            $num = $num + 1;
            $vale = new ValesAlmacen();
            $now=Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
            $vale->Detalles_Generales_Id=$request->ValAlm_DetGen;
            $vale->Tipo_Motor=$request->ValAlm_TipMot;
            $vale->created_at=$now;
            $vale->num=$num;
            $vale->Destino=$destino;
            $vale->status=$status;
            $vale->user_id_created=$request->user()->id;
            $vale->save();
            
            $conceptos=$request->listofconcepts;

            foreach ($conceptos as $concepto ){
                $concepto_vale = new ConceptoVale();
                $concepto_vale->Vale_Almacen_id =$vale->id ;
                $concepto_vale->Descripcion =$concepto['descripcion'] ?? 'Sin Definir';
                $concepto_vale->Cantidad =$concepto['cantidad'] ?? 1;
                $concepto_vale->save();
            }
            DB::commit();
            return response()->json(['message'=>'Vale Creado Exitosamente','element'=>$vale]);
            
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    Public function Editar(Request $request){
        try{
            DB::beginTransaction();
            $vale = ValesAlmacen::findorfail($request->id);
            if($request->filled('ValAlm_Tip') && !$request->filled('ValAlm_Des')){
                throw new \Exception('no se especifico el destinatario');
            }
            $status=$request->filled('ValAlm_Tip') ? $request->ValAlm_Tip: 0;
            $destino=$request->filled('ValAlm_Tip') ? $request->ValAlm_Des:'ALMACEN';

            if($vale->status!=$status){
                 throw new \Exception('no se Puede Cambiar El Tipo De Vale, Eliminalo Y Crealo de Nuevo');
            }
            $vale->Tipo_Motor=$request->ValAlm_TipMot;
            $vale->Destino=$destino;
            
            $vale->save();
            
            $conceptos=$request->listofconcepts;

            $ids=[];
            foreach ($conceptos as $concepto ){
                if(!empty($concepto['id'])){
                    $concepto_vale =ConceptoVale::find($concepto['id']);
                }else{
                    $concepto_vale = new ConceptoVale();
                    $concepto_vale->Vale_Almacen_id =$vale->id ;
                }

                if(!empty($concepto['descripcion']) || !empty($concepto['descripcion'] )){
                    $concepto_vale->Descripcion =$concepto['descripcion'] ?? 'Sin Definir';
                    $concepto_vale->Cantidad =$concepto['cantidad'] ?? 1;
                    $concepto_vale->save();
                    $ids[]=$concepto_vale->id;
                }

            }
            ConceptoVale::whereNotIn('id',$ids)->where('Vale_Almacen_id',$vale->id)->delete();
            DB::commit();
            return response()->json(['message'=>'Vale Creado Exitosamente','element'=>$vale]);
            
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    public function delete(Request $request){
        try{
            DB::beginTransaction();
            $vale = ValesAlmacen::findorfail($request->id);
            $vale->user_id_deleted=$request->user()->id;
            $vale->save();
            $vale->delete();
            DB::commit();
            return response()->json(['message'=>'Vale Eliminado Exitosamente']);
            
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    public function Entregar(Request $request){
        try{
            DB::beginTransaction();
            $fecha_entrega=Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
            $vale = ValesAlmacen::findorfail($request->id);
            $vale->fecha_entrega=$fecha_entrega;
            if($vale->status == 1){
                $vale->fecha_surtido=$fecha_entrega;
            }
            $vale->save();
            DB::commit();
            $message=($vale->status == 0 ? 'Se Entrego Al Almacen : ' : 'El Trabajo Fue Aprobado : ').$fecha_entrega;
            return response()->json(['message'=>$message,'fecha_entrega'=>$fecha_entrega]);
            
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    public function Surtir(Request $request){
        try{
            DB::beginTransaction();
            $fecha_surtido=Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
            $vale = ValesAlmacen::findorfail($request->id);
            $vale->fecha_surtido=$fecha_surtido;
            $vale->save();
            DB::commit();
            if($vale->status == 1){
                throw new \Exception('El Vale Es De Subcontratos');
            }
            $message=($vale->status == 0 ? 'Las Refacciones Se Confirmaron : ' : 'El Trabajo Fue Autorizado : ').$fecha_surtido;
            return response()->json(['message'=>$message,'fecha_surtido'=>$fecha_surtido,'']);
            
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    public function AutorizarSubcontratados(Request $request){
        try{
            DB::beginTransaction();
            $vale = ValesAlmacen::findorfail($request->id);
            $now=Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
            ConceptoVale::where('Vale_Almacen_id',$vale->id)->update(['entregado_at'=>$now]);
            DB::commit();
            if($vale->status == 0){
                throw new \Exception('El Vale Es De Almacen');
            }
            $message=($vale->status == 0 ? 'Las Refacciones Se Confirmaron : ' : 'El Trabajo Fue Autorizado : ').$now;
            return response()->json(['message'=>$message,'fecha_surtido'=>$now]);
            
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }

    public function Verificar(Request $request){
        try{
            $vale = ValesAlmacen::findorfail($request->id);
            $entregado = $vale->fecha_entrega ? Carbon::parse($vale->fecha_entrega)->format('Y-m-d H:i:s') : null;
            $surtido = $vale->fecha_surtido ? Carbon::parse($vale->fecha_surtido)->format('Y-m-d H:i:s') : null;
            return response()->json(['Entregado'=>$entregado,'Surtido'=>$surtido,'Tipo'=>$vale->status]);
        }catch(\Exception $e){
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    public function Read(Request $request){
        try{
            $page = $request->currentPage ?? 1;
            $itemsPerPage = $request->itemsPerPage ?? 10;
            $skip = ($page - 1) * $itemsPerPage;

            $query = ValesAlmacen::with(['detallesGenerales.contrato','detallesGenerales.modulo','detallesGenerales.zona','Conceptos','conceptosEntregados']);
             if ($request->filled('search')) {
                $query->where(function ($q1) use ($request) {
                    $q1->whereHas('detallesGenerales', function ($q) use ($request) {
                        $q->where('OrdenServicio', 'like', '%' . $request->search . '%')
                            ->orWhere('OrdenSeguimiento', 'like', '%' . $request->search . '%');
                    })->orWhereRaw("CONCAT(LPAD(id, 5, '0'), '-', num) LIKE ?", ['%' . $request->search . '%']);
                });
            }

            if ($request->filled('estatus')) {
                if($request->estatus=='0'){
                    $query->whereNull('fecha_entrega');
                }
                elseif($request->estatus=='1'){
                    $query->whereNotNull('fecha_surtido')->whereHas('ConceptosPendientes');
                }
                elseif($request->estatus=='2'){
                    $query->whereNotNull('fecha_surtido')->whereDoesntHave('ConceptosPendientes');
                }elseif ($request->status='3') {
                   $query->whereNull('fecha_surtido')->whereNotNull('fecha_entrega');
                }
            }

            $query->where('status',$request->tipo);
            $elements=[];
            $totalelements = $query->count();
            if($request->tipo == 0){
                $elements = $query->orderbydesc('id')->skip($skip)
                ->take($itemsPerPage)
                ->get()->map(function($item){
                    return[
                        'id'=>$item->id,
                        'OrdenServicio'=>$item->detallesGenerales->OrdenServicio ?? 'N/A',
                        'contrato'=>$item->detallesGenerales->contrato->nombre ?? 'N/A',
                        'modulo'=>$item->detallesGenerales->modulo->descripcion ?? 'N/A',
                        'zona'=>$item->detallesGenerales->zona->nombre ?? 'N/A',
                        'anio'=>$item->detallesGenerales->anio ?? 'N/A',
                        'OrdenSeguimiento'=>$item->detallesGenerales->OrdenSeguimiento ?? 'N/A',
                        'Folio'=>str_pad($item->id, 5, '0', STR_PAD_LEFT).'-'.$item->num,
                        'Creado'=>$item->created_at,
                        'Entregado'=>$item->fecha_entrega,
                        'Confirmado'=>$item->fecha_surtido,
                        'completado'=>($item->conceptosEntregados->count() ?? 0) / ($item->Conceptos->count() ?? 1) == 1,
                        'Surtido'=>($item->conceptosEntregados->count() ?? 0).'/'.($item->Conceptos->count() ?? 0),
                    ];
                })    ;
            }else if($request->tipo == 1){
                $elements = $query->orderbydesc('id')->skip($skip)
                ->take($itemsPerPage)
                ->get()->map(function($item){
                    return[
                        'id'=>$item->id,
                        'OrdenServicio'=>$item->detallesGenerales->OrdenServicio ?? 'N/A',
                        'contrato'=>$item->detallesGenerales->contrato->nombre ?? 'N/A',
                        'modulo'=>$item->detallesGenerales->modulo->descripcion ?? 'N/A',
                        'zona'=>$item->detallesGenerales->zona->nombre ?? 'N/A',
                        'anio'=>$item->detallesGenerales->anio ?? 'N/A',
                        'OrdenSeguimiento'=>$item->detallesGenerales->OrdenSeguimiento ?? 'N/A',
                        'Folio'=>str_pad($item->id, 5, '0', STR_PAD_LEFT).'-'.$item->num,
                        'Creado'=>$item->created_at,
                        'Entregado'=>$item->fecha_entrega,
                        'completado'=>$item->conceptosEntregados->count() != 0 ,
                        'autorizado'=>$item->conceptosEntregados->count() > 0 ? $item->conceptosEntregados[0]->entregado_at :'Pendiente'
                    ];
                })    ;
            }

            return response()->json(compact('elements', 'totalelements'));
        }catch(\Exception $e){
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }

    public function Detalles(Request $request){
        try{
            $vale= ValesAlmacen::findorfail($request->id);
            $conceptos = ConceptoVale::where('Vale_Almacen_id',$request->id)->get()->map(function($item){
                return[
                    'id'=>$item->id,
                    'cantidad'=>$item->Cantidad,
                    'descripcion'=>$item->Descripcion,
                    'entregado'=>$item->entregado_at ? $item->entregado_at->format('Y-m-d H:i:s') : null,
                ];
            });
            return response()->json(compact('conceptos'));
        }catch(\Exception $e){
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    public function EntregarConceptos(Request $request){
        try{
            DB::beginTransaction();
            $ids=$request->ids;
            $now=Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
            ConceptoVale::whereIn('id',$ids)->update(['entregado_at'=>$now]);
            DB::commit();
            return response()->json(['message'=>'Conceptos Entregados Exitosamente']);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    public function CancelarEntregaConceptos(Request $request){
        try{
            DB::beginTransaction();
            $ids=$request->ids;
            ConceptoVale::whereIn('id',$ids)->update(['entregado_at'=>null]);
            DB::commit();
            return response()->json(['message'=>'Entrega de Conceptos Cancelada Exitosamente']);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    public function View(Request $request){
        return view('zcrat.ValesAlmacenGestion');
    }
    public function View2(Request $request){
        return view('zcrat.ValesSubConGestion');
    }
    public function Concentrado(Request $request){
        try{
            $vales = ValesAlmacen::where('Detalles_Generales_Id',$request->id)->get();
            if($vales->isEmpty()){
                return response()->json(['message'=>'No se encontraron vales de almacén asociados a esta orden'],404);
            }
            $elements = ConceptoVale::whereIn('Vale_Almacen_id',$vales->pluck('id'))->get()->map(function($item){
                return[
                    'id'=>$item->id,
                    'cantidad'=>$item->Cantidad,    
                    'descripcion'=>$item->Descripcion
                ];
            });
            return response()->json(compact('elements'));
        }catch(\Exception $e){
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }

    public function DeleteConcepto(Request $request){
        try{
            $concepto= ConceptoVale::findorfail($request->id);
            $idvale=$concepto->Vale_Almacen_id;
            $concepto->delete();

            if(ConceptoVale::where('Vale_Almacen_id',$idvale)->count() == 0){
                $vale= ValesAlmacen::findorfail($idvale);
                $vale->delete();
            }

            return response()->json(['message'=>'Concepto Eliminado Exitosamente']);
        }catch(\Exception $e){
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    public function UpdateConcepto(Request $request){
        try{
            $concepto= ConceptoVale::findorfail($request->id);
            $concepto->Cantidad=$request->cantidad;
            $concepto->Descripcion=$request->descripcion;
            $concepto->save();
            return response()->json(['message'=>'Concepto Actualizado Exitosamente']);
        }catch(\Exception $e){
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    public function CreateConcepto(Request $request){
        try{
            $concepto= new ConceptoVale();
            $lastvale= ValesAlmacen::where('detalles_generales_id',$request->orden_id)->latest()->first();
            if(!$lastvale){
                return response()->json(['message'=>'No se encontro un vale asociado a esta orden'],404);
            }
            $concepto->Vale_Almacen_id=$lastvale->id;
            $concepto->Cantidad=$request->cantidad;
            $concepto->Descripcion=$request->descripcion;
            $concepto->save();
            return response()->json(['message'=>'Concepto Creado Exitosamente','element'=>$concepto]);
        }catch(\Exception $e){
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
}
