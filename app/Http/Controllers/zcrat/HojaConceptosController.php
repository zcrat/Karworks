<?php

namespace App\Http\Controllers\zcrat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HojaConcepto;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\log;

class HojaConceptosController extends Controller
{
    public function UpdateOrCreate(Request $request){
        $request->validate([
            'ConShePreID' => ['required', 'exists:presupuestosnuevos,id'],
            'ConSheList' => ['nullable', 'array'],
            'ConSheList.*.id' => ['nullable', 'exists:hojaconceptos,id'],
            'ConSheList.*.fecha' => ['nullable', 'date'],
            'ConSheList.*.estatus' => ['required', 'in:1,2'],
            'ConSheList.*.cantidad' => ['required', 'integer'],
            'ConSheList.*.clave' => ['required', 'string'],
            'ConSheList.*.descripcion' => ['required', 'string'],
            'ConSheList.*.tipo' => ['required', 'in:1,2,3,4'],
            'ConSheList.*.costo' => ['required', 'numeric'],
            'ConSheList.*.venta' => ['nullable', 'numeric'],
        ], [
            'ConShePreID.required' => 'El presupuesto es obligatorio.',
            'ConShePreID.exists' => 'El presupuesto no existe',
            'ConSheList.required' => 'Debe proporcionar al menos un concepto.',
            'ConSheList.*.id.exists' => 'El ID proporcionado no existe en la tabla hojaconceptos.',
            'ConSheList.*.estatus.required' => 'El estatus es obligatorio.',
            'ConSheList.*.cantidad.required' => 'La cantidad es obligatoria.',
            'ConSheList.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'ConSheList.*.clave.required' => 'La clave es obligatoria.',
            'ConSheList.*.descripcion.required' => 'La descripción es obligatoria.',
            'ConSheList.*.tipo.required' => 'El tipo es obligatorio.',
            'ConSheList.*.tipo.in' => 'El tipo debe ser uno de los valores permitidos (1,2,3,4).',
            'ConSheList.*.costo.required' => 'El costo es obligatorio.',
            'ConSheList.*.costo.numeric' => 'El costo debe ser un valor numérico.',
            'ConSheList.*.venta.numeric' => 'El precio de venta debe ser un valor numérico.'
        ]);
        try {
            $idsineliminar=[];
            $listado=$request->ConSheList ?? [];
            foreach ($listado as $item) {
                if(isset($item['id'])){
                    $idsineliminar[]=$item['id'];
                }
            }
            HojaConcepto::where('presupuesto_id',$request->ConShePreID)->whereNotIn('id',$idsineliminar)->delete();
            foreach ($listado as $item) {
                HojaConcepto::updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'presupuesto_id' => $request->ConShePreID,
                        'fecha' => $item['fecha'],
                        'estatus' => $item['estatus'],
                        'cantidad' => $item['cantidad'],
                        'clave' => $item['clave'],
                        'descripcion' => $item['descripcion'],
                        'tipo' => $item['tipo'],
                        'costo' => $item['costo'],
                        'venta' => $item['venta']
                    ]
                );
            }
            return response()->json(["message" => "Conceptos guardados correctamente."]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getmessage()], 500);
        }
    }
    public function CreateElement(Request $request){
               $request->validate([
                'ConShePreID' => ['required', 'exists:presupuestosnuevos,id'],
                'fecha' => ['nullable', 'string'],
                'remplazo' => ['nullable', 'numeric'],
                'reparar' => ['nullable', 'numeric'],
                'clave' => ['nullable', 'string', 'max:100'],
                'descripcion' => ['nullable', 'string'],
                'partes' => ['nullable', 'numeric'],
                'manoobra' => ['nullable', 'numeric'],
                'subcontratados' => ['nullable', 'numeric'],
                'otros' => ['nullable', 'numeric'],
                'venta' => ['nullable', 'numeric'],
                'venta' => ['porcentaje_utilidad', 'numeric'],
            ]);
        try {
            $datos = ['presupuesto_id' => $request->ConShePreID]; // obligatorio

            $camposOpcionales = [
                'fecha', 'remplazo', 'reparar', 'clave', 'descripcion',
                'partes', 'manoobra', 'subcontratados', 'otros', 'venta','porcentaje_utilidad','iva'
            ];
            $element2 = null;
            foreach ($camposOpcionales as $campo) {
                if ($request->has($campo)) {
                    $datos[$campo] = $request->$campo;
                    if($campo == 'clave'){
                        if($request->filled('clave')){
                            $element2=HojaConcepto::where('clave',$request->clave)->orderbydesc('id')->first();
                            if($element2){
                                $datos['descripcion'] = $element2->descripcion;
                                $datos['partes'] = $element2->partes;
                                $datos['manoobra'] = $element2->manoobra;
                                $datos['subcontratados'] = $element2->subcontratados;
                                $datos['otros'] = $element2->otros;
                                $datos['iva'] = $element2->iva;
                                $datos['porcentaje_utilidad'] = $element2->porcentaje_utilidad;
                                $datos['venta'] = $element2->venta;
                            } 
                        }
                    }
                }
            }

            $element = HojaConcepto::create($datos);
            if($element2){
                return response()->json(["message" => "Concepto actualizado correctamente.","id" => $element->id, "element" => $element]);
            }
            return response()->json(["message" => "Concepto creado correctamente.", "id" => $element->id]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getmessage()], 500);
        }

    }
    public function UpdateElement(Request $request) {
               $request->validate([
                'ConShePreID' => ['required', 'exists:presupuestosnuevos,id'],
                'id' => ['required', 'exists:hojaconceptosbeta,id'],
                'fecha' => ['nullable', 'string'],
                'remplazo' => ['nullable', 'numeric'],
                'reparar' => ['nullable', 'numeric'],
                'clave' => ['nullable', 'string', 'max:100'],
                'descripcion' => ['nullable', 'string'],
                'partes' => ['nullable', 'numeric'],
                'manoobra' => ['nullable', 'numeric'],
                'subcontratados' => ['nullable', 'numeric'],
                'otros' => ['nullable', 'numeric'],
                'venta' => ['nullable', 'numeric'],
                'porcentaje_utilidad' => ['nullable', 'numeric'],
            ]);

        try {
            $users=[1,36,192,170];
            if(!$request->user()->can('editar hoja de conceptos') || !in_array($request->user()->id,$users)){
                return response()->json(["error" => "No tienes permiso para editar este concepto."], 403);
            }
            $element = HojaConcepto::findOrFail($request->id);
            $element2 = null;
            $campos = [
                'fecha', 'remplazo', 'reparar', 'clave', 'descripcion',
                'partes', 'manoobra', 'subcontratados', 'otros', 'venta','porcentaje_utilidad','iva'
            ];
            $datos = [];

            foreach ($campos as $campo) {

                if ($request->has($campo)) {
                    $datos[$campo] = $request->$campo;
                    if($campo == 'clave'){
                        if($request->filled('clave')){
                            $element2=HojaConcepto::where('clave',$request->clave)->where('id','!=',$request->id)->orderbydesc('id')->first();
                            if($element2){
                                $datos['descripcion'] = $element2->descripcion;
                                $datos['partes'] = $element2->partes;
                                $datos['manoobra'] = $element2->manoobra;
                                $datos['subcontratados'] = $element2->subcontratados;
                                $datos['otros'] = $element2->otros;
                                $datos['iva'] = $element2->iva;
                                $datos['porcentaje_utilidad'] = $element2->porcentaje_utilidad;
                                $datos['venta'] = $element2->venta;
                            }
                            break; // Salir del bucle una vez que se ha encontrado la clave 
                        }
                    }
                    if (in_array($campo, ['partes', 'manoobra', 'subcontratados', 'otros', 'venta', 'porcentaje_utilidad', 'iva'])) {

                        $partes = $request->has('partes') ? $request->partes : ($element->partes ?? 0);
                        $manoobra = $request->has('manoobra') ? $request->manoobra : ($element->manoobra ?? 0);
                        $subcontratados = $request->has('subcontratados') ? $request->subcontratados : ($element->subcontratados ?? 0);
                        $otros = $request->has('otros') ? $request->otros : ($element->otros ?? 0);

                        $venta = $request->has('venta') ? $request->venta : ($element->venta ?? 0);

                        $porcentaje_utilidad = $request->has('porcentaje_utilidad') ? $request->porcentaje_utilidad : ($element->porcentaje_utilidad ?? 0);

                        $iva = $request->has('iva') ? $request->iva : ($element->iva ?? 0);

                        $base = $partes + $manoobra + $subcontratados + $otros;
                        $factor = $iva==1? 1.16 : 1;

                        if ($campo == 'venta') {
                            $divisor=($base / $factor);
                            $datos['porcentaje_utilidad'] = intval(((($venta ?? 0) / ($divisor == 0 ? (($venta ?? 1) * 2) : $divisor)) - 1) * 100);
                        } else {
                             $datos['venta'] = round(($base / $factor) * ((($porcentaje_utilidad ?? 0) / 100) + 1), 2);

                        }

                        break;
                    }

                }
            }

            log::info($datos);
            $element->update($datos);
            return response()->json(["message" => "Concepto actualizado correctamente.", "element" => $element]);
            
            return response()->json(["message" => "Concepto actualizado correctamente."]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
    public function DeleteElement(Request $request) {
        try {
            $element = HojaConcepto::findOrFail($request->id);
            $element->delete();
            
            return response()->json(["message" => "Concepto eliminado correctamente."]);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
}
    public function GetElements(Request $request){
        if($request->has('id')){
            $elements=HojaConcepto::where('presupuesto_id',$request->id)->get();
            return response()->json(["elements"=>$elements]);
        }
        return response()->json(["error"=>'No Se Envio El Presupuesto'],422);
    }
    public function Search(Request $request){
        if($request->has('clave')){
            $element=HojaConcepto::where('clave',$request->clave)->orderbydesc('id')->first();
            return response()->json(["element"=>$element]);
        }
        return response()->json(["error"=>'No Se Envio El Presupuesto'],422);
    }
    
}