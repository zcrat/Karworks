<?php

namespace App\Http\Controllers\zcrat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Llantas;
use App\Liquidos;
use App\Bandas;
use App\Seguridad;
use App\Filtros;
use App\Escape;
use App\SuspencionDireccion;
use App\AfinacionMotor;
use App\TrenTransmision;
use App\Frenos;
use App\Electrico;
use App\RevisionLucesEspias;
use App\Mangueras;
use App\InspeccionTecnicaVehiculo;
use App\Models\DetallesGenerales;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class InspeccionVehicularController extends Controller
{
    public function createorupdate(Request $request){
        $request->validate(['id'=>'nullable|exists:detallesgenerales,id',
            'llantas' => 'required|array',
            'llantas.EsDelIzq' => 'required|in:1,2,3',
            'llantas.PreDelIzq' => 'required|numeric',
            'llantas.EsTraIzq' => 'required|in:1,2,3',
            'llantas.PreTraIzq' => 'required|numeric',
            'llantas.EsDelDer' => 'required|in:1,2,3',
            'llantas.PreDelDer' => 'required|numeric',
            'llantas.EsTraDer' => 'required|in:1,2,3',
            'llantas.PreTraDer' => 'required|numeric',
            'llantas.EsRef' => 'required|in:1,2,3',
            'llantas.PreRef' => 'required|numeric',
            'llantas.aliniacion' => 'required|in:1,2,3',

            'RevLucEsp'=>'required|array',
            'RevLucEsp.codigo' => 'required|in:1,2,3',
            'RevLucEsp.notas' => 'nullable|string|max:155',

            'Mangueras'=>'required|array',
            'Mangueras.refrigerante' => 'required|in:1,2,3',
            'Mangueras.direccion' => 'required|in:1,2,3',
            'Mangueras.calefaccion' => 'required|in:1,2,3',

            'Liquidos'=>'required|array',
            'Liquidos.motor' => 'required|in:1,2,3',
            'Liquidos.trasmision' => 'required|in:1,2,3',
            'Liquidos.diferencial' => 'required|in:1,2,3',
            'Liquidos.refrigerante' => 'required|in:1,2,3',
            'Liquidos.frenos' => 'required|in:1,2,3',
            'Liquidos.direccion' => 'required|in:1,2,3',
            'Liquidos.parabrisas' => 'required|in:1,2,3',
            'Liquidos.notas' => 'nullable|string|max:155',
            'Liquidos.OKmotor' => 'required|boolean',
            'Liquidos.OKtrasmision' => 'required|boolean',
            'Liquidos.OKdiferencial' => 'required|boolean',
            'Liquidos.OKrefrigerante' => 'required|boolean',
            'Liquidos.OKfrenos' => 'required|boolean',
            'Liquidos.OKdireccion' => 'required|boolean',
            'Liquidos.OKparabrisas' => 'required|boolean',
            'Liquidos.LLenomotor' => 'required|boolean',
            'Liquidos.LLenotrasmision' => 'required|boolean',
            'Liquidos.LLenodiferencial' => 'required|boolean',
            'Liquidos.LLenorefrigerante' => 'required|boolean',
            'Liquidos.LLenofrenos' => 'required|boolean',
            'Liquidos.LLenodireccion' => 'required|boolean',
            'Liquidos.LLenoparabrisas' => 'required|boolean',

            'Bandas'=>'required|array',
            'Bandas.accesorios' => 'required|in:1,2,3',
            'Bandas.direccion' => 'required|in:1,2,3',
            'Bandas.aire' => 'required|in:1,2,3',

            'Filtros'=>'required|array',
            'Filtros.aire' => 'required|in:1,2,3',
            'Filtros.combustible' => 'required|in:1,2,3',
            'Filtros.aceite' => 'required|in:1,2,3',
            'Filtros.notas' => 'nullable|string|max:155',

            'Seguridad'=>'required|array',
            'Seguridad.freno' => 'required|in:1,2,3',
            'Seguridad.parabrisasdel' => 'required|in:1,2,3',
            'Seguridad.parabrisastra' => 'required|in:1,2,3',
            'Seguridad.notas' => 'nullable|string|max:155',

            'afinacion'=>'required|array',
            'afinacion.tapa' => 'required|in:1,2,3',
            'afinacion.fuel' => 'required|in:1,2,3',

            'trasmision'=>'required|array',
            'trasmision.filtro' => 'required|in:1,2,3',
            'trasmision.union' => 'required|in:1,2,3',
            'trasmision.traccion' => 'required|in:1,2,3',
            'trasmision.juntas' => 'required|in:1,2,3',
            'trasmision.rodamiento' => 'required|in:1,2,3',
            'trasmision.trasmision' => 'required|in:1,2,3',
            'trasmision.clutch' => 'required|in:1,2,3',
            'trasmision.notas' => 'nullable|string|max:155',

            'electrico'=>'required|array',
            'electrico.bateria' => 'required|in:1,2,3',
            'electrico.cables' => 'required|in:1,2,3',

            'luces'=>'required|array',
            'luces.faroizq' => 'required|in:1,2,3',
            'luces.faroder' => 'required|in:1,2,3',
            'luces.cuartosizq' => 'required|in:1,2,3',
            'luces.cuartosder' => 'required|in:1,2,3',
            'luces.freno' => 'required|in:1,2,3',
            'luces.dif' => 'required|in:1,2,3',
            'luces.dit' => 'required|in:1,2,3',
            'luces.ddf' => 'required|in:1,2,3',
            'luces.ddt' => 'required|in:1,2,3',
            'luces.intermitentes' => 'required|in:1,2,3',

            'suspension'=>'required|array',
            'suspension.amortiguadores' => 'required|in:1,2,3',
            'suspension.direccion' => 'required|in:1,2,3',
            'suspension.notas' => 'nullable|string|max:155',

            'pastillas'=>'required|array',
            'pastillas.idel' => 'required|in:1,2,3',
            'pastillas.ddel' => 'required|in:1,2,3',
            'pastillas.itras' => 'required|in:1,2,3',
            'pastillas.dtras' => 'required|in:1,2,3',

            'rotores' => 'required|array',
            'rotores.idel' => 'required|in:1,2,3',
            'rotores.ddel' => 'required|in:1,2,3',
            'rotores.itras'=> 'required|in:1,2,3',
            'rotores.dtras' => 'required|in:1,2,3',

            'pinzas' => 'required|array',
            'pinzas.idel' => 'required|in:1,2,3',
            'pinzas.ddel' => 'required|in:1,2,3',
            'pinzas.itras' => 'required|in:1,2,3',
            'pinzas.dtras' => 'required|in:1,2,3',

            'escape'=>'required|array',
            'escape.mofle' => 'required|in:1,2,3',
            'escape.sensores' => 'required|in:1,2,3',
            'escape.notas' => 'nullable|string|max:155',

            'firmas1' => 'nullable|string',
            'firmas2' => 'nullable|string',
        ]);
        try{
            DB::beginTransaction();
            $llantas = (object) $request->llantas;
            $liquidos = (object) $request->Liquidos;
            $mangueras = (object) $request->Mangueras;
            $revLucEsp = (object) $request->RevLucEsp;
            $bandas = (object) $request->Bandas;
            $filtros = (object) $request->Filtros;
            $seguridad = (object) $request->Seguridad;
            $afinacion = (object) $request->afinacion;
            $trasmision = (object) $request->trasmision;
            $electrico = (object) $request->electrico;
            $luces = (object) $request->luces;
            $suspension = (object) $request->suspension;
            $pastillas = (object) $request->pastillas;
            $rotores = (object) $request->rotores;
            $pinzas = (object) $request->pinzas;
            $escape = (object) $request->escape;

            $exist=InspeccionTecnicaVehiculo::where('DetallesGenerales_id',$request->id)->first();

            if ($exist) {
                $filenamefirma1 = $exist->firma1;
                $filenamefirma2 = $exist->firma2;
                $Liquidos = Liquidos::find($exist->id_liquidos);
                $Mangueras = Mangueras::find($exist->id_mangueras);
                $RevisionLucesEspias = RevisionLucesEspias::find($exist->id_revision_luces_espias);
                $Bandas = Bandas::find($exist->id_bandas);
                $Filtros = Filtros::find($exist->id_filtros);
                $Seguridad = Seguridad::find($exist->id_seguridad);
                $AfinacionMotor = AfinacionMotor::find($exist->id_afinacion_motor);
                $TrenTransmision = TrenTransmision::find($exist->id_tren_transmision);
                $Electrico = Electrico::find($exist->id_electrico);
                $SuspencionDireccion = SuspencionDireccion::find($exist->id_suspencion_direccion);
                $Frenos = Frenos::find($exist->id_frenos);
                $Escape = Escape::find($exist->id_escape);
                $Llantas =Llantas::find($exist->id_llantas) ;
            } else {
                $filenamefirma1 = $request->id . 'firma1_' . time() . '.png';
                $filenamefirma2 = $request->id . 'firma2_' . time() . '.png';
                $Liquidos = new Liquidos();
                $Mangueras = new Mangueras();
                $RevisionLucesEspias = new RevisionLucesEspias();
                $Bandas = new Bandas();
                $Filtros = new Filtros();
                $Seguridad = new Seguridad();
                $AfinacionMotor = new AfinacionMotor();
                $TrenTransmision = new TrenTransmision();
                $Electrico = new Electrico();
                $SuspencionDireccion = new SuspencionDireccion();
                $Frenos = new Frenos();
                $Escape = new Escape();
                $Llantas = new Llantas();
            } 
            $ruta1='inspeccionvehicular/firmastaller/'.$filenamefirma1;
            $ruta2='inspeccionvehicular/firmasclientes/'.$filenamefirma2;

            $this->saveBase64Image($request->firma1, $ruta1);
            $this->saveBase64Image($request->firma2, $ruta2);


            $Llantas->izquierda_delantera = $llantas->EsDelIzq;
            $Llantas->izquierda_delantera_presion = $llantas->PreDelIzq;
            $Llantas->izquierda_trasera = $llantas->EsTraIzq;
            $Llantas->izquierda_trasera_presion = $llantas->PreTraIzq;
            $Llantas->derecha_delantera = $llantas->EsDelDer;
            $Llantas->derecha_delantera_presion = $llantas->PreDelDer;
            $Llantas->derecha_trasera = $llantas->EsTraDer;
            $Llantas->derecha_trasera_presion = $llantas->PreTraDer;
            $Llantas->refaccion = $llantas->EsRef;
            $Llantas->refaccion_presion = $llantas->PreRef;
            $Llantas->alineacion_balanceo = $llantas->aliniacion;
            $Llantas->save();
            

            $Liquidos = $exist ? Liquidos::find($exist->id_liquidos) : new Liquidos();
            $Liquidos->aceite_motor = $liquidos->motor;
            $Liquidos->aceite_motor_ok = $liquidos->OKmotor;
            $Liquidos->aceite_motor_lleno = $liquidos->LLenomotor;
            $Liquidos->transmision = $liquidos->trasmision;
            $Liquidos->transmision_ok = $liquidos->OKtrasmision;
            $Liquidos->transmision_lleno = $liquidos->LLenotrasmision;
            $Liquidos->diferencial_frente_trasero = $liquidos->diferencial;
            $Liquidos->diferencial_frente_trasero_ok = $liquidos->OKdiferencial;
            $Liquidos->diferencial_frente_trasero_lleno = $liquidos->LLenodiferencial;
            $Liquidos->liquido_refrigerante = $liquidos->refrigerante;
            $Liquidos->refrigerante_ok = $liquidos->OKrefrigerante;
            $Liquidos->refrigerante_lleno = $liquidos->LLenorefrigerante;
            $Liquidos->frenos = $liquidos->frenos;
            $Liquidos->frenos_ok = $liquidos->OKfrenos;
            $Liquidos->frenos_lleno = $liquidos->LLenofrenos;
            $Liquidos->direccion_hidraulica = $liquidos->direccion;
            $Liquidos->direccion_hidraulica_ok = $liquidos->OKdireccion;
            $Liquidos->direccion_hidraulica_lleno = $liquidos->LLenodireccion;
            $Liquidos->limpiaparabrisas = $liquidos->parabrisas;
            $Liquidos->limpiaparabrisas_ok = $liquidos->OKparabrisas;
            $Liquidos->limpiaparabrisas_lleno = $liquidos->LLenoparabrisas;
            $Liquidos->liquido_notas = $liquidos->notas??'';
            $Liquidos->save();

            $Mangueras->refrigerante = $mangueras->refrigerante;
            $Mangueras->direccion_aire_acondicionado = $mangueras->direccion;
            $Mangueras->calefaccion = $mangueras->calefaccion;
            $Mangueras->save();

            $RevisionLucesEspias->codigo = $revLucEsp->codigo;
            $RevisionLucesEspias->notas = $revLucEsp->notas??'';
            $RevisionLucesEspias->save();

            $Bandas->accesorios = $bandas->accesorios;
            $Bandas->bandas_direccion_hidraulica = $bandas->direccion;
            $Bandas->alternador_aire_acondicionado = $bandas->aire;
            $Bandas->save();

            $Filtros->aire = $filtros->aire;
            $Filtros->combustible = $filtros->combustible;
            $Filtros->aceite = $filtros->aceite;
            $Filtros->filtro_notas = $filtros->notas??'';
            $Filtros->save();

            $Seguridad->frenos_emergencia = $seguridad->freno;
            $Seguridad->limpiaparabrisas_izquierdo_derecho = $seguridad->parabrisasdel;
            $Seguridad->limpiaparabrisas_trasero = $seguridad->parabrisastra;
            $Seguridad->seguridad_notas = $seguridad->notas??'';
            $Seguridad->save();

            $AfinacionMotor->tapa_distribuidor_bujias_cables = $afinacion->tapa;
            $AfinacionMotor->fuel_injection = $afinacion->fuel;
            $AfinacionMotor->save();

            $TrenTransmision->filtro_transmison = $trasmision->filtro;
            $TrenTransmision->union_transmision_clutch = $trasmision->union;
            $TrenTransmision->eje_traccion_juntas_homocineticas = $trasmision->traccion;
            $TrenTransmision->eje_transmision_juntas_universales = $trasmision->juntas;
            $TrenTransmision->rodamientos_rueda = $trasmision->rodamiento;
            $TrenTransmision->tren_transmision = $trasmision->trasmision;
            $TrenTransmision->clutch = $trasmision->clutch;
            $TrenTransmision->tren_notas = $trasmision->notas??'';
            $TrenTransmision->save();

            $Electrico->sistema_carga_bateria = $electrico->bateria;
            $Electrico->cables_conexiones_fusibles = $electrico->cables;
            $Electrico->faros = 1;
            $Electrico->faro_izquierda = $luces->faroizq;
            $Electrico->faro_derecha = $luces->faroder;
            $Electrico->cuartos = 1;
            $Electrico->cuarto_izquierda = $luces->cuartosizq;
            $Electrico->cuarto_derecha = $luces->cuartosder;
            $Electrico->reversa_frenos = $luces->freno;
            $Electrico->direccionales = $luces->dif;
            $Electrico->direccionales_izquierda_delantera = $luces->dit;
            $Electrico->direccionales_derecha_delantera = $luces->ddf;
            $Electrico->direccionales_izquierda_trasera = $luces->ddt;
            $Electrico->direccionales_derecha_trasera = $luces->ddt;
            $Electrico->intermitentes = $luces->intermitentes;
            $Electrico->save();

            $SuspencionDireccion->amortiguadores_suspencion = $suspension->amortiguadores;
            $SuspencionDireccion->juntas_direccion_rotulas = $suspension->direccion;
            $SuspencionDireccion->suspencion_notas = $suspension->notas??'';
            $SuspencionDireccion->save();

            $Frenos->pastillas_izquierda_delantera = $pastillas->idel;
            $Frenos->pastillas_izquierda_trasera = $pastillas->itras;
            $Frenos->pastillas_derecha_delantera = $pastillas->ddel;
            $Frenos->pastillas_derecha_trasera = $pastillas->dtras;
            $Frenos->rotores_izquierda_delantera = $rotores->idel;
            $Frenos->rotores_izquierda_trasera = $rotores->itras;
            $Frenos->rotores_derecha_delantera = $rotores->ddel;
            $Frenos->rotores_derecha_trasera = $rotores->dtras;
            $Frenos->pinzas_cilindros_rueda_izquierda_delantera = $pinzas->idel;
            $Frenos->pinzas_cilindros_rueda_izquierda_trasera = $pinzas->itras;
            $Frenos->pinzas_cilindros_rueda_derecha_delantera = $pinzas->ddel;
            $Frenos->pinzas_cilindros_rueda_derecha_trasera = $pinzas->dtras;
            $Frenos->save();

            $Escape->mofle_convertidor_catlitico = $escape->mofle;
            $Escape->sensores_soporte_tubos = $escape->sensores;
            $Escape->escape_notas = $escape->notas??'';
            $Escape->save();

            

            
            if(!$exist){
                $exist=new InspeccionTecnicaVehiculo();
                $exist->DetallesGenerales_id=$request->id;
                $exist->id_llantas=$Llantas->id;
                $exist->id_liquidos=$Liquidos ->id;
                $exist->id_bandas=$Bandas->id;
                $exist->id_seguridad=$Seguridad->id;
                $exist->id_filtros=$Filtros->id;
                $exist->id_escape=$Escape->id;
                $exist->id_suspencion_direccion=$SuspencionDireccion->id;
                $exist->id_afinacion_motor=$AfinacionMotor->id;
                $exist->id_tren_transmision=$TrenTransmision->id;
                $exist->id_frenos=$Frenos->id;
                $exist->id_electrico=$Electrico->id;
                $exist->id_revision_luces_espias=$RevisionLucesEspias->id;
                $exist->id_mangueras=$Mangueras->id;
                $exist->user_id=$request->user()->id;
                $exist->user_id=$request->user()->id;
                $exist->firma1=$filenamefirma1;
                $exist->firma2=$filenamefirma2;
                $exist->save();
            }
            DB::commit();
            return response()->json(['message' => $exist?'Actualizado Correctamente':'Creado Correctamente','id'=>$exist->id]);
        }catch(\Throwable $th){
            DB::rollBack();
            return response()->json(['message' => $th->getMessage()],500);
        }
    }
    public function Read(Request $request){
        $request->validate(['id'=>'required|exists:detallesgenerales,id']);
        $exist=InspeccionTecnicaVehiculo::with(['llantas',
                                                'liquidos',
                                                'bandas',
                                                'seguridad',
                                                'filtros',
                                                'escape',
                                                'suspencionDireccion',
                                                'afinacionMotor',
                                                'trenTransmision',
                                                'frenos',
                                                'electrico',
                                                'revisionLucesEspias',
                                                'mangueras'])
        ->where('DetallesGenerales_id',$request->id)->first();
        if (!$exist) {
            return response()->json(['message'=>'No Exites','data'=>[]]);
        }
        $data=[
                    'llantas' => [
                        'EsDelIzq' => $exist->llantas->izquierda_delantera,
                        'PreDelIzq' => $exist->llantas->izquierda_delantera_presion,
                        'EsTraIzq' => $exist->llantas->izquierda_trasera,
                        'PreTraIzq' => $exist->llantas->izquierda_trasera_presion,
                        'EsDelDer' => $exist->llantas->derecha_delantera,
                        'PreDelDer' => $exist->llantas->derecha_delantera_presion,
                        'EsTraDer' => $exist->llantas->derecha_trasera,
                        'PreTraDer' => $exist->llantas->derecha_trasera_presion,
                        'EsRef' => $exist->llantas->refaccion,
                        'PreRef' => $exist->llantas->refaccion_presion,
                        'aliniacion' => $exist->llantas->alineacion_balanceo,
                    ],
                    'RevLucEsp' => [
                        'codigo' => $exist->revisionLucesEspias->codigo,
                        'notas' => $exist->revisionLucesEspias->notas,
                    ],
                    'Mangueras' => [
                        'refrigerante' => $exist->mangueras->refrigerante,
                        'direccion' => $exist->mangueras->direccion_aire_acondicionado,
                        'calefaccion' => $exist->mangueras->calefaccion,
                    ],
                    'Liquidos' => [
                        'motor' => $exist->liquidos->aceite_motor,
                        'OKmotor' => $exist->liquidos->aceite_motor_ok==1,
                        'LLenomotor' => $exist->liquidos->aceite_motor_lleno==1,
                        'trasmision' => $exist->liquidos->transmision,
                        'OKtrasmision' => $exist->liquidos->transmision_ok==1,
                        'LLenotrasmision' => $exist->liquidos->transmision_lleno==1,
                        'diferencial' => $exist->liquidos->diferencial_frente_trasero,
                        'OKdiferencial' => $exist->liquidos->diferencial_frente_trasero_ok==1,
                        'LLenodiferencial' => $exist->liquidos->diferencial_frente_trasero_lleno==1,
                        'refrigerante' => $exist->liquidos->liquido_refrigerante,
                        'OKrefrigerante' => $exist->liquidos->refrigerante_ok==1,
                        'LLenorefrigerante' => $exist->liquidos->refrigerante_lleno==1,
                        'frenos' => $exist->liquidos->frenos,
                        'OKfrenos' => $exist->liquidos->frenos_ok==1,
                        'LLenofrenos' => $exist->liquidos->frenos_lleno==1,
                        'direccion' => $exist->liquidos->direccion_hidraulica,
                        'OKdireccion' => $exist->liquidos->direccion_hidraulica_ok==1,
                        'LLenodireccion' => $exist->liquidos->direccion_hidraulica_lleno==1,
                        'parabrisas' => $exist->liquidos->limpiaparabrisas,
                        'OKparabrisas' => $exist->liquidos->limpiaparabrisas_ok==1,
                        'LLenoparabrisas' => $exist->liquidos->limpiaparabrisas_lleno==1,
                        'notas' => $exist->liquidos->liquido_notas,
                    ],
                    'Bandas' => [
                        'accesorios' => $exist->bandas->accesorios,
                        'direccion' => $exist->bandas->bandas_direccion_hidraulica,
                        'aire' => $exist->bandas->alternador_aire_acondicionado,
                    ],
                    'Filtros' => [
                        'aire' => $exist->filtros->aire,
                        'combustible' => $exist->filtros->combustible,
                        'aceite' => $exist->filtros->aceite,
                        'notas' => $exist->filtros->filtro_notas,
                    ],
                    'Seguridad' => [
                        'freno' => $exist->seguridad->frenos_emergencia,
                        'parabrisasdel' => $exist->seguridad->limpiaparabrisas_izquierdo_derecho,
                        'parabrisastra' => $exist->seguridad->limpiaparabrisas_trasero,
                        'notas' => $exist->seguridad->seguridad_notas,
                    ],
                    'afinacion' => [
                        'tapa' => $exist->afinacionMotor->tapa_distribuidor_bujias_cables,
                        'fuel' => $exist->afinacionMotor->fuel_injection,
                    ],
                    'trasmision' => [
                        'filtro' => $exist->trenTransmision->filtro_transmison,
                        'union' => $exist->trenTransmision->union_transmision_clutch,
                        'traccion' => $exist->trenTransmision->eje_traccion_juntas_homocineticas,
                        'juntas' => $exist->trenTransmision->eje_transmision_juntas_universales,
                        'rodamiento' => $exist->trenTransmision->rodamientos_rueda,
                        'trasmision' => $exist->trenTransmision->tren_transmision,
                        'clutch' => $exist->trenTransmision->clutch,
                        'notas' => $exist->trenTransmision->tren_notas,
                    ],
                    'electrico' => [
                        'bateria' => $exist->electrico->sistema_carga_bateria,
                        'cables' => $exist->electrico->cables_conexiones_fusibles,
                    ],
                    'luces' => [
                        'faroizq' => $exist->electrico->faro_izquierda,
                        'faroder' => $exist->electrico->faro_derecha,
                        'cuartosizq' => $exist->electrico->cuarto_izquierda,
                        'cuartosder' => $exist->electrico->cuarto_derecha,
                        'freno' => $exist->electrico->reversa_frenos,
                        'dif' => $exist->electrico->direccionales,
                        'dit' => $exist->electrico->direccionales_izquierda_delantera,
                        'ddf' => $exist->electrico->direccionales_derecha_delantera,
                        'ddt' => $exist->electrico->direccionales_izquierda_trasera,
                        'intermitentes' => $exist->electrico->intermitentes,
                    ],
                    'suspension' => [
                        'amortiguadores' => $exist->suspencionDireccion->amortiguadores_suspencion,
                        'direccion' => $exist->suspencionDireccion->juntas_direccion_rotulas,
                        'notas' => $exist->suspencionDireccion->suspencion_notas,
                    ],
                    'pastillas' => [
                        'idel' => $exist->frenos->pastillas_izquierda_delantera,
                        'ddel' => $exist->frenos->pastillas_derecha_delantera,
                        'itras' => $exist->frenos->pastillas_izquierda_trasera,
                        'dtras' => $exist->frenos->pastillas_derecha_trasera,
                    ],
                    'rotores' => [
                        'idel' => $exist->frenos->rotores_izquierda_delantera,
                        'ddel' => $exist->frenos->rotores_derecha_delantera,
                        'itras' => $exist->frenos->rotores_izquierda_trasera,
                        'dtras' => $exist->frenos->rotores_derecha_trasera,
                    ],
                    'pinzas' => [
                        'idel' => $exist->frenos->pinzas_cilindros_rueda_izquierda_delantera,
                        'ddel' => $exist->frenos->pinzas_cilindros_rueda_derecha_delantera,
                        'itras' => $exist->frenos->pinzas_cilindros_rueda_izquierda_trasera,
                        'dtras' => $exist->frenos->pinzas_cilindros_rueda_derecha_trasera,
                    ],
                    'escape' => [
                        'mofle' => $exist->escape->mofle_convertidor_catlitico,
                        'sensores' => $exist->escape->sensores_soporte_tubos,
                        'notas' => $exist->escape->escape_notas,
                    ],
                    'imagenes'=>[
                        'firma1' => $exist->firma1,
                        'firma2' => $exist->firma2,
                    ]
                ];
       return response()->json(['message'=>'Existe','data'=>$data]);
    }
    public function PDF(Request $request){
        //$request->validate(['id'=>'required|exists:detallesgenerales,id']);
        $InspeccionVehicular=InspeccionTecnicaVehiculo::with([
            'detallesGenerales.modulo.FacturaEmisor',
            'detallesGenerales.recepcionesVehiculares',
            'user',
            'llantas',
            'liquidos',
            'bandas',
            'seguridad',
            'filtros',
            'escape',
            'suspencionDireccion',
            'afinacionMotor',
            'trenTransmision',
            'frenos',
            'electrico',
            'revisionLucesEspias',
            'mangueras',
        ])->where('DetallesGenerales_id',$request->id)->first();
        if (!$InspeccionVehicular) {
            $detalles=DetallesGenerales::with(['modulo.FacturaEmisor','recepcionesVehiculares'])->find($request->id);
            $user=$request->user();
            $InspeccionVehicular=(object)[
                'detallesGenerales'=>$detalles,
                'user'=>$user,
                'llantas'=>(object)[],
                'liquidos'=>(object)[],
                'bandas'=>(object)[],
                'seguridad'=>(object)[],
                'filtros'=>(object)[],
                'escape'=>(object)[],
                'suspencionDireccion'=>(object)[],
                'afinacionMotor'=>(object)[],
                'trenTransmision'=>(object)[],
                'frenos'=>(object)[],
                'electrico'=>(object)[],
                'revisionLucesEspias'=>(object)[],
                'mangueras'=>(object)[],
                'created_at'=>Carbon::now()
            ];
        }

        
        return \View::make('pdf.InspeccionVehicularPDF', compact('InspeccionVehicular'))->render();
        // $html = view('reportes.InspeccionVehicularPDF', compact('InspeccionVehicular'))->render();
        // return response()->json(['message'=>'Existe','html'=>$html]);
    }
    public function PDF2(Request $request){
        $request->validate(['id'=>'required|exists:detallesgenerales,id']);
        $InspeccionVehicular=InspeccionTecnicaVehiculo::with([
            'detallesGenerales.modulo.FacturaEmisor',
            'detallesGenerales.recepcionesVehiculares',
            'user',
            'llantas',
            'liquidos',
            'bandas',
            'seguridad',
            'filtros',
            'escape',
            'suspencionDireccion',
            'afinacionMotor',
            'trenTransmision',
            'frenos',
            'electrico',
            'revisionLucesEspias',
            'mangueras',
        ])->where('DetallesGenerales_id',$request->id)->first();

        if (!$InspeccionVehicular) {
            $detalles=DetallesGenerales::with(['modulo.FacturaEmisor','recepcionesVehiculares'])->find($request->id);
            $user=$request->user();
           $InspeccionVehicular=collect([
            'detallesGenerales'=>$detalles,
            'user'=>$user,
            'llantas'=>null,
            'liquidos'=>null,
            'bandas'=>null,
            'seguridad'=>null,
            'filtros'=>null,
            'escape'=>null,
            'suspencionDireccion'=>null,
            'afinacionMotor'=>null,
            'trenTransmision'=>null,
            'frenos'=>null,
            'electrico'=>null,
            'revisionLucesEspias'=>null,
            'mangueras'=>null,
            'created_at'=>Carbon::now()
           ]);
        }
       return view('reportes.InspeccionVehicularPDF', compact('InspeccionVehicular'));
    }
    private function saveBase64Image($imagenBase64, $directorio){
        // Validar formato base64
        if (!preg_match('/^data:image\/(png|jpeg);base64,/', $imagenBase64)) {
            log::info('Formato de imagen no válido.');
        }
        // Decodificar la imagen
        $data = substr($imagenBase64, strpos($imagenBase64, ',') + 1);
        $data = base64_decode($data);
        if ($data === false) {
            log::info('Error al decodificar la imagen.');
        }
        // Guardar la imagen en el almacenamiento
        Storage::put("public/$directorio", $data);
    }
}
