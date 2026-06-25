<?php

namespace App\Http\Controllers\zcrat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalidasTecnicos;
use App\Exports\HistorialConceptosReportes;
use App\Exports\PresupuestosReporte;
use App\Models\Presupuesto;
use App\Models\RecepcionesVehiculares;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GenerarReportes extends Controller
{
    public function TecnicosSalidas(Request $request)
    {
        $filename = 'documentos/ReportesGenerados/Salidas' . now()->format('Ymd_His') . '.xlsx';

        Excel::store(new SalidasTecnicos($request), $filename, 'public');

        $url = Storage::disk('public')->url($filename);

        return response()->json(['excel' => $url]);
    }
    public function Presupuestos(Request $request)
    {
        $filename = 'documentos/ReportesGenerados/presupuestos'. now()->format('Ymd_His') . '.xlsx';
        Excel::store(new PresupuestosReporte($request->elements), $filename, 'public');

        $url = Storage::disk('public')->url($filename);

        return response()->json(['excel' => url($url)]);

    }
    public function PresupuestosPaginados(Request $request)
    {
        $estatus = $request->estatus;
        $empresas = $request->empresas;
        $fechamin = $request->fechamin;
        $fechamax = $request->fechamax;
        $elements = Presupuesto::withTrashed()->with('detallesGenerales')->where(function($query) use ($estatus, $empresas, $fechamin, $fechamax) {

            if ($estatus) {
                if($estatus == 'eliminado'){
                    $query->whereNotNull('delete_at');
                }else{
                    $query->where('Status_id', $estatus);
                }
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
        })->pluck('id');
        $filename = 'documentos/ReportesGenerados/presupuestos'. now()->format('Ymd_His') . '.xlsx';
        Excel::store(new PresupuestosReporte($elements), $filename, 'public');

        $url = Storage::disk('public')->url($filename);

        return response()->json(['excel' => $url]);
    }
    public function ExportDataConceptosHistorial(Request $request){
            
            
            $filename = 'documentos/ReportesGenerados/HistoriaConceptos'. now()->format('Ymd_His') . '.xlsx';
            Excel::store(new HistorialConceptosReportes($request), $filename, 'public');

            $url = Storage::disk('public')->url($filename);

            return response()->json(['excel' => url($url)]);

    }
    public function ReporteUnidadesSeguimientoPDF(Request $request){
            
            $taller_user=$request->user()->taller_id;

            $elements = RecepcionesVehiculares::with([
            'detallesGenerales',
            'detallesGenerales.Vehiculo',
            'detallesGenerales.Empresa',
            'detallesGenerales.modulo',
            'detallesGenerales.contrato',
            'detallesGenerales.zona',
            'detallesGenerales.DateDiagnosticoInicio',
            'detallesGenerales.DateDiagnosticoTerminado',
            'detallesGenerales.DateTerminado',
            'detallesGenerales.DateVerificado',
            'detallesGenerales.DateEntregado',
            'detallesGenerales.Vales',
            'detallesGenerales.Taller',
            ])->whereHas('detallesGenerales', function ($query) use ($taller_user) {
                $query->doesntHave('DateEntregado')->where('taller_id','=',$taller_user);
            });

            $elements = $elements->get();

            $elements= $elements->map(function($element){
                $estado='Sin Diagnostico';
                $diagostico=$element->detallesGenerales->DateDiagnosticoInicio;
                $diagostico2=$element->detallesGenerales->DateDiagnosticoTerminado;
                $DateTerminado=$element->detallesGenerales->DateTerminado;
                $DateVerificado=$element->detallesGenerales->DateVerificado;
                $ValesNoEntregados=$element->detallesGenerales->ValesNoEntregados->count();
                $ValesNoConfirmados=$element->detallesGenerales->ValesNoConfirmados->count();
                $ValesPendientes=$element->detallesGenerales->ValesPendientes->count();
                $ValesTerminados=$element->detallesGenerales->ValesTerminados->count();
                $vales=$element->detallesGenerales->Vales->count();
                if($diagostico){
                    $estado='Diagnostico En Proceso';
                }
                if($diagostico2){
                    $estado='Sin Vales';
                    if($vales > 0){
                        $estado='Vales Pendientes';
                        if ($ValesTerminados === $vales ){
                            $estado='Todos Los Vales Terminados';
                        }else{
                            $subestado=[];
                            if($ValesNoEntregados > 0){
                                $subestado[]="{$ValesNoEntregados} Vales No Entregados";
                            }
                            if($ValesNoConfirmados > 0){
                                $subestado[]="{$ValesNoConfirmados} Vales No Confirmados";
                            }
                            if($ValesPendientes > 0){
                                $subestado[]="{$ValesPendientes} Vales Con Refaccion Pendientes De Entrega";
                            }
                            if($ValesTerminados > 0){
                                $subestado[]="{$ValesTerminados} Completados";
                            }
                            $estado=implode(',',$subestado);
                        }
                    } 
                }
                
                if($DateTerminado){
                    $estado='Unidade Terminada Pendiente de Verificacion';

                }
                if($DateVerificado){
                    $estado='Unidade Terminada,Esperando al Cliente Para La Entrega';
                }
                return[
                        'OrdenServicio' => $element->detallesGenerales->OrdenServicio,
                        'OrdenSeguimiento' => $element->detallesGenerales->OrdenSeguimiento,
                        'taller'=>$element->detallesGenerales->Taller->nombre,
                        'subcontrato'=>$element->detallesGenerales->has_subcontrato ? 'Subcontrato' : 'Taller',
                        'Ubicacion' => $element->detallesGenerales->Ubicacion,
                        'Economico' => $element->detallesGenerales->Vehiculo->no_economico,
                        'Placa' => $element->detallesGenerales->Vehiculo->placas,
                        'Marca' => $element->detallesGenerales->Vehiculo->marca->nombre,
                        'Modelo' => $element->detallesGenerales->Vehiculo->modelo->nombre,
                        'Entrada' => $element->detallesGenerales->Fecha_entrada,
                        'fallas' => $element->detallesGenerales->Indicaciones_cliente,
                        'estaus' => $estado
                ];
            });
        return \View::make('pdf.ReporteUnidadesSeguimiento', compact('elements'))->render();  

    }
}
