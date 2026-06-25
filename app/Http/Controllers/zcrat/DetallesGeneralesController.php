<?php

namespace App\Http\Controllers\zcrat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetallesGenerales;
use App\Models\Contratos;
use App\Models\Sucursales;
use App\Models\ArchivoSalida;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\RecepcionesVehiculares;
use App\Models\Reingresos;
use App\Models\CondicionesPintura;
use App\Models\EquipoInventario;
use App\Models\ExterioresEquipo;
use App\Models\FotoRecepcionVehicular;
use App\Models\InterioresEquipo;
use App\Models\Presupuesto;
use App\Models\TrabajosParciales;
use App\Models\ContratosPerZona;
use App\Models\SeguimientoUnidades;
use Carbon\Carbon;

class DetallesGeneralesController extends Controller
{
    public function GetElement(Request $request){
            if (!$request->ajax()) {
                return redirect()->route('homevue');
            }
            if($request->has('id')){
                try {
                    $detalles=DetallesGenerales::with([
                        'Empresa',
                        'tipoVehiculo',
                        'Customer',
                        'AdministradorTrasporte',
                        'JefedeProceso',
                        'Trabajador',
                        'Vehiculo',])->findorfail($request->id);
                    return response()->json([
                        'element' => $detalles
                    ]);
                } catch (\Exception $e) {
                    return response()->json(['message'=>$e->getmessage()],500);
                }
            }
        return response()->json(['message'=>'No Se Envio La Informacion Requerida, Contacte A Soporte'],500);
    }
    public function UpdateModuloCortana(Request $request){
        $request->validate([
            'DetGenID' => ['required','exists:detallesgenerales,id'],
            'modulo_cortana' => 'required|exists:contratos_modulo,id'
        ]);
        try {
            // Crear un nuevo registro en el modelo Vehiculo
            $moodulo=ContratosPerZona::findorfail($request->modulo_cortana);
            $DetallesGen = DetallesGenerales::findOrFail($request->DetGenID); // Encuentra el vehículo existente por su ID
            $DetallesGen->update([
                'modulo_id' => $moodulo->modulo_id,
                'zona_id' => $moodulo->zona_id,
                'contrato_id' => $moodulo->contrato_id,
                'anio' => $moodulo->anio,
                
            ]);
            return response()->json(['message' => 'Detalles Generales Actualizados con éxito'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function UpdateModulo(Request $request){
        $request->validate([
            'DetGenID' => ['required','exists:detallesgenerales,id'],
            'modulo' => 'required|exists:modulos,id',
            'contrato' => 'required|exists:contratos,id',
            'zona' => 'required|exists:sucursales,id',
            'anio' => 'required|numeric|min:2025',
        ]);
        try {
            // Crear un nuevo registro en el modelo Vehiculo
            $DetallesGen = DetallesGenerales::findOrFail($request->DetGenID); // Encuentra el vehículo existente por su ID
            $DetallesGen->update([
                'modulo_id' => $request->modulo,
                'zona_id' => $request->zona,
                'contrato_id' => $request->contrato,
                'anio' => $request->anio,
                
            ]);
            return response()->json(['message' => 'Detalles Generales Actualizados con éxito'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function Update(Request $request){
        $request->validate([
            'DetGenId' => ['required','exists:detallesgenerales,id'],
            'DetGenOrdSer' => ['required','unique:detallesgenerales,OrdenServicio,'. $request->DetGenId],
            'DetGenOrdSeg' => ['required','unique:detallesgenerales,OrdenServicio,'. $request->DetGenId],
            'DetGenOrdOpc' => ['nullable','string'],
            'DetGenUbi' => ['required','string'],
            'DetGenFecAlt' => ['required','date'],
            'DetGenFecEsp' => ['required','date'],
            'DetGenKilEnt' => ['required','integer'],
            'DetGenTel' => ['required','integer'],
            'DetGenGasEnt' => ['required','exists:nivelescombustible,id'],
            'DetGenEmp'=>['required','exists:empresas,id'],
            'DetGenCli' => ['required','exists:customers,id'],
            'DetGenAdmTra' => ['required','exists:users_taller,id'],
            'DetGenJefPro' => ['required','exists:users_taller,id'],
            'DetGenTra' => ['required','exists:users_taller,id'],
            'DetGenVeh' => ['required','exists:vehiculos,id'],
            'DetGenVehTip' => ['required','exists:tipos_vehiculo_concepto,id'],
            'DetGenIndCli' => ['required','string'],
        ]);
        try {
            // Crear un nuevo registro en el modelo Vehiculo
            $DetallesGen = DetallesGenerales::findOrFail($request->DetGenId); // Encuentra el vehículo existente por su ID
            $DetallesGen->update([
                'OrdenServicio' => $request->DetGenOrdSer,
                'OrdenSeguimiento' => $request->DetGenOrdSeg,
                'Orden' => $request->DetGenOrdOpc,
                'Telefono' => $request->DetGenTel,
                'Fecha_Esperada' => $request->DetGenFecEsp,
                'Ubicacion' => $request->DetGenUbi,
                //'Fecha_entrada' => $request->DetGenFecAlt,
                'Kilometraje_entrada' => $request->DetGenKilEnt,
                'Gas_entrada' => $request->DetGenGasEnt,
                'Vehiculo_id' => $request->DetGenVeh,
                'Tipo_Vehiculo_Concepto_id' => $request->DetGenVehTip,
                'User_update_id' => Auth::user()->id,
                'Empresa_id' => $request->DetGenEmp,
                'Customer_id' => $request->DetGenCli,
                'AdministradorTrasporte_id' => $request->DetGenAdmTra,
                'JefedeProceso_id' => $request->DetGenJefPro,
                'Trabajador_id' => $request->DetGenTra,
                'Indicaciones_cliente' => $request->DetGenIndCli,
            ]);
            $element=DetallesGenerales::with([
                'Empresa',
                'tipoVehiculo',
                'Customer',
                'AdministradorTrasporte',
                'JefedeProceso',
                'Trabajador',
                'Vehiculo',])->findorfail($DetallesGen->id);

            return response()->json(['message' => 'Detalles Generales Actualizados con éxito','Element'=>$element], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function ViewTecnicosSalidas(Request $request){
        $elementostotales=DetallesGenerales::count();
        $Contratos=Contratos::pluck('nombre','id');
        $Zonas=Sucursales::pluck('nombre','id');
        return view('zcrat.TrabadoresSalidas',compact('elementostotales','Contratos','Zonas'));
    }
    Public function GetSalidasElements(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        try {
            $detalles=DetallesGenerales::with([
                'RecepcionVehicular.tecnico',
                'Vehiculo',
                'modulo',
                'zona',
                'contrato'])->orderbydesc('Fecha_salida')->get();


            $elements=$detalles->map(function ($detalles) {
                $horas = Carbon::parse($detalles->Fecha_entrada)->diffInMinutes( $detalles->Fecha_salida ? Carbon::parse($detalles->Fecha_salida) : Carbon::now('UTC')->subHours(6)
        );
                return [
                    'id' => $detalles->id,
                    'OrdenServicio' => $detalles->OrdenServicio,
                    'economico' => $detalles->Vehiculo->no_economico,
                    'tecnico' => $detalles->RecepcionVehicular->tecnico->nombre ?? 'Sin técnico',
                    'descripcion' => $detalles->RecepcionVehicular->Notas ?? 'Sin notas',
                    'entrada' => $detalles->Fecha_entrada,
                    'diagnostico' => $detalles->Diagnostico,
                    'pedidohecho' => $detalles->PedidoHecho,
                    'pedidoentregado' => $detalles->PedidoEntregado,
                    'salida' => $detalles->Fecha_salida,
                    'horas' =>round($horas/60,2),
                    'modulo_id' => $detalles->modulo->id,
                    'zona_id' => $detalles->zona->id,
                    'anio' => $detalles->anio,
                    'contrato' => $detalles->contrato->nombre,
                    'modulo' => $detalles->modulo->descripcion,
                    'zona' => $detalles->zona->nombre,

                ];
            });
            $detalles2=TrabajosParciales::with(['OrdenServicio',
                'OrdenServicio.RecepcionVehicular.tecnico',
                'OrdenServicio.Vehiculo',
                'OrdenServicio.modulo',
                'OrdenServicio.zona',
                'OrdenServicio.contrato'])->orderbydesc('created_at')->get();


            $elements2=$detalles2->map(function ($detalles2) {
                $detalles=$detalles2->OrdenServicio;
                return [
                    'id' => $detalles->id,
                    'OrdenServicio' => $detalles->OrdenServicio,
                    'economico' => $detalles->Vehiculo->no_economico,
                    'tecnico' => $detalles->RecepcionVehicular->tecnico->nombre ?? 'Sin técnico',
                    'descripcion' => $detalles2->descripcion,
                    'entrada' => Carbon::parse($detalles2->created_at)->subMinutes(($detalles2->horas+1)*60)->format('Y-m-d H:i:s'),
                    'diagnostico' => '',
                    'pedidohecho' => '',
                    'pedidoentregado' =>'',
                    'salida' => Carbon::parse($detalles2->created_at)->subHours(1)->format('Y-m-d H:i:s'),
                    'horas' => $detalles2->horas,
                    'modulo_id' => $detalles->modulo->id,
                    'zona_id' => $detalles->zona->id,
                    'anio' => $detalles->anio,
                    'contrato' => $detalles->contrato->nombre,
                    'modulo' => $detalles->modulo->descripcion,
                    'zona' => $detalles->zona->nombre,

                ];
            });
            $elements = $elements->merge($elements2)->sortByDesc('salida')->values();
            return response()->json([
                'elements' => $elements
            ]);
        } catch (\Exception $e) {
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    Public function GetElements(Request $request){
        if (!$request->ajax()) {
            return redirect()->route('homevue');
        }
        try {
            $detalles=DetallesGenerales::with([
                'Empresa',
                'Vehiculo',
                'modulo',
                'zona',
                'contrato'])->orderbydesc('id')->get();


            $elements=$detalles->map(function ($detalles) {
                $dias = Carbon::parse($detalles->Fecha_entrada)->diffInDays(
        $detalles->Fecha_salida ? Carbon::parse($detalles->Fecha_salida) : Carbon::now('UTC')->subHours(6)

        );
                return [
                    'id' => $detalles->id,
                    'OrdenServicio' => $detalles->OrdenServicio,
                    'OrdenSeguimiento' => $detalles->OrdenSeguimiento,
                    'empresa' => $detalles->empresa->nombre ?? '',
                    'empresa_id' => $detalles->Empresa->id,
                    'entrada' => $detalles->Fecha_entrada,
                    'salida' => $detalles->Fecha_salida,
                    'economico' => $detalles->Vehiculo->no_economico,
                    'placas' => $detalles->Vehiculo->placas,
                    'serie' => $detalles->Vehiculo->vim,
                    'modulo_id' => $detalles->modulo->id,
                    'zona_id' => $detalles->zona->id,
                    'anio' => $detalles->anio,
                    'contrato' => $detalles->contrato->nombre,
                    'modulo' => $detalles->modulo->descripcion,
                    'zona' => $detalles->zona->nombre,
                    'dias' => $dias,

                ];
            });
            return response()->json([
                'elements' => $elements
            ]);
        } catch (\Exception $e) {
            return response()->json(['message'=>$e->getmessage()],500);
        }
    }
    public function EntradasYSalidasView(Request $request){
        $elementostotales=DetallesGenerales::count();
        $Contratos=Contratos::pluck('nombre','id');
        $Zonas=Sucursales::pluck('nombre','id');
        return view('zcrat.EntradasSalidas',compact('elementostotales','Contratos','Zonas'));
    }
    public function GetDataEntradaSalida(Request $request){
        $element = DetallesGenerales::with('ArchivosSalida:id,Nombre,Detalles_Generales_Id')->where('id', $request->id)->get();

        if ($element->isEmpty()) {
            return response()->json(['message' => 'No se encontró el elemento'], 404);
        }

        $element = $element->map(function ($detalles) {
            return [
                'id' => $detalles->id,
                'gasolina' => $detalles->Gas_salida,
                'kilometraje' => $detalles->Kilometraje_salida,
                'fecha' => $detalles->Fecha_salida,
                'archivos' => $detalles->ArchivosSalida,
            ];
        });

                return response()->json([
                    'element'=>$element[0],
                ]);
    }
    public function GetDataSalida(Request $request){
        $element = DetallesGenerales::with('RecepcionVehicular')->where('id', $request->id)->get();

        if ($element->isEmpty()) {
            return response()->json(['message' => 'No se encontró el elemento'], 404);
        }

        $element = $element->map(function ($detalles) {
            return [
                'id' => $detalles->id,
                'gasolina' => $detalles->Gas_salida,
                'kilometraje' => $detalles->Kilometraje_salida,
                'notas' => $detalles->RecepcionVehicular->Notas,
                'idtecnico' => $detalles->RecepcionVehicular->tecnico->id??'',
                'tecnico' => $detalles->RecepcionVehicular->tecnico->nombre??'',
            ];
        });

                return response()->json([
                    'element'=>$element[0],
                ]);
    }
    public function DeleteArchivoSalida(Request $request){
        $request->validate([ 
        'id' => 'required|exists:archivos_salidas,id',], 
        [ 
        'id.required' => 'El Movimiento es obligatorio.', 
        'id.exists' => 'El Movimiento seleccionado no es esta disponible.', ]);
        $cajamov = ArchivoSalida::find($request->id);
        $cajamov->delete();
        return "eliminado";
    }
    public function UpdateExit(Request $request){
        $request->validate([ 
            'DetGenId' => 'required|exists:detallesgenerales,id',  
            'DetGenGasSal' => 'required|in:0,1,2,3,4', 
            'DetGenKilSal' => 'required|numeric|min:0',
            'RecVehNot' => 'nullable|string',
            'RecVehTec' => 'nullable|exists:tecnicos1,id'
        ]);

        DB::beginTransaction();
        try {

            $Detalles=DetallesGenerales::findorfail($request->DetGenId);
            $Detalles->Gas_salida=$request->DetGenGasSal;
            $Detalles->Kilometraje_salida=$request->DetGenKilSal;
            $Detalles->Fecha_salida=Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
            $Detalles->save();

            if($request->has('RecVehNot') && $request->has('RecVehTec')){
                $recepcion=RecepcionesVehiculares::where('DetallesGenerales_id',$request->DetGenId)->first();
                if($recepcion){
                    $recepcion->Notas=$request->RecVehNot;
                    $recepcion->Tecnico_id=$request->RecVehTec;
                    $recepcion->save();
                }
            }
            if ($request->hasFile('nuevo_archivo')) {
        
                $archivo = $request->file('nuevo_archivo');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $ruta = '/public/documentos/salidas/';
                $archivo->storeAs($ruta, $nombreArchivo);
                
                $cotizacion =ArchivoSalida::create([
                    'Detalles_Generales_Id'=>$Detalles->id,
                    'Nombre'=>$nombreArchivo,
                ]) ;       
                }      
            DB::commit();
            return response()->json([
                'message'=>'Hora Registrada',
                'element'=>$Detalles
            ]);
        }catch (\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }
    public function DeleteExit(Request $request){
        $request->validate(['DetGenId' => 'required|exists:detallesgenerales,id']);
        try {
            DB::beginTransaction();
            $detalles=DetallesGenerales::findorfail($request->DetGenId);
            $usersvalidos=[170,1,$detalles->User_id];
           if (!in_array($request->user()->id, $usersvalidos)) {
                return response()->json(['message' =>'No Puedes Cambiar La Hora de Salida'],422);
            }
            if(empty($detalles->Fecha_salida)){
                return response()->json(['message' =>'No se Ha Registrado Salida'],422);
            }
            $fechaSalida = Carbon::parse($detalles->Fecha_salida);
            if ($fechaSalida->greaterThan(Carbon::now()) && $fechaSalida->diffInDays(Carbon::now()) > 3) {
                return response()->json(['message' => 'Ya supera los tres dias '],500);
            }
            

            $detalles->Fecha_salida=null;
            $detalles->save();
            DB::commit();
            return response()->json([
                'message'=>'Hora del Salida Eliminada',
            ]);
        }catch (\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }

    }
    public function UpdateDiagnostico(Request $request){
        $request->validate(['DetGenId' => 'required|exists:detallesgenerales,id']);
        try {
            DB::beginTransaction();
            $Detalles=DetallesGenerales::findorfail($request->DetGenId);
                if(!empty($Detalles->Diagnostico)){
                    return response()->json([
                    'errors' => ['DetGenId' => ['Ya se Ha Registrado']]

                ],422);
            }
            $Detalles->Diagnostico=Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
        ;
            $Detalles->save();
            DB::commit();
            return response()->json([
                'message'=>'Hora del Diagnostico Registrada',
                'element'=>$Detalles
            ]);
        }catch (\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }

    }
    public function UpdatePedidoHecho(Request $request){
        $request->validate(['DetGenId' => 'required|exists:detallesgenerales,id']);
        try {
            DB::beginTransaction();
            $Detalles=DetallesGenerales::findorfail($request->DetGenId);
                if(!empty($Detalles->PedidoHecho)){
                    return response()->json([
                    'errors' => ['DetGenId' => ['Ya se Ha Registrado']]

                ],422);
            }
            $Detalles->PedidoHecho=Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
            $Detalles->save();
            DB::commit();
            return response()->json([
                'message'=>'Hora De Pedido De Refacciones Registrada',
                'element'=>$Detalles
            ]);
        }catch (\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }
    public function UpdatePedidoEntregado(Request $request){
        $request->validate(['DetGenId' => 'required|exists:detallesgenerales,id']);
        try {
            DB::beginTransaction();
            $Detalles=DetallesGenerales::findorfail($request->DetGenId);
                if(!empty($Detalles->PedidoEntregado)){
                    return response()->json([
                   'errors' => ['DetGenId' => ['Ya se Ha Registrado']]

                ],422);
            }
            $Detalles->PedidoEntregado=Carbon::now('UTC')->subHours(6)->format('Y-m-d H:i:s');
        ;
            $Detalles->save();
            DB::commit();
            return response()->json([
                'message'=>'Hora De Entrega De Refacciones Registrada',
                'element'=>$Detalles
            ]);
        }catch (\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }
    public function CreateTrabajosParciales(Request $request){
        $request->validate(['DetGenId' => 'required|exists:detallesgenerales,id','descripcion' => 'required|string|max:500']);
        try {
            DB::beginTransaction();
            $Trabajo=TrabajosParciales::create([
                'Detalles_Generales_Id' => $request->DetGenId,
                'descripcion' => $request->descripcion,
                'horas' => $request->horas ?? 0,
                'user_id' => Auth::user()->id,
            ]);
            $Trabajo->save();
            DB::commit();
            return response()->json([
                'message'=>'Trabajo Parcial Registrado'
            ]);
        }catch (\Exception $e) {
            DB::rollBack();
            return abort(500, $e->getMessage());
        }
    }
    public function ReadTrabajosParciales(Request $request){
        $request->validate(['DetGenId' => 'required|exists:detallesgenerales,id']);
        try {
            $Trabajos=TrabajosParciales::where('Detalles_Generales_Id',$request->DetGenId)->get()->map(function ($trabajo) {
                return [
                    'id' => $trabajo->id,
                    'descripcion' => $trabajo->descripcion,
                    'fecha' => Carbon::parse($trabajo->created_at)->subHours(1)->format('Y-m-d H:i:s'),
                    'horas' => $trabajo->horas,
                    'user_id' => $trabajo->user_id,
                    'user' => $trabajo->User ? $trabajo->User->name : 'Desconocido',
                ];
            });
            return response()->json([
                'elements'=>$Trabajos
            ]);
        }catch (\Exception $e) {
            return abort(500, $e->getMessage());
        }
    }
    public function UpdateTrabajosParciales(Request $request){
        $request->validate(['id' => 'required|exists:trabajos_parciales,id','descripcion' => 'required|string|max:500']);
        try {
            DB::beginTransaction();
            $Trabajos=TrabajosParciales::findorfail($request->id)->Update([
                'descripcion' => $request->descripcion,
                'user_id' => Auth::user()->id,
            ]);
            DB::commit();
            return response()->json([
                'message'=>'Trabajo Parcial Modificado Exitosamente',
            ]);
        } catch (\Exception $e) {
            return abort(500, $e->getMessage());
        }
    }
    public function DeleteTrabajosParciales(Request $request){
        $request->validate(['id' => 'required|exists:trabajos_parciales,id']);
        try {
            DB::beginTransaction();
            $Trabajo=TrabajosParciales::findorfail($request->id);
            $Trabajo->delete();
            DB::commit();
            return response()->json([
                'message'=>'Trabajo Parcial Eliminado Exitosamente'
            ]);
        } catch (\Exception $e) {
            return abort(500, $e->getMessage());
        }
    }
    public function Reingreso(Request $request){
        $request->validate(['DetGenId'=>['required','exists:DetallesGenerales,id']]);

        try{
            DB::beginTransaction();
            $idoriginal=$request->DetGenId;
            $detalles=DetallesGenerales::with(['DateTerminado','DateVerificado','DateEntregado'])->find($idoriginal);
            Reingresos::create([
                'orden_original'=>$detalles->id,
                'fecha_terminado'=>$detalles->DateTerminado->fecha,
                'fecha_verificado'=>$detalles->DateVerificado->fecha,
                'fecha_entregado'=>$detalles->DateEntregado->fecha
            ]);
            SeguimientoUnidades::whereIn('id',[$detalles->DateTerminado->id,$detalles->DateVerificado->id,$detalles->DateEntregado->id])->delete();

            $detalles->Kilometraje_salida=null;
            $detalles->Gas_salida=null;
            $detalles->Fecha_salida=null;
            $detalles->save();
            
            DB::commit();       
            return response()->json(['message'=>'Reingreso Correcto']);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message'=>$e->getMessage()],500);
        }
    }
    public function ToggleSubcontrato(Request $request){
        $request->validate(['DetGenId'=>['required','exists:DetallesGenerales,id']]);

        try{
            DB::beginTransaction();
            $idoriginal=$request->DetGenId;
            $detalles=DetallesGenerales::with(['DateTerminado','DateVerificado','DateEntregado'])->find($idoriginal);
            $detalles->has_subcontrato=$detalles->has_subcontrato == '0' ? '1' : '0';
            $detalles->save();
            
            DB::commit();       
            return response()->json(['message'=>'Cambio Correcto']);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message'=>$e->getMessage()],500);
        }
    }
    public function ReingresoOrden(Request $request){
        $request->validate(['DetGenId'=>['required','exists:DetallesGenerales,id']]);

        try{
            DB::beginTransaction();
            $idoriginal=optional(Reingresos::where('orden_reingreso',$request->DetGenId)->orderbydesc('id')->first())->orden_original ?? $request->DetGenId;

            $detalles=DetallesGenerales::find($idoriginal);

            if(!$detalles){
               throw new \Exception('Se Elimino La Orden Original, Pide De Favor Que Se Restaure Para El Reingreso de un Reingreso');
            }
            $num_re = Reingresos::where('orden_reingreso',$idoriginal)->count() + 1;

            $det=DetallesGenerales::create([
                'OrdenServicio'=>$detalles->OrdenServicio.'_R'.$num_re,
                'Orden'=>$detalles->Orden,
                'OrdenSeguimiento'=>$detalles->OrdenSeguimiento,
                'Ubicacion'=>$detalles->Ubicacion,
                'Fecha_Esperada'=>$detalles->Fecha_Esperada,
                'Kilometraje_entrada'=>$detalles->Kilometraje_entrada,
                'Gas_entrada'=>$detalles->Gas_entrada,
                'Fecha_entrada'=>now(),
                'Vehiculo_id'=>$detalles->Vehiculo_id,
                'Tipo_Vehiculo_Concepto_id'=>$detalles->Tipo_Vehiculo_Concepto_id,
                'User_id'=>Auth::user()->id,
                'User_update_id'=>Auth::user()->id,
                'Empresa_id'=>$detalles->Empresa_id,
                'Customer_id'=>$detalles->Customer_id,
                'AdministradorTrasporte_id'=>$detalles->AdministradorTrasporte_id,
                'JefedeProceso_id'=>$detalles->JefedeProceso_id,
                'Trabajador_id'=>$detalles->Trabajador_id,
                'Telefono'=>$detalles->Telefono,
                'contrato_id'=>$detalles->contrato_id,
                'modulo_id'=>$detalles->modulo_id,
                'anio'=>$detalles->anio,
                'zona_id'=>$detalles->zona_id,
                'Indicaciones_cliente'=>$detalles->Indicaciones_cliente,
            ]);
            
            $recepcion=RecepcionesVehiculares::with(['fotos','pintura','inventario','exteriores','interiores'])->where('DetallesGenerales_id',$detalles->id)->first();
            
            if($recepcion){
                $firma=$recepcion->Firma;
                $Carro=$recepcion->Carro;
                $extension = 'png'; 
                $fileName = uniqid() . '.' . $extension;
                Storage::copy("public/carros/$Carro", "public/carros/$fileName");
                Storage::copy("public/firmastaller/$firma", "public/firmastaller/$fileName");
                $re=RecepcionesVehiculares::create([
                    'DetallesGenerales_id'=>$det->id,
                    'Notas'=>$recepcion->Notas,
                    'Notas_Retraso'=>$recepcion->Notas_Retraso,
                    'Tecnico_id'=>$recepcion->Tecnico_id,
                    'Firma'=>$fileName,
                    'Carro'=>$fileName
                ]);

                foreach($recepcion->fotos as $foto){
                    $fileName = uniqid() . '.' . $extension;
                    Storage::copy("public/evidenciasrecepcionvehicular/$foto->Foto", "public/evidenciasrecepcionvehicular/$fileName");
                    FotoRecepcionVehicular::create([
                        'RecepcionVehicular_id'=>$re->id,
                        'Foto'=>$fileName
                    ]);
                }
                $pintura=$recepcion->pintura ? $recepcion->pintura[0]: collect([]);
                CondicionesPintura::create([
                    'RecepcionVehicular_id'=>$re->id,
                    'decolorada'=>$pintura->decolorada ?? 0,
                    'emblemas_completos'=>$pintura->emblemas_completos ?? 0,
                    'color_no_igual'=>$pintura->color_no_igual ?? 0,
                    'logos'=>$pintura->logos ?? 0,
                    'exeso_rayones'=>$pintura->exeso_rayones ?? 0,
                    'exeso_rociado'=>$pintura->exeso_rociado ?? 0,
                    'pequenias_grietas'=>$pintura->pequenias_grietas ?? 0,
                    'danios_granizado'=>$pintura->danios_granizado ?? 0,
                    'carroceria_golpes'=>$pintura->carroceria_golpes ?? 0,
                    'lluvia_acido'=>$pintura->lluvia_acido ?? 0
                ]);
                $inventario=$recepcion->inventario ? $recepcion->inventario[0]: collect([]);
                EquipoInventario::create([
                    'RecepcionVehicular_id'=>$re->id,
                    'llanta'=>$inventario->llanta,
                    'cubreruedas'=>$inventario->cubreruedas,
                    'cables_corriente'=>$inventario->cables_corriente,
                    'candado_ruedas'=>$inventario->candado_ruedas,
                    'estuche_herramientas'=>$inventario->estuche_herramientas,
                    'gato'=>$inventario->gato,
                    'llave_tuercas'=>$inventario->llave_tuercas,
                    'tarjeta_circulacion'=>$inventario->tarjeta_circulacion,
                    'triangulo_seguridad'=>$inventario->triangulo_seguridad,
                    'extinguidor'=>$inventario->extinguidor,
                    'placas'=>$inventario->placas,
                ]);
                $exteriores=$recepcion->exteriores ? $recepcion->exteriores[0]: collect([]);
                ExterioresEquipo::create([
                    'RecepcionVehicular_id'=>$re->id,
                    'antena_radio'=>$exteriores->antena_radio ?? 3,
                    'antena_telefono'=>$exteriores->antena_telefono ?? 3,
                    'antena_cb'=>$exteriores->antena_cb ?? 3,
                    'estribos'=>$exteriores->estribos ?? 3,
                    'espejos_laterales'=>$exteriores->espejos_laterales ?? 3,
                    'guardafangos'=>$exteriores->guardafangos ?? 3,
                    'parabrisas'=>$exteriores->parabrisas ?? 3,
                    'sistema_alarma'=>$exteriores->sistema_alarma ?? 3,
                    'limpia_parabrisas'=>$exteriores->limpia_parabrisas ?? 3,
                    'luces_exteriores'=>$exteriores->luces_exteriores ?? 3,
                ]);
                $interiores=$recepcion->interiores ? $recepcion->interiores[0]: collect([]);
                InterioresEquipo::create([
                    'RecepcionVehicular_id'=>$re->id,
                    'puerta_interior_frontal'=>$interiores->puerta_interior_frontal??3,
                    'puerta_interior_trasera'=>$interiores->puerta_interior_trasera??3,
                    'puerta_delantera_frontal'=>$interiores->puerta_delantera_frontal??3,
                    'puerta_delantera_trasera'=>$interiores->puerta_delantera_trasera??3,
                    'asiento_interior_frontal'=>$interiores->asiento_interior_frontal??3,
                    'asiento_interior_trasera'=>$interiores->asiento_interior_trasera??3,
                    'asiento_delantera_frontal'=>$interiores->asiento_delantera_frontal??3,
                    'asiento_delantera_trasera'=>$interiores->asiento_delantera_trasera??3,
                    'consola_central'=>$interiores->consola_central??3,
                    'claxon'=>$interiores->claxon??3,
                    'tablero'=>$interiores->tablero??3,
                    'quemacocos'=>$interiores->quemacocos??3,
                    'toldo'=>$interiores->toldo??3,
                    'elevadores_eletricos'=>$interiores->elevadores_eletricos??3,
                    'luces_interiores'=>$interiores->luces_interiores??3,
                    'seguros_eletricos'=>$interiores->seguros_eletricos??3,
                    'tapetes'=>$interiores->tapetes??3,
                    'climatizador'=>$interiores->climatizador??3,
                    'radio'=>$interiores->radio??3,
                    'espejos_retrovizor'=>$interiores->espejos_retrovizor??3,
                ]);
                Reingresos::create([
                    'orden_reingreso'=>$det->id,
                    'orden_original'=>$det->id
                ]);
            }else{
                throw new \Exception ('Ya No Esta La Recpcion Vehicular');
            }
            Presupuesto::create([
                'DetallesGenerales_id' => $det->id,
                'Observaciones'=>'DE ACUERDO A LO DIFICIL DE LA FALLA PARA SU REPARACION',
                'Mano_Obra_Descripcion'=>$recepcion->notas,
                'Garantia'=>'LO ESTIPULADO EN CONTRATO',
                'Folio'=>$detalles->OrdenServicio.'_R'.$num_re,
                'FechaDeVigencia'=>now(),
                'Factura_id'=>0,
                'Tipo_id'=>3,
                'Status_id'=>0,
                'User_update_id'=>Auth::user()->id
            ]);
            DB::commit();       
            
            return response()->json(['message'=>'Reingreso Dado De Alta']);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message'=>$e->getMessage()],500);
        }
    }
}