<?php

namespace App\Http\Controllers\zcrat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UsersTaller;
use App\Models\Color;
use App\Models\Marca;
use App\Models\Tecnicos;
use App\Models\Modelo;
use App\Models\Talleres;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
class OneAttributeController extends Controller
{
  public function Create(Request $request){
    $origin=['ColorVehiculo','ModeloVehiculo','MarcaVehiculo','UserTaller1','UserTaller2','UserTaller3','UserTaller4','talleres'];
    $modals=[
        'ColorVehiculo'=> new Color(),
        'UserTaller'=> new UsersTaller(),
        'MarcaVehiculo'=> new Marca(),
        'ModeloVehiculo'=> new Modelo(),
        'talleres'=> new Talleres(),
    ];
    $messages = [
        'OneAttributeInput.required' => 'El Nombre Es Obligatorio',
        'OneAttributeOrigin.required' => 'El Origen Es Obligatorio',
        'OneAttributeOrigin.in' => 'El Origen No Es Valido',
    ];
    $request->validate([
        'OneAttributeInput' => 'required',
        'OneAttributeOrigin' => ['required', Rule::in($origin)],
    ], $messages);
    try {
        DB::beginTransaction();
        if(strpos($request->OneAttributeOrigin,'UserTaller')!== false){
            
            if($request->OneAttributeOrigin == 'UserTaller4')
            {
                $model=new Tecnicos();
            }else{
                $model=$modals['UserTaller'];
                $tipo=substr($request->OneAttributeOrigin, strlen('UserTaller'));
                $model->tipo_user_taller_id=$tipo;

            }
            
            
        }else{
            $model=$modals[$request->OneAttributeOrigin];
        }
        if($request->OneAttributeOrigin == 'ModeloVehiculo'){
            $model->marca_id=$request->marca;
        }
        $model->nombre=$request->OneAttributeInput;
        $model->save();
        DB::commit();
        return response()->json(['message' => 'Creado Correctamente','id'=>$model->id,'nombre'=>$model->nombre], 200); 
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => $e->getmessage()], 500); 
    }
  } 
}
