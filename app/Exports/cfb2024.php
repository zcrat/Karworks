<?php

namespace App\Exports;

use App\presupuestos2023;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
class cfb2024 implements FromCollection,WithHeadings,WithStyles
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $buscar = $this->request->buscar;
        $criterio = $this->request->criterio;
        $contrato = $this->request->contrato;
        $fecha_inicio = $this->request->fecha_inicio;
        $fecha_final = $this->request->fecha_final;
        $eco = $this->request->eco_id;

        $not=$buscar ? 'LIKE' : 'NOT LIKE';

        $buscar=$buscar ? $buscar : 5;

        if($contrato==0){
            $contrato='';
        }
        if($criterio=='num_comprobante')
        {
            $criterio='presupuestos2023.status';
        }

        $cotizacionesQuery = presupuestos2023::join('pVehiculos2023','presupuestos2023.pVehiculos_id','=','pVehiculos2023.id')
            ->join('pGenerales2023','presupuestos2023.pGenerales_id','=','pGenerales2023.id')
            ->join('empresas','presupuestos2023.empresa_id','=','empresas.id')
            ->join('estatus', 'estatus.id', '=', 'presupuestos2023.status')
            ->join('pcarrito2023', 'pcarrito2023.presupuesto_id', '=', 'presupuestos2023.id')
            ->select('pGenerales2023.NSolicitud','pVehiculos2023.identificador','pVehiculos2023.marca','pVehiculos2023.modelo', 'pVehiculos2023.ano','pVehiculos2023.placas','pVehiculos2023.vin','pGenerales2023.FechaAlta',
            'empresas.nombre as empresa', 
            DB::raw('SUM(pcarrito2023.cantidad * pcarrito2023.precio_v) as SubTotal'),
            DB::raw('(SUM(pcarrito2023.cantidad * pcarrito2023.precio_v)) * 0.16 as Iva'),
            DB::raw('(SUM(pcarrito2023.cantidad * pcarrito2023.precio_v)) * 1.16 as Total'),
            'estatus.nombre')
            ->where('presupuestos2023.eco_id', '=', $eco)
            ->where($criterio, $not, '%' . $buscar . '%')
            ->where('empresas.id', 'like', '%' . $contrato . '%')
            ->groupBy(
                'pGenerales2023.NSolicitud',
                'pVehiculos2023.identificador',
                'pVehiculos2023.marca',
                'pVehiculos2023.modelo',
                'pVehiculos2023.ano',
                'pVehiculos2023.placas',
                'pVehiculos2023.vin',
                'pGenerales2023.FechaAlta',
                'empresas.nombre',
                'estatus.nombre'
            );
        if (!empty($fecha_inicio)) {
            $cotizacionesQuery->where('presupuestos2023.created_at', '>',$fecha_inicio);
        }
        if (!empty($fecha_final)) {
            $cotizacionesQuery->where('presupuestos2023.created_at', '<',$fecha_final);
        }

        return $cotizacionesQuery->orderBy('presupuestos2023.status', 'asc')->orderBy('presupuestos2023.id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Folio','Economico','Marca','Modelo','Año','Placas','VIN','Fecha','Empresa','SubTotal','Iva','Total','Estatus'
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Negrita en los encabezados
            1 => ['font' => ['bold' => true]],


        ];
    }
}
