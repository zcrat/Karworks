<?php

namespace App\Http\Controllers\zcrat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PresupuestoCarrito;
use App\Models\Presupuesto;
use App\Models\DetallesGenerales;
use App\Models\Vehiculo;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
class VehiculosController extends Controller
{

    public function GetDataConceptosHistorial(Request $request){
            $vehiculo=vehiculo::find($request->vehiculo);

            $idsDetalles = DetallesGenerales::where('Vehiculo_id', $vehiculo->id)
                ->when($request->filled('orden'), function($q) use ($request) {return $q->where('id', $request->orden);})
                ->pluck('id');
            $idsPresupuestos = Presupuesto::whereIn('DetallesGenerales_id', $idsDetalles)
                ->pluck('id');
            $conceptos = PresupuestoCarrito::with(['DatosConcepto','presupuesto.detallesGenerales'])
                ->whereIn('Presupuesto_id', $idsPresupuestos);

            if ($request->filled('fechamin') && $request->filled('fechamax')) {
                $conceptos = $conceptos->whereBetween('created_at', [
                    Carbon::parse($request->fechamin)->startOfDay(),
                    Carbon::parse($request->fechamax)->endOfDay()
                ]);
            } elseif ($request->filled('fechamin')) {
                $conceptos = $conceptos->where('created_at', '>=', Carbon::parse($request->fechamin)->startOfDay());
            } elseif ($request->filled('fechamax')) {
                $conceptos = $conceptos->where('created_at', '<=', Carbon::parse($request->fechamax)->endOfDay());
            }
 
            if($request->filled('search')){
                $search='%'.$request->search.'%';
                $conceptos=$conceptos->whereHas('DatosConcepto',function($query) use ($search){
                    $query->where('descripcion','LIKE',$search);
                });
            } 
                    
            $elementsperpege=$request->itemsPerPage;
            $page=$request->currentPage;
            $totalelements=(clone $conceptos)->count();
            $elements=$conceptos->orderbydesc('created_at')->skip(($page - 1) * ($elementsperpege))->take($elementsperpege)->get()->map(function($E){
                return [
                    'orden'=>$E->presupuesto->detallesGenerales->OrdenServicio,
                    'codigo'=>$E->DatosConcepto->num,
                    'cantidad'=>$E->Cantidad,
                    'fecha'=>Carbon::parse($E->created_at)->format('Y-m-d H:m'),
                    'concepto'=>$E->DatosConcepto->descripcion,
                    'costo'=>$E->Costo,
                    'precio'=>$E->Venta,
                    'total'=>$E->Venta * $E->Cantidad
                ];
            });
            
            $message='';
            if ($totalelements == 0 ){
                $message='Sin Resultados Para La Busqueda';
                if(PresupuestoCarrito::whereIn('Presupuesto_id', $idsPresupuestos)->count() === 0){
                    if ($request->filled('orden')){
                        $message='Orden Sin Conceptos En Los Presupuestos';
                    }else{
                        $message='Ordenes Sin Conceptos En Los Presupuestos';

                    };
                }
            }

            return response()->json(compact('totalelements','elements','message'));
    }
    public function GetDataElement(Request $request){
        if($request->has('id')){
            $vehiculo=vehiculo::find($request->id);
            if(!empty($vehiculo)){
                return response()->json(["element"=>$vehiculo]);
            }
            return response()->json(["error"=>'No Se Pudo Obtener Los Datos del Vehiculo'],404);
        }
        return response()->json(["error"=>'No SE Enviaron los datos Necesarios'],422);
    }
    public function Create(Request $request){
       $request->validate([
            'VehTip' => ['required','exists:tipo_auto,id'],
            'VehMar' => ['required','exists:marcas,id'],
            'VehMod' => ['required','exists:modelos,id'],
            'VehCol' => ['required','exists:colores,id'],
            'VehAnio' => ['required','integer'],
            'VehNumEco'=>['required','string','unique:vehiculos,no_economico'],
            'VehVim' => ['required','string'],
            'VehPla' => ['required','string']
        ], [
            'VehTip.required' => 'Tipo de vehículo es obligatorio.',
            'VehTip.exists' => 'Tipo de vehículo seleccionado no es válido.',
            'VehMar.required' => 'Marca del vehículo es obligatorio.',
            'VehMar.exists' => 'Marca seleccionada no es válida.',
            'VehMod.required' => 'Modelo del vehículo es obligatorio.',
            'VehMod.exists' => 'Modelo seleccionado no es válido.',
            'VehCol.required' => 'Color del vehículo es obligatorio.',
            'VehCol.exists' => 'Color seleccionado no es válido.',
            'VehAnio.required' => 'Año del vehículo es obligatorio.',
            'VehAnio.integer' => 'Año del vehículo debe ser un número entero.',
            'VehNumEco.required' => 'Número económico es obligatorio.',
            'VehNumEco.string' => 'Número económico debe ser una cadena.',
            'VehNumEco.unique' => 'Número económico debe ser unico.',
            'VehVim.required' => 'VIN es obligatorio.',
            'VehVim.string' => 'VIN debe ser una cadena de caracteres.',
            'VehPla.required' => 'Placas es obligatorio.',
            'VehPla.string' => 'Placas debe ser una cadena de caracteres.',
        ]);
        try {
            // Crear un nuevo registro en el modelo Vehiculo
            $vehiculo = Vehiculo::create([
                'tipo_id' => $request->VehTip,
                'marca_id' => $request->VehMar,
                'modelo_id' => $request->VehMod,
                'color_id' => $request->VehCol,
                'anio' => $request->VehAnio,
                'no_economico' => $request->VehNumEco,
                'vim' => $request->VehVim,
                'placas' => $request->VehPla,
            ]);
            return response()->json(['success' => 'Vehículo creado con éxito','id'=>$vehiculo->id,'nombre'=>$vehiculo->no_economico . '-' .$vehiculo->placas], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el vehículo', 'message' => $e->getMessage()], 500);
        }
    }
    public function Update(Request $request){
        $request->validate([
            'VehId' => ['required','exists:vehiculos,id'],
            'VehTip' => ['required','exists:tipo_auto,id'],
            'VehMar' => ['required','exists:marcas,id'],
            'VehMod' => ['required','exists:modelos,id'],
            'VehCol' => ['required','exists:colores,id'],
            'VehAnio' => ['required','integer'],
            'VehNumEco'=>['required','string'],
            'VehVim' => ['required','string'],
            'VehPla' => ['required','string']
        ], [
            'VehId.required' => 'El Vehículo es obligatorio.',
            'VehId.exists' => 'El Vehículo seleccionado no es válido.',
            'VehTip.required' => 'Tipo de vehículo es obligatorio.',
            'VehTip.exists' => 'Tipo de vehículo seleccionado no es válido.',
            'VehMar.required' => 'Marca del vehículo es obligatorio.',
            'VehMar.exists' => 'Marca seleccionada no es válida.',
            'VehMod.required' => 'Modelo del vehículo es obligatorio.',
            'VehMod.exists' => 'Modelo seleccionado no es válido.',
            'VehCol.required' => 'Color del vehículo es obligatorio.',
            'VehCol.exists' => 'Color seleccionado no es válido.',
            'VehAnio.required' => 'Año del vehículo es obligatorio.',
            'VehAnio.integer' => 'Año del vehículo debe ser un número entero.',
            'VehNumEco.required' => 'Número económico es obligatorio.',
            'VehNumEco.integer' => 'Número económico debe ser un número entero.',
            'VehNumEco.unique' => 'Número económico debe ser unico.',
            'VehVim.required' => 'VIN es obligatorio.',
            'VehVim.string' => 'VIN debe ser una cadena de caracteres.',
            'VehPla.required' => 'Placas es obligatorio.',
            'VehPla.string' => 'Placas debe ser una cadena de caracteres.',
        ]);
        try {
            // Crear un nuevo registro en el modelo Vehiculo
            $vehiculo = Vehiculo::findOrFail($request->VehId); // Encuentra el vehículo existente por su ID
            $vehiculo->update([
                'tipo_id' => $request->VehTip,
                'marca_id' => $request->VehMar,
                'modelo_id' => $request->VehMod,
                'color_id' => $request->VehCol,
                'anio' => $request->VehAnio,
                'no_economico' => $request->VehNumEco,
                'vim' => $request->VehVim,
                'placas' => $request->VehPla,
            ]);
            return response()->json(['success' => 'Vehículo Actualizado con éxito','id'=>$vehiculo->id,'nombre'=>$vehiculo->no_economico . '-' .$vehiculo->placas], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
